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
            $query->where('title', 'like', "%{$q}%")->orWhere('sku', 'like', "%{$q}%");
        }

        $products = $query->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
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

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
