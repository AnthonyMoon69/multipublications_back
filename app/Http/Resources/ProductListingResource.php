<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'platform' => $this->platform?->value,
            'external_id' => $this->external_id,
            'url' => $this->url,
            'listed_at' => $this->listed_at?->toIso8601String(),
            'sold_at' => $this->sold_at?->toIso8601String(),
            'notes' => $this->notes,
        ];
    }
}
