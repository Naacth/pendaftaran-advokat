<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="cek-status-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-9 col-lg-7 col-xl-6">

        <!-- Card Utama -->
        <div class="cek-status-card">
          <!-- Header Card -->
          <div class="cek-status-card-header">
            <div class="position-relative z-1">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:48px;height:48px;background:rgba(212,168,67,.2);border:1.5px solid rgba(212,168,67,.35);border-radius:12px;display:grid;place-items:center;color:var(--clr-gold);font-size:1.3rem">
                  <i class="bi bi-search"></i>
                </div>
                <div>
                  <h4 class="mb-0 fw-bold text-white" style="font-size:1.15rem">Cek Status Pendaftaran</h4>
                  <div class="text-white-50 small mt-1">Masukkan nomor pendaftaran dan verifikasi data Anda</div>
                </div>
              </div>

              <?php if (isset($error)): ?>
              <div class="alert border-0 mb-0 mt-3" style="background:rgba(239,68,68,.15);border-left:3px solid #ef4444 !important;border-radius:var(--radius-sm)">
                <div class="d-flex align-items-center gap-2 text-white">
                  <i class="bi bi-exclamation-triangle-fill" style="color:#f87171;flex-shrink:0"></i>
                  <span style="font-size:.875rem"><?= esc($error) ?></span>
                </div>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Form -->
          <div class="cek-status-card-body">
            <form action="<?= base_url('pkpa/cek-status') ?>" method="post">
              <?= csrf_field() ?>

              <div class="mb-3">
                <label for="no_pendaftaran" class="form-label">No. Pendaftaran</label>
                <div class="input-group">
                  <span class="input-group-text" style="background:var(--clr-lighter);border-color:var(--clr-border)">
                    <i class="bi bi-hash" style="color:var(--clr-navy2)"></i>
                  </span>
                  <input type="text" name="no_pendaftaran" id="no_pendaftaran"
                         class="form-control form-control-lg"
                         placeholder="PKPA-XX2026-0001" required
                         value="<?= old('no_pendaftaran', $result['no_pendaftaran'] ?? '') ?>"
                         style="border-left:none;font-family:monospace;letter-spacing:.04em">
                </div>
              </div>

              <div class="mb-4">
                <label for="verifikasi" class="form-label">Verifikasi Identitas</label>
                <div class="input-group">
                  <span class="input-group-text" style="background:var(--clr-lighter);border-color:var(--clr-border)">
                    <i class="bi bi-shield-lock" style="color:var(--clr-navy2)"></i>
                  </span>
                  <input type="text" name="verifikasi" id="verifikasi"
                         class="form-control form-control-lg"
                         placeholder="No. WA atau 4 digit terakhir NIK" required>
                </div>
                <div class="form-text mt-1">Contoh: 0812345... atau 4321</div>
              </div>

              <button type="submit" class="btn w-100 btn-submit py-3">
                <i class="bi bi-search me-2"></i>Cek Status Saya
              </button>
            </form>

            <!-- Info helper -->
            <div class="mt-4 p-3 rounded-3" style="background:var(--clr-light);border:1px solid var(--clr-border)">
              <div class="d-flex gap-2 align-items-start">
                <i class="bi bi-lightbulb-fill mt-1" style="color:var(--clr-gold);font-size:.9rem;flex-shrink:0"></i>
                <p class="small text-muted mb-0" style="line-height:1.6">Nomor pendaftaran Anda tersedia pada halaman konfirmasi setelah pendaftaran, atau dari email/screenshot yang Anda simpan.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Hasil Cek Status -->
        <?php if (isset($result)): ?>
        <div class="hasil-card">
          <!-- Header hasil -->
          <div class="hasil-card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-person-check-fill" style="color:var(--clr-navy2)"></i>
              <h6 class="mb-0 fw-bold" style="color:var(--clr-navy);font-size:.92rem">Hasil Pencarian</h6>
            </div>
            <?php
              $badges = [
                'menunggu_verifikasi' => '<span class="status-pill status-menunggu"><i class="bi bi-hourglass-split"></i>Menunggu</span>',
                'perlu_perbaikan'     => '<span class="status-pill status-perbaikan"><i class="bi bi-pencil-square"></i>Perlu Perbaikan</span>',
                'diverifikasi'        => '<span class="status-pill status-verified"><i class="bi bi-check-circle-fill"></i>Diverifikasi</span>',
                'ditolak'             => '<span class="status-pill status-tolak"><i class="bi bi-x-circle-fill"></i>Ditolak</span>',
              ];
              echo $badges[$result['status_pendaftaran']] ?? $result['status_pendaftaran'];
            ?>
          </div>

          <!-- Tabel data -->
          <table class="table hasil-table mb-0">
            <tbody>
              <tr>
                <th>Nama Lengkap</th>
                <td class="fw-semibold"><?= esc($result['nama_lengkap']) ?></td>
              </tr>
              <tr>
                <th>No. Pendaftaran</th>
                <td><span class="font-monospace fw-semibold" style="color:var(--clr-navy2);letter-spacing:.04em"><?= esc($result['no_pendaftaran']) ?></span></td>
              </tr>
              <tr>
                <th>NIK</th>
                <td class="text-muted"><?= esc($result['nik_masked']) ?></td>
              </tr>
              <tr>
                <th>Tgl. Daftar</th>
                <td class="text-muted"><?= date('d M Y, H:i', strtotime($result['created_at'])) ?></td>
              </tr>
              <tr>
                <th>Status Pendaftaran</th>
                <td><?= $badges[$result['status_pendaftaran']] ?? $result['status_pendaftaran'] ?></td>
              </tr>
              <tr>
                <th>Status Pembayaran</th>
                <td>
                  <?php
                    $payBadges = [
                      'belum_bayar'         => '<span class="status-pill bg-light text-secondary border"><i class="bi bi-dash-circle"></i>Belum Bayar</span>',
                      'menunggu_konfirmasi' => '<span class="status-pill status-menunggu"><i class="bi bi-clock-history"></i>Menunggu Konfirmasi</span>',
                      'lunas'               => '<span class="status-pill status-verified"><i class="bi bi-check-circle-fill"></i>Lunas</span>',
                    ];
                    echo $payBadges[$result['status_pembayaran']] ?? $result['status_pembayaran'];
                  ?>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Catatan admin -->
          <?php if (in_array($result['status_pendaftaran'], ['perlu_perbaikan', 'ditolak']) && $result['catatan_admin']): ?>
          <div class="px-3 pb-3">
            <div class="alert border-0 mt-3 mb-0 rounded-3"
                 style="background:<?= $result['status_pendaftaran'] === 'ditolak' ? '#FFF1F0' : '#EFF6FF' ?>;border-left:3px solid <?= $result['status_pendaftaran'] === 'ditolak' ? '#ef4444' : '#3b82f6' ?> !important">
              <div class="fw-bold mb-1" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.5px;color:<?= $result['status_pendaftaran'] === 'ditolak' ? '#820014' : '#1e40af' ?>">
                <i class="bi bi-chat-left-text-fill me-1"></i>Catatan Panitia
              </div>
              <p class="mb-0 small" style="color:<?= $result['status_pendaftaran'] === 'ditolak' ? '#991b1b' : '#1d4ed8' ?>;line-height:1.7"><?= nl2br(esc($result['catatan_admin'])) ?></p>
            </div>
          </div>
          <?php endif; ?>

          <!-- Aksi -->
          <div class="px-3 pb-3 pt-2 border-top" style="border-color:var(--clr-lighter) !important">
            <div class="d-flex flex-wrap gap-2 mt-3">
              <?php if ($result['status_pendaftaran'] === 'perlu_perbaikan'): ?>
              <a href="<?= base_url('pkpa/perbaikan/' . $result['public_token']) ?>" class="btn btn-warning fw-semibold px-4">
                <i class="bi bi-pencil-square me-2"></i>Lakukan Perbaikan
              </a>
              <?php endif; ?>
              <?php if ($result['status_pendaftaran'] === 'diverifikasi' && $result['status_pembayaran'] === 'belum_bayar'): ?>
              <button class="btn btn-success fw-semibold px-4" onclick="alert('Fitur upload bukti bayar segera hadir.')">
                <i class="bi bi-upload me-2"></i>Unggah Bukti Bayar
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Back link -->
        <div class="text-center mt-4">
          <a href="<?= base_url('pkpa') ?>" class="text-muted text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
          </a>
        </div>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
