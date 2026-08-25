<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'status_label' => ucfirst($this->status),
            'subtotal_amount' => (float) $this->subtotal_amount,
            'shipping_amount' => (float) $this->shipping_amount,
            'discount_amount' => (float) $this->discount_amount,
            'coupon_code' => $this->coupon_code,
            'total_amount' => (float) $this->total_amount,
            'formatted_total' => rupiah($this->total_amount),
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'shipping_courier' => $this->shipping_courier,
            'shipping_service' => $this->shipping_service,
            'shipping_tracking_number' => $this->shipping_tracking_number,
            'shipping_address' => $this->shipping_address,
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(fn($item) => [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'variant_name' => $item->variant_name,
                    'sku' => $item->sku,
                    'unit_price' => (float) $item->unit_price,
                    'quantity' => (int) $item->quantity,
                    'subtotal' => (float) $item->subtotal,
                ]);
            }),
            'created_at' => $this->created_at?->toISOString(),
            'paid_at' => $this->paid_at?->toISOString(),
        ];
    }
}
