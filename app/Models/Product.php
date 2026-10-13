<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'compare_at_price',
        'stock',
        'primary_image',
        'is_featured',
        'is_sale',
        'is_new',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_sale' => 'boolean',
        'is_new' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->compare_at_price && $this->compare_at_price > $this->price) {
            return (int) round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
        }
        return 0;
    }

    public function getNameAttribute(): string
    {
        return $this->title ?? '';
    }

    public function getFormattedPriceAttribute(): string
    {
        return '৳ ' . number_format($this->price, 0);
    }

    public function getFormattedComparePriceAttribute(): ?string
    {
        return $this->compare_at_price ? '৳ ' . number_format($this->compare_at_price, 0) : null;
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        if ($this->primary_image) {
            if (str_starts_with($this->primary_image, 'http')) {
                return $this->primary_image;
            }
            if (str_starts_with($this->primary_image, 'assets/') || str_starts_with($this->primary_image, 'images/')) {
                return asset($this->primary_image);
            }
            return asset('storage/' . $this->primary_image);
        }
        return asset('assets/images/logo.png');
    }
}
