(() => {
  document.querySelectorAll('.allpacode-admin__upload-logo').forEach((button) => {
    button.addEventListener('click', () => {
      if (!window.wp?.media) return;

      const input = button.closest('.allpacode-admin__media')?.querySelector('.allpacode-admin__logo-url');
      const frame = window.wp.media({
        title: 'Seleccionar logo',
        button: { text: 'Usar este logo' },
        multiple: false,
        library: { type: 'image' },
      });

      frame.on('select', () => {
        const attachment = frame.state().get('selection').first()?.toJSON();
        if (attachment?.url && input) input.value = attachment.url;
      });

      frame.open();
    });
  });

  document.querySelectorAll('[data-allpacode-confirm]').forEach((element) => {
    element.addEventListener('click', (event) => {
      if (!window.confirm(element.dataset.allpacodeConfirm)) {
        event.preventDefault();
      }
    });
  });
})();
