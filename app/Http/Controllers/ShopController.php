<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, ?string $categorySlug = null)
    {
        $currentCategory = null;
        $query = Product::where('is_active', true)->with(['category', 'variants', 'images']);

        if ($categorySlug && $categorySlug !== 'all') {
            $currentCategory = Category::where('slug', $categorySlug)->firstOrFail();
            $query->where('category_id', $currentCategory->id);
        }

        // Search query
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'featured');
        match ($sort) {
            'price-asc' => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            'newest' => $query->latest(),
            'best-selling' => $query->where('is_featured', true)->latest(),
            default => $query->orderBy('is_featured', 'desc')->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('pages.collection', compact('products', 'categories', 'currentCategory', 'sort'));
    }
}
