<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Daftar pilihan ikon kategori yang tersedia.
     */
    public static function getAvailableIcons(): array
    {
        return [
            'laptop'       => ['label' => 'Laptop & Ultrabook', 'group' => 'Perangkat'],
            'desktop'      => ['label' => 'Desktop PC & Rakitan', 'group' => 'Perangkat'],
            'cpu'          => ['label' => 'Prosesor (CPU)', 'group' => 'Komponen'],
            'gpu'          => ['label' => 'Kartu Grafis (GPU)', 'group' => 'Komponen'],
            'motherboard'  => ['label' => 'Motherboard', 'group' => 'Komponen'],
            'ram'          => ['label' => 'RAM & Memori', 'group' => 'Komponen'],
            'hard-drive'   => ['label' => 'SSD & Harddisk', 'group' => 'Komponen'],
            'zap'          => ['label' => 'Power Supply (PSU)', 'group' => 'Komponen'],
            'fan'          => ['label' => 'Pendingin / CPU Cooler', 'group' => 'Komponen'],
            'box'          => ['label' => 'Casing PC', 'group' => 'Komponen'],
            'monitor'      => ['label' => 'Monitor Gaming & Layar', 'group' => 'Periferal'],
            'keyboard'     => ['label' => 'Keyboard Mekanikal', 'group' => 'Periferal'],
            'mouse'        => ['label' => 'Mouse Gaming', 'group' => 'Periferal'],
            'headphones'   => ['label' => 'Audio & Headset', 'group' => 'Periferal'],
            'gamepad'      => ['label' => 'Gamepad & Gaming Gear', 'group' => 'Periferal'],
            'wifi'         => ['label' => 'Perangkat Jaringan (WiFi)', 'group' => 'Jaringan'],
            'printer'      => ['label' => 'Printer & Scanner', 'group' => 'Kantor'],
            'shield'       => ['label' => 'Software & Garansi', 'group' => 'Digital'],
            'sparkles'     => ['label' => 'Aksesoris & Lainnya', 'group' => 'Lainnya'],
        ];
    }

    /**
     * Render SVG Icon untuk kategori ini.
     */
    public function renderIcon(string $class = 'w-5 h-5'): string
    {
        $iconKey = strtolower(trim($this->icon ?? ''));

        // Fallback berdasarkan slug jika icon kosong
        if (empty($iconKey)) {
            $slug = strtolower($this->slug ?? '');
            if (str_contains($slug, 'laptop')) $iconKey = 'laptop';
            elseif (str_contains($slug, 'desktop') || str_contains($slug, 'pc-rakitan')) $iconKey = 'desktop';
            elseif (str_contains($slug, 'komponen') || str_contains($slug, 'prosesor') || str_contains($slug, 'cpu')) $iconKey = 'cpu';
            elseif (str_contains($slug, 'vga') || str_contains($slug, 'gpu') || str_contains($slug, 'grafis')) $iconKey = 'gpu';
            elseif (str_contains($slug, 'motherboard') || str_contains($slug, 'mainboard')) $iconKey = 'motherboard';
            elseif (str_contains($slug, 'ram') || str_contains($slug, 'memori')) $iconKey = 'ram';
            elseif (str_contains($slug, 'storage') || str_contains($slug, 'penyimpanan') || str_contains($slug, 'ssd') || str_contains($slug, 'hdd')) $iconKey = 'hard-drive';
            elseif (str_contains($slug, 'monitor') || str_contains($slug, 'layar')) $iconKey = 'monitor';
            elseif (str_contains($slug, 'keyboard')) $iconKey = 'keyboard';
            elseif (str_contains($slug, 'mouse')) $iconKey = 'mouse';
            elseif (str_contains($slug, 'audio') || str_contains($slug, 'headset')) $iconKey = 'headphones';
            elseif (str_contains($slug, 'gamepad') || str_contains($slug, 'gaming')) $iconKey = 'gamepad';
            elseif (str_contains($slug, 'cooler') || str_contains($slug, 'fan') || str_contains($slug, 'pendingin')) $iconKey = 'fan';
            elseif (str_contains($slug, 'psu') || str_contains($slug, 'power')) $iconKey = 'zap';
            elseif (str_contains($slug, 'casing') || str_contains($slug, 'case')) $iconKey = 'box';
            elseif (str_contains($slug, 'jaringan') || str_contains($slug, 'wifi') || str_contains($slug, 'router')) $iconKey = 'wifi';
            elseif (str_contains($slug, 'printer') || str_contains($slug, 'scanner')) $iconKey = 'printer';
            elseif (str_contains($slug, 'software') || str_contains($slug, 'aplikasi') || str_contains($slug, 'lisensi')) $iconKey = 'shield';
            else $iconKey = 'sparkles';
        }

        $icons = [
            'laptop' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
            'desktop' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 21h8M12 17v4" stroke-width="2" stroke-linecap="round"/></svg>',
            'cpu' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>',
            'gpu' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="12" r="2.5" stroke-width="2"/><circle cx="16" cy="12" r="2.5" stroke-width="2"/><path d="M2 10h3M2 14h3" stroke-width="2" stroke-linecap="round"/></svg>',
            'motherboard' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><rect x="7" y="7" width="5" height="5" stroke-width="2"/><path d="M16 7v3M16 14v3M7 16h6" stroke-width="2" stroke-linecap="round"/></svg>',
            'ram' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="10" rx="1" stroke-width="2"/><path d="M6 17v2M10 17v2M14 17v2M18 17v2M6 7V5M18 7V5" stroke-width="2" stroke-linecap="round"/><line x1="5" y1="11" x2="19" y2="11" stroke-width="1.5" stroke-dasharray="2 2"/></svg>',
            'hard-drive' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2" stroke-width="2"/><circle cx="7" cy="16" r="1" fill="currentColor"/><circle cx="10" cy="16" r="1" fill="currentColor"/><line x1="14" y1="16" x2="17" y2="16" stroke-width="2" stroke-linecap="round"/></svg>',
            'ssd' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2" stroke-width="2"/><circle cx="7" cy="16" r="1" fill="currentColor"/><circle cx="10" cy="16" r="1" fill="currentColor"/><line x1="14" y1="16" x2="17" y2="16" stroke-width="2" stroke-linecap="round"/></svg>',
            'monitor' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" stroke-width="2"/><path d="M8 21h8M12 17v4" stroke-width="2" stroke-linecap="round"/></svg>',
            'keyboard' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2"/><path d="M6 9h.01M10 9h.01M14 9h.01M18 9h.01M6 13h.01M18 13h.01M10 13h4" stroke-width="2" stroke-linecap="round"/></svg>',
            'mouse' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="6" y="3" width="12" height="18" rx="6" stroke-width="2"/><line x1="12" y1="7" x2="12" y2="11" stroke-width="2" stroke-linecap="round"/></svg>',
            'headphones' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 18v-6a9 9 0 0118 0v6" stroke-width="2" stroke-linecap="round"/><path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3v5zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3v5z" stroke-width="2"/></svg>',
            'gamepad' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 12h4m-2-2v4m7-2h.01m3-2h.01" stroke-width="2" stroke-linecap="round"/><rect x="2" y="6" width="20" height="12" rx="6" stroke-width="2"/></svg>',
            'fan' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" stroke-width="2"/><path d="M12 9C12 5 15 3 15 3s0 3-3 6zm3 3c4 0 6 3 6 3s-3 0-6-3zm-3 3c0 4-3 6-3 6s0-3 3-6zm-3-3c-4 0-6-3-6-3s3 0 6 3z" stroke-width="2" stroke-linecap="round"/></svg>',
            'zap' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'box' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" stroke-width="2"/><polyline points="3.27 6.96 12 12.01 20.73 6.96" stroke-width="2"/><line x1="12" y1="22.08" x2="12" y2="12" stroke-width="2"/></svg>',
            'wifi' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 12.55a11 11 0 0114.08 0M1.42 9a16 16 0 0121.16 0M8.53 16.11a6 6 0 016.95 0M12 20h.01" stroke-width="2" stroke-linecap="round"/></svg>',
            'printer' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9" stroke-width="2"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" stroke-width="2"/><rect x="6" y="14" width="12" height="8" stroke-width="2"/></svg>',
            'shield' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'sparkles' => '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6l2.1 2.1M5.6 18.4l2.1-2.1m8.6-8.6l2.1-2.1" stroke-width="2" stroke-linecap="round"/></svg>',
        ];

        return $icons[$iconKey] ?? $icons['sparkles'];
    }

    /**
     * Accessor untuk $category->icon_svg.
     */
    public function getIconSvgAttribute(): string
    {
        return $this->renderIcon('w-5 h-5');
    }
}
