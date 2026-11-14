(() => {
  const hideCustomSelectClones = (root = document) => {
    const cloneSelectors = [
      '.nice-select',
      '.select2',
      '.select2-container',
      '.chosen-container',
      '.selectric-wrapper',
      '.choices',
      '.ts-wrapper',
      '.dropdown',
      '[role="combobox"]',
      '[class*="select"]',
    ];

    root.querySelectorAll('.allpacode-gr select').forEach((select) => {
      select.classList.add('allpacode-gr__native-select');

      let sibling = select.nextElementSibling;
      while (sibling && sibling.tagName !== 'LABEL') {
        if (cloneSelectors.some((selector) => sibling.matches(selector)) && sibling.tagName !== 'SELECT') {
          sibling.classList.add('allpacode-gr__select-clone');
          sibling.setAttribute('aria-hidden', 'true');
        }

        sibling = sibling.nextElementSibling;
      }
    });
  };

  const fieldNames = {
    consumer_name: 'Nombres y apellidos',
    document_type: 'Tipo de documento',
    document_number: 'Número de documento',
    phone: 'Teléfono',
    email: 'Correo electrónico',
    allpacode_ubigeo_department: 'Departamento / Región',
    allpacode_ubigeo_province: 'Provincia',
    allpacode_ubigeo_district: 'Distrito',
    allpacode_ubigeo_code: 'Ubigeo',
    address: 'Dirección',
    type: 'Clase de caso',
    product_service: 'Producto o servicio',
    claimed_amount: 'Monto reclamado',
    description: 'Descripción',
    consumer_request: 'Pedido del consumidor',
    consent: 'Aceptación de privacidad',
  };

  const getField = (form, name) => form.elements[name] || form.querySelector(`[name="${name.replace(/"/g, '\\"')}"]`);

  const clearErrors = (form) => {
    form.querySelectorAll('.allpacode-gr__field-error').forEach((item) => item.remove());
    form.querySelectorAll('.has-error').forEach((item) => item.classList.remove('has-error'));
    form.querySelectorAll('[aria-invalid="true"]').forEach((item) => item.removeAttribute('aria-invalid'));
  };

  const fieldContainer = (field) => field.closest('label') || field.closest('.allpacode-gr__ubigeo') || field.parentElement;

  const showFieldError = (form, name, message) => {
    const field = getField(form, name);
    if (!field) return;

    const container = fieldContainer(field);
    const error = document.createElement('div');
    error.className = 'allpacode-gr__field-error';
    error.textContent = message;

    field.setAttribute('aria-invalid', 'true');
    container?.classList.add('has-error');
    container?.append(error);
  };

  const renderErrors = (form, errors = {}) => {
    let firstField = null;

    Object.entries(errors).forEach(([name, messages]) => {
      const message = Array.isArray(messages) ? messages[0] : messages;
      showFieldError(form, name, message || AllpaCodeGR.i18n.error);
      firstField = firstField || getField(form, name);
    });

    firstField?.focus({ preventScroll: true });
    firstField?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  };

  const markRequiredFields = (form) => {
    form.querySelectorAll('[required]').forEach((field) => {
      const label = field.closest('label');
      if (!label || label.dataset.requiredMarked === '1') return;

      const existingLabelText = [...label.children].find((child) => child.tagName === 'SPAN');
      const labelTextNode = [...label.childNodes].find((node) => node.nodeType === Node.TEXT_NODE && node.textContent.trim());
      let labelText = existingLabelText;

      if (labelText) {
        labelText.classList.add('allpacode-gr__label-text');
      } else if (labelTextNode) {
        labelText = document.createElement('span');
        labelText.className = 'allpacode-gr__label-text';
        labelText.textContent = labelTextNode.textContent.trim();
        label.replaceChild(labelText, labelTextNode);
      } else {
        labelText = document.createElement('span');
        labelText.className = 'allpacode-gr__label-text';
        labelText.textContent = fieldNames[field.name] || '';
        label.insertBefore(labelText, field);
      }

      const badge = document.createElement('span');
      badge.className = 'allpacode-gr__required';
      badge.textContent = '*';
      badge.setAttribute('aria-label', AllpaCodeGR.i18n.required || 'Obligatorio');
      labelText.append(badge);
      label.dataset.requiredMarked = '1';
    });
  };

  const refreshNonce = async (form) => {
    const nonce = form.querySelector('[name="nonce"]');
    if (!nonce) return;

    const data = new FormData();
    data.set('action', 'allpacode_gr_nonce');

    const response = await fetch(AllpaCodeGR.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: data,
    });
    const json = await response.json();
    if (json.success && json.data?.nonce) {
      nonce.value = json.data.nonce;
    }
  };

  const parseResponse = async (response) => {
    const text = await response.text();
    try {
      return JSON.parse(text);
    } catch (error) {
      if (text.trim() === '-1') {
        return { success: false, data: { message: AllpaCodeGR.i18n.session } };
      }
      return { success: false, data: { message: AllpaCodeGR.i18n.error } };
    }
  };

  const normalizeErrors = (errors = {}) => Object.fromEntries(
    Object.entries(errors).map(([name, messages]) => [
      name,
      Array.isArray(messages) ? messages.map((message) => String(message)) : [String(messages)],
    ])
  );

  const clientErrors = (form) => {
    const errors = {};
    form.querySelectorAll('input, select, textarea').forEach((field) => {
      if (field.type === 'checkbox' && !field.checked) {
        errors[field.name] = [`${fieldNames[field.name] || 'Este campo'} es obligatorio.`];
        return;
      }

      if (field.type !== 'checkbox' && !String(field.value || '').trim()) {
        if (field.required) {
          errors[field.name] = [`${fieldNames[field.name] || 'Este campo'} es obligatorio.`];
        }
        return;
      }

      if (field.validity && !field.validity.valid) {
        if (field.validity.typeMismatch && field.type === 'email') {
          errors[field.name] = ['Ingresa un correo electrónico válido.'];
        } else if (field.validity.tooShort) {
          errors[field.name] = [`${fieldNames[field.name] || 'Este campo'} debe tener más caracteres.`];
        } else if (field.validity.rangeOverflow || field.validity.rangeUnderflow) {
          errors[field.name] = [`${fieldNames[field.name] || 'Este campo'} está fuera del rango permitido.`];
        } else {
          errors[field.name] = [`${fieldNames[field.name] || 'Este campo'} no tiene un valor válido.`];
        }
      }
    });

    return errors;
  };

  const loadScript = (src) => new Promise((resolve, reject) => {
    const existing = [...document.scripts].find((script) => script.src === src);
    if (existing) {
      existing.addEventListener('load', resolve, { once: true });
      if (existing.dataset.loaded === 'true') resolve();
      return;
    }

    const script = document.createElement('script');
    script.src = src;
    script.async = true;
    script.defer = true;
    script.onload = () => {
      script.dataset.loaded = 'true';
      resolve();
    };
    script.onerror = reject;
    document.head.appendChild(script);
  });

  const initTurnstile = async () => {
    if (AllpaCodeGR.captcha?.provider !== 'turnstile' || !AllpaCodeGR.captcha.siteKey) return;
    if (!window.turnstile) {
      await loadScript('https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit');
    }

    document.querySelectorAll('.allpacode-gr__captcha[data-turnstile]:not([data-rendered])').forEach((element) => {
      window.turnstile.render(element, {
        sitekey: AllpaCodeGR.captcha.siteKey,
        callback: (token) => {
          const input = element.closest('form')?.querySelector('[name="captcha_token"]');
          if (input) input.value = token;
        },
      });
      element.dataset.rendered = 'true';
    });
  };

  const getRecaptchaToken = async () => {
    if (AllpaCodeGR.captcha?.provider !== 'recaptcha' || !AllpaCodeGR.captcha.siteKey) return '';
    if (!window.grecaptcha) {
      await loadScript(`https://www.google.com/recaptcha/api.js?render=${encodeURIComponent(AllpaCodeGR.captcha.siteKey)}`);
    }

    return new Promise((resolve) => {
      window.grecaptcha.ready(() => {
        window.grecaptcha.execute(AllpaCodeGR.captcha.siteKey, { action: 'allpacode_gr_submit' }).then(resolve);
      });
    });
  };

  initTurnstile();
  hideCustomSelectClones();

  document.querySelectorAll('.allpacode-gr').forEach((formShell) => {
    const observer = new MutationObserver(() => hideCustomSelectClones(formShell));
    observer.observe(formShell, { childList: true, subtree: true });
  });

  const forms = document.querySelectorAll('.allpacode-gr__form');

  forms.forEach((form) => {
    markRequiredFields(form);

    form.addEventListener('submit', async (event) => {
      event.preventDefault();

      const submit = form.querySelector('.allpacode-gr__submit');
      const notice = form.querySelector('.allpacode-gr__notice');
      const original = submit.textContent;

      submit.disabled = true;
      submit.textContent = AllpaCodeGR.i18n.sending;
      clearErrors(form);
      notice.className = 'allpacode-gr__notice';
      notice.textContent = '';

      try {
        const localErrors = clientErrors(form);
        if (Object.keys(localErrors).length) {
          renderErrors(form, localErrors);
          throw new Error(AllpaCodeGR.i18n.error);
        }

        await refreshNonce(form);

        const recaptchaToken = await getRecaptchaToken();
        const data = new FormData(form);
        if (recaptchaToken) {
          data.set('captcha_token', recaptchaToken);
        }
        if (AllpaCodeGR.captcha?.provider === 'turnstile' && AllpaCodeGR.captcha.siteKey && !data.get('captcha_token') && !data.get('cf-turnstile-response')) {
          throw new Error(AllpaCodeGR.i18n.captcha);
        }

        const response = await fetch(AllpaCodeGR.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: data,
        });
        const json = await parseResponse(response);

        if (!json.success) {
          if (json.data?.errors) {
            renderErrors(form, normalizeErrors(json.data.errors));
          }
          throw new Error(json.data?.message || AllpaCodeGR.i18n.error);
        }

        notice.classList.add('is-success');
        notice.innerHTML = `${json.data.message}<br><strong>${json.data.code}</strong> · <a href="${json.data.receipt_url}" target="_blank" rel="noopener">Descargar constancia</a>`;
        form.reset();
      } catch (error) {
        notice.classList.add('is-error');
        notice.textContent = error.message || AllpaCodeGR.i18n.error;
      } finally {
        submit.disabled = false;
        submit.textContent = original;
      }
    });
  });
})();
