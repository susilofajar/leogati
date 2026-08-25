<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $previousStatus, public ?string $note = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'paid' => 'Pembayaran Dikonfirmasi',
            'processing' => 'Pesanan Sedang Diproses',
            'packed' => 'Pesanan Sedang Dikemas',
            'shipped' => 'Pesanan Telah Dikirim',
            'delivered' => 'Pesanan Telah Tiba di Tujuan',
            'completed' => 'Pesanan Telah Selesai',
            'cancelled' => 'Pesanan Dibatalkan',
            'refunded' => 'Dana Pesanan Dikembalikan',
        ];

        $statusText = $statusLabels[$this->order->status] ?? ucfirst($this->order->status);

        $msg = "Status pesanan {$this->order->order_number} telah diperbarui menjadi '{$statusText}'.";
        if ($this->order->shipping_tracking_number) {
            $msg .= " Nomor Resi: {$this->order->shipping_tracking_number}.";
        }
        if ($this->note) {
            $msg .= " Catatan: {$this->note}";
        }

        return [
            'type' => 'order_status_updated',
            'title' => "Pembaruan Pesanan: {$statusText}",
            'message' => $msg,
            'url' => route('customer.orders.show', $this->order->id),
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'shipping_tracking_number' => $this->order->shipping_tracking_number,
        ];
    }
}
