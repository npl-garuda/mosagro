(() => {
  const root = document.querySelector('[data-product-gallery]');
  if (!root) return;
  const mainImg = root.querySelector('[data-gallery-image]');
  const thumbs = Array.from(root.querySelectorAll('[data-gallery-thumb]'));
  const prev = root.querySelector('[data-gallery-prev]');
  const next = root.querySelector('[data-gallery-next]');
  if (!mainImg || thumbs.length < 2) return;
  const urls = thumbs.map((thumb) => thumb.querySelector('img')?.getAttribute('src')).filter(Boolean);
  if (urls.length < 2) return;
  let index = 0;
  const show = (nextIndex) => {
    index = (nextIndex + urls.length) % urls.length;
    mainImg.src = urls[index];
    thumbs.forEach((thumb, i) => {
      const on = i === index;
      thumb.classList.toggle('is-active', on);
      thumb.setAttribute('aria-pressed', on ? 'true' : 'false');
    });
  };
  thumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
      const i = Number(thumb.dataset.index || 0);
      show(i);
    });
  });
  prev?.addEventListener('click', () => show(index - 1));
  next?.addEventListener('click', () => show(index + 1));
  let touchX = null;
  const stage = root.querySelector('[data-gallery-main]');
  stage?.addEventListener('touchstart', (e) => {
    touchX = e.changedTouches?.[0]?.clientX ?? null;
  }, { passive: true });
  stage?.addEventListener('touchend', (e) => {
    if (touchX == null) return;
    const x = e.changedTouches?.[0]?.clientX;
    if (x == null) return;
    const dx = x - touchX;
    touchX = null;
    if (Math.abs(dx) < 40) return;
    show(index + (dx < 0 ? 1 : -1));
  }, { passive: true });
})();
(() => {
  const tabs = Array.from(document.querySelectorAll('.product-tab'));
  const panels = Array.from(document.querySelectorAll('.product-panel'));
  if (!tabs.length) return;
  const activate = (name) => {
    tabs.forEach((tab) => {
      const on = tab.dataset.tab === name;
      tab.classList.toggle('is-active', on);
      tab.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    panels.forEach((panel) => {
      const on = panel.dataset.panel === name;
      panel.classList.toggle('is-active', on);
      panel.hidden = !on;
    });
  };
  const openDocuments = (scroll) => {
    activate('documents');
    if (!scroll) return;
    const target = document.getElementById('documents');
    if (!target) return;
    requestAnimationFrame(() => {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  };
  tabs.forEach((tab) => tab.addEventListener('click', () => activate(tab.dataset.tab)));
  document.querySelectorAll('[data-open-tab]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const name = btn.getAttribute('data-open-tab');
      if (!name) return;
      e.preventDefault();
      if (name === 'documents') openDocuments(true);
      else {
        activate(name);
        document.getElementById('panel-' + name)?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    });
  });
  if (location.hash === '#documents') openDocuments(true);
  window.addEventListener('hashchange', () => {
    if (location.hash === '#documents') openDocuments(true);
  });
})();
(() => {
  const root = document.querySelector('[data-related-slider]');
  if (!root) return;
  const track = root.querySelector('[data-related-track]');
  const prev = root.querySelector('[data-related-prev]');
  const next = root.querySelector('[data-related-next]');
  if (!track || !prev || !next) return;
  if (!track.children.length) return;
  const gap = () => {
    const styles = getComputedStyle(track);
    return parseFloat(styles.columnGap || styles.gap) || 0;
  };
  const cardStep = () => {
    const card = track.querySelector('.related-card');
    if (!card) return 0;
    return card.getBoundingClientRect().width + gap();
  };
  const pageStep = () => {
    const perPage = window.matchMedia('(min-width: 800px)').matches ? 4 : 2;
    const count = track.querySelectorAll('.related-card').length;
    return cardStep() * Math.min(perPage, Math.max(count, 1));
  };
  const maxScroll = () => Math.max(0, track.scrollWidth - track.clientWidth);
  const updateNav = () => {
    const max = maxScroll();
    const canScroll = max > 2;
    root.classList.toggle('is-scrollable', canScroll);
    prev.disabled = !canScroll || track.scrollLeft <= 2;
    next.disabled = !canScroll || track.scrollLeft >= max - 2;
  };
  prev.addEventListener('click', () => {
    track.scrollBy({ left: -pageStep(), behavior: 'smooth' });
  });
  next.addEventListener('click', () => {
    track.scrollBy({ left: pageStep(), behavior: 'smooth' });
  });
  track.addEventListener('scroll', updateNav, { passive: true });
  track.addEventListener('scrollend', updateNav);
  window.addEventListener('resize', updateNav);
  updateNav();
})();
