<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="sukses-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-9 col-lg-7 col-xl-6">

        <div class="sukses-card">
          <!-- Success Header -->
          <div class="sukses-card-header">
            <div class="position-relative z-1">
              <div class="sukses-icon-wrapper">
                <i class="bi bi-check-lg text-white" style="font-size:2.2rem;font-weight:900"></i>
              </div>
              <h2 class="fw-bold text-white mb-1" style="font-size:1.4rem">Pendaftaran Berhasil!</h2>
              <p class="text-white mb-0" style="opacity:.75;font-size:.9rem">
                Data Anda telah kami terima dengan baik
              </p>
            </div>
          </div>

          <!-- Card Body -->
          <div class="p-4 p-md-5">

            <!-- Nomor Pendaftaran -->
            <div class="text-center mb-4">
              <p class="text-muted small mb-1 fw-semibold" style="text-transform:uppercase;letter-spacing:1px;font-size:.72rem">Nomor Pendaftaran Anda</p>
              <div class="no-pendaftaran-box">
                <div class="no-pendaftaran-value"><?= esc($reg['no_pendaftaran']) ?></div>
                <p class="text-muted small mt-2 mb-0">Simpan nomor ini — diperlukan untuk mengecek status</p>
              </div>
            </div>

            <!-- Info Pendaftar -->
            <div class="rounded-3 mb-4 overflow-hidden" style="border:1.5px solid var(--clr-border)">
              <div style="background:var(--clr-light);padding:.7rem 1.1rem;border-bottom:1px solid var(--clr-border)">
                <div class="fw-semibold small" style="color:var(--clr-navy);font-size:.82rem">
                  <i class="bi bi-person-fill me-2" style="color:var(--clr-gold)"></i>Ringkasan Pendaftaran
                </div>
              </div>
              <div class="p-3">
                <table class="table table-borderless table-sm mb-0">
                  <tr>
                    <td class="text-muted small pe-4 py-1" style="width:40%">Nama Lengkap</td>
                    <td class="fw-semibold small py-1"><?= esc($reg['nama_lengkap']) ?></td>
                  </tr>
                  <tr>
                    <td class="text-muted small pe-4 py-1">Angkatan</td>
                    <td class="fw-semibold small py-1"><?= esc($batch['nama']) ?></td>
                  </tr>
                  <tr>
                    <td class="text-muted small pe-4 py-1">Tanggal Daftar</td>
                    <td class="fw-semibold small py-1"><?= date('d M Y, H:i', strtotime($reg['created_at'])) ?></td>
                  </tr>
                </table>
              </div>
            </div>

            <!-- Langkah Berikutnya -->
            <div class="mb-4">
              <div class="fw-bold mb-3" style="font-size:.88rem;color:var(--clr-navy);text-transform:uppercase;letter-spacing:.5px">
                <i class="bi bi-arrow-right-circle-fill me-2" style="color:var(--clr-gold)"></i>Langkah Berikutnya
              </div>
              <ul class="steps-list">
                <li>
                  <div class="step-bullet">1</div>
                  <div class="small" style="color:var(--clr-text2);line-height:1.6">Panitia akan melakukan <strong>verifikasi berkas</strong> pendaftaran Anda dalam <strong>1–2 hari kerja</strong>.</div>
                </li>
                <li>
                  <div class="step-bullet">2</div>
                  <div class="small" style="color:var(--clr-text2);line-height:1.6">Cek status pendaftaran secara berkala melalui menu <strong>Cek Status</strong> menggunakan Nomor Pendaftaran di atas.</div>
                </li>
                <li>
                  <div class="step-bullet">3</div>
                  <div class="small" style="color:var(--clr-text2);line-height:1.6"><strong>Simpan screenshot</strong> halaman ini atau catat Nomor Pendaftaran di tempat yang aman.</div>
                </li>
              </ul>
            </div>

            <!-- CTA Buttons -->
            <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
              <a href="<?= base_url('pkpa/cek-status') ?>" class="btn btn-submit px-4">
                <i class="bi bi-search me-2"></i>Cek Status Saya
              </a>
              <a href="<?= base_url('pkpa') ?>" class="btn btn-outline-secondary px-4">
                <i class="bi bi-house-door me-2"></i>Beranda
              </a>
            </div>

            <!-- Kontak Bantuan -->
            <?php if (!empty($settings['wa_ribka'])): ?>
            <div class="text-center pt-3" style="border-top:1px solid var(--clr-lighter)">
              <p class="text-muted small mb-2">Butuh bantuan segera?</p>
              <a href="https://wa.me/<?= esc($settings['wa_ribka']) ?>?text=Halo%20Ribka%2C%20saya%20ingin%20menanyakan%20pendaftaran%20PKPA%20No%3A%20<?= urlencode($reg['no_pendaftaran']) ?>"
                 target="_blank" class="btn-wa text-decoration-none">
                <i class="bi bi-whatsapp"></i>Hubungi Panitia
              </a>
            </div>
            <?php endif; ?>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
