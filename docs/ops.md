# Staging / ops (МосАгро)

Статус на **2026-10-22**. Секреты - только в `docs/credentials.local.md` (не в git).

## Репозиторий и код

- GitHub: https://github.com/npl-garuda/mosagro (ветка `main`)
- Локальный корень: `/Users/npl/MOSAGRO`
- Движок в git: `laravel/` - полный Laravel (модели, Filament, migrations, views, public CSS/JS/images)
- Runtime на VPS: `/var/www/mosagro` (`.env`, `vendor`, кэши, `storage/app` uploads - только на сервере)
- Клон монорепо на VPS: `/opt/mosagro` (`git pull` для справки; webroot отдельно)
- **Актуальная тройка:** git `main` = локальный `laravel/` = `/var/www/mosagro` (без `.env` / `vendor` / uploads)
- Дизайн/вёрстка-эталон: `design_v3/`, `layout/` (не смешивать с runtime)
- `docs/credentials.local.md`, `.env`, бэкапы drawio - в `.gitignore`

### Снять движок со staging в git

```bash
rsync -avz --delete \
  -e 'ssh -p 4222 -i ~/.ssh/id_ed25519 -o IdentitiesOnly=yes' \
  --exclude '.env' --exclude '.env.*' --exclude 'vendor/' --exclude 'node_modules/' \
  --exclude 'storage/logs/*' --exclude 'storage/framework/cache/data/' \
  --exclude 'storage/framework/sessions/*' --exclude 'storage/framework/views/*' \
  --exclude 'bootstrap/cache/*.php' --exclude 'public/storage' \
  --exclude 'storage/app/public/*' \
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
  'cd /var/www/mosagro && php artisan migrate --force && php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache'
```

После смены `.env` на сервере всегда: `php artisan config:cache`.

### Git push

- Remote: `https://github.com/npl-garuda/mosagro.git` (через `gh` auth)

## VPS

| Параметр | Значение |
| --- | --- |
| IP | `87.242.65.116` |
| SSH | порт **4222**, только ключ |
| ОС | Ubuntu 26.04, ~1.5 ГБ RAM + swap 2 ГБ |
| UFW | allow `4222`, `80`; HTTPS (**443**) пока нет |
| Стек | Nginx 1.28, PHP 8.5-FPM, MySQL 8.4 (127.0.0.1), Composer 2.9 |
| App | Laravel **13** + Filament **4** |
| Docroot | `/var/www/mosagro/public` |

Старый сайт на shared Masterhost **не трогать** до DNS cutover.

## Laravel `.env` (staging)

- `APP_ENV=production`, `APP_DEBUG=false`, `LOG_LEVEL=error`
- `APP_URL=http://87.242.65.116`
- `APP_LOCALE=ru`
- `QUEUE_CONNECTION=sync`
- `MAIL_SCHEME=smtps` + SMTP Mastermail `:465`
- `SESSION_DRIVER=database`

Права `.env`: `chown root:www-data` + `chmod 640`.

## Публичный сайт (актуально)

Маршруты: `/`, `/about`, `/catalog`, `/catalog/{category}`, `/catalog/{category}/{product}`, `/exclusive`, `/documents`, `/documents/{section}`, `/price`, `/price/thanks`, `/dealer`, `/dealer/thanks`, `/contacts`, `/privacy`, `/consent`, `/requisites`.

Редиректы 301 на `/price`: `/cart`, `/checkout`, `/login`, `/cabinet`.

### Каталог и карточка

- Категории и товары из админки; фильтры-чипы + живой поиск по названию
- Несколько фото товара (`product_images`); первое = основное; на карточке слайдер + миниатюры
- Бейджи «Новинка» / «Эксклюзив» только от флагов `is_new` / `is_exclusive`
- `/exclusive` - товары с **основной** категорией `floora`; кнопка «Смотреть в каталоге» → `/catalog/floora`

### Формы

- `/price` и `/dealer`: одинаковые поля; обязателен телефон; throttle `5,1`
- Сохранение в `leads` (`price_request` / `dealer_request`, статус `new`) + письмо активным `lead_recipients`
- `/price/thanks` и `/dealer/thanks` открываются **только** после успешной отправки (flash `form_thanks`); прямой заход → редирект на форму
- Галочки ПДн на форме нет; ссылки на `/privacy` и `/consent` в футере

### Legal / реквизиты

- Тексты политики и согласия из документов заказчика (исходники в `docs/*.docx`)
- Реквизиты + кнопка скачивания xls: `/files/kartochka-klienta-mosagro-vtb.xls` (без телефонов)

### Медиа

- `php artisan storage:link` → `/storage/...`
- Товары: `products/`; документы: `documents/`; публичные файлы сайта: `public/files/`, `public/images/`

## Админка Filament

- URL: `/admin`
- CRUD: бренды, категории, товары (+ категории/primary, галерея, флаги), фасовки, разделы/документы, партнёры, заявки, получатели
- Заявки: список RU, сортировка `created_at desc`; статусы вручную: Новая / В работе / Закрыта; создание из админки выключено
- Партнёры в админке пока **не** связаны с полосой на главной (там hardcoded SVG)
- Виджет «Заявки сегодня» - счётчик с 00:00 Europe/Moscow
- Логин: `admin@mos-agro.ru` (пароль в credentials)

## Безопасность

Сделано: `APP_DEBUG=false`, throttle форм, `.env` 640, `FilamentUser`, storage без исполнения PHP.

Осталось: HTTPS + `SESSION_SECURE_COOKIE`, смена пароля админа, security headers, CAPTCHA по желанию.

## Почта

- SMTP: `smtp.mastermail.ru:465` SSL
- Отправка заявок проверена

## Что дальше

1. SEO: sitemap.xml / robots / каноникал / Schema.org
2. Полный каталог от заказчика; контент Floora (фото, документы раздела)
3. Партнёры с админки на витрину
4. Домен, SSL, DNS cutover
