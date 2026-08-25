<?php

namespace App\Jobs;

use App\Services\ReportingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateSalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 120;

    public function __construct(
        protected string $startDate,
        protected string $endDate,
        protected string $format = 'csv'
    ) {}

    public function handle(ReportingService $reportingService): void
    {
        try {
            $salesData = $reportingService->getSalesReport($this->startDate, $this->endDate);

            $filename = "sales_report_{$this->startDate}_to_{$this->endDate}.{$this->format}";
            $filepath = "reports/{$filename}";

            // Generate CSV format
            if ($this->format === 'csv') {
                $csvContent = $this->generateCsv($salesData);
                Storage::disk('local')->put($filepath, $csvContent);
            }

            Log::info('Sales report generated successfully', [
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'format' => $this->format,
                'filename' => $filename,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate sales report', [
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'error' => $e->getMessage(),
            ]);

            $this->release(120);
        }
    }

    protected function generateCsv(array $data): string
    {
        $csv = "Date,Order Count,Total Revenue\n";

        foreach ($data as $row) {
            $csv .= "{$row['date']},{$row['orders']},{$row['revenue']}\n";
        }

        return $csv;
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Sales report generation job failed permanently', [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'error' => $exception->getMessage(),
        ]);
    }
}