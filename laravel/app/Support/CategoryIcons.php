<?php
namespace App\Support;
class CategoryIcons
{
    public const DEFAULT = 'leaf';
    /** @var array<string, string> key => label */
    public const OPTIONS = [
        'leaf' => 'Лист',
        'rodent' => 'Грызуны',
        'other' => 'Прочее',
        'spray' => 'Опрыскивание',
        'bug' => 'Вредители',
        'fungus' => 'Болезни',
        'drop' => 'Капля',
        'root' => 'Корни',
        'seed' => 'Семена',
        'flower' => 'Цветы',
        'soil' => 'Почва',
        'shield' => 'Защита',
        'bottle' => 'Флакон',
        'bag' => 'Фасовка',
        'sun' => 'Солнце',
        'water' => 'Полив',
        'tree' => 'Дерево',
        'wheat' => 'Зерновые',
        'bee' => 'Био',
        'flask' => 'Состав',
    ];
    public static function keys(): array
    {
        return array_keys(self::OPTIONS);
    }
    public static function isValid(?string $key): bool
    {
        return $key !== null && isset(self::OPTIONS[$key]);
    }
    public static function filename(?string $key): string
    {
        $key = self::isValid($key) ? $key : self::DEFAULT;
        return 'icon-'.$key.'.png';
    }
    public static function assetUrl(?string $key): string
    {
        return asset('images/'.self::filename($key));
    }
    /** @return array<string, string> */
    public static function selectOptions(): array
    {
        $out = [];
        foreach (self::OPTIONS as $key => $label) {
            $url = e(self::assetUrl($key));
            $safeLabel = e($label);
            $out[$key] = '<span style="display:inline-flex;align-items:center;gap:.5rem"><img src="'.$url.'" alt="" width="28" height="28" style="flex-shrink:0">'.$safeLabel.'</span>';
        }
        return $out;
    }
    public static function fromSlug(?string $slug): string
    {
        return match ($slug) {
            'gryuzuny' => 'rodent',
            'prochee' => 'other',
            'hb-101', 'floora' => 'leaf',
            'bolezni' => 'fungus',
            'vrediteli', 'ot-vreditelei' => 'bug',
            'stimulyatory' => 'root',
            'udobreniya' => 'drop',
            'novinki' => 'sun',
            'sredstva-zashiti-rasteniy' => 'shield',
            default => self::DEFAULT,
        };
    }
}
