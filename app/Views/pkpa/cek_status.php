<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      
      <!-- Card Cek Status -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4 p-md-5">
          <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: var(--clr-navy);">Cek Status Pendaftaran</h2>
            <p class="text-muted">Masukkan Nomor Pendaftaran dan verifikasi data Anda.</p>
          </div>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger">
              <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc($error) ?>
            </div>
          <?php endif; ?>

          <form action="<?= base_url('pkpa/cek-status') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
              <label for="no_pendaftaran" class="form-label fw-semibold">No. Pendaftaran</label>
              <input type="text" name="no_pendaftaran" id="no_pendaftaran" class="form-control form-control-lg" 
                     placeholder="PKPA-XX2026-0001" required value="<?= old('no_pendaftaran', $result['no_pendaftaran'] ?? '') ?>">
            </div>

            <div class="mb-4">
              <label for="verifikasi" class="form-label fw-semibold">Verifikasi (No. WA atau 4 digit terakhir NIK)</label>
              <input type="text" name="verifikasi" id="verifikasi" class="form-control form-control-lg" 
                     placeholder="Contoh: 08123... atau 4321" required>
            </div>

            <button type="submit" class="btn btn-accent btn-lg w-100">
              <i class="bi bi-search me-2"></i>Cek Status
            </button>
          </form>
        </div>
      </div>

      <!-- Hasil Cek Status -->
      <?php if (isset($result)): ?>
        <div class="card shadow-sm border-0" id="hasil-status">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Hasil Pencarian</h5>
            
            <table class="table table-borderless mb-0">
              <tbody>
                <tr>
                  <th class="ps-0 text-muted" width="40%">Nama Lengkap</th>
                  <td class="fw-semibold"><?= esc($result['nama_lengkap']) ?></td>
                </tr>
                <tr>
                  <th class="ps-0 text-muted">No. Pendaftaran</th>
                  <td><span class="font-monospace text-primary"><?= esc($result['no_pendaftaran']) ?></span></td>
                </tr>
                <tr>
                  <th class="ps-0 text-muted">NIK</th>
                  <td><?= esc($result['nik_masked']) ?></td>
                </tr>
                <tr>
                  <th class="ps-0 text-muted">Tgl. Daftar</th>
                  <td><?= date('d M Y, H:i', strtotime($result['created_at'])) ?></td>
                </tr>
                <tr>
                  <th class="ps-0 text-muted">Status Pendaftaran</th>
                  <td>
                    <?php
                      $badges = [
                        'menunggu_verifikasi' => '<span class="status-pill status-menunggu"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi</span>',
                        'perlu_perbaikan'     => '<span class="status-pill status-perbaikan"><i class="bi bi-pencil-square"></i> Perlu Perbaikan</span>',
                        'diverifikasi'        => '<span class="status-pill status-verified"><i class="bi bi-check-circle-fill"></i> Diverifikasi</span>',
                        'ditolak'             => '<span class="status-pill status-tolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>',
                      ];
                      echo $badges[$result['status_pendaftaran']] ?? $result['status_pendaftaran'];
                    ?>
                  </td>
                </tr>
                <tr>
                  <th class="ps-0 text-muted pb-0">Status Pembayaran</th>
                  <td class="pb-0">
                    <?php
                      $payBadges = [
                        'belum_bayar'         => '<span class="status-pill bg-light text-secondary border"><i class="bi bi-dash-circle"></i> Belum Bayar</span>',
                        'menunggu_konfirmasi' => '<span class="status-pill status-menunggu"><i class="bi bi-clock-history"></i> Menunggu Konfirmasi</span>',
                        'lunas'               => '<span class="status-pill status-verified"><i class="bi bi-check-circle-fill"></i> Lunas</span>',
                      ];
                      echo $payBadges[$result['status_pembayaran']] ?? $result['status_pembayaran'];
                    ?>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Tampilkan catatan jika perlu perbaikan atau ditolak -->
            <?php if (in_array($result['status_pendaftaran'], ['perlu_perbaikan', 'ditolak']) && $result['catatan_admin']): ?>
              <div class="alert <?= $result['status_pendaftaran'] === 'ditolak' ? 'alert-danger' : 'alert-info' ?> mt-4 mb-0">
                <h6 class="alert-heading fw-bold"><i class="bi bi-chat-left-text-fill me-2"></i>Catatan Panitia:</h6>
                <p class="mb-0 mt-2"><?= nl2br(esc($result['catatan_admin'])) ?></p>
              </div>
            <?php endif; ?>

            <!-- Tombol Aksi Tambahan -->
            <div class="mt-4 pt-3 border-top d-flex gap-2">
              <?php if ($result['status_pendaftaran'] === 'perlu_perbaikan'): ?>
                <a href="<?= base_url('pkpa/perbaikan/' . $result['public_token']) ?>" class="btn btn-warning">
                  <i class="bi bi-pencil-square me-2"></i>Lakukan Perbaikan Data
                </a>
              <?php endif; ?>
              
              <?php if ($result['status_pendaftaran'] === 'diverifikasi' && $result['status_pembayaran'] === 'belum_bayar'): ?>
                <!-- Tombol ini nanti mengarah ke halaman/modal upload bukti bayar (Opsional P1) -->
                <button class="btn btn-success" onclick="alert('Fitur upload bukti bayar segera hadir.')">
                  <i class="bi bi-upload me-2"></i>Unggah Bukti Bayar
                </button>
              <?php endif; ?>
            </div>
            
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?= $this->endSection() ?>
