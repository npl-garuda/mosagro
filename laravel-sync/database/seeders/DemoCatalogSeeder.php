<?php
namespace Database\Seeders;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Document;
use App\Models\DocumentSection;
use App\Models\LeadRecipient;
use App\Models\Packaging;
use App\Models\PartnerBrand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        LeadRecipient::query()->firstOrCreate(
            ['email' => 'administrator@mos-agro.ru'],
            ['is_active' => true, 'sort' => 0]
        );
        $brand = Brand::query()->updateOrCreate(
            ['slug' => 'mosagro'],
            ['name' => 'МосАгро', 'sort' => 0, 'is_active' => true]
        );
        $categories = [
            ['slug' => 'novinki', 'name' => 'Новинки', 'sort' => 0],
            ['slug' => 'bolezni', 'name' => 'От болезней растений', 'sort' => 1],
            ['slug' => 'stimulyatory', 'name' => 'Стимуляторы роста и корнеобразования', 'sort' => 2],
            ['slug' => 'vrediteli', 'name' => 'От вредителей', 'sort' => 3],
            ['slug' => 'udobreniya', 'name' => 'Комплексные удобрения', 'sort' => 4],
        ];
        $categoryModels = [];
        foreach ($categories as $row) {
            $categoryModels[$row['slug']] = Category::query()->updateOrCreate(
                ['slug' => $row['slug']],
                ['name' => $row['name'], 'sort' => $row['sort'], 'is_active' => true]
            );
        }
        $kornevin = Product::query()->updateOrCreate(
            ['slug' => 'kornevin'],
            [
                'brand_id' => $brand->id,
                'name' => 'Корневин',
                'short_description' => 'Стимулятор корнеобразования для черенков, рассады и луковиц. Оптом от МосАгро - несколько фасовок, документы для реализации в карточке.',
                'composition' => 'Действующее вещество - индолилмасляная кислота (уточнить по факту).',
                'application' => 'Для укоренения черенков, пересадки рассады и обработки луковиц - по инструкции на упаковке.',
                'is_new' => true,
                'is_exclusive' => false,
                'is_active' => true,
                'sort' => 0,
            ]
        );
        $hb = Product::query()->updateOrCreate(
            ['slug' => 'hb-101'],
            [
                'brand_id' => $brand->id,
                'name' => 'HB-101',
                'short_description' => 'Биологический стимулятор FLORA (Япония) - эксклюзивная дистрибуция в РФ.',
                'composition' => 'Растительные экстракты (демо-текст).',
                'application' => 'По инструкции производителя для сада и фермерских хозяйств.',
                'is_new' => false,
                'is_exclusive' => true,
                'is_active' => true,
                'sort' => 1,
            ]
        );
        DB::table('product_category')->whereIn('product_id', [$kornevin->id, $hb->id])->delete();
        $kornevin->categories()->attach([
            $categoryModels['stimulyatory']->id => ['is_primary' => true, 'sort' => 0],
            $categoryModels['novinki']->id => ['is_primary' => false, 'sort' => 1],
        ]);
        $hb->categories()->attach([
            $categoryModels['stimulyatory']->id => ['is_primary' => true, 'sort' => 0],
        ]);
        Packaging::query()->where('product_id', $kornevin->id)->delete();
        foreach (['5 г', '8 г', '10 г', '250 г'] as $i => $name) {
            Packaging::query()->create([
                'product_id' => $kornevin->id,
                'name' => $name,
                'sort' => $i,
            ]);
        }
        Packaging::query()->where('product_id', $hb->id)->delete();
        foreach (['6 мл', '50 мл', '100 мл', '10 г'] as $i => $name) {
            Packaging::query()->create([
                'product_id' => $hb->id,
                'name' => $name,
                'sort' => $i,
            ]);
        }
        $section = DocumentSection::query()->updateOrCreate(
            ['slug' => 'mosagro'],
            [
                'name' => 'МосАгро',
                'description' => 'Документы на препараты производства МосАгро',
                'brand_id' => $brand->id,
                'sort' => 0,
                'is_active' => true,
            ]
        );
        Document::query()->updateOrCreate(
            ['title' => 'СГР Корневин (демо)', 'product_id' => $kornevin->id],
            [
                'file_path' => null,
                'external_url' => null,
                'document_section_id' => $section->id,
                'sort' => 0,
            ]
        );
        PartnerBrand::query()->updateOrCreate(
            ['name' => 'Август'],
            ['logo' => null, 'url' => null, 'sort' => 0, 'is_active' => true]
        );
        PartnerBrand::query()->updateOrCreate(
            ['name' => 'Ортон'],
            ['logo' => null, 'url' => null, 'sort' => 1, 'is_active' => true]
        );
        PartnerBrand::query()->updateOrCreate(
            ['name' => 'FLORA'],
            ['logo' => null, 'url' => 'https://www.mos-agro.ru/', 'sort' => 2, 'is_active' => true]
        );
    }
}
