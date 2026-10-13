<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('info', 'Your cart is empty. Please add items to checkout.');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $insideDhaka = (float) Setting::get('delivery_inside_dhaka', 60);
        $outsideDhaka = (float) Setting::get('delivery_outside_dhaka', 120);

        return view('pages.checkout', compact('cart', 'subtotal', 'insideDhaka', 'outsideDhaka'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => ['required', 'regex:/^(?:\+?88|01)?\d{9,11}$/'],
            'delivery_address' => 'required|string|max:500',
            'city_zone' => 'required|in:inside_dhaka,outside_dhaka',
            'customer_email' => 'nullable|email|max:150',
            'payment_method' => 'nullable|string|in:cash_on_delivery,bkash,nagad',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $insideDhaka = (float) Setting::get('delivery_inside_dhaka', 60);
        $outsideDhaka = (float) Setting::get('delivery_outside_dhaka', 120);
        $deliveryCharge = ($request->city_zone === 'inside_dhaka') ? $insideDhaka : $outsideDhaka;

        $freeThreshold = (float) Setting::get('free_delivery_threshold', 2500);
        if ($subtotal >= $freeThreshold && $freeThreshold > 0) {
            $deliveryCharge = 0.00;
        }

        $total = $subtotal + $deliveryCharge;
        $orderNumber = 'MH-' . strtoupper(Str::random(2)) . rand(1000, 9999);

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'delivery_address' => $request->delivery_address,
            'city_zone' => $request->city_zone,
            'delivery_charge' => $deliveryCharge,
            'subtotal' => $subtotal,
            'discount' => 0.00,
            'total' => $total,
            'payment_method' => $request->payment_method ?? 'cash_on_delivery',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'notes' => $request->notes,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['title'],
                'variant_name' => $item['variant'] ?? null,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity'],
                'image_url' => $item['image'] ?? null,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('order.success', $order->order_number);
    }

    public function quickOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant' => 'nullable|string',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => ['required', 'regex:/^(?:\+?88|01)?\d{9,11}$/'],
            'delivery_address' => 'required|string|max:500',
            'city_zone' => 'required|in:inside_dhaka,outside_dhaka',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;
        $subtotal = $product->price * $quantity;

        $insideDhaka = (float) Setting::get('delivery_inside_dhaka', 60);
        $outsideDhaka = (float) Setting::get('delivery_outside_dhaka', 120);
        $deliveryCharge = ($request->city_zone === 'inside_dhaka') ? $insideDhaka : $outsideDhaka;

        $total = $subtotal + $deliveryCharge;
        $orderNumber = 'MH-' . strtoupper(Str::random(2)) . rand(1000, 9999);

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'delivery_address' => $request->delivery_address,
            'city_zone' => $request->city_zone,
            'delivery_charge' => $deliveryCharge,
            'subtotal' => $subtotal,
            'discount' => 0.00,
            'total' => $total,
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'notes' => $request->notes,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->title,
            'variant_name' => $request->variant,
            'price' => $product->price,
            'quantity' => $quantity,
            'total' => $subtotal,
            'image_url' => $product->primary_image_url,
        ]);

        return redirect()->route('order.success', $order->order_number);
    }
}
