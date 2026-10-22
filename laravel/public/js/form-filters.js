(() => {
  const filters = {
    phone: /[^0-9+() \-]/g,
    email: /[^a-zA-Z0-9@._+\-]/g,
  };
  document.querySelectorAll('[data-filter]').forEach((input) => {
    const key = input.getAttribute('data-filter');
    const re = filters[key];
    if (!re) return;
    input.addEventListener('input', () => {
      const next = input.value.replace(re, '');
      if (next !== input.value) input.value = next;
    });
  });
})();
