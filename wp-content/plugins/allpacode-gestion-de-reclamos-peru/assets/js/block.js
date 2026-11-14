(() => {
  const { registerBlockType } = wp.blocks;
  const { createElement: el } = wp.element;

  registerBlockType('allpacode/gestion-reclamos', {
    title: 'Gestión de Reclamos',
    icon: 'feedback',
    category: 'widgets',
    edit: () => el('div', { className: 'components-placeholder' }, [
      el('div', { className: 'components-placeholder__label', key: 'label' }, 'Gestión de Reclamos'),
      el('p', { key: 'text' }, 'El formulario se renderiza en el frontend.'),
    ]),
    save: () => null,
  });
})();
