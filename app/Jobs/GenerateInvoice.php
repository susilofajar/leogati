<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 60;

    public function __construct(
        protected Order $order
    ) {}

    public function handle(): void
    {
        try {
            $payment = $this->order->payment;
            if (!$payment) {
                Log::warning('Cannot generate invoice: no payment found', [
                    'order_id' => $this->order->id,
                    'order_number' => $this->order->order_number,
                ]);
                return;
            }

            $invoiceData = $this->generateInvoiceData($this->order, $payment);
            $filename = "invoice_{$this->order->order_number}.txt";
            $filepath = "invoices/{$filename}";

            Storage::disk('local')->put($filepath, $invoiceData);

            Log::info('Invoice generated successfully', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'filename' => $filename,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate invoice', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'error' => $e->getMessage(),
            ]);

            $this->release(60);
        }
    }

    protected function generateInvoiceData(Order $order, Payment $payment): string
    {
        $invoice = "LEOGATISTORE - INVOICE\n";
        $invoice .= str_repeat('=', 50) . "\n\n";
        $invoice .= "Invoice Number: {$payment->payment_number}\n";
        $invoice .= "Order Number: {$order->order_number}\n";
        $invoice .= "Date: " . $order->created_at->format('Y-m-d H:i:s') . "\n";
        $invoice .= "Payment Method: {$order->payment_method}\n";
        $invoice .= "Payment Status: {$payment->status}\n\n";

        $invoice .= str_repeat('-', 50) . "\n";
        $invoice .= "CUSTOMER INFORMATION\n";
        $invoice .= str_repeat('-', 50) . "\n";
        $invoice .= "Name: {$order->user->name}\n";
        $invoice .= "Email: {$order->user->email}\n";

        if ($order->shipping_address) {
            $address = $order->shipping_address;
            $invoice .= "Address: {$address['address_line']}\n";
            $invoice .= "City: {$address['city']}\n";
            $invoice .= "Province: {$address['province']}\n";
            $invoice .= "Postal Code: {$address['postal_code']}\n";
        }

        $invoice .= "\n" . str_repeat('-', 50) . "\n";
        $invoice .= "ORDER ITEMS\n";
        $invoice .= str_repeat('-', 50) . "\n";

        foreach ($order->items as $item) {
            $invoice .= "{$item->product_name} - {$item->variant_name}\n";
            $invoice .= "SKU: {$item->sku}\n";
            $invoice .= "Quantity: {$item->quantity}\n";
            $invoice .= "Unit Price: Rp " . number_format($item->unit_price, 0, ',', '.') . "\n";
            $invoice .= "Subtotal: Rp " . number_format($item->subtotal, 0, ',', '.') . "\n\n";
        }

        $invoice .= str_repeat('-', 50) . "\n";
        $invoice .= "PAYMENT SUMMARY\n";
        $invoice .= str_repeat('-', 50) . "\n";
        $invoice .= "Subtotal: Rp " . number_format($order->subtotal_amount, 0, ',', '.') . "\n";
        $invoice .= "Shipping: Rp " . number_format($order->shipping_amount, 0, ',', '.') . "\n";
        $invoice .= "Discount: Rp " . number_format($order->discount_amount, 0, ',', '.') . "\n";
        $invoice .= str_repeat('-', 50) . "\n";
        $invoice .= "TOTAL: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n";

        $invoice .= str_repeat('=', 50) . "\n";
        $invoice .= "Thank you for shopping at LEOGATISTORE!\n";
        $invoice .= str_repeat('=', 50) . "\n";

        return $invoice;
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Invoice generation job failed permanently', [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'error' => $exception->getMessage(),
        ]);
    }
}