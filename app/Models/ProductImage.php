<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        $img = trim((string) $this->image_url);

        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }

        if (str_starts_with($img, 'assets/') || str_starts_with($img, 'images/')) {
            return asset($img);
        }

        if (str_starts_with($img, 'uploads/')) {
            return asset($img);
        }

        if (file_exists(public_path('uploads/' . $img))) {
            return asset('uploads/' . $img);
        }

        if (str_starts_with($img, 'storage/')) {
            return asset($img);
        }

        return asset('storage/' . ltrim($img, '/'));
    }
}
