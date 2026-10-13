<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }} - The Patchee BD</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #222;
            padding: 40px;
            font-size: 13px;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #1c1c1c;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .logo {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .tagline {
            font-size: 10px;
            color: #777;
            letter-spacing: 2px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th {
            background: #f7f7f7;
            padding: 10px;
            text-align: left;
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 1px solid #ddd;
        }
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }
        .total-box {
            text-align: right;
            margin-top: 20px;
        }
        .total-box p {
            margin: 4px 0;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            border-top: 2px solid #1c1c1c;
            padding-top: 8px;
            margin-top: 8px;
        }
        .print-btn {
            background: #1c1c1c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 11px;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; padding: 0; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="max-width: 800px; margin: auto; text-align: right;">
        <button class="print-btn" onclick="window.print()">Print Packing Invoice</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div>
                <div class="logo">PATCHEE</div>
                <div class="tagline">BANGLADESH • THE PATCHEE BD</div>
            </div>
            <div style="text-align: right;">
                <h2 style="margin: 0; font-size: 18px; text-transform: uppercase;">INVOICE</h2>
                <p style="margin: 3px 0 0; color: #555;">Order #{{ $order->order_number }}</p>
                <p style="margin: 2px 0 0; color: #777; font-size: 11px;">Date: {{ $order->created_at->format('d M, Y') }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div>
                <strong style="text-transform: uppercase; font-size: 11px; color: #888;">CUSTOMER / SHIP TO:</strong>
                <p style="margin: 5px 0 0; font-weight: bold; font-size: 14px;">{{ $order->customer_name }}</p>
                <p style="margin: 2px 0;">{{ $order->delivery_address }}</p>
                <p style="margin: 2px 0;">Zone: {{ $order->city_zone === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}</p>
                <p style="margin: 2px 0;">Phone: <strong>{{ $order->customer_phone }}</strong></p>
            </div>
            <div style="text-align: right;">
                <strong style="text-transform: uppercase; font-size: 11px; color: #888;">STORE INFORMATION:</strong>
                <p style="margin: 5px 0 0; font-weight: bold;">The Patchee Bangladesh</p>
                <p style="margin: 2px 0;">Banani, Road 11, Dhaka</p>
                <p style="margin: 2px 0;">Hotline: {{ \App\Models\Setting::get('phone', '+880 1711-223344') }}</p>
                <p style="margin: 2px 0;">Payment: <strong>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</strong></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Color / Variant</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td><strong>{{ $item->product_name }}</strong></td>
                        <td>{{ $item->variant_name ?? 'Default' }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">৳ {{ number_format($item->price, 0) }}</td>
                        <td style="text-align: right;">৳ {{ number_format($item->total, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-box">
            <p>Subtotal: ৳ {{ number_format($order->subtotal, 0) }}</p>
            <p>Delivery Charge: ৳ {{ number_format($order->delivery_charge, 0) }}</p>
            <p class="grand-total">Total Amount: ৳ {{ number_format($order->total, 0) }}</p>
        </div>

        <div style="margin-top: 40px; border-top: 1px dashed #ccc; padding-top: 15px; font-size: 11px; color: #777; text-align: center;">
            Thank you for shopping with The Patchee BD! Please inspect parcel upon delivery. For queries, contact us at {{ \App\Models\Setting::get('phone', '+880 1711-223344') }}.
        </div>
    </div>

</body>
</html>
