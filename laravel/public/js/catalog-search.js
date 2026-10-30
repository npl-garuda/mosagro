(() => {
  const form = document.querySelector('.catalog-search-form');
  const input = document.querySelector('#catalog-q');
  const results = document.querySelector('[data-catalog-results]');
  if (!form || !input || !results) return;
  let timer = null;
  let controller = null;
  let hadQuery = input.value.trim() !== '';
  const catalogIndexUrl = (() => {
    try {
      const url = new URL(form.action, window.location.origin);
      url.search = '';
      url.hash = '';
      return url.pathname.replace(/\/$/, '') || '/catalog';
    } catch (e) {
      return '/catalog';
    }
  })();
  const normalizePath = (path) => {
    const clean = (path || '/').replace(/\/$/, '');
    return clean || '/';
  };
  const buildUrl = (baseHref) => {
    const url = new URL(baseHref || form.action, window.location.origin);
    const q = input.value.trim();
    if (q) url.searchParams.set('q', q);
    else url.searchParams.delete('q');
    if (!baseHref) url.searchParams.delete('page');
    return url.pathname + url.search + url.hash;
  };
  const markAllProductsChip = () => {
    document.querySelectorAll('.catalog-chip').forEach((chip) => {
      const chipPath = normalizePath(new URL(chip.href, window.location.origin).pathname);
      chip.classList.toggle('is-active', chipPath === catalogIndexUrl);
    });
  };
  const syncChips = (href) => {
    const url = new URL(href, window.location.origin);
    const hasQ = !!url.searchParams.get('q')?.trim();
    const targetPath = normalizePath(url.pathname);
    document.querySelectorAll('.catalog-chip').forEach((chip) => {
      const chipPath = normalizePath(new URL(chip.href, window.location.origin).pathname);
      const isAll = chipPath === catalogIndexUrl;
      chip.classList.toggle('is-active', hasQ ? isAll : chipPath === targetPath);
    });
  };
  const goAllProducts = () => {
    markAllProductsChip();
    const currentPath = normalizePath(window.location.pathname);
    const hasQuery = !!window.location.search;
    if (currentPath === catalogIndexUrl && !hasQuery) {
      load(catalogIndexUrl);
      return;
    }
    window.location.assign(catalogIndexUrl);
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
        syncChips(href);
      })
      .catch((error) => {
        if (error.name === 'AbortError') return;
      })
      .finally(() => {
        results.classList.remove('is-loading');
        results.removeAttribute('aria-busy');
      });
  };
  const onQueryChange = () => {
    const q = input.value.trim();
    clearTimeout(timer);
    if (!q) {
      const shouldReset = hadQuery
        || new URLSearchParams(window.location.search).has('q')
        || normalizePath(window.location.pathname) !== catalogIndexUrl;
      hadQuery = false;
      if (shouldReset) goAllProducts();
      else markAllProductsChip();
      return;
    }
    hadQuery = true;
    markAllProductsChip();
    timer = setTimeout(() => load(buildUrl()), 320);
  };
  input.addEventListener('input', onQueryChange);
  input.addEventListener('search', onQueryChange);
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    clearTimeout(timer);
    const q = input.value.trim();
    if (!q) {
      hadQuery = false;
      goAllProducts();
      return;
    }
    hadQuery = true;
    markAllProductsChip();
    load(buildUrl());
  });
  results.addEventListener('click', (event) => {
    const link = event.target.closest('a.catalog-page');
    if (!link) return;
    event.preventDefault();
    load(link.getAttribute('href'));
  });
})();
