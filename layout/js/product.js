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
