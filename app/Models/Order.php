<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'city_zone',
        'delivery_charge',
        'subtotal',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'courier_name',
        'consignment_id',
        'tracking_code',
        'courier_status',
        'courier_response',
        'shipped_at',
        'notes',
    ];

    protected $casts = [
        'delivery_charge' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'courier_response' => 'array',
        'shipped_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return '৳ ' . number_format($this->total, 0);
    }

    public function isDispatchedToCourier(): bool
    {
        return !empty($this->consignment_id);
    }

    public function getPathaoTrackingUrlAttribute(): ?string
    {
        if (empty($this->consignment_id)) {
            return null;
        }
        $env = Setting::get('pathao_environment', 'sandbox');
        $baseUrl = ($env === 'production') 
            ? 'https://merchant.pathao.com/tracking' 
            : 'https://courier-api-sandbox.pathao.com/tracking';

        return "{$baseUrl}?consignment_id={$this->consignment_id}";
    }

    public function getCourierStatusBadgeClassAttribute(): string
    {
        $status = strtolower($this->courier_status ?? '');
        return match($status) {
            'pending', 'pickup_pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'in_transit', 'picked', 'pickup_assigned' => 'bg-blue-100 text-blue-800 border-blue-200',
            'delivered' => 'bg-green-100 text-green-800 border-green-200',
            'return', 'returned', 'cancelled' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-neutral-100 text-neutral-700 border-neutral-200'
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->order_status) {
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
            'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
            'delivered' => 'bg-green-100 text-green-800 border-green-200',
            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }
}
