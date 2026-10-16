<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Services\ProductAiImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'variants'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('section')) {
            match ($request->section) {
                'featured' => $query->where('is_featured', true),
                'sale' => $query->where('is_sale', true),
                'new' => $query->where('is_new', true),
                'active' => $query->where('is_active', true),
                'draft' => $query->where('is_active', false),
                default => null,
            };
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $counts = [
            'all' => Product::count(),
            'featured' => Product::where('is_featured', true)->count(),
            'sale' => Product::where('is_sale', true)->count(),
            'new' => Product::where('is_new', true)->count(),
            'active' => Product::where('is_active', true)->count(),
            'draft' => Product::where('is_active', false)->count(),
        ];

        $categories = Category::orderBy('name')->get();
        $products = $query->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products', 'counts', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function aiImportAnalyze(Request $request, ProductAiImportService $aiService)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $result = $aiService->importFromUrl($request->url);

        return response()->json($result);
    }

    public function aiImportQuickCreate(Request $request, ProductAiImportService $aiService)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'primary_image_path' => 'required|string',
        ]);

        $product = $aiService->createProductFromData($request->all());

        return response()->json([
            'success' => true,
            'message' => "Product '{$product->title}' imported and published successfully!",
            'redirect_url' => route('admin.products.index'),
            'product' => $product,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'primary_image' => 'nullable|string',
            'ai_downloaded_image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $slug = Str::slug($request->title);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        $imagePath = $request->primary_image;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('products', 'public');
            $uploadsDir = public_path('uploads/products');
            if (!is_dir($uploadsDir)) {
                @mkdir($uploadsDir, 0755, true);
            }
            @copy(storage_path('app/public/' . $imagePath), $uploadsDir . '/' . basename($imagePath));

            if (!is_link(public_path('storage'))) {
                $storagePubDir = public_path('storage/products');
                if (!is_dir($storagePubDir)) {
                    @mkdir($storagePubDir, 0755, true);
                }
                @copy(storage_path('app/public/' . $imagePath), $storagePubDir . '/' . basename($imagePath));
            }
        } elseif ($request->filled('ai_downloaded_image')) {
            $imagePath = $request->ai_downloaded_image;
        }

        $product = Product::create([
            'title' => $request->title,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'sku' => $request->sku ?? ('MH-PROD-' . strtoupper(Str::random(4))),
            'price' => $request->price,
            'compare_at_price' => $request->compare_at_price,
            'stock' => $request->stock,
            'primary_image' => $imagePath,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'is_sale' => $request->boolean('is_sale'),
            'is_new' => $request->boolean('is_new'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Process manually uploaded gallery images
        $sort = 1;
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                if ($file && $file->isValid()) {
                    $gPath = $file->store('products', 'public');
                    $uploadsDir = public_path('uploads/products');
                    if (!is_dir($uploadsDir)) {
                        @mkdir($uploadsDir, 0755, true);
                    }
                    @copy(storage_path('app/public/' . $gPath), $uploadsDir . '/' . basename($gPath));

                    if (!is_link(public_path('storage'))) {
                        $storagePubDir = public_path('storage/products');
                        if (!is_dir($storagePubDir)) {
                            @mkdir($storagePubDir, 0755, true);
                        }
                        @copy(storage_path('app/public/' . $gPath), $storagePubDir . '/' . basename($gPath));
                    }

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $gPath,
                        'is_primary' => false,
                        'sort_order' => $sort++,
                    ]);
                }
            }
        }

        if ($request->filled('ai_gallery_images')) {
            // Attach gallery images if available from AI import
            $gallery = is_array($request->ai_gallery_images) ? $request->ai_gallery_images : json_decode($request->ai_gallery_images, true);
            if (is_array($gallery)) {
                foreach ($gallery as $g) {
                    $gPath = is_array($g) ? ($g['path'] ?? null) : $g;
                    if ($gPath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_url' => $gPath,
                            'is_primary' => false,
                            'sort_order' => $sort++,
                        ]);
                    }
                }
            }
        }

        // Process variants if provided
        if ($request->filled('variant_names')) {
            $names = explode(',', $request->variant_names);
            foreach ($names as $name) {
                $name = trim($name);
                if ($name) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $name,
                        'stock' => 15,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully with all images.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load(['variants', 'images']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'primary_image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imagePath = $product->primary_image;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('products', 'public');
            $uploadsDir = public_path('uploads/products');
            if (!is_dir($uploadsDir)) {
                @mkdir($uploadsDir, 0755, true);
            }
            @copy(storage_path('app/public/' . $imagePath), $uploadsDir . '/' . basename($imagePath));

            if (!is_link(public_path('storage'))) {
                $storagePubDir = public_path('storage/products');
                if (!is_dir($storagePubDir)) {
                    @mkdir($storagePubDir, 0755, true);
                }
                @copy(storage_path('app/public/' . $imagePath), $storagePubDir . '/' . basename($imagePath));
            }
        } elseif ($request->filled('primary_image')) {
            $imagePath = $request->primary_image;
        }

        $product->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'sku' => $request->sku,
            'price' => $request->price,
            'compare_at_price' => $request->compare_at_price,
            'stock' => $request->stock,
            'primary_image' => $imagePath,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'is_sale' => $request->boolean('is_sale'),
            'is_new' => $request->boolean('is_new'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Process deletion of requested gallery images
        if ($request->filled('delete_gallery_images')) {
            $delIds = is_array($request->delete_gallery_images)
                ? $request->delete_gallery_images
                : explode(',', $request->delete_gallery_images);

            $imagesToDelete = ProductImage::where('product_id', $product->id)
                ->whereIn('id', $delIds)
                ->get();

            foreach ($imagesToDelete as $delImg) {
                @unlink(storage_path('app/public/' . $delImg->image_url));
                @unlink(public_path('uploads/' . $delImg->image_url));
                @unlink(public_path('storage/' . $delImg->image_url));
                $delImg->delete();
            }
        }

        // Process new gallery images upload
        if ($request->hasFile('gallery_images')) {
            $sort = ($product->images()->max('sort_order') ?? 0) + 1;
            foreach ($request->file('gallery_images') as $file) {
                if ($file && $file->isValid()) {
                    $gPath = $file->store('products', 'public');
                    $uploadsDir = public_path('uploads/products');
                    if (!is_dir($uploadsDir)) {
                        @mkdir($uploadsDir, 0755, true);
                    }
                    @copy(storage_path('app/public/' . $gPath), $uploadsDir . '/' . basename($gPath));

                    if (!is_link(public_path('storage'))) {
                        $storagePubDir = public_path('storage/products');
                        if (!is_dir($storagePubDir)) {
                            @mkdir($storagePubDir, 0755, true);
                        }
                        @copy(storage_path('app/public/' . $gPath), $storagePubDir . '/' . basename($gPath));
                    }

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $gPath,
                        'is_primary' => false,
                        'sort_order' => $sort++,
                    ]);
                }
            }
        }

        if ($request->filled('variant_names')) {
            ProductVariant::where('product_id', $product->id)->delete();
            $names = explode(',', $request->variant_names);
            foreach ($names as $name) {
                $name = trim($name);
                if ($name) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $name,
                        'stock' => 15,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function deleteGalleryImage(ProductImage $image)
    {
        @unlink(storage_path('app/public/' . $image->image_url));
        @unlink(public_path('uploads/' . $image->image_url));
        @unlink(public_path('storage/' . $image->image_url));
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gallery image deleted successfully.',
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:mark_featured,unmark_featured,mark_sale,unmark_sale,mark_new,unmark_new,set_active,set_draft,delete',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ]);

        $ids = $request->product_ids;
        $count = count($ids);

        match ($request->action) {
            'mark_featured' => Product::whereIn('id', $ids)->update(['is_featured' => true]),
            'unmark_featured' => Product::whereIn('id', $ids)->update(['is_featured' => false]),
            'mark_sale' => Product::whereIn('id', $ids)->update(['is_sale' => true]),
            'unmark_sale' => Product::whereIn('id', $ids)->update(['is_sale' => false]),
            'mark_new' => Product::whereIn('id', $ids)->update(['is_new' => true]),
            'unmark_new' => Product::whereIn('id', $ids)->update(['is_new' => false]),
            'set_active' => Product::whereIn('id', $ids)->update(['is_active' => true]),
            'set_draft' => Product::whereIn('id', $ids)->update(['is_active' => false]),
            'delete' => Product::whereIn('id', $ids)->delete(),
        };

        return back()->with('success', "Bulk action applied successfully to {$count} product(s).");
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
