# layout - статическая вёрстка

Статус: **набор страниц закрыт** (2026-10-20) по эталону `design_v3/`. Desktop и mobile - один адаптивный HTML на страницу.

Источник правды для движка: `laravel/` (с staging). Сюда кладём согласованные срезы для просмотра без PHP.

**Следующий шаг проекта:** паритет `layout/` → `laravel/` + staging, затем SEO на движке (см. `PLAN.md`).

## Как открыть

```bash
cd layout
python3 -m http.server 8777
# http://127.0.0.1:8777/01-home.html
```

Или открыть HTML файл напрямую в браузере.

## Файлы

| Файл | Страница | Статус |
| --- | --- | --- |
| `01-home.html` | Главная `/` | готово |
| `02-catalog.html` | Каталог `/catalog` | готово |
| `03-product.html` | Карточка товара | готово; вкладки Описание / Применение / Документы |
| `04-about.html` | О компании `/about` | готово |
| `05-documents.html` | Документы `/documents` | готово |
| `05-documents-section.html` | Раздел документов | готово |
| `06-contacts.html` | Контакты `/contacts` | готово |
| `07-price.html` | Прайс `/price` | готово |
| `08-price-thanks.html` | Прайс «спасибо» | готово |
| `09-dealer.html` | Стать дилером `/dealer` | готово |
| `09-dealer-thanks.html` | Дилер «спасибо» | готово |
| `10-exclusive.html` | Эксклюзив `/exclusive` | готово (посадка Flora) |
| `11-catalog-category.html` | Категория СЗР `/catalog/szr` | готово |
| `11-catalog-category-gryuzuny.html` | Категория грызуны | готово |
| `11-catalog-category-hb101.html` | Категория HB-101 (Flora) `/catalog/hb-101` | готово |
| `11-catalog-category-prochee.html` | Категория прочее | готово |
| `12-requisites.html` | Реквизиты `/requisites` | готово |
| `13-privacy.html` | Политика ПДн `/privacy` | готово |
| `14-consent.html` | Согласие `/consent` | готово |

Нумерация совпадает с `design_v3/desktop/` / `mobile/`.

## Структура

```
layout/
  *.html
  css/site.css
  js/menu.js
  images/          # лого, leaf-фоны, иконки, partners/
  README.md
```

## Заметки аудита (2026-10-20)

- Навигация шапки/футера/юр. ссылок согласована; битых HTML-целей нет.
- Демо-заглушки `href="#"`: PDF, «Документы ↓» в списках, пагинация каталога, `action="#"` у поиска - до реальных файлов/бэка.
- Эксклюзив - посадочная по бренду Flora; те же товары в каталоге `/catalog/hb-101` в обычной структуре строк.
