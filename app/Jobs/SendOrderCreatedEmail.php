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

class SendOrderCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    public function __construct(
        protected Order $order,
        protected User $user
    ) {}

    public function handle(): void
    {
        try {
            // Send email notification
            Mail::raw(
                "Pesanan {$this->order->order_number} berhasil dibuat.\n\n" .
                "Total: Rp " . number_format($this->order->total_amount, 0, ',', '.') . "\n" .
                "Status: " . $this->order->status . "\n\n" .
                "Terima kasih telah berbelanja di LEOGATISTORE!",
                function ($message) {
                    $message->to($this->user->email, $this->user->name)
                        ->subject('Pesanan Baru - ' . $this->order->order_number);
                }
            );

            Log::info('Order created email sent successfully', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'user_email' => $this->user->email,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send order created email', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'user_email' => $this->user->email,
                'error' => $e->getMessage(),
            ]);

            $this->release(60); // Retry after 60 seconds
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Order created email job failed permanently', [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'user_email' => $this->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}