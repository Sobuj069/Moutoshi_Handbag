<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PathaoService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('courier')) {
            if ($request->courier === 'pathao') {
                $query->whereNotNull('consignment_id');
            } elseif ($request->courier === 'unassigned') {
                $query->whereNull('consignment_id');
            }
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($b) use ($q) {
                $b->where('order_number', 'like', "%{$q}%")
                  ->orWhere('customer_name', 'like', "%{$q}%")
                  ->orWhere('customer_phone', 'like', "%{$q}%")
                  ->orWhere('consignment_id', 'like', "%{$q}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order, PathaoService $pathaoService)
    {
        $order->load(['items.product']);
        $pathaoConfigured = $pathaoService->isConfigured();
        $pathaoStores = $pathaoConfigured ? $pathaoService->getStores() : [];
        $pathaoCities = $pathaoConfigured ? $pathaoService->getCities() : [];

        return view('admin.orders.show', compact('order', 'pathaoConfigured', 'pathaoStores', 'pathaoCities'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed',
        ]);

        $order->update([
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status ?? $order->payment_status,
        ]);

        return back()->with('success', "Order #{$order->order_number} status updated to {$request->order_status}.");
    }

    public function sendToPathao(Request $request, Order $order, PathaoService $pathaoService)
    {
        if ($order->isDispatchedToCourier()) {
            return back()->with('error', "Order #{$order->order_number} has already been sent to Pathao with Consignment ID: {$order->consignment_id}.");
        }

        $request->validate([
            'recipient_name' => 'nullable|string|max:100',
            'recipient_phone' => 'nullable|string|max:20',
            'recipient_address' => 'nullable|string|max:500',
            'recipient_city' => 'nullable|integer',
            'recipient_zone' => 'nullable|integer',
            'recipient_area' => 'nullable|integer',
            'item_weight' => 'nullable|numeric|min:0.5',
            'amount_to_collect' => 'nullable|numeric|min:0',
            'special_instruction' => 'nullable|string|max:300',
            'store_id' => 'nullable|integer',
        ]);

        $result = $pathaoService->createOrder($order, $request->all());

        if ($result['success']) {
            $order->update([
                'courier_name' => 'Pathao',
                'consignment_id' => $result['consignment_id'],
                'tracking_code' => $result['tracking_code'],
                'courier_status' => $result['courier_status'] ?? 'Pending',
                'courier_response' => $result['raw'] ?? null,
                'order_status' => ($order->order_status === 'pending') ? 'processing' : $order->order_status,
                'shipped_at' => now(),
            ]);

            return back()->with('success', "Order #{$order->order_number} dispatched to Pathao Courier successfully! Consignment ID: {$result['consignment_id']}");
        }

        return back()->with('error', "Pathao Dispatch Error: {$result['message']}");
    }

    public function trackPathao(Order $order, PathaoService $pathaoService)
    {
        if (!$order->consignment_id) {
            return back()->with('error', 'This order has no Pathao Consignment ID yet.');
        }

        $result = $pathaoService->trackOrder($order->consignment_id);

        if ($result['success']) {
            $newStatus = $result['status'] ?? $order->courier_status;
            $currentResponse = is_array($order->courier_response) ? $order->courier_response : [];
            $currentResponse['latest_track'] = $result['data'] ?? [];

            $order->update([
                'courier_status' => $newStatus,
                'courier_response' => $currentResponse,
            ]);

            return back()->with('success', "Pathao status updated: {$newStatus}");
        }

        return back()->with('error', "Failed to fetch status from Pathao: {$result['message']}");
    }

    public function getPathaoZones(Request $request, PathaoService $pathaoService)
    {
        $cityId = (int) $request->get('city_id');
        if (!$cityId) {
            return response()->json([]);
        }

        $zones = $pathaoService->getZones($cityId);
        return response()->json($zones);
    }

    public function getPathaoAreas(Request $request, PathaoService $pathaoService)
    {
        $zoneId = (int) $request->get('zone_id');
        if (!$zoneId) {
            return response()->json([]);
        }

        $areas = $pathaoService->getAreas($zoneId);
        return response()->json($areas);
    }

    public function invoice(Order $order)
    {
        $order->load(['items.product']);
        return view('admin.orders.invoice', compact('order'));
    }
}
