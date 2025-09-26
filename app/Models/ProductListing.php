<?php

namespace App\Models;

use App\Enums\ListingPlatform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'platform',
        'external_id',
        'url',
        'listed_at',
        'sold_at',
        'notes',
    ];

    protected $casts = [
        'platform' => ListingPlatform::class,
        'listed_at' => 'datetime',
        'sold_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
