(() => {
  const blocks = Array.from(document.querySelectorAll('[data-text-clamp]'));
  if (!blocks.length) return;
  const moreLabel = 'Читать полностью';
  const lessLabel = 'Свернуть';
  const measure = (block) => {
    const body = block.querySelector('[data-text-clamp-body]');
    const toggle = block.querySelector('[data-text-clamp-toggle]');
    if (!body || !toggle) return;
    const lines = Number(block.dataset.clampLines || 4);
    body.style.setProperty('--clamp-lines', String(lines));
    const expanded = block.classList.contains('is-expanded');
    if (expanded) {
      body.classList.remove('is-clamped');
      toggle.hidden = false;
      toggle.textContent = lessLabel;
      toggle.setAttribute('aria-expanded', 'true');
      return;
    }
    body.classList.add('is-clamped');
    const overflows = body.scrollHeight > body.clientHeight + 1;
    toggle.hidden = !overflows;
    toggle.textContent = moreLabel;
    toggle.setAttribute('aria-expanded', 'false');
    if (!overflows) body.classList.remove('is-clamped');
  };
  blocks.forEach((block) => {
    const toggle = block.querySelector('[data-text-clamp-toggle]');
    toggle?.addEventListener('click', () => {
      block.classList.toggle('is-expanded');
      measure(block);
    });
    measure(block);
  });
  window.addEventListener('resize', () => {
    blocks.forEach((block) => {
      if (block.classList.contains('is-expanded')) return;
      measure(block);
    });
  });
  document.querySelectorAll('.product-tab').forEach((tab) => {
    tab.addEventListener('click', () => {
      requestAnimationFrame(() => blocks.forEach(measure));
    });
  });
})();
(() => {
  const root = document.querySelector('[data-product-gallery]');
  if (!root) return;
  const mainImg = root.querySelector('[data-gallery-image]');
  const openBtn = root.querySelector('[data-gallery-open]');
  const thumbs = Array.from(root.querySelectorAll('[data-gallery-thumb]'));
  const prev = root.querySelector('[data-gallery-prev]');
  const next = root.querySelector('[data-gallery-next]');
  if (!mainImg) return;
  const urlsFromThumbs = thumbs
    .map((thumb) => thumb.querySelector('img')?.getAttribute('src'))
    .filter(Boolean);
  const urls = urlsFromThumbs.length
    ? urlsFromThumbs
    : [mainImg.getAttribute('src')].filter(Boolean);
  if (!urls.length) return;
  let index = urls.indexOf(mainImg.getAttribute('src'));
  if (index < 0) index = 0;
  const ZOOM = 2.4;
  const ZOOM_MIN = 1;
  const ZOOM_MAX = 4;
  const lightbox = document.createElement('div');
  lightbox.className = 'photo-lightbox';
  lightbox.setAttribute('role', 'dialog');
  lightbox.setAttribute('aria-modal', 'true');
  lightbox.setAttribute('aria-label', 'Просмотр фото');
  lightbox.hidden = true;
  lightbox.innerHTML = [
    '<div class="photo-lightbox-backdrop" data-lightbox-close></div>',
    '<button type="button" class="photo-lightbox-close" data-lightbox-close aria-label="Закрыть"><svg viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3.2 3.2a.75.75 0 0 1 1.06 0L8 6.94l3.74-3.74a.75.75 0 1 1 1.06 1.06L9.06 8l3.74 3.74a.75.75 0 1 1-1.06 1.06L8 9.06l-3.74 3.74a.75.75 0 1 1-1.06-1.06L6.94 8 3.2 4.26a.75.75 0 0 1 0-1.06z"/></svg></button>',
    urls.length > 1
      ? '<button type="button" class="photo-lightbox-nav photo-lightbox-prev" data-lightbox-prev aria-label="Предыдущее фото">‹</button>'
      : '',
    urls.length > 1
      ? '<button type="button" class="photo-lightbox-nav photo-lightbox-next" data-lightbox-next aria-label="Следующее фото">›</button>'
      : '',
    '<div class="photo-lightbox-stage" data-lightbox-stage>',
    '<img class="photo-lightbox-image" alt="" data-lightbox-image draggable="false">',
    '</div>',
    '<div class="photo-lightbox-counter" data-lightbox-counter hidden></div>',
  ].join('');
  document.documentElement.appendChild(lightbox);
  const lightboxStage = lightbox.querySelector('[data-lightbox-stage]');
  const lightboxImg = lightbox.querySelector('[data-lightbox-image]');
  const lightboxCounter = lightbox.querySelector('[data-lightbox-counter]');
  const lightboxCloseBtn = lightbox.querySelector('.photo-lightbox-close');
  const lightboxPrev = lightbox.querySelector('[data-lightbox-prev]');
  const lightboxNext = lightbox.querySelector('[data-lightbox-next]');
  let scale = 1;
  let tx = 0;
  let ty = 0;
  let scrollY = 0;
  let lastFocus = null;
  let ignoreOpenUntil = 0;
  const applyTransform = () => {
    lightboxImg.style.transform = `translate3d(${tx}px, ${ty}px, 0) scale(${scale})`;
    lightbox.classList.toggle('is-zoomed', scale > 1);
    lightboxImg.classList.toggle('is-zoomed', scale > 1);
  };
  const resetZoom = () => {
    scale = 1;
    tx = 0;
    ty = 0;
    applyTransform();
  };
  const clampScale = (value) => Math.max(ZOOM_MIN, Math.min(ZOOM_MAX, value));
  const clampPan = () => {
    if (scale <= 1.01) {
      scale = 1;
      tx = 0;
      ty = 0;
      return;
    }
    const baseW = lightboxImg.offsetWidth;
    const baseH = lightboxImg.offsetHeight;
    const maxX = Math.max(0, (baseW * scale - window.innerWidth) / 2 + 24);
    const maxY = Math.max(0, (baseH * scale - window.innerHeight) / 2 + 24);
    tx = Math.max(-maxX, Math.min(maxX, tx));
    ty = Math.max(-maxY, Math.min(maxY, ty));
  };
  const zoomAt = (clientX, clientY, nextScale = ZOOM) => {
    const rect = lightboxImg.getBoundingClientRect();
    const ox = clientX - (rect.left + rect.width / 2);
    const oy = clientY - (rect.top + rect.height / 2);
    const prev = scale;
    scale = clampScale(nextScale);
    if (scale <= 1) {
      resetZoom();
      return;
    }
    tx = tx + ox * (1 - scale / prev);
    ty = ty + oy * (1 - scale / prev);
    clampPan();
    applyTransform();
  };
  const toggleZoom = (clientX, clientY) => {
    if (scale > 1) resetZoom();
    else zoomAt(clientX, clientY, ZOOM);
  };
  const touchDistance = (a, b) => {
    const dx = a.clientX - b.clientX;
    const dy = a.clientY - b.clientY;
    return Math.hypot(dx, dy);
  };
  const touchMidpoint = (a, b) => ({
    x: (a.clientX + b.clientX) / 2,
    y: (a.clientY + b.clientY) / 2,
  });
  const syncCounter = () => {
    if (!lightboxCounter) return;
    lightboxCounter.textContent = `${index + 1} / ${urls.length}`;
    lightboxCounter.hidden = urls.length < 2;
  };
  const show = (nextIndex) => {
    index = (nextIndex + urls.length) % urls.length;
    mainImg.src = urls[index];
    lightboxImg.src = urls[index];
    resetZoom();
    thumbs.forEach((thumb, i) => {
      const on = i === index;
      thumb.classList.toggle('is-active', on);
      thumb.setAttribute('aria-pressed', on ? 'true' : 'false');
    });
    syncCounter();
  };
  thumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
      show(Number(thumb.dataset.index || 0));
    });
  });
  prev?.addEventListener('click', (e) => {
    e.stopPropagation();
    show(index - 1);
  });
  next?.addEventListener('click', (e) => {
    e.stopPropagation();
    show(index + 1);
  });
  let touchX = null;
  const galleryStage = root.querySelector('[data-gallery-main]');
  galleryStage?.addEventListener('touchstart', (e) => {
    touchX = e.changedTouches?.[0]?.clientX ?? null;
  }, { passive: true });
  galleryStage?.addEventListener('touchend', (e) => {
    if (touchX == null || urls.length < 2) return;
    const x = e.changedTouches?.[0]?.clientX;
    if (x == null) return;
    const dx = x - touchX;
    touchX = null;
    if (Math.abs(dx) < 40) return;
    show(index + (dx < 0 ? 1 : -1));
  }, { passive: true });
  const lockScroll = () => {
    scrollY = window.scrollY || window.pageYOffset || 0;
    document.documentElement.classList.add('lightbox-open');
    document.body.classList.add('lightbox-open');
  };
  const unlockScroll = () => {
    document.documentElement.classList.remove('lightbox-open');
    document.body.classList.remove('lightbox-open');
    window.scrollTo(0, scrollY);
  };
  const blockPageScroll = (e) => {
    if (lightbox.hidden) return;
    if (e.target.closest?.('[data-lightbox-stage]')) return;
    e.preventDefault();
  };
  document.addEventListener('touchmove', blockPageScroll, { passive: false });
  const openLightbox = () => {
    lastFocus = document.activeElement;
    lightboxImg.src = urls[index];
    lightboxImg.alt = mainImg.alt || '';
    resetZoom();
    syncCounter();
    lockScroll();
    lightbox.hidden = false;
    requestAnimationFrame(() => {
      lightboxCloseBtn?.focus({ preventScroll: true });
    });
  };
  const closeLightbox = () => {
    if (lightbox.hidden) return;
    lightbox.hidden = true;
    resetZoom();
    unlockScroll();
    ignoreOpenUntil = Date.now() + 500;
    if (lastFocus && typeof lastFocus.focus === 'function') {
      lastFocus.focus({ preventScroll: true });
    }
  };
  openBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    if (Date.now() < ignoreOpenUntil) return;
    openLightbox();
  });
  const isEmptyHit = (target) => {
    if (!target) return false;
    if (target.hasAttribute?.('data-lightbox-close')) return true;
    if (target === lightbox || target === lightboxStage) return true;
    return false;
  };
  lightbox.addEventListener('pointerdown', (e) => {
    if (e.target.closest('.photo-lightbox-nav')) return;
    if (e.target.closest('[data-lightbox-image]')) return;
    if (e.target.closest('.photo-lightbox-close')) return;
    e.preventDefault();
  });
  lightbox.addEventListener('click', (e) => {
    if (e.target.closest('.photo-lightbox-nav')) return;
    if (e.target.closest('[data-lightbox-image]')) return;
    if (scale > 1) {
      resetZoom();
      return;
    }
    if (isEmptyHit(e.target) || e.target.closest?.('[data-lightbox-close]')) {
      e.preventDefault();
      e.stopPropagation();
      closeLightbox();
    }
  });
  lightboxPrev?.addEventListener('click', (e) => {
    e.stopPropagation();
    show(index - 1);
  });
  lightboxNext?.addEventListener('click', (e) => {
    e.stopPropagation();
    show(index + 1);
  });
  document.addEventListener('keydown', (e) => {
    if (lightbox.hidden) return;
    if (e.key === 'Escape') {
      e.preventDefault();
      if (scale > 1) resetZoom();
      else closeLightbox();
      return;
    }
    if (scale > 1 || urls.length < 2) return;
    if (e.key === 'ArrowLeft') {
      e.preventDefault();
      show(index - 1);
    } else if (e.key === 'ArrowRight') {
      e.preventDefault();
      show(index + 1);
    }
  });
  let pointerMoved = false;
  let dragX = 0;
  let dragY = 0;
  let startTx = 0;
  let startTy = 0;
  let dragging = false;
  lightboxImg.addEventListener('click', (e) => {
    if (pointerMoved) return;
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      e.preventDefault();
      e.stopPropagation();
      toggleZoom(e.clientX, e.clientY);
    }
  });
  lightboxImg.addEventListener('pointerdown', (e) => {
    if (e.pointerType === 'mouse' && e.button !== 0) return;
    if (scale <= 1) return;
    dragging = true;
    pointerMoved = false;
    dragX = e.clientX;
    dragY = e.clientY;
    startTx = tx;
    startTy = ty;
    lightboxImg.setPointerCapture?.(e.pointerId);
  });
  lightboxImg.addEventListener('pointermove', (e) => {
    if (!dragging) return;
    const dx = e.clientX - dragX;
    const dy = e.clientY - dragY;
    if (Math.abs(dx) > 3 || Math.abs(dy) > 3) pointerMoved = true;
    tx = startTx + dx;
    ty = startTy + dy;
    clampPan();
    applyTransform();
  });
  const endDrag = () => {
    dragging = false;
  };
  lightboxImg.addEventListener('pointerup', endDrag);
  lightboxImg.addEventListener('pointercancel', endDrag);
  let lastTapAt = 0;
  let lastTapX = 0;
  let lastTapY = 0;
  let swipeX = null;
  let swipeY = null;
  let swiped = false;
  let pinching = false;
  let pinchStartDist = 0;
  let pinchStartScale = 1;
  let pinchStartTx = 0;
  let pinchStartTy = 0;
  let pinchMidX = 0;
  let pinchMidY = 0;
  const beginPinch = (touches) => {
    if (touches.length < 2) return;
    pinching = true;
    dragging = false;
    swiped = true;
    pointerMoved = true;
    lastTapAt = 0;
    pinchStartDist = touchDistance(touches[0], touches[1]) || 1;
    pinchStartScale = scale;
    pinchStartTx = tx;
    pinchStartTy = ty;
    const mid = touchMidpoint(touches[0], touches[1]);
    pinchMidX = mid.x;
    pinchMidY = mid.y;
  };
  lightboxStage.addEventListener('touchstart', (e) => {
    if (e.touches.length >= 2) {
      e.preventDefault();
      beginPinch(e.touches);
      return;
    }
    if (e.touches.length !== 1) return;
    pinching = false;
    const t = e.touches[0];
    swipeX = t.clientX;
    swipeY = t.clientY;
    swiped = false;
    pointerMoved = false;
    if (scale > 1) {
      dragging = true;
      dragX = t.clientX;
      dragY = t.clientY;
      startTx = tx;
      startTy = ty;
    }
  }, { passive: false });
  lightboxStage.addEventListener('touchmove', (e) => {
    if (e.touches.length >= 2) {
      e.preventDefault();
      if (!pinching) beginPinch(e.touches);
      const dist = touchDistance(e.touches[0], e.touches[1]) || 1;
      const mid = touchMidpoint(e.touches[0], e.touches[1]);
      scale = clampScale(pinchStartScale * (dist / pinchStartDist));
      if (scale <= 1.01) {
        scale = 1;
        tx = 0;
        ty = 0;
      } else {
        const ratio = scale / pinchStartScale;
        const halfW = window.innerWidth / 2;
        const halfH = window.innerHeight / 2;
        tx = mid.x - halfW - (pinchMidX - halfW - pinchStartTx) * ratio;
        ty = mid.y - halfH - (pinchMidY - halfH - pinchStartTy) * ratio;
        clampPan();
      }
      applyTransform();
      return;
    }
    if (pinching || swipeX == null) return;
    const t = e.touches[0];
    if (!t) return;
    const dx = t.clientX - swipeX;
    const dy = t.clientY - swipeY;
    if (Math.abs(dx) > 8 || Math.abs(dy) > 8) {
      swiped = true;
      pointerMoved = true;
    }
    if (scale > 1 && dragging) {
      e.preventDefault();
      tx = startTx + dx;
      ty = startTy + dy;
      clampPan();
      applyTransform();
    }
  }, { passive: false });
  lightboxStage.addEventListener('touchend', (e) => {
    if (lightbox.hidden) return;
    if (pinching) {
      if (e.touches.length >= 2) {
        beginPinch(e.touches);
        return;
      }
      if (e.touches.length === 1) {
        pinching = false;
        dragging = true;
        const t = e.touches[0];
        swipeX = t.clientX;
        swipeY = t.clientY;
        dragX = t.clientX;
        dragY = t.clientY;
        startTx = tx;
        startTy = ty;
        if (scale <= 1.01) resetZoom();
        else clampPan();
        applyTransform();
        return;
      }
      pinching = false;
      dragging = false;
      swipeX = null;
      swipeY = null;
      if (scale <= 1.01) resetZoom();
      else {
        clampPan();
        applyTransform();
      }
      return;
    }
    dragging = false;
    const t = e.changedTouches?.[0];
    if (!t) return;
    const dx = swipeX == null ? 0 : t.clientX - swipeX;
    const dy = swipeY == null ? 0 : t.clientY - swipeY;
    const targetIsImage = !!e.target.closest?.('[data-lightbox-image]');
    swipeX = null;
    swipeY = null;
    if (scale <= 1 && !swiped && urls.length > 1 && Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
      show(index + (dx < 0 ? 1 : -1));
      lastTapAt = 0;
      return;
    }
    if (swiped) return;
    const now = Date.now();
    const isDouble = now - lastTapAt < 300
      && Math.abs(t.clientX - lastTapX) < 28
      && Math.abs(t.clientY - lastTapY) < 28;
    if (isDouble) {
      if (targetIsImage) {
        e.preventDefault();
        toggleZoom(t.clientX, t.clientY);
      }
      lastTapAt = 0;
      return;
    }
    if (!targetIsImage && scale <= 1) {
      e.preventDefault();
      e.stopPropagation();
      lastTapAt = 0;
      closeLightbox();
      return;
    }
    lastTapAt = now;
    lastTapX = t.clientX;
    lastTapY = t.clientY;
  }, { passive: false });
  lightboxStage.addEventListener('touchcancel', () => {
    pinching = false;
    dragging = false;
    swipeX = null;
    swipeY = null;
    if (scale <= 1.01) resetZoom();
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
