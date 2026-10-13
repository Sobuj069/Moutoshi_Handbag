<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductAiImportService
{
    protected string $apiKey;
    protected array $models = [
        'gemini-3.5-flash-lite',
        'gemini-flash-lite-latest',
        'gemini-3.5-flash',
        'gemini-3.1-flash-lite',
    ];

    public function __construct()
    {
        $this->apiKey = (string) (Setting::get('gemini_api_key') ?: config('services.gemini.api_key', ''));
    }

    /**
     * Import and structure product data from any URL (Pinterest or e-commerce/web).
     */
    public function importFromUrl(string $url): array
    {
        $url = trim($url);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'success' => false,
                'message' => 'Please provide a valid web URL (e.g. Pinterest, Shopify, Daraz, etc.).',
            ];
        }

        $isPinterest = (bool) preg_match('/(pinterest\.com|pin\.it)/i', $url);

        if ($isPinterest) {
            $scraped = $this->scrapePinterestFast($url);
            $effectiveUrl = $scraped['effective_url'];
        } else {
            $fetchResult = $this->fetchUrlContent($url);
            if (!$fetchResult['success']) {
                return [
                    'success' => false,
                    'message' => 'Could not access the provided URL: ' . $fetchResult['error'],
                ];
            }
            $effectiveUrl = $fetchResult['effective_url'];
            $scraped = $this->scrapeGenericWeb($effectiveUrl, $fetchResult['html']);
        }

        // Download and filter high-quality images
        $downloadedImages = $this->downloadHighQualityImages($scraped['image_candidates']);

        if (empty($downloadedImages)) {
            return [
                'success' => false,
                'message' => 'Could not extract high-quality product images from this link. Please verify the URL.',
            ];
        }

        $primaryImage = $downloadedImages[0];
        $galleryImages = array_slice($downloadedImages, 1, 4);

        // Generate structured product attributes via Gemini AI
        $categories = Category::all(['id', 'name', 'slug']);
        $aiData = $this->generateProductDetailsWithGemini($effectiveUrl, $scraped, $categories);

        // Category fallback
        $categoryId = $aiData['category_id'] ?? null;
        if (!$categoryId || !Category::where('id', $categoryId)->exists()) {
            $fallback = Category::where('slug', 'tote-bags')->orWhere('slug', 'handbags')->first() ?? Category::first();
            $categoryId = $fallback?->id;
            $aiData['category_name'] = $fallback?->name ?? 'Handbags';
        }

        $sku = 'MH-AI-' . strtoupper(Str::random(4));

        return [
            'success' => true,
            'source_url' => $effectiveUrl,
            'is_pinterest' => $isPinterest,
            'data' => [
                'title' => $aiData['title'] ?? ($scraped['title'] ?: 'Moutoshi Aesthetic Handcrafted Bag'),
                'category_id' => $categoryId,
                'category_name' => $aiData['category_name'] ?? 'Tote Bags',
                'price' => (float) ($aiData['price'] ?? 850),
                'compare_at_price' => (float) ($aiData['compare_at_price'] ?? 1250),
                'stock' => 50,
                'sku' => $sku,
                'brand' => $aiData['brand'] ?? 'Moutoshi',
                'variants' => $aiData['variants'] ?? 'Off-White, Black, Beige',
                'short_description' => $aiData['short_description'] ?? 'Artisanal handcrafted bag designed with aesthetic charm and practical elegance.',
                'description' => $aiData['description'] ?? 'Aesthetic bag crafted with premium quality materials, sturdy stitching, and spacious interior compartments for everyday styling.',
                'primary_image_path' => $primaryImage['path'],
                'primary_image_url' => $primaryImage['url'],
                'primary_image_width' => $primaryImage['width'],
                'primary_image_height' => $primaryImage['height'],
                'primary_image_size_kb' => round($primaryImage['bytes'] / 1024, 1),
                'gallery_images' => $galleryImages,
                'is_featured' => true,
                'is_sale' => true,
                'is_new' => true,
                'is_active' => true,
            ],
        ];
    }

    /**
     * Pinterest fast scraper using oEmbed + resolution upgrade (under 1 second).
     */
    protected function scrapePinterestFast(string $url): array
    {
        $effectiveUrl = $url;
        $title = '';
        $description = '';
        $author = '';
        $imageCandidates = [];

        // If short link pin.it, resolve destination URL
        if (stripos($url, 'pin.it') !== false || !preg_match('/pin\/(\d+)/', $url)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: $url;
            curl_close($ch);
        }

        // Extract Pin ID
        if (preg_match('/pin\/(\d+)/', $effectiveUrl, $mPin)) {
            $pinId = $mPin[1];
            $oembedUrl = "https://www.pinterest.com/oembed.json?url=" . urlencode("https://www.pinterest.com/pin/$pinId/");

            $ch = curl_init($oembedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $oembedRes = curl_exec($ch);
            curl_close($ch);

            $oembed = json_decode($oembedRes, true);
            if (!empty($oembed)) {
                $title = trim($oembed['title'] ?? '');
                $author = trim($oembed['author_name'] ?? '');
                if (!empty($oembed['thumbnail_url'])) {
                    $thumb = $oembed['thumbnail_url'];
                    // High-res upgrades
                    $imageCandidates[] = preg_replace('/\/[0-9]+x\//', '/originals/', $thumb);
                    $imageCandidates[] = preg_replace('/\/[0-9]+x\//', '/736x/', $thumb);
                    $imageCandidates[] = $thumb;
                }
            }
        }

        // Quick attempt to get extra description if title is empty or short
        if (empty($title) || strlen($title) < 10) {
            $ch = curl_init($effectiveUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $html = (string) curl_exec($ch);
            curl_close($ch);

            if ($html) {
                if (preg_match('/<meta property="og:title" content="([^"]+)"/i', $html, $m)) {
                    $title = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
                }
                if (preg_match('/<meta property="og:description" content="([^"]+)"/i', $html, $m)) {
                    $description = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
                }
                if (preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $m)) {
                    $ogImg = $m[1];
                    $imageCandidates[] = preg_replace('/\/[0-9]+x\//', '/originals/', $ogImg);
                    $imageCandidates[] = preg_replace('/\/[0-9]+x\//', '/736x/', $ogImg);
                }
            }
        }

        return [
            'effective_url' => $effectiveUrl,
            'title' => $title,
            'description' => $description,
            'author' => $author,
            'brand' => $author,
            'image_candidates' => array_values(array_unique(array_filter($imageCandidates))),
        ];
    }

    /**
     * Create Product directly from imported data.
     */
    public function createProductFromData(array $data): Product
    {
        $title = $data['title'] ?? 'Moutoshi Imported Bag';
        $slug = Str::slug($title);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        $product = Product::create([
            'title' => $title,
            'slug' => $slug,
            'category_id' => $data['category_id'] ?? null,
            'sku' => $data['sku'] ?? ('MH-AI-' . strtoupper(Str::random(4))),
            'price' => $data['price'] ?? 850,
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'stock' => $data['stock'] ?? 50,
            'primary_image' => $data['primary_image_path'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'is_featured' => !empty($data['is_featured']),
            'is_sale' => !empty($data['is_sale']),
            'is_new' => !empty($data['is_new']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        // Attach gallery images
        if (!empty($data['gallery_images']) && is_array($data['gallery_images'])) {
            $sort = 1;
            foreach ($data['gallery_images'] as $img) {
                $imgPath = is_array($img) ? ($img['path'] ?? null) : $img;
                if ($imgPath) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $imgPath,
                        'is_primary' => false,
                        'sort_order' => $sort++,
                    ]);
                }
            }
        }

        // Attach variants
        if (!empty($data['variants'])) {
            $variants = is_array($data['variants']) ? $data['variants'] : explode(',', (string) $data['variants']);
            foreach ($variants as $var) {
                $var = trim($var);
                if ($var) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $var,
                        'stock' => 15,
                    ]);
                }
            }
        }

        return $product;
    }

    /**
     * Fetch generic web URL content.
     */
    protected function fetchUrlContent(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
        ]);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_ENCODING, '');

        $html = curl_exec($ch);
        $err = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: $url;
        curl_close($ch);

        if ($err || $code >= 400 || !$html) {
            return [
                'success' => false,
                'error' => $err ?: "Server returned HTTP $code",
            ];
        }

        return [
            'success' => true,
            'html' => $html,
            'effective_url' => $effectiveUrl,
        ];
    }

    /**
     * Generic E-Commerce Web Scraper (Shopify, Daraz, Amazon, Zara, WooCommerce, etc.)
     */
    protected function scrapeGenericWeb(string $url, string $html): array
    {
        $title = '';
        $description = '';
        $brand = '';
        $price = null;
        $imageCandidates = [];

        // 1. JSON-LD Schema
        if (preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $html, $matches)) {
            foreach ($matches[1] as $jsonStr) {
                $data = json_decode($jsonStr, true);
                if (is_array($data)) {
                    $this->parseJsonLd($data, $title, $description, $brand, $price, $imageCandidates);
                }
            }
        }

        // 2. OpenGraph & Meta Tags
        if (empty($title) && preg_match('/<meta property="og:title" content="([^"]+)"/i', $html, $m)) {
            $title = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
        }
        if (empty($description) && preg_match('/<meta property="og:description" content="([^"]+)"/i', $html, $m)) {
            $description = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
        }
        if (preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $m)) {
            $imageCandidates[] = $m[1];
        }
        if (preg_match('/<meta name="twitter:image" content="([^"]+)"/i', $html, $m)) {
            $imageCandidates[] = $m[1];
        }

        // HTML Fallback for Title
        if (empty($title)) {
            if (preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $html, $m)) {
                $title = trim(strip_tags($m[1]));
            } elseif (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                $title = trim(strip_tags($m[1]));
            }
        }

        // HTML Fallback for Description
        if (empty($description) && preg_match('/<meta name="description" content="([^"]+)"/i', $html, $m)) {
            $description = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
        }

        // Find high-res images in <img> tags
        if (preg_match_all('/<img[^>]+(data-zoom-image|data-high-res|data-src|src)=["\']([^"\']+\.(jpg|jpeg|png|webp)[^"\']*)["\']/i', $html, $imgMatches)) {
            foreach ($imgMatches[2] as $src) {
                if (preg_match('/(logo|icon|badge|avatar|sprite)/i', $src)) continue;
                $imageCandidates[] = $this->resolveAbsoluteUrl($src, $url);
            }
        }

        return [
            'effective_url' => $url,
            'title' => $title,
            'description' => $description,
            'author' => $brand,
            'brand' => $brand,
            'price' => $price,
            'image_candidates' => array_values(array_unique(array_filter($imageCandidates))),
        ];
    }

    /**
     * Recursively parse schema.org Product data
     */
    protected function parseJsonLd(array $data, string &$title, string &$desc, string &$brand, &$price, array &$images): void
    {
        $type = $data['@type'] ?? '';
        if (is_array($type)) $type = implode(' ', $type);

        if (stripos($type, 'Product') !== false) {
            if (empty($title) && !empty($data['name'])) $title = $data['name'];
            if (empty($desc) && !empty($data['description'])) $desc = $data['description'];
            if (empty($brand) && !empty($data['brand']['name'])) $brand = $data['brand']['name'];
            
            if (!empty($data['image'])) {
                if (is_string($data['image'])) $images[] = $data['image'];
                elseif (is_array($data['image'])) {
                    foreach ($data['image'] as $im) {
                        if (is_string($im)) $images[] = $im;
                        elseif (is_array($im) && !empty($im['url'])) $images[] = $im['url'];
                    }
                }
            }

            if (!empty($data['offers'])) {
                $offers = isset($data['offers'][0]) ? $data['offers'][0] : $data['offers'];
                if (!empty($offers['price'])) $price = (float) $offers['price'];
            }
        }

        foreach ($data as $v) {
            if (is_array($v)) {
                $this->parseJsonLd($v, $title, $desc, $brand, $price, $images);
            }
        }
    }

    /**
     * Convert relative image URLs to absolute
     */
    protected function resolveAbsoluteUrl(string $src, string $baseUrl): string
    {
        if (str_starts_with($src, '//')) return 'https:' . $src;
        if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) return $src;

        $parsed = parse_url($baseUrl);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? '';

        if (str_starts_with($src, '/')) {
            return "{$scheme}://{$host}{$src}";
        }

        return "{$scheme}://{$host}/" . ltrim($src, '/');
    }

    /**
     * Download candidate images and verify high resolution (>= 300x300 px).
     */
    protected function downloadHighQualityImages(array $candidates): array
    {
        $downloaded = [];

        foreach ($candidates as $imgUrl) {
            if (count($downloaded) >= 5) break;

            $ch = curl_init($imgUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $binary = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || !$binary || strlen($binary) < 5000) {
                continue;
            }

            $imgInfo = @getimagesizefromstring($binary);
            if (!$imgInfo) continue;

            $width = $imgInfo[0];
            $height = $imgInfo[1];
            $mime = $imgInfo['mime'] ?? '';

            // Ensure High Quality (minimum 300x300 px)
            if ($width < 300 || $height < 300) {
                continue;
            }

            $ext = match ($mime) {
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => 'jpg',
            };

            $filename = 'ai_import_' . now()->format('Ymd_His') . '_' . Str::random(6) . '.' . $ext;
            $relativeDir = 'products';
            $relativePath = $relativeDir . '/' . $filename;

            Storage::disk('public')->put($relativePath, $binary);

            $downloaded[] = [
                'path' => $relativePath,
                'url' => asset('storage/' . $relativePath),
                'width' => $width,
                'height' => $height,
                'mime' => $mime,
                'bytes' => strlen($binary),
            ];
        }

        return $downloaded;
    }

    /**
     * Call Google Gemini API to structure the product into Moutoshi format.
     */
    protected function generateProductDetailsWithGemini(string $url, array $scraped, $categories): array
    {
        $categoriesList = $categories->map(fn($c) => "- ID {$c->id}: {$c->name} ({$c->slug})")->implode("\n");

        $prompt = "You are a senior fashion merchandiser for 'Moutoshi Handbag' (website: moutoshihandbag.shop), an elegant aesthetic boutique selling handcrafted botanical canvas totes, utility bags, shoulder bags, and luxury handbags in Bangladesh.
A product link was imported:
URL: {$url}
Raw Title: " . ($scraped['title'] ?: 'Aesthetic Handcrafted Handbag') . "
Raw Brand/Author: " . ($scraped['brand'] ?: ($scraped['author'] ?: 'Moutoshi')) . "
Raw Description: " . ($scraped['description'] ?: 'High quality aesthetic handbag with premium stitching and interior pockets.') . "

Available Categories in Store:
{$categoriesList}

Task:
Generate a high-converting, professional e-commerce product entry.
- Title: Catchy, aesthetic title in English (e.g. 'Moutoshi Wildflower Embroidered Canvas Tote Bag').
- Category: Select the best matching category from the available store categories list. Return both its numeric category_id and its category_name.
- Pricing in Bangladeshi Taka (৳):
  - Canvas totes typically 750 to 950 BDT.
  - Utility/Multi-pocket bags 850 to 1150 BDT.
  - Shoulder / Leather / Duo sets 1150 to 1650 BDT.
  - Compare price (MRP) should be 30-45% higher than sale price.
- Brand: 'Moutoshi' (or keep original luxury brand if well-known).
- Variants: 2 to 4 comma-separated stylish color names (e.g. 'Off-White, Olive Green, Classic Black').
- Short Description: 1 to 2 engaging sentences highlighting style and practicality.
- Description: Detailed, structured product copy with features, materials (heavy-duty canvas, metal zippers), pocket compartments, and styling tips.

Return ONLY a valid JSON object matching this exact schema:
{
  \"title\": \"String\",
  \"category_id\": Number,
  \"category_name\": \"String\",
  \"price\": Number,
  \"compare_at_price\": Number,
  \"brand\": \"String\",
  \"variants\": \"String\",
  \"short_description\": \"String\",
  \"description\": \"String\"
}";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'temperature' => 0.4,
            ]
        ];

        // Attempt models with automatic fallback
        foreach ($this->models as $model) {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($this->apiKey);

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $res = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $res) {
                $decoded = json_decode($res, true);
                $text = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '';
                if ($text) {
                    $json = json_decode($text, true);
                    if (is_array($json) && !empty($json['title'])) {
                        return $json;
                    }
                }
            } else {
                Log::warning("Gemini AI model {$model} returned HTTP {$httpCode}: " . substr((string) $res, 0, 200));
            }
        }

        // Fallback default if AI endpoints are all unreachable
        return [
            'title' => $scraped['title'] ?: 'Moutoshi Aesthetic Handcrafted Bag',
            'category_id' => 2,
            'category_name' => 'Tote Bags',
            'price' => 850,
            'compare_at_price' => 1250,
            'brand' => 'Moutoshi',
            'variants' => 'Off-White, Black, Beige',
            'short_description' => 'Aesthetic handcrafted bag designed for everyday elegance and versatile functionality.',
            'description' => 'Crafted with premium high-density canvas and reinforced stitching, this versatile bag is perfect for university, work, and casual weekend outings.',
        ];
    }

    /**
     * Test Gemini API Connection
     */
    public function testConnection(?string $key = null): array
    {
        $apiKey = $key ?: $this->apiKey;
        $payload = json_encode([
            'contents' => [
                ['parts' => [['text' => 'Respond in JSON: {"status": "ok", "app": "Moutoshi Handbag"}']]]
            ],
            'generationConfig' => ['responseMimeType' => 'application/json']
        ]);

        foreach ($this->models as $model) {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($apiKey);
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $res = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return [
                    'success' => true,
                    'model' => $model,
                    'message' => "Gemini AI connection successful using model: {$model}.",
                ];
            }
        }

        return [
            'success' => false,
            'message' => "Could not connect to Google Gemini API. Please verify the API key.",
        ];
    }
}
