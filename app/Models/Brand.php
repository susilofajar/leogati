<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Render logo resmi mitra brand.
     */
    public function renderLogo(string $class = 'h-7 w-auto max-w-[120px] object-contain'): string
    {
        // 1. Jika brand memiliki logo file yang diunggah
        if (!empty($this->logo)) {
            $logoUrl = str_starts_with($this->logo, 'http') ? $this->logo : asset($this->logo);
            return '<img src="' . e($logoUrl) . '" alt="' . e($this->name) . '" class="' . $class . '">';
        }

        $key = strtolower(trim($this->slug ?? $this->name ?? ''));

        // 2. Official Vector SVG Brand Logos
        $logos = [
            'asus' => '<svg viewBox="0 0 100 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L4 22H9L10.8 17H17.2L19 22H24L16 2H12ZM14 6.5L15.8 13H12.2L14 6.5Z" fill="#00539B"/>
                <path d="M28 17C28 19.8 30.2 22 33.5 22C36.8 22 39 19.8 39 17V15H34V17C34 17.6 34.4 18 35 18C35.6 18 36 17.6 36 17V15.5C36 14.7 35.3 14 34.5 14L32.5 13.5C30 12.8 28.5 11 28.5 8.5C28.5 5.5 31 3 34.5 3C38 3 40.5 5.5 40.5 8.5H36.5C36.5 7.4 35.6 6.5 34.5 6.5C33.4 6.5 32.5 7.4 32.5 8.5C32.5 9.3 33.1 9.8 33.8 10L36 10.5C39 11.2 40.5 13 40.5 15.5C40.5 19 37.5 22 33.5 22C29.5 22 28 19 28 17Z" fill="#00539B"/>
                <path d="M45 2H49V14C49 16.2 50.8 18 53 18C55.2 18 57 16.2 57 14V2H61V14C61 18.4 57.4 22 53 22C48.6 22 45 18.4 45 14V2Z" fill="#00539B"/>
                <path d="M67 17C67 19.8 69.2 22 72.5 22C75.8 22 78 19.8 78 17V15H73V17C73 17.6 73.4 18 74 18C74.6 18 75 17.6 75 17V15.5C75 14.7 74.3 14 73.5 14L71.5 13.5C69 12.8 67.5 11 67.5 8.5C67.5 5.5 70 3 73.5 3C77 3 79.5 5.5 79.5 8.5H75.5C75.5 7.4 74.6 6.5 73.5 6.5C72.4 6.5 71.5 7.4 71.5 8.5C71.5 9.3 72.1 9.8 72.8 10L75 10.5C78 11.2 79.5 13 79.5 15.5C79.5 19 76.5 22 72.5 22C68.5 22 67 19 67 17Z" fill="#00539B"/>
                <line x1="8" y1="12" x2="20" y2="12" stroke="#00539B" stroke-width="1.5"/>
            </svg>',

            'nvidia' => '<svg viewBox="0 0 110 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.5 3C8.1 3 3 7.9 3 14C3 17.8 5 21.2 8 23.2V19.8C6.2 18.3 5 16.3 5 14C5 9 9.3 5 14.5 5C19.7 5 24 9 24 14C24 16.3 22.8 18.3 21 19.8V23.2C24 21.2 26 17.8 26 14C26 7.9 20.9 3 14.5 3Z" fill="#76B900"/>
                <path d="M14.5 7C10.4 7 7 10.1 7 14C7 16.3 8.2 18.3 10 19.5V17C9 16 8.5 15 8.5 14C8.5 10.7 11.2 8 14.5 8C17.8 8 20.5 10.7 20.5 14C20.5 15 20 16 19 17V19.5C20.8 18.3 22 16.3 22 14C22 10.1 18.6 7 14.5 7Z" fill="#76B900"/>
                <text x="32" y="18" fill="#1A1A1A" font-family="system-ui, sans-serif" font-weight="900" font-size="14" letter-spacing="1">NVIDIA</text>
            </svg>',

            'amd' => '<svg viewBox="0 0 90 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 3H16V9H8V17H2V3Z" fill="#ED1C24"/>
                <path d="M16 9H22V15H16V9Z" fill="#ED1C24"/>
                <path d="M9 16H15V22H9V16Z" fill="#ED1C24"/>
                <text x="28" y="19" fill="#111827" font-family="system-ui, sans-serif" font-weight="900" font-size="16" letter-spacing="0.5">AMD</text>
            </svg>',

            'intel' => '<svg viewBox="0 0 85 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <text x="4" y="18" fill="#0071C5" font-family="system-ui, sans-serif" font-weight="900" font-size="19" letter-spacing="-0.5">intel</text>
                <circle cx="8" cy="4" r="2" fill="#00C7FD"/>
            </svg>',

            'msi' => '<svg viewBox="0 0 85 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="2" y="3" width="20" height="18" rx="4" fill="#E10600"/>
                <path d="M8 8L12 16L16 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <text x="28" y="18" fill="#111827" font-family="system-ui, sans-serif" font-weight="900" font-size="16" letter-spacing="1">msi</text>
            </svg>',

            'gigabyte' => '<svg viewBox="0 0 120 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <text x="2" y="17" fill="#0054A6" font-family="system-ui, sans-serif" font-weight="900" font-size="14" letter-spacing="1.5">GIGABYTE</text>
                <circle cx="112" cy="7" r="3" fill="#FF5000"/>
            </svg>',

            'lenovo' => '<svg viewBox="0 0 95 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="2" y="2" width="91" height="20" rx="3" fill="#E2231A"/>
                <text x="8" y="17" fill="white" font-family="system-ui, sans-serif" font-weight="800" font-size="13" letter-spacing="0.5">Lenovo</text>
            </svg>',

            'hp' => '<svg viewBox="0 0 65 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="14" cy="12" r="11" fill="#0096D6"/>
                <text x="6.5" y="17" fill="white" font-family="system-ui, sans-serif" font-weight="900" font-style="italic" font-size="15">hp</text>
                <text x="32" y="17" fill="#111827" font-family="system-ui, sans-serif" font-weight="800" font-size="14">HP</text>
            </svg>',

            'acer' => '<svg viewBox="0 0 85 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <text x="2" y="18" fill="#83B81A" font-family="system-ui, sans-serif" font-weight="900" font-size="17" letter-spacing="0.5">acer</text>
            </svg>',

            'dell' => '<svg viewBox="0 0 85 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="13" cy="12" r="11" stroke="#007DB8" stroke-width="2"/>
                <text x="6" y="16.5" fill="#007DB8" font-family="system-ui, sans-serif" font-weight="900" font-size="11">DELL</text>
                <text x="32" y="17" fill="#111827" font-family="system-ui, sans-serif" font-weight="800" font-size="14">DELL</text>
            </svg>',

            'logitech' => '<svg viewBox="0 0 105 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="9" fill="#00B8FC"/>
                <path d="M12 7V12H17" stroke="white" stroke-width="2" stroke-linecap="round"/>
                <text x="26" y="17" fill="#111827" font-family="system-ui, sans-serif" font-weight="800" font-size="13">logitech</text>
            </svg>',

            'razer' => '<svg viewBox="0 0 95 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 3C8 3 4 7 4 12C4 17 8 21 12 21C16 21 20 17 20 12C20 7 16 3 12 3Z" stroke="#00FF00" stroke-width="2"/>
                <path d="M8 12L12 8L16 12L12 16L8 12Z" fill="#00FF00"/>
                <text x="28" y="17" fill="#00FF00" font-family="system-ui, sans-serif" font-weight="900" font-size="14" letter-spacing="1">RAZER</text>
            </svg>',

            'samsung' => '<svg viewBox="0 0 115 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="57" cy="12" rx="55" ry="10" fill="#1428A0"/>
                <text x="12" y="16" fill="white" font-family="system-ui, sans-serif" font-weight="900" font-size="12" letter-spacing="1.5">SAMSUNG</text>
            </svg>',

            'lg' => '<svg viewBox="0 0 70 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" fill="#A50034"/>
                <circle cx="9" cy="9" r="1.5" fill="white"/>
                <path d="M15 8V16H9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                <text x="28" y="18" fill="#111827" font-family="system-ui, sans-serif" font-weight="900" font-size="15">LG</text>
            </svg>',

            'corsair' => '<svg viewBox="0 0 110 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 18L12 4L20 18H4Z" stroke="#111827" stroke-width="2" stroke-linejoin="round"/>
                <path d="M8 14L12 8L16 14H8Z" fill="#111827"/>
                <text x="26" y="17" fill="#111827" font-family="system-ui, sans-serif" font-weight="900" font-size="13" letter-spacing="1">CORSAIR</text>
            </svg>',

            'kingston' => '<svg viewBox="0 0 110 24" class="' . $class . '" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="2" y="3" width="18" height="18" rx="3" fill="#ED1C24"/>
                <text x="6" y="17" fill="white" font-family="system-ui, sans-serif" font-weight="900" font-size="13">K</text>
                <text x="26" y="17" fill="#111827" font-family="system-ui, sans-serif" font-weight="800" font-size="13">Kingston</text>
            </svg>',
        ];

        if (isset($logos[$key])) {
            return $logos[$key];
        }

        // Check if key is partially matched
        foreach ($logos as $brandKey => $svg) {
            if (str_contains($key, $brandKey)) {
                return $svg;
            }
        }

        // Fallback default stylized typography badge
        return '<div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-800 font-extrabold text-xs tracking-wider uppercase">' . e($this->name) . '</div>';
    }

    /**
     * Accessor untuk $brand->logo_svg
     */
    public function getLogoSvgAttribute(): string
    {
        return $this->renderLogo('h-7 w-auto');
    }
}
