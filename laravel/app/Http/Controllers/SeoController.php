<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\DocumentSection;
use App\Models\Product;
use Illuminate\Http\Response;
class SeoController extends Controller
{
    public function robots(): Response
    {
        $sitemap = url('/sitemap.xml');
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /livewire',
            'Disallow: /price/thanks',
            'Disallow: /dealer/thanks',
            '',
            'Sitemap: '.$sitemap,
            '',
        ];
        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
    public function sitemap(): Response
    {
        $urls = [];
        $add = function (string $loc, $lastmod = null, string $changefreq = 'weekly', string $priority = '0.7') use (&$urls): void {
            $urls[] = [
                'loc' => $loc,
                'lastmod' => $lastmod ? $lastmod->toAtomString() : null,
                'changefreq' => $changefreq,
                'priority' => $priority,
            ];
        };
        $add(route('home'), null, 'daily', '1.0');
        $add(route('about'), null, 'monthly', '0.8');
        $add(route('catalog.index'), null, 'daily', '0.9');
        $add(route('exclusive'), null, 'weekly', '0.9');
        $add(route('documents.index'), null, 'weekly', '0.8');
        $add(route('contacts'), null, 'monthly', '0.7');
        $add(route('price.create'), null, 'monthly', '0.8');
        $add(route('dealer.create'), null, 'monthly', '0.7');
        $add(route('privacy'), null, 'yearly', '0.3');
        $add(route('consent'), null, 'yearly', '0.3');
        $add(route('requisites'), null, 'yearly', '0.4');
        Category::query()->activeOrdered()->get()->each(function (Category $category) use ($add): void {
            $add(route('catalog.category', $category->slug), $category->updated_at, 'weekly', '0.8');
        });
        Product::query()
            ->where('is_active', true)
            ->with(['categories'])
            ->orderBy('sort')
            ->orderBy('name')
            ->get()
            ->each(function (Product $product) use ($add): void {
                $add($product->catalogUrl(), $product->updated_at, 'weekly', '0.9');
            });
        DocumentSection::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get()
            ->each(function (DocumentSection $section) use ($add): void {
                $add(route('documents.section', $section->slug), $section->updated_at, 'weekly', '0.6');
            });
        $xml = view('seo.sitemap', ['urls' => $urls])->render();
        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
