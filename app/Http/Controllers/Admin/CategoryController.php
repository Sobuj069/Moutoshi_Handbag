<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $imagePath = $request->image;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('categories', 'public');
        }

        $slug = Str::slug($request->name);
        $count = Category::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured', true),
            'sort_order' => (int) $request->sort_order,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $imagePath = $category->image;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('categories', 'public');
        } elseif ($request->filled('image')) {
            $imagePath = $request->image;
        }

        $category->update([
            'name' => $request->name,
            'image' => $imagePath,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => (int) $request->sort_order,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
