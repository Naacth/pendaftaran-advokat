/**
 * datatable-init.js
 * Helper DataTables server-side dengan CSRF rotate otomatis
 * Setiap halaman admin yang butuh DataTables tinggal panggil:
 *   initDataTable(selector, ajaxUrl, columns, extraData?, options?)
 */

/**
 * @param {string}   selector   — CSS selector tabel (#tbl-xxx)
 * @param {string}   ajaxUrl    — URL endpoint datatable (POST)
 * @param {Array}    columns    — array kolom DataTables
 * @param {Function} extraData  — fungsi(d) untuk append filter, atau null
 * @param {Object}   options    — override default DataTables options
 */
window.initDataTable = (selector, ajaxUrl, columns, extraData = null, options = {}) => {
  const CSRF = {
    name: document.querySelector('meta[name="csrf-name"]')?.content || 'csrf_token',
    hash: document.querySelector('meta[name="csrf-hash"]')?.content || '',
  };

  return $(selector).DataTable({
    processing : true,
    serverSide : true,
    ajax: {
      url  : ajaxUrl,
      type : 'POST',
      data : (d) => {
        if (typeof extraData === 'function') extraData(d);
        d[CSRF.name] = CSRF.hash;
      },
      dataSrc: (json) => {
        // rotate CSRF hash
        if (json.csrf_hash) CSRF.hash = json.csrf_hash;
        return json.data;
      },
      error: (xhr) => {
        showToast('error', 'Terjadi kesalahan. Silakan refresh halaman.');
        console.error('DataTables AJAX error:', xhr);
      },
    },
    columns,
    order      : options.order    || [[columns.length - 2, 'desc']],
    pageLength : options.pageLength || 25,
    lengthMenu : [10, 25, 50, 100],
    language   : { url: BASE_URL + '/assets/vendor/datatables/id.json' },
    dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
         "<'row'<'col-12'tr>>" +
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    ...options,
  });
};

/**
 * Helper CSRF AJAX untuk aksi hapus / ubah status via fetch
 * @param {string} url
 * @param {Object} body  — key-value (tidak perlu sertakan token, otomatis ditambah)
 */
window.csrfPost = async (url, body = {}) => {
  const csrfName = document.querySelector('meta[name="csrf-name"]')?.content || 'csrf_token';
  const csrfHash = document.querySelector('meta[name="csrf-hash"]')?.content || '';

  const formData = new FormData();
  for (const [k, v] of Object.entries(body)) formData.append(k, v);
  formData.append(csrfName, csrfHash);

  const res  = await fetch(url, { method: 'POST', body: formData });
  const json = await res.json();

  // Rotate CSRF hash jika dikembalikan server
  if (json.csrf_hash) {
    const meta = document.querySelector('meta[name="csrf-hash"]');
    if (meta) meta.content = json.csrf_hash;
  }

  return json;
};
