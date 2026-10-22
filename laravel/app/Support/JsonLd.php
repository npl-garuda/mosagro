<?php
namespace App\Support;
use App\Models\Product;
class JsonLd
{
    public static function organization(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'ООО «МосАгро»',
            'url' => url('/'),
            'logo' => asset('images/logo.png'),
            'email' => 'sales@mos-agro.ru',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'проезд Нансена, 1',
                'addressLocality' => 'Москва',
                'addressCountry' => 'RU',
            ],
        ];
    }
    /** @param list<array{name:string,url:?string}> $items */
    public static function breadcrumbs(array $items): array
    {
        $list = [];
        foreach (array_values($items) as $i => $item) {
            $entry = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
            ];
            if (! empty($item['url'])) {
                $entry['item'] = $item['url'];
            }
            $list[] = $entry;
        }
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }
    public static function product(Product $product, string $productUrl, ?string $imageUrl = null): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->seoDescription(),
            'url' => $productUrl,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name ?? 'МосАгро',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('price.create'),
                'availability' => 'https://schema.org/InStock',
                'priceCurrency' => 'RUB',
            ],
        ];
        if ($imageUrl) {
            $data['image'] = [$imageUrl];
        }
        return $data;
    }
    public static function script(array $data): string
    {
        return '<script type="application/ld+json">'.json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP
        ).'</script>';
    }
}
