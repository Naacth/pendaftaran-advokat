<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 text-center">
      
      <div class="mb-4">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
      </div>
      
      <h2 class="fw-bold mb-3" style="color: var(--clr-navy);">Pendaftaran Berhasil Terkirim!</h2>
      <p class="lead text-muted mb-4">Terima kasih, data pendaftaran PKPA Anda telah kami terima.</p>

      <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body p-4">
          <p class="mb-1 text-muted text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: .85rem;">Nomor Pendaftaran Anda</p>
          <h3 class="fw-bold text-primary mb-3 font-monospace"><?= esc($reg['no_pendaftaran']) ?></h3>
          
          <table class="table table-borderless table-sm text-start mb-0 w-auto mx-auto">
            <tr>
              <th class="text-muted fw-normal pe-4">Nama Lengkap</th>
              <td class="fw-semibold"><?= esc($reg['nama_lengkap']) ?></td>
            </tr>
            <tr>
              <th class="text-muted fw-normal pe-4">Angkatan</th>
              <td class="fw-semibold"><?= esc($batch['nama']) ?></td>
            </tr>
            <tr>
              <th class="text-muted fw-normal pe-4">Tanggal Daftar</th>
              <td class="fw-semibold"><?= date('d M Y, H:i', strtotime($reg['created_at'])) ?></td>
            </tr>
          </table>
        </div>
      </div>

      <div class="alert alert-info text-start mb-5 shadow-sm border-0">
        <h5 class="alert-heading fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Langkah Berikutnya</h5>
        <hr>
        <ol class="mb-0 ps-3">
          <li class="mb-2">Panitia akan melakukan verifikasi berkas pendaftaran Anda (biasanya memakan waktu 1-2 hari kerja).</li>
          <li class="mb-2">Anda dapat mengecek status pendaftaran secara berkala melalui menu <strong>Cek Status</strong> menggunakan Nomor Pendaftaran di atas.</li>
          <li>Simpan Nomor Pendaftaran ini baik-baik (Anda juga bisa mem-screenshot halaman ini).</li>
        </ol>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="<?= base_url('pkpa/cek-status') ?>" class="btn btn-outline-primary px-4">
          <i class="bi bi-search me-2"></i>Cek Status Sekarang
        </a>
        <a href="<?= base_url('pkpa') ?>" class="btn btn-accent px-4">
          <i class="bi bi-house-door-fill me-2"></i>Kembali ke Beranda
        </a>
      </div>
      
      <?php if (!empty($settings['wa_ribka'])): ?>
      <div class="mt-5 pt-4 border-top">
        <p class="text-muted small mb-2">Butuh bantuan segera?</p>
        <a href="https://wa.me/<?= esc($settings['wa_ribka']) ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
          <i class="bi bi-whatsapp me-1"></i> Hubungi Panitia
        </a>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?= $this->endSection() ?>
