<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'min_price' => $this->variants->min('price'),
            'max_price' => $this->variants->max('price'),
            'total_stock' => $this->variants->sum('stock'),
            'average_rating' => (float) $this->average_rating,
            'reviews_count' => (int) $this->reviews_count,
            'variants' => $this->whenLoaded('variants', function () {
                return $this->variants->map(fn($v) => [
                    'id' => $v->id,
                    'name' => $v->name,
                    'sku' => $v->sku,
                    'price' => (float) $v->price,
                    'formatted_price' => rupiah($v->price),
                    'stock' => (int) $v->stock,
                    'weight_grams' => (int) $v->weight_grams,
                    'is_serialized' => (bool) $v->is_serialized,
                ]);
            }),
            'specifications' => $this->whenLoaded('specifications', function () {
                return $this->specifications->map(fn($s) => [
                    'group' => $s->attribute?->group?->name,
                    'name' => $s->attribute?->name,
                    'value' => $s->value,
                    'unit' => $s->attribute?->unit,
                ]);
            }),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
