<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusUpdatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    public function __construct(
        protected Order $order,
        protected User $user,
        protected string $previousStatus
    ) {}

    public function handle(): void
    {
        try {
            $statusMessage = match ($this->order->status) {
                'paid' => 'Pembayaran Anda telah dikonfirmasi',
                'processing' => 'Pesanan Anda sedang diproses',
                'packed' => 'Pesanan Anda telah dikemas',
                'shipped' => 'Pesanan Anda telah dikirim',
                'delivered' => 'Pesanan Anda telah diterima',
                'completed' => 'Pesanan Anda telah selesai',
                'cancelled' => 'Pesanan Anda telah dibatalkan',
                'refunded' => 'Pengembalian dana telah diproses',
                default => 'Status pesanan Anda telah diperbarui',
            };

            Mail::raw(
                "Status pesanan {$this->order->order_number} telah diperbarui.\n\n" .
                "Status sebelumnya: {$this->previousStatus}\n" .
                "Status baru: {$this->order->status}\n" .
                "Catatan: {$statusMessage}\n\n" .
                "Terima kasih telah berbelanja di LEOGATISTORE!",
                function ($message) {
                    $message->to($this->user->email, $this->user->name)
                        ->subject('Update Status Pesanan - ' . $this->order->order_number);
                }
            );

            Log::info('Order status updated email sent successfully', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'user_email' => $this->user->email,
                'previous_status' => $this->previousStatus,
                'new_status' => $this->order->status,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send order status updated email', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'user_email' => $this->user->email,
                'error' => $e->getMessage(),
            ]);

            $this->release(60);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Order status updated email job failed permanently', [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'user_email' => $this->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}