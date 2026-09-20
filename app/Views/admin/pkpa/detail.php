<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="fw-bold mb-0">
    <a href="<?= base_url('admin/pkpa/pendaftar') ?>" class="text-dark text-decoration-none">
      <i class="bi bi-arrow-left me-2"></i>Detail Pendaftar
    </a>
  </h5>
  <div>
    <span class="badge bg-secondary me-2">Daftar: <?= date('d M Y H:i', strtotime($reg['created_at'])) ?></span>
    <span class="badge bg-primary border">No. <?= esc($reg['no_pendaftaran']) ?></span>
  </div>
</div>

<div class="row g-4">
  <!-- ── PANEL KIRI: DATA PRIBADI & PENDIDIKAN ── -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Peserta</h6>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0 align-middle">
          <tbody>
            <tr><th width="30%" class="ps-4">Angkatan</th><td><span class="badge bg-light text-dark border"><?= esc($reg['angkatan']) ?></span></td></tr>
            <tr><th class="ps-4">Nama Lengkap</th><td class="fw-semibold fs-6"><?= esc($reg['nama_lengkap']) ?></td></tr>
            <tr><th class="ps-4">NIK</th><td><span class="font-monospace"><?= esc($reg['nik']) ?></span></td></tr>
            <tr><th class="ps-4">Tempat, Tgl Lahir</th><td><?= esc($reg['tempat_lahir']) ?>, <?= date('d M Y', strtotime($reg['tanggal_lahir'])) ?></td></tr>
            <tr><th class="ps-4">Jenis Kelamin</th><td><?= $reg['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
            <tr>
              <th class="ps-4">WhatsApp / Email</th>
              <td>
                <a href="https://wa.me/<?= esc($reg['no_wa']) ?>" target="_blank" class="text-decoration-none me-3"><i class="bi bi-whatsapp text-success me-1"></i><?= esc($reg['no_wa']) ?></a>
                <a href="mailto:<?= esc($reg['email']) ?>" class="text-decoration-none"><i class="bi bi-envelope text-secondary me-1"></i><?= esc($reg['email']) ?></a>
              </td>
            </tr>
            <tr><th class="ps-4 pb-3">Alamat</th><td class="pb-3"><?= esc($reg['alamat']) ?><br><small class="text-muted"><?= esc($reg['kota']) ?></small></td></tr>
            <tr class="table-light"><td colspan="2" class="fw-bold ps-4 text-secondary small text-uppercase" style="letter-spacing:1px;">Latar Belakang Pendidikan</td></tr>
            <tr><th class="ps-4">Perguruan Tinggi</th><td><?= esc($reg['asal_kampus']) ?></td></tr>
            <tr><th class="ps-4">Program Studi</th><td><?= esc($reg['program_studi']) ?></td></tr>
            <tr><th class="ps-4">Gelar & Tahun Lulus</th><td><?= esc($reg['gelar']) ?> (Lulus tahun <?= esc($reg['tahun_lulus']) ?>)</td></tr>
            <tr class="table-light"><td colspan="2" class="fw-bold ps-4 text-secondary small text-uppercase" style="letter-spacing:1px;">Pekerjaan (Opsional)</td></tr>
            <tr><th class="ps-4 pb-3">Pekerjaan / Instansi</th><td class="pb-3"><?= esc($reg['pekerjaan']) ?: '-' ?> / <?= esc($reg['instansi']) ?: '-' ?></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── DOKUMEN ── -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-folder-check me-2 text-primary"></i>Dokumen Terlampir</h6>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <?php if (empty($docs)): ?>
            <div class="col-12 text-center text-muted py-3">Belum ada dokumen yang diunggah.</div>
          <?php else: ?>
            <?php foreach ($docs as $doc): ?>
            <div class="col-md-6">
              <div class="border rounded p-3 d-flex align-items-center">
                <div class="fs-1 me-3 text-secondary">
                  <?php if (str_contains($doc['mime'], 'image/')): ?>
                    <i class="bi bi-file-earmark-image text-primary"></i>
                  <?php elseif (str_contains($doc['mime'], 'pdf')): ?>
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                  <?php else: ?>
                    <i class="bi bi-file-earmark"></i>
                  <?php endif; ?>
                </div>
                <div class="flex-grow-1 min-w-0">
                  <div class="fw-semibold text-truncate" title="<?= esc($doc['nama_asli']) ?>"><?= esc($doc['nama_asli']) ?></div>
                  <div class="small text-muted mb-2 text-uppercase"><?= str_replace('_', ' ', $doc['jenis_dokumen']) ?> &bull; <?= round($doc['ukuran'] / 1024) ?> KB</div>
                  <a href="<?= base_url("admin/pkpa/pendaftar/{$reg['id']}/dokumen/{$doc['id']}") ?>" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-eye me-1"></i>Lihat
                  </a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- ── PANEL KANAN: VERIFIKASI & PEMBAYARAN ── -->
  <div class="col-lg-4">
    <!-- Status Verifikasi -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">Verifikasi Panitia</h6>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="small text-muted d-block mb-1">Status Saat Ini</label>
          <?php
            $badges = [
              'menunggu_verifikasi' => '<span class="badge bg-warning fs-6 w-100 py-2">Menunggu Verifikasi</span>',
              'perlu_perbaikan'     => '<span class="badge bg-info fs-6 w-100 py-2">Perlu Perbaikan</span>',
              'diverifikasi'        => '<span class="badge bg-success fs-6 w-100 py-2">Diverifikasi</span>',
              'ditolak'             => '<span class="badge bg-danger fs-6 w-100 py-2">Ditolak</span>',
            ];
            echo $badges[$reg['status_pendaftaran']] ?? '-';
          ?>
        </div>

        <form id="form-verifikasi">
          <div class="mb-3">
            <label class="small fw-semibold mb-1">Ubah Status</label>
            <select name="status" id="status" class="form-select border-secondary">
              <option value="menunggu_verifikasi" <?= $reg['status_pendaftaran'] === 'menunggu_verifikasi' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
              <option value="perlu_perbaikan" <?= $reg['status_pendaftaran'] === 'perlu_perbaikan' ? 'selected' : '' ?>>Perlu Perbaikan</option>
              <option value="diverifikasi" <?= $reg['status_pendaftaran'] === 'diverifikasi' ? 'selected' : '' ?>>Diverifikasi (Lengkap)</option>
              <option value="ditolak" <?= $reg['status_pendaftaran'] === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="small fw-semibold mb-1">Catatan (Pesan ke Peserta)</label>
            <textarea name="catatan" id="catatan" rows="3" class="form-control" placeholder="Wajib diisi jika status Perlu Perbaikan atau Ditolak..."><?= esc($reg['catatan_admin']) ?></textarea>
          </div>
          <button type="submit" class="btn btn-navy w-100"><i class="bi bi-save me-1"></i>Simpan Verifikasi</button>
        </form>
      </div>
    </div>

    <!-- Status Pembayaran -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">Status Pembayaran</h6>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="small text-muted d-block mb-1">Status Saat Ini</label>
          <?php
            $payBadges = [
              'belum_bayar'         => '<span class="badge bg-light text-secondary border fs-6 w-100 py-2">Belum Bayar</span>',
              'menunggu_konfirmasi' => '<span class="badge bg-warning fs-6 w-100 py-2">Menunggu Konfirmasi</span>',
              'lunas'               => '<span class="badge bg-success fs-6 w-100 py-2">Lunas</span>',
            ];
            echo $payBadges[$reg['status_pembayaran']] ?? '-';
          ?>
        </div>
        
        <?php if ($reg['status_pembayaran'] !== 'lunas'): ?>
          <button type="button" id="btn-konfirmasi-bayar" class="btn btn-success w-100" <?= $reg['status_pendaftaran'] !== 'diverifikasi' ? 'disabled title="Verifikasi data terlebih dahulu"' : '' ?>>
            <i class="bi bi-check-circle me-1"></i>Tandai Lunas
          </button>
          <?php if ($reg['status_pendaftaran'] !== 'diverifikasi'): ?>
            <div class="small text-danger mt-2 text-center"><i class="bi bi-info-circle me-1"></i>Verifikasi data peserta (Diverifikasi) terlebih dahulu sebelum dapat menandai pembayaran lunas.</div>
          <?php endif; ?>
        <?php else: ?>
          <div class="alert alert-success py-2 text-center mb-0 border-0"><i class="bi bi-check-all me-1"></i>Pembayaran sudah lunas</div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<?= $this->section('scripts') ?>
<script>
// Handle Simpan Verifikasi
document.getElementById('form-verifikasi').addEventListener('submit', async function(e) {
  e.preventDefault();
  const status = document.getElementById('status').value;
  const catatan = document.getElementById('catatan').value.trim();
  
  if ((status === 'perlu_perbaikan' || status === 'ditolak') && catatan === '') {
    return Swal.fire('Perhatian', 'Catatan admin WAJIB diisi jika status Perlu Perbaikan atau Ditolak.', 'warning');
  }

  const ok = await swalConfirm({
    title: 'Simpan Verifikasi?',
    text: 'Pastikan status dan catatan yang diberikan sudah benar.'
  });

  if (ok) {
    const res = await csrfPost(`<?= base_url("admin/pkpa/pendaftar/{$reg['id']}/status") ?>`, { status, catatan });
    if (res.success) {
      showToast('success', res.message);
      setTimeout(() => location.reload(), 1000);
    } else {
      Swal.fire('Gagal', res.message, 'error');
    }
  }
});

// Handle Konfirmasi Pembayaran
const btnBayar = document.getElementById('btn-konfirmasi-bayar');
if (btnBayar) {
  btnBayar.addEventListener('click', async function() {
    const ok = await swalConfirm({
      title: 'Tandai Pembayaran Lunas?',
      text: 'Pastikan Anda telah mengecek mutasi rekening sebelum mengkonfirmasi ini. Tindakan ini tidak dapat dibatalkan.'
    });

    if (ok) {
      const res = await csrfPost(`<?= base_url("admin/pkpa/pendaftar/{$reg['id']}/pembayaran") ?>`);
      if (res.success) {
        showToast('success', res.message);
        setTimeout(() => location.reload(), 1000);
      } else {
        Swal.fire('Gagal', res.message, 'error');
      }
    }
  });
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
