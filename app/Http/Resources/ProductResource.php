<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $user_id
 */

class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'brand' => $this->brand,
            'gender' => $this->gender?->value,
            'condition' => $this->condition?->value,
            'price' => (float) $this->price,
            'currency' => $this->currency,
            'size' => $this->size,
            'is_sold' => $this->is_sold,
            'notes' => $this->notes,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'listings' => ProductListingResource::collection($this->whenLoaded('listings')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
