<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $categories = Category::where('is_featured', true)->orderBy('sort_order')->get();
        
        // Just Landed / New Arrivals
        $justLanded = Product::where('is_active', true)
            ->where('is_new', true)
            ->with(['variants', 'images'])
            ->latest()
            ->take(8)
            ->get();

        // Best Sellers / Featured
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['variants', 'images'])
            ->take(8)
            ->get();

        // Sale Products
        $saleProducts = Product::where('is_active', true)
            ->where('is_sale', true)
            ->with(['variants', 'images'])
            ->take(8)
            ->get();

        $announcements = [
            Setting::get('announcement_1', 'No Tricks, Just BOO-tiful Deals 👻 | Up to 75% Off'),
            Setting::get('announcement_2', 'Free Delivery on prepaid orders over ৳2,500'),
            Setting::get('announcement_3', 'Cash On Delivery Available All Over Bangladesh 🇧🇩'),
        ];

        return view('pages.home', compact(
            'banners',
            'categories',
            'justLanded',
            'featuredProducts',
            'saleProducts',
            'announcements'
        ));
    }
}
