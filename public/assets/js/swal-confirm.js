/**
 * swal-confirm.js
 * Interceptor SweetAlert2 untuk semua form[data-swal-confirm]
 * dan helper swalConfirm() untuk aksi non-form (mis. tombol hapus DataTables)
 */

// ── Form submit interceptor ────────────────────────────────────────────────
document.addEventListener('submit', async (e) => {
  const form = e.target.closest('form[data-swal-confirm]');
  if (!form || form.dataset.confirmed === '1') return;

  e.preventDefault();

  if (!form.checkValidity()) {
    form.classList.add('was-validated');
    return;
  }

  const result = await Swal.fire({
    title          : form.dataset.swalTitle   || 'Simpan data?',
    text           : form.dataset.swalText    || 'Pastikan data yang Anda isi sudah benar.',
    icon           : form.dataset.swalIcon    || 'question',
    showCancelButton: true,
    confirmButtonText: form.dataset.swalConfirm || 'Ya, simpan',
    cancelButtonText : 'Batal',
    reverseButtons  : true,
    confirmButtonColor: '#0B2A66',
  });

  if (result.isConfirmed) {
    form.dataset.confirmed = '1';
    const btn = form.querySelector('[type="submit"]');
    if (btn) {
      btn.disabled  = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
    }
    form.submit();
  }
});

// ── Helper untuk aksi AJAX non-form ──────────────────────────────────────
window.swalConfirm = async ({
  title = 'Yakin?',
  text  = '',
  icon  = 'warning',
  confirmText = 'Ya, lanjutkan',
  dangerMode  = false,
} = {}) => {
  const result = await Swal.fire({
    title,
    text,
    icon,
    showCancelButton  : true,
    confirmButtonText : confirmText,
    cancelButtonText  : 'Batal',
    reverseButtons    : true,
    confirmButtonColor: dangerMode ? '#dc3545' : '#0B2A66',
  });
  return result.isConfirmed;
};

// ── Tampilkan flash dari server (flashdata) ───────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  // Cek data-flash attribute pada <body>
  const body = document.body;
  if (body.dataset.flashSuccess) {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: body.dataset.flashSuccess, timer: 3000, showConfirmButton: false });
  }
  if (body.dataset.flashError) {
    Swal.fire({ icon: 'error', title: 'Gagal', text: body.dataset.flashError });
  }
  if (body.dataset.flashInfo) {
    Swal.fire({ icon: 'info', title: 'Info', text: body.dataset.flashInfo, timer: 3000, showConfirmButton: false });
  }
});

// ── Toast helper ──────────────────────────────────────────────────────────
window.showToast = (icon, message) => {
  Swal.fire({
    toast           : true,
    position        : 'top-end',
    icon,
    title           : message,
    showConfirmButton: false,
    timer           : 3000,
    timerProgressBar: true,
  });
};
