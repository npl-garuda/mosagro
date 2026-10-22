(() => {
  const form = document.querySelector('.catalog-search-form');
  const input = document.querySelector('#catalog-q');
  const results = document.querySelector('[data-catalog-results]');
  if (!form || !input || !results) return;
  let timer = null;
  let controller = null;
  const buildUrl = (baseHref) => {
    const url = new URL(baseHref || form.action, window.location.origin);
    const q = input.value.trim();
    if (q) url.searchParams.set('q', q);
    else url.searchParams.delete('q');
    if (!baseHref) url.searchParams.delete('page');
    return url.pathname + url.search + url.hash;
  };
  const load = (href) => {
    if (controller) controller.abort();
    controller = new AbortController();
    results.classList.add('is-loading');
    results.setAttribute('aria-busy', 'true');
    fetch(href, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'text/html',
      },
      signal: controller.signal,
      credentials: 'same-origin',
    })
      .then((response) => {
        if (!response.ok) throw new Error('search failed');
        return response.text();
      })
      .then((html) => {
        results.innerHTML = html.trim() || '<p class="catalog-empty">Таких товаров не найдено, проверьте правильность поиска ...</p>';
        history.replaceState(null, '', href);
      })
      .catch((error) => {
        if (error.name === 'AbortError') return;
      })
      .finally(() => {
        results.classList.remove('is-loading');
        results.removeAttribute('aria-busy');
      });
  };
  const schedule = (delay = 320) => {
    clearTimeout(timer);
    timer = setTimeout(() => load(buildUrl()), delay);
  };
  input.addEventListener('input', () => schedule());
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    clearTimeout(timer);
    load(buildUrl());
  });
  results.addEventListener('click', (event) => {
    const link = event.target.closest('a.catalog-page');
    if (!link) return;
    event.preventDefault();
    load(link.getAttribute('href'));
  });
})();
