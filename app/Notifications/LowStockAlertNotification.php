<?php

namespace App\Notifications;

use App\Models\ProductVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockAlertNotification extends Notification
{
    use Queueable;

    public function __construct(public ProductVariant $variant)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'low_stock_alert',
            'title' => 'Peringatan Stok Kritis!',
            'message' => "Stok produk '{$this->variant->product?->name} ({$this->variant->name})' tersisa {$this->variant->stock} unit. Segera lakukan pengadaan stok ulang.",
            'url' => route('admin.inventaris.index'),
            'variant_id' => $this->variant->id,
            'sku' => $this->variant->sku,
            'current_stock' => $this->variant->stock,
        ];
    }
}
