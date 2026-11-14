(() => {
  const cache = new Map();

  const fetchJson = async (url) => {
    if (cache.has(url)) return cache.get(url);
    const response = await fetch(url, { credentials: 'same-origin' });
    const json = await response.json();
    cache.set(url, json);
    return json;
  };

  const fillOptions = (select, items, placeholder) => {
    select.innerHTML = '';
    select.append(new Option(placeholder, ''));
    items.forEach((item) => select.append(new Option(item.name, item.code)));
    select.disabled = false;
  };

  const bind = (root) => {
    const department = root.querySelector('[data-allpacode-ubigeo-department], [name="billing_allpacode_department"]');
    const province = root.querySelector('[data-allpacode-ubigeo-province], [name="billing_allpacode_province"]');
    const district = root.querySelector('[data-allpacode-ubigeo-district], [name="billing_allpacode_district"]');
    const code = root.querySelector('[data-allpacode-ubigeo-code], [name="billing_allpacode_ubigeo"]');

    if (!department || !province || !district || department.dataset.allpacodeBound === '1') return;

    department.dataset.allpacodeBound = '1';
    province.disabled = true;
    district.disabled = true;

    department.addEventListener('change', async () => {
      code.value = '';
      district.innerHTML = '';
      district.append(new Option(AllpaCodeUbigeo.i18n.district, ''));
      district.disabled = true;
      if (!department.value) {
        fillOptions(province, [], AllpaCodeUbigeo.i18n.province);
        province.disabled = true;
        return;
      }

      const items = await fetchJson(`${AllpaCodeUbigeo.restUrl}/provincias?departamento=${encodeURIComponent(department.value)}`);
      fillOptions(province, items, AllpaCodeUbigeo.i18n.province);
    });

    province.addEventListener('change', async () => {
      code.value = '';
      if (!province.value) {
        fillOptions(district, [], AllpaCodeUbigeo.i18n.district);
        district.disabled = true;
        return;
      }

      const items = await fetchJson(`${AllpaCodeUbigeo.restUrl}/distritos?provincia=${encodeURIComponent(province.value)}`);
      fillOptions(district, items, AllpaCodeUbigeo.i18n.district);
    });

    district.addEventListener('change', () => {
      code.value = district.value || '';
    });
  };

  const init = () => {
    document.querySelectorAll('[data-allpacode-ubigeo], .woocommerce-billing-fields__field-wrapper, form.checkout').forEach(bind);
  };

  document.addEventListener('DOMContentLoaded', init);
  document.body?.addEventListener('updated_checkout', init);
})();
