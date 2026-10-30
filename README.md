# МосАгро - справочник проекта

Живой вход в проект: **текущее состояние**, где что лежит, куда смотреть по ops / SEO / контенту.  
Исторический план этапов и согласований: [`PLAN.md`](PLAN.md) (не обновлять как основной статус - ориентир здесь).

Обновлено: **2026-10-22**.

## Что это

B2B-сайт ООО «МосАгро»: витрина СЗР/удобрений оптом + админка Filament. Цен на сайте нет - заявки на прайс и дилерство. Старый сайт `http://www.mos-agro.ru/` - только справочник фактов, до cutover **не трогать**.

| | |
| --- | --- |
| Staging (сейчас «боевой» runtime) | http://87.242.65.116/ |
| Админка | http://87.242.65.116/admin |
| Git | https://github.com/npl-garuda/mosagro (`main`) |
| Стек | Laravel 13 + Filament 4, Nginx, PHP 8.5, MySQL 8.4 |

## Текущее состояние (кратко)

**Готово на staging**
- Публичная витрина по `design_v3` / `layout` → Blade в `laravel/`
- Каталог, поиск, галерея фото, бейджи новинка/эксклюзив
- `/exclusive` = товары с основной категорией `floora`
- Формы `/price` и `/dealer` (throttle, thanks только после отправки)
- Legal / реквизиты + скачивание карточки организации
- SEO волны 1–2: robots, sitemap, canonical/OG/JSON-LD, noindex admin, поля `seo_*` в админке
- Админы: CRUD учёток в админке (Система → Администраторы)

**Дальше**
1. Полный каталог от заказчика (фото, тексты, документы) + seo по чеклисту
2. Cutover: домен, SSL, DNS, 301 со старого, Вебмастер/GSC, Метрика
3. Security checklist перед релизом (см. ops / PLAN)
4. Партнёры с админки на витрину (сейчас hardcoded SVG)
5. SEO волна 4 - мониторинг после запуска

## Карта документов

| Документ | Зачем |
| --- | --- |
| [`docs/ops.md`](docs/ops.md) | Staging, деплой rsync, VPS, почта, безопасность, SEO ops |
| [`docs/credentials.local.md`](docs/credentials.local.md) | Секреты (SSH, `.env`, пароли) - **не в git** |
| [`docs/seo.md`](docs/seo.md) | SEO-аудит, ядро запросов, волны 1–4, чеклисты карточки и cutover |
| [`docs/nginx-cutover.example.conf`](docs/nginx-cutover.example.conf) | Образец nginx на домен/HTTPS |
| [`docs/copy.md`](docs/copy.md) | Тексты витрины, SEO title/description черновик |
| [`docs/data-model.md`](docs/data-model.md) | Сущности БД, правила каталога / exclusive / заявок |
| [`docs/sitemap.md`](docs/sitemap.md) | Карта URL публички и админки |
| [`PLAN.md`](PLAN.md) | Исходный план этапов, зафиксированные продуктовые решения |
| [`docs/prototype-wireframes.drawio`](docs/prototype-wireframes.drawio) | Прототип wireframes |

Исходники юр. текстов / лого / карточка клиента - в `docs/*.docx`, `docs/logo.png`, xls (по мере наличия).

## Структура репозитория

```
laravel/          # runtime-движок (в git) ↔ VPS /var/www/mosagro
docs/             # справочники и секреты (credentials.local - gitignore)
design_v3/        # эталон макетов
layout/           # статическая вёрстка-эталон (не webroot)
PLAN.md           # исторический план
README.md         # этот файл
```

На VPS дополнительно: `/opt/mosagro` - клон монорепо (`git pull`); webroot приложения - `/var/www/mosagro`.

**Синхрон тройки:** `git main` ≈ локальный `laravel/` ≈ `/var/www/mosagro` (без `.env`, `vendor`, uploads). Команды деплоя/съёма - в [`docs/ops.md`](docs/ops.md). После rsync на VPS - права `storage/` на `www-data` (иначе падают загрузки PDF/фото в админке).

## Админка (Filament)

- Каталог: бренды, категории, товары (галерея, флаги, seo_*), фасовки
- Контент: разделы/документы, партнёры (пока не на витрине)
- Заявки + получатели писем
- Система: администраторы

Логин по умолчанию - в `docs/credentials.local.md`.

## SEO (срез)

- Staging: `/robots.txt`, `/sitemap.xml`, canonical, OG, Schema.org
- Cutover: чеклист в `docs/seo.md`; 301 - `laravel/config/legacy_redirects.php`; Метрика - `YANDEX_METRIKA_ID` в `.env`

## Контакты продукта (публичные)

- Email: `sales@mos-agro.ru`
- Адрес на сайте: Москва, проезд Нансена, 1
- Телефонов компании на сайте нет (осознанно)

## Как обновлять этот README

При смене статуса (этап, URL, новый важный док) - править **сначала** блок «Текущее состояние» и таблицу документов. Детали деплоя и команд - только в `docs/ops.md`, не дублировать сюда длинными блоками.
