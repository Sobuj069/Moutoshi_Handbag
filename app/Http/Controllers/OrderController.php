<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('pages.order-success', compact('order'));
    }

    public function track(Request $request)
    {
        $order = null;
        $searched = false;

        if ($request->filled('order_number')) {
            $searched = true;
            $query = Order::where('order_number', trim($request->order_number))->with('items');

            if ($request->filled('phone')) {
                $query->where('customer_phone', 'like', '%' . trim($request->phone) . '%');
            }

            $order = $query->first();
        }

        return view('pages.track-order', compact('order', 'searched'));
    }
}
