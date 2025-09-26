<?php

namespace App\Models;

use App\Enums\ConditionType;
use App\Enums\GenderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'brand',
        'gender',
        'condition',
        'price',
        'currency',
        'size',
        'is_sold',
        'notes',
    ];

    protected $casts = [
        'gender' => GenderType::class,
        'condition' => ConditionType::class,
        'price' => 'decimal:2',
        'is_sold' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(ProductListing::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['brand'] ?? null, fn ($q, $brand) => $q->where('brand', 'like', "%{$brand}%"))
            ->when($filters['gender'] ?? null, fn ($q, $gender) => $q->where('gender', $gender))
            ->when($filters['condition'] ?? null, fn ($q, $condition) => $q->where('condition', $condition))
            ->when($filters['size'] ?? null, fn ($q, $size) => $q->where('size', $size))
            ->when($filters['is_sold'] ?? null, fn ($q, $sold) => $q->where('is_sold', filter_var($sold, FILTER_VALIDATE_BOOLEAN)))
            ->when($filters['platform'] ?? null, function ($q, $platform) {
                $platform = strtolower($platform);

                $q->whereHas('listings', fn ($listingQuery) => $listingQuery->where('platform', $platform));
            })
            ->when($filters['min_price'] ?? null, fn ($q, $price) => $q->where('price', '>=', $price))
            ->when($filters['max_price'] ?? null, fn ($q, $price) => $q->where('price', '<=', $price))
            ->when($filters['created_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['created_to'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
    }
}
