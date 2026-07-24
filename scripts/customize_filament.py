#!/usr/bin/env python3
from pathlib import Path
base = Path('/var/www/mosagro')
# navigation labels for resources
labels = {
    'Brands/BrandResource.php': ('Бренды', 'Бренд', 'Бренды', 'Каталог', 1),
    'Categories/CategoryResource.php': ('Категории', 'Категория', 'Категории', 'Каталог', 2),
    'Products/ProductResource.php': ('Товары', 'Товар', 'Товары', 'Каталог', 3),
    'Packagings/PackagingResource.php': ('Фасовки', 'Фасовка', 'Фасовки', 'Каталог', 4),
    'PartnerBrands/PartnerBrandResource.php': ('Партнёры', 'Партнёр', 'Партнёры', 'Контент', 1),
    'DocumentSections/DocumentSectionResource.php': ('Разделы документов', 'Раздел документов', 'Разделы документов', 'Контент', 2),
    'Documents/DocumentResource.php': ('Документы', 'Документ', 'Документы', 'Контент', 3),
    'LeadRecipients/LeadRecipientResource.php': ('Получатели заявок', 'Получатель', 'Получатели заявок', 'Заявки', 2),
    'Leads/LeadResource.php': ('Заявки', 'Заявка', 'Заявки', 'Заявки', 1),
}
for rel, (nav, model_label, plural, group, sort) in labels.items():
    path = base / 'app/Filament/Resources' / rel
    text = path.read_text()
    if "protected static ?string $navigationLabel" in text:
        continue
    insert = f'''
    protected static ?string $navigationLabel = '{nav}';
    protected static ?string $modelLabel = '{model_label}';
    protected static ?string $pluralModelLabel = '{plural}';
    protected static string|\\UnitEnum|null $navigationGroup = '{group}';
    protected static ?int $navigationSort = {sort};
'''
    # need UnitEnum import
    if 'UnitEnum' not in text:
        text = text.replace('use BackedEnum;', 'use BackedEnum;\nuse UnitEnum;')
    needle = "    protected static ?string $recordTitleAttribute"
    if needle not in text:
        needle = "    protected static string|BackedEnum|null $navigationIcon"
        # insert after navigationIcon line
        lines = text.splitlines(True)
        out = []
        done = False
        for line in lines:
            out.append(line)
            if (not done) and 'navigationIcon' in line:
                out.append(insert)
                done = True
        text = ''.join(out)
    else:
        text = text.replace(needle, insert + '\n' + needle, 1)
    path.write_text(text)
    print('labeled', rel)
# Lead: disable create in nav (leads from public form)
lead = base / 'app/Filament/Resources/Leads/LeadResource.php'
lt = lead.read_text()
if 'canCreate' not in lt:
    lt = lt.replace(
        '    public static function getRelations(): array',
        '''    public static function canCreate(): bool
    {
        return false;
    }
    public static function getRelations(): array'''
    )
    lead.write_text(lt)
    print('lead canCreate false')
# widget
widgets = base / 'app/Filament/Widgets'
widgets.mkdir(parents=True, exist_ok=True)
(widgets / 'LeadsTodayWidget.php').write_text("""<?php
namespace App\\Filament\\Widgets;
use App\\Models\\Lead;
use Filament\\Widgets\\StatsOverviewWidget;
use Filament\\Widgets\\StatsOverviewWidget\\Stat;
use Illuminate\\Support\\Carbon;
class LeadsTodayWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $start = Carbon::now('Europe/Moscow')->startOfDay()->utc();
        $count = Lead::query()
            ->where('type', 'price_request')
            ->where('created_at', '>=', $start)
            ->count();
        return [
            Stat::make('Заявки сегодня', (string) $count)
                ->description('с 00:00 по Москве')
                ->color('success'),
        ];
    }
}
""")
print('widget written')
# AdminPanelProvider: remove FilamentInfoWidget, keep Account + discover
provider = base / 'app/Providers/Filament/AdminPanelProvider.php'
pt = provider.read_text()
pt = pt.replace('use Filament\\Widgets\\FilamentInfoWidget;\n', '')
pt = pt.replace(
'''            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])''',
'''            ->widgets([
                AccountWidget::class,
                \\App\\Filament\\Widgets\\LeadsTodayWidget::class,
            ])'''
)
provider.write_text(pt)
print('provider updated')
# brand panel
pt = provider.read_text()
if "->brandName(" not in pt:
    pt = pt.replace("->login()", "->login()\n            ->brandName('МосАгро')")
    provider.write_text(pt)
print('done')
