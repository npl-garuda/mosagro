# Staging / ops (МосАгро)

Статус на **2026-07-24**. Секреты - только в `docs/credentials.local.md` (не в git).

## Репозиторий и код

- GitHub: https://github.com/npl-garuda/mosagro (ветка `main`)
- Локальный корень: `/Users/npl/MOSAGRO`
- Движок в git: `laravel/` - полный Laravel с staging (модели, Filament, migrations, views)
- Runtime на VPS: `/var/www/mosagro` (`.env`, `vendor`, кэши - только на сервере, не в git)
- Клон монорепо на VPS: `/opt/mosagro` (`git pull` для справки; webroot отдельно)
- Цепочка синхронизации движка: **VPS `/var/www/mosagro` → git `laravel/` → локальная копия**
- Дизайн/вёрстка отдельно: `design_v3/`, `layout/` (не смешивать с runtime)
- Скрипты первичной настройки админки/схемы: `scripts/setup_schema.py`, `scripts/customize_filament.py` (уже применены на VPS)
- `docs/credentials.local.md`, `.env`, бэкапы drawio (`.$*`, `*.bkp`) - в `.gitignore`

### Снять движок с staging в git (источник правды - сервер)

```bash
rsync -avz --delete \
  -e 'ssh -p 4222 -i ~/.ssh/id_ed25519 -o IdentitiesOnly=yes' \
  --exclude '.env' --exclude '.env.*' --exclude 'vendor/' --exclude 'node_modules/' \
  --exclude 'storage/logs/*' --exclude 'storage/framework/cache/data/' \
  --exclude 'storage/framework/sessions/*' --exclude 'storage/framework/views/*' \
  --exclude 'bootstrap/cache/*.php' --exclude 'public/storage' \
  root@87.242.65.116:/var/www/mosagro/ laravel/
```

### Деплой `laravel/` на staging

```bash
rsync -avz -e 'ssh -p 4222 -i ~/.ssh/id_ed25519 -o IdentitiesOnly=yes' \
  --exclude '.env' --exclude 'vendor/' --exclude 'node_modules/' \
  --exclude 'storage/logs/*' --exclude 'storage/framework/cache/data/' \
  --exclude 'storage/framework/sessions/*' --exclude 'storage/framework/views/*' \
  --exclude 'bootstrap/cache/*.php' --exclude 'public/storage' \
  laravel/ root@87.242.65.116:/var/www/mosagro/
ssh -p 4222 -i ~/.ssh/id_ed25519 -o IdentitiesOnly=yes root@87.242.65.116 \
  'cd /var/www/mosagro && php artisan optimize:clear && php artisan config:cache && php artisan route:cache'
```

После смены `.env` на сервере всегда: `php artisan config:cache`.

### Git push

- Remote в репо: `git@github.com:npl-garuda/mosagro.git`
- Локальный `~/.ssh/id_ed25519` на GitHub **не** добавлен → `git push` по SSH падает
- Рабочий путь: HTTPS через `gh` (`gh auth setup-git`), либо добавить SSH-ключ в GitHub

## VPS

| Параметр | Значение |
| --- | --- |
| IP | `87.242.65.116` |
| SSH | порт **4222**, только ключ, `PermitRootLogin prohibit-password` |
| ОС | Ubuntu 26.04, ~1.5 ГБ RAM + swap 2 ГБ |
| UFW | allow `4222`, `80`; HTTPS (**443**) пока нет |
| Стек | Nginx 1.28, PHP 8.5-FPM, MySQL 8.4 (только `127.0.0.1`), Composer 2.9 |
| App | Laravel **13.21** + Filament **4.12** |
| Docroot | `/var/www/mosagro/public` |

Старый сайт на shared Masterhost **не трогать** до DNS cutover.

## Laravel `.env` (staging)

Актуально после hardening 2026-07-24:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `LOG_LEVEL=error`
- `APP_URL=http://87.242.65.116`
- `QUEUE_CONNECTION=sync`
- `MAIL_SCHEME=smtps` + SMTP Mastermail `:465`
- Сессии: `SESSION_DRIVER=database`

### Права на `.env`

Нужно: PHP-FPM (`www-data`) читает файл; остальные пользователи ОС - нет.

```bash
chown root:www-data /var/www/mosagro/.env
chmod 640 /var/www/mosagro/.env
```

- `644` / world-readable - **нельзя**
- `600` от `root:root` без группы `www-data` - сайт может не подняться, если нет актуального `config:cache`
- Дубликаты секретов на сервере: `/root/mosagro-db.env`, `/root/mosagro-admin.env` - `chmod 600`

С веба `/.env` закрыт Nginx (`deny` для dotfiles) → `403`.

### Владельцы файлов приложения

Часть дерева после rsync с Mac: uid `501` / `staff`. Рабочий минимум: `storage/` и `bootstrap/cache/` → `www-data:www-data`. Имеет смысл позже выровнять весь `/var/www/mosagro` под deploy-user / `www-data`.

## Безопасность (сделано / осталось)

### Сделано 2026-07-24

1. `APP_DEBUG=false`, `APP_ENV=production`, config cache
2. Из `DatabaseSeeder` убран factory-user `test@example.com` (не сидить тестовых админов на VPS)
3. `POST /price` → `throttle:5,1` (6-й запрос за минуту → `429`)
4. Права `.env` → `640 root:www-data`
5. `User` реализует `FilamentUser::canAccessPanel()` - без этого в `production` Filament отдаёт **403 Forbidden** после логина (в `local` пускал всех)
6. **Публичные файлы витрины** (PDF документов, фото товаров, логотипы):
   - диск Laravel `public` + `php artisan storage:link` → URL `/storage/...`
   - Filament `FileUpload` (не текстовый путь): документы только PDF ≤20 МБ (обязателен); фото JPEG/PNG/WebP; логотипы без SVG
   - каталоги: `documents/`, `products/`, `partners/`
   - у товара: RelationManager **«Документы»** - несколько PDF на одну карточку
   - Nginx `location ^~ /storage/` - статика, **`.php` в storage → 403** (не исполняется)
   - PHP `upload_max_filesize=20M` / `post_max_size=24M`
   - витрина: `Document::publicFileUrl()`, `Product::publicImageUrl()` (только пути `documents/` / `products/`, без `..`)
   - нет картинки → плейсхолдер «Фото скоро»; есть → `<img>`
   - поле `external_url` в БД есть, в UI **не используется** (только локальный PDF)
   - приватный диск `storage/app/private` (и `.env`, логи) **не** публикуются в webroot

### Как загрузить документ в админке

1. `/admin` → Документы → создать/редактировать → «Файл (PDF)»
2. Или `/admin` → Товар → редактирование → блок **«Документы»** → «Добавить документ» (несколько на товар)
3. Ссылка на сайте: `/storage/documents/...` (публично для СГР/деклараций)

### Как загрузить фото товара

1. `/admin` → Товар → поле «Фото» → JPEG/PNG/WebP ≤5 МБ
2. На карточке и в сетке каталога: фото или «Фото скоро»

### Осталось (не блокеры до дизайна / домена)

- HTTPS + редирект HTTP→HTTPS + `SESSION_SECURE_COOKIE=true`, затем смена пароля админа
- Security headers в Nginx (CSP, X-Frame-Options и т.п. - частично X-Content-Type-Options на `/storage/`)
- Allowlist `http`/`https` для URL партнёров (`PartnerBrand.url`)
- fail2ban или аналог на SSH
- CAPTCHA/honeypot на форму (throttle уже есть)
- 2FA для Filament (по желанию)
- Выровнять владельцев файлов приложения (`501:staff` после rsync с Mac)

Админка сейчас по **HTTP** - для staging приемлемо до сертификата; не использовать с недоверенных сетей.

## Публичный сайт (каркас)

Маршруты: `/`, `/about`, `/catalog`, `/catalog/{category}`, `/catalog/{category}/{product}`, `/exclusive`, `/documents`, `/documents/{section}`, `/price`, `/price/thanks`, `/contacts`, `/privacy`, `/consent`, `/requisites`.

Редиректы 301 на `/price`: `/cart`, `/checkout`, `/login`, `/cabinet`.

Форма прайса: валидация → `leads` (`type=price_request`) → письмо активным `lead_recipients` (fallback: `MAIL_FROM_ADDRESS`). Получатель по демо-seeder: `administrator@mos-agro.ru`.
Форма дилера `/dealer`: те же поля → `leads` (`type=dealer_request`) → письмо `DealerRequestMail`.

Демо-каталог: `php artisan db:seed --class=DemoCatalogSeeder` (Корневин, HB-101, категории, партнёры).

UI черновой (не финальный дизайн). Тексты из `docs/copy.md`.

## Админка Filament

- URL: `/admin` (логин `/admin/login`)
- CRUD: бренды, категории, товары, фасовки, разделы/документы, партнёры, заявки, получатели
- Навигация RU; группа «Заявки»; создание заявки из админки отключено (`canCreate=false`)
- Виджет «Заявки сегодня» - счётчик `price_request` с 00:00 Europe/Moscow
- Один пользователь: `admin@mos-agro.ru` (пароль в credentials)

## Почта

- SMTP: `smtp.mastermail.ru:465` SSL (не `smtp.masterhost.ru`)
- Отправка заявок проверена
- SPF/DKIM - к боевому запуску / домену

## Что ждём дальше

1. ~~Вёрстка по `design_v3/` (desktop + адаптив)~~ - готово → `layout/` (2026-10-20)
2. Паритет статики с `laravel/` (Blade/CSS/JS) + выкладка на staging
3. SEO на движке (title/description, sitemap, robots, каноникал, Schema.org)
4. Полный каталог от заказчика (этап 5)
5. Домен, SSL, DNS cutover (этап 6)
