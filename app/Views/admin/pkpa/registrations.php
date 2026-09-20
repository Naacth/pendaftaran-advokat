<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm mb-4">
  <div class="card-body py-3 d-flex flex-wrap gap-3 align-items-center bg-white rounded">
    <div class="me-auto">
      <h6 class="mb-0 fw-bold">Data Pendaftar PKPA</h6>
      <small class="text-muted">Kelola pendaftaran, verifikasi, dan pembayaran peserta.</small>
    </div>
    
    <!-- Filter -->
    <select id="filter_batch" class="form-select form-select-sm w-auto">
      <option value="">-- Semua Angkatan --</option>
      <?php foreach ($batches as $b): ?>
        <option value="<?= $b['id'] ?>"><?= esc($b['nama']) ?></option>
      <?php endforeach; ?>
    </select>
    
    <select id="filter_status" class="form-select form-select-sm w-auto">
      <option value="">-- Semua Status --</option>
      <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
      <option value="perlu_perbaikan">Perlu Perbaikan</option>
      <option value="diverifikasi">Diverifikasi</option>
      <option value="ditolak">Ditolak</option>
    </select>
    
    <select id="filter_bayar" class="form-select form-select-sm w-auto">
      <option value="">-- Semua Pembayaran --</option>
      <option value="belum_bayar">Belum Bayar</option>
      <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
      <option value="lunas">Lunas</option>
    </select>
    
    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="table.ajax.reload()">
      <i class="bi bi-funnel me-1"></i>Filter
    </button>
    <a href="#" id="btnExport" class="btn btn-sm btn-success">
      <i class="bi bi-file-earmark-excel me-1"></i>Ekspor CSV
    </a>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive p-3">
      <table id="tbl-registrations" class="table table-hover table-striped w-100 align-middle">
        <thead>
          <tr>
            <th width="30">No</th>
            <th>No. Daftar</th>
            <th>Angkatan</th>
            <th>Nama Peserta</th>
            <th>No. WA</th>
            <th>Tgl Daftar</th>
            <th>Status Pendaftaran</th>
            <th>Status Pembayaran</th>
            <th width="80" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->section('scripts') ?>
<script>
let table;

document.addEventListener('DOMContentLoaded', () => {
  table = initDataTable('#tbl-registrations', BASE_URL + '/admin/pkpa/pendaftar/datatable', [
    { data: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'no_pendaftaran', render: d => `<span class="font-monospace text-primary fw-semibold">${d}</span>` },
    { data: 'angkatan', orderable: false, searchable: false },
    { data: 'nama_lengkap' },
    { data: 'no_wa', render: d => `<a href="https://wa.me/${d}" target="_blank" class="text-decoration-none"><i class="bi bi-whatsapp text-success me-1"></i>${d}</a>` },
    { data: 'created_at_fmt' },
    { data: 'status_pendaftaran_badge', orderable: false, searchable: false },
    { data: 'status_pembayaran_badge', orderable: false, searchable: false },
    { data: 'aksi', orderable: false, searchable: false, className: 'text-center' }
  ], (d) => {
    // Append filter data
    d.batch_id   = document.getElementById('filter_batch').value;
    d.status     = document.getElementById('filter_status').value;
    d.pembayaran = document.getElementById('filter_bayar').value;
  });

  // Handle Export URL
  document.getElementById('btnExport').addEventListener('click', function(e) {
    e.preventDefault();
    const batch = document.getElementById('filter_batch').value;
    const stat  = document.getElementById('filter_status').value;
    const pay   = document.getElementById('filter_bayar').value;
    window.location.href = `${BASE_URL}/admin/pkpa/pendaftar/export?batch_id=${batch}&status=${stat}&pembayaran=${pay}`;
  });

  // Handle Hapus
  $('#tbl-registrations').on('click', '.btn-hapus-reg', async function() {
    const id = $(this).data('id');
    const nama = $(this).data('nama');
    
    const ok = await swalConfirm({
      title: 'Hapus Pendaftar?',
      text: `Yakin hapus pendaftar "${nama}"? Data akan dihapus sementara (soft delete).`,
      dangerMode: true,
      confirmText: 'Ya, hapus'
    });
    
    if (ok) {
      const res = await csrfPost(BASE_URL + '/admin/pkpa/pendaftar/hapus/' + id);
      showToast(res.success ? 'success' : 'error', res.message);
      if (res.success) table.ajax.reload(null, false);
    }
  });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
