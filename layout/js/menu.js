(() => {
  const burger = document.querySelector('[data-burger]');
  const menu = document.querySelector('[data-mobile-menu]');
  const closeBtn = document.querySelector('[data-menu-close]');
  if (!burger || !menu) return;
  const fitMenu = () => {
    menu.style.width = document.documentElement.clientWidth + 'px';
  };
  const open = () => {
    fitMenu();
    menu.hidden = false;
    burger.setAttribute('aria-expanded', 'true');
    document.body.classList.add('menu-open');
  };
  const close = () => {
    menu.hidden = true;
    burger.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('menu-open');
    menu.style.width = '';
  };
  burger.addEventListener('click', open);
  closeBtn?.addEventListener('click', close);
  menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', close));
  window.addEventListener('resize', () => {
    if (!menu.hidden) fitMenu();
  });
  window.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !menu.hidden) close();
  });
})();
