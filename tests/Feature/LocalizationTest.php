<?php

namespace Tests\Feature;

use App\Support\Formatter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test application default locale and timezone.
     */
    public function test_app_is_configured_with_indonesian_locale_and_timezone(): void
    {
        $this->assertEquals('id', config('app.locale'));
        $this->assertEquals('Asia/Jakarta', config('app.timezone'));
    }

    /**
     * Test Rupiah currency formatter.
     */
    public function test_rupiah_formatter_formats_correctly(): void
    {
        $this->assertEquals('Rp 15.000.000', rupiah(15000000));
        $this->assertEquals('Rp 2.500.000', Formatter::rupiah(2500000));
        $this->assertEquals('750.000', rupiah(750000, false));
    }

    /**
     * Test Indonesian date formatter.
     */
    public function test_indonesian_date_formatter(): void
    {
        $date = '2026-08-19';
        $formatted = tgl_indo($date);

        $this->assertStringContainsString('Agustus', $formatted);
        $this->assertStringContainsString('2026', $formatted);
    }

    /**
     * Test validation error returns Indonesian messages.
     */
    public function test_validation_returns_indonesian_messages(): void
    {
        $response = $this->post('/masuk', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Kolom alamat email wajib diisi.',
            'password' => 'Kolom kata sandi wajib diisi.',
        ]);
    }
}
