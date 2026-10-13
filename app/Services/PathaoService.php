<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PathaoService
{
    /**
     * Get the Pathao API base URL based on environment setting.
     */
    public function getBaseUrl(?string $env = null): string
    {
        $environment = $env ?: Setting::get('pathao_environment', 'sandbox');
        return ($environment === 'production')
            ? 'https://api-hermes.pathao.com'
            : 'https://courier-api-sandbox.pathao.com';
    }

    /**
     * Check if minimum Pathao API credentials are configured.
     */
    public function isConfigured(): bool
    {
        return !empty(Setting::get('pathao_client_id'))
            && !empty(Setting::get('pathao_client_secret'))
            && !empty(Setting::get('pathao_client_email'))
            && !empty(Setting::get('pathao_client_password'));
    }

    /**
     * Issue or retrieve cached access token from Pathao.
     */
    public function issueToken(bool $forceRefresh = false, ?array $overrideCredentials = null): ?string
    {
        $clientId = $overrideCredentials['client_id'] ?? Setting::get('pathao_client_id');
        $clientSecret = $overrideCredentials['client_secret'] ?? Setting::get('pathao_client_secret');
        $username = $overrideCredentials['email'] ?? Setting::get('pathao_client_email');
        $password = $overrideCredentials['password'] ?? Setting::get('pathao_client_password');
        $env = $overrideCredentials['environment'] ?? Setting::get('pathao_environment', 'sandbox');

        if (!$clientId || !$clientSecret || !$username || !$password) {
            return null;
        }

        $cacheKey = "pathao_token_" . md5($clientId . $username . $env);

        if (!$forceRefresh && !$overrideCredentials && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $baseUrl = $this->getBaseUrl($env);

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->asJson()
                ->post("{$baseUrl}/aladdin/api/v1/issue-token", [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'username' => $username,
                    'password' => $password,
                    'grant_type' => 'password',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['access_token'] ?? null;
                $expiresIn = (int) ($data['expires_in'] ?? 3600);

                if ($token) {
                    $ttl = max(60, $expiresIn - 300);
                    Cache::put($cacheKey, $token, $ttl);
                    return $token;
                }
            }

            Log::error('Pathao issue-token failed: ' . $response->body());
            return null;
        } catch (\Throwable $e) {
            Log::error('Pathao issue-token exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Test credentials and fetch available store locations.
     */
    public function testConnection(?array $credentials = null): array
    {
        $token = $this->issueToken(forceRefresh: true, overrideCredentials: $credentials);

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with Pathao. Please verify Client ID, Client Secret, Email, and Password.',
                'stores' => [],
            ];
        }

        $env = $credentials['environment'] ?? Setting::get('pathao_environment', 'sandbox');
        $stores = $this->getStores($token, $env);

        return [
            'success' => true,
            'message' => 'Pathao credentials verified successfully! Found ' . count($stores) . ' store(s).',
            'stores' => $stores,
        ];
    }

    /**
     * Retrieve merchant stores from Pathao.
     */
    public function getStores(?string $token = null, ?string $env = null): array
    {
        $token = $token ?: $this->issueToken();
        if (!$token) {
            return [];
        }

        $baseUrl = $this->getBaseUrl($env);

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->acceptJson()
                ->get("{$baseUrl}/aladdin/api/v1/stores");

            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['data'] ?? $data['data'] ?? [];
            }
        } catch (\Throwable $e) {
            Log::error('Pathao getStores exception: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Retrieve cities list (Bangladesh country id = 1).
     */
    public function getCities(?string $token = null): array
    {
        $token = $token ?: $this->issueToken();
        if (!$token) {
            return [];
        }

        $cacheKey = 'pathao_cities_list';
        return Cache::remember($cacheKey, 86400, function () use ($token) {
            try {
                $baseUrl = $this->getBaseUrl();
                $response = Http::timeout(10)
                    ->withToken($token)
                    ->acceptJson()
                    ->get("{$baseUrl}/aladdin/api/v1/countries/1/city-list");

                if ($response->successful()) {
                    return $response->json()['data']['data'] ?? [];
                }
            } catch (\Throwable $e) {
                Log::error('Pathao getCities exception: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Retrieve zones list for a city.
     */
    public function getZones(int $cityId, ?string $token = null): array
    {
        $token = $token ?: $this->issueToken();
        if (!$token || !$cityId) {
            return [];
        }

        $cacheKey = "pathao_zones_city_{$cityId}";
        return Cache::remember($cacheKey, 86400, function () use ($token, $cityId) {
            try {
                $baseUrl = $this->getBaseUrl();
                $response = Http::timeout(10)
                    ->withToken($token)
                    ->acceptJson()
                    ->get("{$baseUrl}/aladdin/api/v1/cities/{$cityId}/zone-list");

                if ($response->successful()) {
                    return $response->json()['data']['data'] ?? [];
                }
            } catch (\Throwable $e) {
                Log::error('Pathao getZones exception: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Retrieve areas list for a zone.
     */
    public function getAreas(int $zoneId, ?string $token = null): array
    {
        $token = $token ?: $this->issueToken();
        if (!$token || !$zoneId) {
            return [];
        }

        $cacheKey = "pathao_areas_zone_{$zoneId}";
        return Cache::remember($cacheKey, 86400, function () use ($token, $zoneId) {
            try {
                $baseUrl = $this->getBaseUrl();
                $response = Http::timeout(10)
                    ->withToken($token)
                    ->acceptJson()
                    ->get("{$baseUrl}/aladdin/api/v1/zones/{$zoneId}/area-list");

                if ($response->successful()) {
                    return $response->json()['data']['data'] ?? [];
                }
            } catch (\Throwable $e) {
                Log::error('Pathao getAreas exception: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Dispatch an order to Pathao Courier.
     */
    public function createOrder(Order $order, array $custom = []): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Pathao API credentials are not configured in Store Settings.',
            ];
        }

        $token = $this->issueToken();
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Unable to authenticate with Pathao API. Please check your credentials.',
            ];
        }

        $storeId = $custom['store_id'] ?? Setting::get('pathao_store_id');
        if (empty($storeId)) {
            $stores = $this->getStores($token);
            $storeId = $stores[0]['store_id'] ?? null;
        }

        if (empty($storeId)) {
            return [
                'success' => false,
                'message' => 'No Pathao Store ID configured. Please specify a Store ID in Settings or Dispatch form.',
            ];
        }

        // Standardize recipient phone: remove non-digits, ensure 11 digits format
        $phone = preg_replace('/\D/', '', $custom['recipient_phone'] ?? $order->customer_phone);
        if (str_starts_with($phone, '880')) {
            $phone = substr($phone, 2);
        }

        $itemDescription = $custom['item_description'] ?? null;
        if (!$itemDescription) {
            $itemsSummary = $order->items->pluck('product_name')->take(2)->implode(', ');
            $itemDescription = 'Moutoshi Bag: ' . ($itemsSummary ?: 'Ladies Handbag');
        }

        // City & Zone selection
        $cityId = !empty($custom['recipient_city']) ? (int) $custom['recipient_city'] : 1;
        $zoneId = !empty($custom['recipient_zone']) ? (int) $custom['recipient_zone'] : 1;

        $amountToCollect = isset($custom['amount_to_collect']) 
            ? (float) $custom['amount_to_collect']
            : ($order->payment_status === 'paid' ? 0.0 : (float) $order->total);

        $payload = [
            'store_id' => (int) $storeId,
            'merchant_order_id' => $order->order_number,
            'recipient_name' => $custom['recipient_name'] ?? $order->customer_name,
            'recipient_phone' => $phone,
            'recipient_address' => $custom['recipient_address'] ?? $order->delivery_address,
            'recipient_city' => $cityId,
            'recipient_zone' => $zoneId,
            'delivery_type' => (int) ($custom['delivery_type'] ?? 48), // 48: Normal Delivery
            'item_type' => (int) ($custom['item_type'] ?? 2),         // 2: Parcel
            'special_instruction' => $custom['special_instruction'] ?? ($order->notes ?? 'Handle with care'),
            'item_quantity' => (int) ($custom['item_quantity'] ?? max(1, $order->items->sum('quantity'))),
            'item_weight' => max(0.5, (float) ($custom['item_weight'] ?? 0.5)),
            'amount_to_collect' => (int) round($amountToCollect),
            'item_description' => substr($itemDescription, 0, 200),
        ];

        if (!empty($custom['recipient_area'])) {
            $payload['recipient_area'] = (int) $custom['recipient_area'];
        }

        $baseUrl = $this->getBaseUrl();

        try {
            $response = Http::timeout(20)
                ->withToken($token)
                ->acceptJson()
                ->asJson()
                ->post("{$baseUrl}/aladdin/api/v1/orders", $payload);

            $data = $response->json();

            if ($response->successful() && !empty($data['data']['consignment_id'])) {
                $orderData = $data['data'];
                return [
                    'success' => true,
                    'consignment_id' => $orderData['consignment_id'],
                    'tracking_code' => $orderData['consignment_id'],
                    'courier_status' => $orderData['order_status'] ?? 'Pending',
                    'message' => $data['message'] ?? 'Order dispatched to Pathao successfully.',
                    'raw' => $data,
                ];
            }

            $errorMessage = $data['message'] ?? 'Failed to create order on Pathao.';
            if (!empty($data['errors'])) {
                $details = is_array($data['errors']) ? json_encode($data['errors']) : (string) $data['errors'];
                $errorMessage .= " ({$details})";
            }

            Log::warning('Pathao order dispatch failed: ' . json_encode($data));

            return [
                'success' => false,
                'message' => $errorMessage,
                'raw' => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('Pathao createOrder exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Network error connecting to Pathao API: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get live tracking info for a consignment.
     */
    public function trackOrder(string $consignmentId): array
    {
        $token = $this->issueToken();
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Authentication failed with Pathao.',
            ];
        }

        $baseUrl = $this->getBaseUrl();

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->acceptJson()
                ->get("{$baseUrl}/aladdin/api/v1/orders/{$consignmentId}/info");

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data['data'] ?? $data,
                    'status' => $data['data']['order_status'] ?? 'Unknown',
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not fetch status from Pathao: ' . $response->body(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }
}
