<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'button_text' => 'required|string|max:50',
            'button_url' => 'required|string|max:255',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'required|integer',
        ]);

        $imagePath = $request->image_url;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('banners', 'public');
        }

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'badge' => $request->badge,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'image_url' => $imagePath ?? 'https://thepatchee.com/cdn/shop/files/DESKTOP_SIZE.webp?v=1791810459&width=1800',
            'sort_order' => (int) $request->sort_order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'button_text' => 'required|string|max:50',
            'button_url' => 'required|string|max:255',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'required|integer',
        ]);

        $imagePath = $banner->image_url;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('banners', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'badge' => $request->badge,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'image_url' => $imagePath,
            'sort_order' => (int) $request->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
