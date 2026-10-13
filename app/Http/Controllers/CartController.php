<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $freeShippingThreshold = (float) Setting::get('free_delivery_threshold', 0);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('pages.cart', compact('cart', 'subtotal', 'freeShippingThreshold'));
    }

    public function data()
    {
        $cart = session()->get('cart', []);
        $items = array_values($cart);
        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $count = collect($items)->sum('quantity');
        $freeShippingThreshold = (float) Setting::get('free_delivery_threshold', 0);

        return response()->json([
            'items' => $items,
            'count' => $count,
            'subtotal' => $subtotal,
            'freeShippingThreshold' => $freeShippingThreshold,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'variant' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) ($request->quantity ?? 1);
        $variant = $request->variant ?? ($product->variants->first()?->name ?? 'Default');
        
        $cart = session()->get('cart', []);
        $cartKey = $product->id . '_' . md5($variant);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'compare_at_price' => (float) $product->compare_at_price,
                'image' => $product->primary_image_url,
                'variant' => $variant,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        $items = array_values($cart);
        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $count = collect($items)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'items' => $items,
            'count' => $count,
            'subtotal' => $subtotal,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);
        $cartKey = $request->cart_key;

        if (isset($cart[$cartKey])) {
            if ($request->quantity <= 0) {
                unset($cart[$cartKey]);
            } else {
                $cart[$cartKey]['quantity'] = $request->quantity;
            }
            session()->put('cart', $cart);
        }

        $items = array_values($cart);
        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $count = collect($items)->sum('quantity');

        return response()->json([
            'success' => true,
            'items' => $items,
            'count' => $count,
            'subtotal' => $subtotal,
        ]);
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}
