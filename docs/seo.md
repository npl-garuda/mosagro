# SEO: аудит, ядро, дорожная карта (МосАгро)

Статус: **2026-10-22**. Цель: топ по бренду «МосАгро» и именным препаратам (B2B / опт). Приоритет выдачи: Яндекс, Google - тем же техфундаментом.

Гарантии «место №1» нет: позиции зависят от каталога, конкуренции, 301 со старого сайта и внешних факторов. Здесь - контролируемый максимум.

## Аудит (staging на момент плана)

### Уже было
- `lang=ru`, title/description на ключевых страницах, один H1, ЧПУ `/catalog/{category}/{product}`, HTML-крошки
- B2B-копирайт (опт, дилеры, СГР); лендинг `/exclusive` под НВ-101

### Пробелы (закрываются волнами)
| Проблема | Волна |
| --- | --- |
| Нет sitemap / слабый robots | 1 |
| Нет canonical, OG, Schema.org | 1 |
| `/admin` без noindex | 1 |
| Пустые alt в списках | 1 |
| Шаблонные meta категорий / нет полей в админке | 1–2 |
| Мало SKU на staging | 2 + этап 5 (контент заказчика) |
| HTTP / IP, нет Вебмастера / Метрики | 3 |

## Целевое ядро (ориентир)

**Бренд:** МосАгро, Мос Агро, mos-agro, ООО МосАгро + опт / прайс / дилер  
**Препараты (сейчас):** Корневин оптом; HB-101 / НВ-101 оптом; Floora / FLORA  
**Категории:** по факту админки + коммерческие `seo_h1` / `seo_title`  
**Интент:** прайс, дилер, документы СГР, фасовки (не розница «купить дешево»)

Таблица «страница → запросы → title/H1» дополняется по мере каталога (ниже чеклист).

| Страница | Целевые запросы (черновик) | Title / H1 |
| --- | --- | --- |
| `/` | мосагро, сзр оптом | из `docs/copy.md` |
| `/about` | мосагро компания | бренд + «с 2005» |
| `/catalog/.../kornevin` | корневин оптом | seo_* или `{name} оптом \| МосАгро` |
| `/catalog/.../floora` + `/exclusive` | нв-101 / hb-101 оптом | НВ-101 / exclusive |
| `/price` | прайс мосагро | форма, не конкурирует с SKU |

## Волны

### Волна 1 - техSEO (движок)
- Динамические `/robots.txt`, `/sitemap.xml`
- canonical, OG/Twitter, JSON-LD (Organization, BreadcrumbList, Product)
- noindex: thanks + `/admin` (`X-Robots-Tag` + meta)
- alt фото = имя товара
- partial SEO в layout

### Волна 2 - контент / админка
- Поля `seo_title`, `seo_description`, `seo_h1` у Product и Category
- Чеклист карточки препарата (ниже)
- Уникальные тексты при загрузке полного каталога

### Волна 3 - запуск на mos-agro.ru
См. раздел «Чеклист cutover» ниже. Код: `config/legacy_redirects.php`, опционально `YANDEX_METRIKA_ID` в `.env`.

### Волна 4 - поддержка (ежемесячно)
- Индекс Вебмастер/GSC: покрытие, исключения, 404
- Позиции: бренд + топ препаратов/категорий
- Новые SKU только по чеклисту карточки
- CWV / вес шрифтов и hero при необходимости
- Квартально: дубли title, `composer audit` по желанию

## Чеклист карточки препарата (перед публикацией)

1. Уникальный `short_description` 150–300+ символов (не копипаст конкурентов)
2. Заполнены Описание / Применение; фото (не только «Фото скоро»)
3. Документы привязаны; фасовки актуальны
4. Primary-категория верная (URL и крошки)
5. При необходимости: `seo_title` / `seo_description` / `seo_h1`
6. Внутренние связи: категория, при Floora - `/exclusive`, документы раздела

## Чеклист cutover (волна 3)

1. HTTPS (Let's Encrypt), `APP_URL=https://mos-agro.ru`, `SESSION_SECURE_COOKIE=true`, config:cache
2. Canonical/sitemap на https (автоматически от `APP_URL`)
3. Инвентарь URL старого сайта → записи в `config/legacy_redirects.php` + деплой
4. Nginx: редирект IP / www → основной https-хост
5. Яндекс.Вебмастер + Google Search Console: sitemap, главное зеркало
6. `YANDEX_METRIKA_ID` в `.env`; цели на submit `/price` и `/dealer`
7. Сверка NAP с Яндекс Бизнес / картами (oid на контактах)
8. robots: Sitemap на боевой URL; `/admin` по-прежнему Disallow + noindex
9. Nginx: образец [docs/nginx-cutover.example.conf](nginx-cutover.example.conf); 301 старых URL - `laravel/config/legacy_redirects.php`
10. `.env`: `YANDEX_METRIKA_ID=…` (подключается в layout автоматически)

## Smoke после волны 1 (staging)

- `GET /robots.txt` - Disallow `/admin`, строка Sitemap
- `GET /sitemap.xml` - 200, URL товаров/категорий
- Карточка товара: canonical, og:*, JSON-LD Product + BreadcrumbList
- `/admin/login` - `X-Robots-Tag: noindex`
- `/price/thanks` без сессии - редирект; с noindex в шаблоне thanks
