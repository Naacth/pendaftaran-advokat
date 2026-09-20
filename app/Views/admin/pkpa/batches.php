<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm">
  <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-bold">Kelola Angkatan PKPA</h6>
    <button type="button" class="btn btn-sm btn-navy" data-bs-toggle="modal" data-bs-target="#modalBatch" onclick="resetForm()">
      <i class="bi bi-plus-lg me-1"></i>Tambah Angkatan
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tbl-batch" class="table table-hover table-striped w-100 align-middle">
        <thead>
          <tr>
            <th width="50">No</th>
            <th>Kode</th>
            <th>Nama Angkatan</th>
            <th>Pendaftaran</th>
            <th>Pelaksanaan</th>
            <th>Kuota</th>
            <th>Biaya</th>
            <th>Status</th>
            <th width="100">Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalBatch" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formBatch" data-swal-confirm="Ya, simpan">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Form Angkatan PKPA</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="id">
          
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Kode Angkatan <span class="text-danger">*</span></label>
              <input type="text" name="kode" id="kode" class="form-control text-uppercase" required maxlength="30" placeholder="Contoh: XX2026">
            </div>
            <div class="col-md-8">
              <label class="form-label small fw-semibold">Nama Angkatan <span class="text-danger">*</span></label>
              <input type="text" name="nama" id="nama" class="form-control" required maxlength="150">
            </div>
            
            <div class="col-12"><hr class="my-2"></div>
            
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Buka Daftar <span class="text-danger">*</span></label>
              <input type="date" name="buka_daftar" id="buka_daftar" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tutup Daftar <span class="text-danger">*</span></label>
              <input type="date" name="tutup_daftar" id="tutup_daftar" class="form-control" required>
            </div>
            
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tanggal Mulai PKPA (Opsional)</label>
              <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tanggal Selesai PKPA (Opsional)</label>
              <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control">
            </div>
            
            <div class="col-12"><hr class="my-2"></div>
            
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Kuota (Opsional)</label>
              <input type="number" name="kuota" id="kuota" class="form-control" min="1" placeholder="Kosong = tanpa batas">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Biaya (Rp) <span class="text-danger">*</span></label>
              <input type="number" name="biaya" id="biaya" class="form-control" min="0" required value="0">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Status Angkatan</label>
              <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1">
                <label class="form-check-label" for="is_active">Jadikan Aktif Publik</label>
              </div>
              <small class="text-muted d-block mt-1" style="font-size: .7rem">Hanya 1 angkatan yang bisa aktif.</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-navy">Simpan Angkatan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->section('scripts') ?>
<script>
let table;

document.addEventListener('DOMContentLoaded', () => {
  table = initDataTable('#tbl-batch', BASE_URL + '/admin/pkpa/angkatan/datatable', [
    { data: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'kode' },
    { data: 'nama' },
    { data: 'buka_daftar', render: d => d ? d.split('-').reverse().join('/') : '' },
    { data: 'tutup_daftar', render: d => d ? d.split('-').reverse().join('/') : '' },
    { data: 'kuota', render: d => d ? d : '<span class="text-muted">Tanpa Batas</span>' },
    { data: 'biaya', render: d => 'Rp ' + parseInt(d).toLocaleString('id-ID') },
    { data: 'status_badge', orderable: false, searchable: false },
    { data: 'aksi', orderable: false, searchable: false }
  ]);

  // Handle Edit
  $('#tbl-batch').on('click', '.btn-edit-batch', function() {
    const btn = $(this);
    $('#id').val(btn.data('id'));
    $('#kode').val(btn.data('kode'));
    $('#nama').val(btn.data('nama'));
    $('#buka_daftar').val(btn.data('buka'));
    $('#tutup_daftar').val(btn.data('tutup'));
    $('#tanggal_mulai').val(btn.data('mulai'));
    $('#tanggal_selesai').val(btn.data('selesai'));
    $('#kuota').val(btn.data('kuota'));
    $('#biaya').val(btn.data('biaya'));
    $('#is_active').prop('checked', btn.data('aktif') == 1);
    
    new bootstrap.Modal('#modalBatch').show();
  });

  // Handle Hapus
  $('#tbl-batch').on('click', '.btn-hapus-batch', async function() {
    const id = $(this).data('id');
    const nama = $(this).data('nama');
    
    const ok = await swalConfirm({
      title: 'Hapus Angkatan?',
      text: `Anda yakin ingin menghapus "${nama}"? (Tindakan ini tidak menghapus data pendaftar yang sudah ada).`,
      dangerMode: true,
      confirmText: 'Ya, hapus'
    });
    
    if (ok) {
      const res = await csrfPost(BASE_URL + '/admin/pkpa/angkatan/hapus/' + id);
      showToast(res.success ? 'success' : 'error', res.message);
      if (res.success) table.ajax.reload(null, false);
    }
  });

  // Handle Submit Form via AJAX override (karena kita pakai swal-confirm, form otomatis submit biasa, tapi kita ubah ke ajax)
  document.getElementById('formBatch').addEventListener('submit', async function(e) {
    if (this.dataset.confirmed !== '1') return; // dicegat swal-confirm dulu
    e.preventDefault(); // cegah reload
    
    const formData = new FormData(this);
    // Jika checkbox tidak dicentang, FormData tidak mengirim value, jadi tambahkan manual
    if (!this.querySelector('#is_active').checked) formData.append('is_active', '0');
    
    // Tambah CSRF
    const csrfName = document.querySelector('meta[name="csrf-name"]').content;
    const csrfHash = document.querySelector('meta[name="csrf-hash"]').content;
    formData.append(csrfName, csrfHash);
    
    try {
      const res = await fetch(BASE_URL + '/admin/pkpa/angkatan/simpan', { method: 'POST', body: formData });
      const json = await res.json();
      
      if (json.csrf_hash) document.querySelector('meta[name="csrf-hash"]').content = json.csrf_hash;
      
      if (json.success) {
        bootstrap.Modal.getInstance('#modalBatch').hide();
        showToast('success', json.message);
        table.ajax.reload(null, false);
        resetForm();
      } else {
        Swal.fire('Gagal', json.message, 'error');
      }
    } catch (err) {
      Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
    } finally {
      // kembalikan state form
      this.dataset.confirmed = '0';
      const btn = this.querySelector('[type="submit"]');
      btn.disabled = false;
      btn.innerHTML = 'Simpan Angkatan';
    }
  });
});

function resetForm() {
  document.getElementById('formBatch').reset();
  document.getElementById('id').value = '';
  document.getElementById('formBatch').classList.remove('was-validated');
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
