<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
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
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        $imagePath = $request->primary_image;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('products', 'public');
        }

        $product = Product::create([
            'title' => $request->title,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'sku' => $request->sku ?? ('PTC-' . strtoupper(Str::random(4))),
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

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
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
        ]);

        $imagePath = $product->primary_image;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('products', 'public');
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
