<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Menghasilkan XML Sitemap dinamis untuk Google & mesin pencari lainnya.
     */
    public function sitemap(): Response
    {
        $staticUrls = [
            [
                'loc'        => route('home'),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority'   => '1.0',
            ],
            [
                'loc'        => route('products.index'),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority'   => '0.9',
            ],
            [
                'loc'        => route('pc_builder.index'),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.8',
            ],
            [
                'loc'        => route('warranty.check'),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority'   => '0.7',
            ],
            [
                'loc'        => route('comparison.index'),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.6',
            ],
        ];

        $categories = Category::where('is_active', true)
            ->select(['slug', 'updated_at'])
            ->get()
            ->map(function ($cat) {
                return [
                    'loc'        => route('categories.show', $cat->slug),
                    'lastmod'    => $cat->updated_at ? $cat->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority'   => '0.8',
                ];
            })
            ->toArray();

        $brands = Brand::where('is_active', true)
            ->select(['slug', 'updated_at'])
            ->get()
            ->map(function ($brand) {
                return [
                    'loc'        => route('brands.show', $brand->slug),
                    'lastmod'    => $brand->updated_at ? $brand->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.7',
                ];
            })
            ->toArray();

        $products = Product::active()
            ->select(['slug', 'updated_at'])
            ->get()
            ->map(function ($prod) {
                return [
                    'loc'        => route('products.show', $prod->slug),
                    'lastmod'    => $prod->updated_at ? $prod->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority'   => '0.9',
                ];
            })
            ->toArray();

        $urls = array_merge($staticUrls, $categories, $brands, $products);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Menghasilkan robots.txt dinamis.
     */
    public function robots(): Response
    {
        $sitemapUrl = route('sitemap');

        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /akun/\n";
        $content .= "Disallow: /checkout/\n";
        $content .= "Disallow: /keranjang\n";
        $content .= "Disallow: /webhook/\n";
        $content .= "Disallow: /api/\n\n";
        $content .= "Sitemap: {$sitemapUrl}\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
