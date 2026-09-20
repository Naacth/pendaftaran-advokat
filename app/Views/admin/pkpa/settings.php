<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">Pengaturan PKPA</h6>
      </div>
      <div class="card-body">
        
        <!-- Tampilkan notifikasi (karena tidak pakai AJAX untuk form ini) -->
        <?php if (session('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/pkpa/pengaturan/simpan') ?>" method="post" data-swal-confirm="Ya, simpan pengaturan">
          <?= csrf_field() ?>

          <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i class="bi bi-whatsapp me-2"></i>Kontak WhatsApp Panitia</h6>
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">WA Ribka</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                <input type="text" name="wa_ribka" class="form-control" value="<?= esc($settings['wa_ribka']['value'] ?? '') ?>" placeholder="628xxx">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">WA Yuni</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                <input type="text" name="wa_yuni" class="form-control" value="<?= esc($settings['wa_yuni']['value'] ?? '') ?>" placeholder="628xxx">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">WA Robert</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                <input type="text" name="wa_robert" class="form-control" value="<?= esc($settings['wa_robert']['value'] ?? '') ?>" placeholder="628xxx">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">WA Ruby</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                <input type="text" name="wa_ruby" class="form-control" value="<?= esc($settings['wa_ruby']['value'] ?? '') ?>" placeholder="628xxx">
              </div>
            </div>
          </div>

          <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i class="bi bi-bank me-2"></i>Informasi Rekening Pembayaran</h6>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Nama Bank</label>
              <input type="text" name="rekening_bank" class="form-control" value="<?= esc($settings['rekening_bank']['value'] ?? '') ?>" placeholder="Contoh: BCA">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Nomor Rekening</label>
              <input type="text" name="rekening_nomor" class="form-control" value="<?= esc($settings['rekening_nomor']['value'] ?? '') ?>" placeholder="1234567890">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Atas Nama</label>
              <input type="text" name="rekening_atas_nama" class="form-control" value="<?= esc($settings['rekening_atas_nama']['value'] ?? '') ?>" placeholder="DPC PERADI TANGERANG">
            </div>
          </div>

          <div class="text-end border-top pt-3">
            <button type="submit" class="btn btn-navy px-4"><i class="bi bi-save me-1"></i>Simpan Pengaturan</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?= $this->section('scripts') ?>
<script>
// Override form submit (karena controller balikin JSON)
document.querySelector('form').addEventListener('submit', async function(e) {
  if (this.dataset.confirmed !== '1') return;
  e.preventDefault();
  
  const formData = new FormData(this);
  const csrfName = document.querySelector('meta[name="csrf-name"]').content;
  const csrfHash = document.querySelector('meta[name="csrf-hash"]').content;
  formData.append(csrfName, csrfHash);
  
  try {
    const res = await fetch(this.action, { method: 'POST', body: formData });
    const json = await res.json();
    
    if (json.csrf_hash) document.querySelector('meta[name="csrf-hash"]').content = json.csrf_hash;
    
    if (json.success) {
      showToast('success', json.message);
    } else {
      Swal.fire('Gagal', json.message || 'Terjadi kesalahan.', 'error');
    }
  } catch (err) {
    Swal.fire('Error', 'Gagal menghubungi server.', 'error');
  } finally {
    this.dataset.confirmed = '0';
    const btn = this.querySelector('[type="submit"]');
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan Pengaturan';
  }
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
