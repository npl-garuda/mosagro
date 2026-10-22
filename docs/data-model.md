# Модель данных

Статус: актуально под staging (2026-10-22). Реализация: `laravel/` (миграции + Filament).

## Продуктовые правила (согласовано)

- **Фасовки:** одноимённые позиции со старого сайта склеиваем в один товар; фасовки - дочерние записи. Комплекты («Корневин х2…») - отдельные товары, если не являются просто фасовкой.
- **Новинка / Эксклюзив:** флаги `is_new` и `is_exclusive` только включают бейджи на сайте. Отдельные категории под них не нужны.
- **Бренд / производитель:** обязателен в MVP (`Brand`).
- **Страница `/exclusive`:** товары с основной категорией `floora`; не связана с галкой «Эксклюзив».
- **Контент каталога:** полный перечень товаров (названия, описания и т.д.) поставляется заказчиком отдельно. Старый сайт - только справка, не эталон.
- **Админка:** полный CRUD карточек товаров (создание, изменение, удаление) и связанных сущностей.

## Сущности

### Brand

Бренд / производитель.

- id
- name
- slug
- sort
- is_active
- timestamps

### Category

Категории каталога (обычные разделы ассортимента).

- id
- name
- slug
- sort
- is_active
- timestamps

Товар связан с категориями через M2M `product_category` (основная + дополнительные при необходимости).

### product_category (pivot)

- product_id
- category_id
- is_primary (bool) - основная категория для URL карточки / хлебных крошек
- sort (nullable)

Флаги `is_new` / `is_exclusive` - только бейджи, на состав категорий не влияют.

### Product

Товар (карточка).

- id
- brand_id
- name
- slug
- short_description
- composition
- application
- image (nullable; дубль основного фото для списков каталога)
- is_new (bool) - бейдж «Новинка»
- is_exclusive (bool) - бейдж «Эксклюзив»
- is_active
- sort
- timestamps

Категории: через `product_category` (не один `category_id`).

Фото: таблица `product_images` (product_id, path, is_primary, sort). Первое в админке = основное (`is_primary`, пишется в `products.image`). В карточке - большой слайд + миниатюры.

### Packaging

Фасовка товара (у товара может быть несколько).

- id
- product_id
- name (например «10 г», «ампула 2 мл»)
- sort

Фасовки показываются в карточке справочно.

### DocumentSection

Общий раздел документов (по бренду / производителю и т.п.).

- id
- name
- slug
- description (nullable)
- brand_id (nullable) - удобная связь с Brand
- sort
- is_active

### Document

Файл или внешняя ссылка.

- id
- title
- file_path (nullable в БД; в админке обязателен PDF через FileUpload)
- external_url (nullable в БД; **не используется в UI** - оставлен для совместимости)
- document_section_id (nullable)
- product_id (nullable; у товара может быть несколько Document)
- sort

Правило: документ привязан к разделу и/или к товару (хотя бы одно из двух).

### PartnerBrand (бренды-партнёры)

Блок логотипов на витрине («Бренды-партнёры»). Не путать с Brand (производитель товара в каталоге).

- id
- name
- logo (nullable)
- url (nullable)
- sort
- is_active

В коде/БД допустимо имя таблицы `partner_brands` или `partners` с подписью в UI «Бренды-партнёры».

### LeadRecipient

Ящики для писем по заявкам (1..N, управляются в админке).

- id
- email
- is_active
- sort

### Lead

Заявка (`leads`).

- id
- type: `price_request` | `dealer_request`
- name - название организации (с формы: `organization_name`)
- phone - телефон организации
- email - email организации (на форме необязателен; в БД строка, может быть пустой)
- inn (nullable)
- city (nullable; в текущих формах не используется)
- comment (nullable)
- contact_preference (nullable) - формат работы с формы `work_format` (опт / розница / сеть / маркетплейс)
- status: `new` | `in_progress` | `done` (Новая / В работе / Закрыта) - меняется **вручную** в админке
- timestamps

Обязательное поле с формы: phone.  
Необязательные: organization_name, inn, email, work_format, comment.  
Согласия ПДн в заявке не храним. Создание Lead из админки выключено.

## Связи

- Brand 1 → N Product
- Brand 1 → N DocumentSection (опционально)
- Product N ↔ Category (через product_category)
- Product 1 → N Packaging
- Product 1 → N ProductImage
- Product N ↔ Document
- DocumentSection 1 → N Document
- LeadRecipient - без FK на Lead; письмо уходит на все активные ящики

## Админка (CRUD)

Filament, полный CRUD (создание / изменение / удаление):

- Brand, Category, Product (+ фасовки, категории, флаги, бренд, галерея `product_images`)
- DocumentSection, Document
- PartnerBrand (бренды-партнёры; на витрине пока не подключены)
- LeadRecipient
- Lead - просмотр / правка статуса (создание выключено; список по `created_at` desc)

### Дашборд админки

Виджет «Заявки сегодня»: количество заявок (`Lead`), созданных **с 00:00 текущего дня по Москве (Europe/Moscow)**. Не путать со статусом `new` - считаются все заявки за календарные сутки МСК.

## Вне модели БД (MVP)

- Старые адреса `/cart`, `/checkout`, `/login`, `/cabinet` при обращении редиректятся на `/price`.

## Витрины (как строить)

- Общий каталог: активные товары; фильтр/группировка по категориям (кроме отдельной логики новинок - по желанию).
- `/exclusive`: товары с основной категорией `floora`, независимо от бейджа «Эксклюзив».
- Карточка в общем каталоге: URL от primary-категории.
