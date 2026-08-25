<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_created',
            'title' => 'Pesanan Berhasil Dibuat!',
            'message' => 'Pesanan nomor ' . $this->order->order_number . ' sebesar ' . rupiah($this->order->total_amount) . ' berhasil dibuat dan menunggu pembayaran.',
            'url' => route('customer.orders.show', $this->order->id),
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total_amount' => $this->order->total_amount,
        ];
    }
}
