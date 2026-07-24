# Модель данных (этап 0)

Статус: зафиксировано (с уточнениями по каталогу). Ориентир для Laravel-миграций (этап 3). Прототип и тексты - на согласовании у заказчика.

## Продуктовые правила (согласовано)

- **Фасовки:** одноимённые позиции со старого сайта склеиваем в один товар; фасовки - дочерние записи. Комплекты («Корневин х2…») - отдельные товары, если не являются просто фасовкой.
- **Новинки (вариант B):** флаг `is_new` (бейдж) + участие в категории «Новинки» через M2M `product_category`. Основная категория тоже через M2M (или primary + доп. связи - см. ниже).
- **Бренд / производитель:** обязателен в MVP (`Brand`).
- **Эксклюзив:** отдельная витрина `/exclusive` + флаг `is_exclusive`; товар с флагом виден и в общем каталоге (если активен).
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

Категории каталога (в т.ч. системная «Новинки»).

- id
- name
- slug
- sort
- is_active
- timestamps

Товар связан с категориями через M2M `product_category` (основная категория + «Новинки» и при необходимости другие).

### product_category (pivot)

- product_id
- category_id
- is_primary (bool) - основная категория для URL карточки / хлебных крошек
- sort (nullable)

Правило для новинок: `is_new = true` и связь с категорией «Новинки».

### Product

Товар (карточка).

- id
- brand_id
- name
- slug
- short_description
- composition
- application
- image (nullable; на старте плейсхолдер)
- is_new (bool) - бейдж «Новинка»
- is_exclusive (bool) - участвует в `/exclusive` и может быть в общем каталоге
- is_active
- sort
- timestamps

Категории: через `product_category` (не один `category_id`).

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

Заявка.

- id
- type: `price_request`
- name
- phone
- email
- inn (nullable)
- city (nullable)
- comment (nullable)
- contact_preference (nullable)
- status (например `new`)
- timestamps

Обязательные поля с формы: name (ФИО), phone, email.  
Необязательные: inn, city, comment, contact_preference.

## Связи

- Brand 1 → N Product
- Brand 1 → N DocumentSection (опционально)
- Product N ↔ Category (через product_category)
- Product 1 → N Packaging
- Product N ↔ Document
- DocumentSection 1 → N Document
- LeadRecipient - без FK на Lead; письмо уходит на все активные ящики

## Админка (CRUD)

Filament, полный CRUD (создание / изменение / удаление):

- Brand, Category, Product (+ фасовки, категории, флаги, бренд, фото)
- DocumentSection, Document
- PartnerBrand (бренды-партнёры, логотипы)
- LeadRecipient
- Lead - как минимум просмотр (редактирование статуса - по желанию в MVP)

### Дашборд админки

Виджет «Заявки сегодня»: количество заявок (`Lead`), созданных **с 00:00 текущего дня по Москве (Europe/Moscow)**. Не путать со статусом `new` - считаются все заявки за календарные сутки МСК.

## Вне модели БД (MVP)

- Старые адреса `/cart`, `/checkout`, `/login`, `/cabinet` при обращении редиректятся на `/price`.

## Витрины (как строить)

- Общий каталог: активные товары; фильтр/группировка по категориям (кроме отдельной логики новинок - по желанию).
- `/catalog/.../novinki` или категория «Новинки»: товары со связью на эту категорию (и обычно `is_new = true`).
- `/exclusive`: товары с `is_exclusive = true`.
- Карточка в общем каталоге: URL от primary-категории.
