<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- ── Page Header ── -->
<div class="form-page-header">
  <div class="container">
    <div class="form-breadcrumb">
      <a href="<?= base_url('pkpa') ?>"><i class="bi bi-house-door me-1"></i>Beranda</a>
      <span class="separator"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Formulir Pendaftaran</span>
    </div>
    <h1 class="form-page-title">Daftar PKPA <?= esc($batch['nama']) ?></h1>
    <p class="form-page-subtitle mt-1">Isi formulir dengan data yang benar dan lengkap sesuai dokumen resmi Anda</p>

    <div class="form-steps mt-4">
      <div class="form-step active">
        <div class="step-num">1</div>
        <span class="d-none d-sm-inline">Data Diri</span>
      </div>
      <div class="form-step-divider"></div>
      <div class="form-step active">
        <div class="step-num">2</div>
        <span class="d-none d-sm-inline">Pendidikan</span>
      </div>
      <div class="form-step-divider"></div>
      <div class="form-step active">
        <div class="step-num">3</div>
        <span class="d-none d-sm-inline">Dokumen</span>
      </div>
      <div class="form-step-divider"></div>
      <div class="form-step active">
        <div class="step-num">4</div>
        <span class="d-none d-sm-inline">Kirim</span>
      </div>
    </div>
  </div>
</div>

<!-- ── Form Container ── -->
<div class="form-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-xl-8">

        <!-- Tampilkan error validasi -->
        <?php if (session('errors')): ?>
        <div class="alert border-0 mb-4 shadow-sm" style="background:#FFF1F0;border-left:4px solid #ef4444 !important;border-radius:var(--radius)" role="alert">
          <div class="d-flex gap-3 align-items-start">
            <i class="bi bi-exclamation-triangle-fill mt-1" style="color:#ef4444;font-size:1.1rem;flex-shrink:0"></i>
            <div>
              <div class="fw-bold mb-1" style="color:#820014;font-size:.92rem">Mohon perbaiki data berikut:</div>
              <ul class="mb-0 ps-3" style="font-size:.875rem;color:#991b1b">
                <?php foreach (session('errors') as $err): ?>
                <li><?= esc($err) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('pkpa/daftar') ?>" method="post" enctype="multipart/form-data"
              class="needs-validation" novalidate
              data-swal-confirm="Ya, kirim pendaftaran"
              data-swal-title="Kirim pendaftaran?"
              data-swal-text="Data tidak dapat diubah kecuali diminta perbaikan oleh panitia.">
          <?= csrf_field() ?>
          <input type="hidden" name="batch_id" value="<?= esc($batch['id']) ?>">

          <!-- ── DATA PRIBADI ── -->
          <div class="form-section">
            <div class="form-section-title">
              <i class="bi bi-person-fill"></i>
              Data Pribadi
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap"
                       class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>"
                       value="<?= old('nama_lengkap') ?>" placeholder="Sesuai ijazah / KTP" required minlength="3" maxlength="150">
                <div class="invalid-feedback"><?= session('errors.nama_lengkap') ?? 'Nama lengkap wajib diisi (minimal 3 karakter).' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="nik">NIK (16 digit) <span class="text-danger">*</span></label>
                <input type="text" name="nik" id="nik"
                       class="form-control <?= session('errors.nik') ? 'is-invalid' : '' ?>"
                       value="<?= old('nik') ?>" placeholder="1234567890123456" required pattern="\d{16}" maxlength="16">
                <div class="invalid-feedback"><?= session('errors.nik') ?? 'NIK harus 16 digit angka.' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                <input type="text" name="tempat_lahir" id="tempat_lahir"
                       class="form-control <?= session('errors.tempat_lahir') ? 'is-invalid' : '' ?>"
                       value="<?= old('tempat_lahir') ?>" required maxlength="100">
                <div class="invalid-feedback"><?= session('errors.tempat_lahir') ?? 'Wajib diisi.' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                       class="form-control <?= session('errors.tanggal_lahir') ? 'is-invalid' : '' ?>"
                       value="<?= old('tanggal_lahir') ?>"
                       max="<?= date('Y-m-d', strtotime('-21 years')) ?>" required>
                <div class="form-text">Usia minimal 21 tahun</div>
                <div class="invalid-feedback"><?= session('errors.tanggal_lahir') ?? 'Usia minimal 21 tahun.' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <div class="d-flex gap-3 pt-1">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L" <?= old('jenis_kelamin') === 'L' ? 'checked' : '' ?> required>
                    <label class="form-check-label" for="jk_l">Laki-laki</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P" <?= old('jenis_kelamin') === 'P' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="jk_p">Perempuan</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="kota">Kota/Kabupaten <span class="text-danger">*</span></label>
                <input type="text" name="kota" id="kota"
                       class="form-control <?= session('errors.kota') ? 'is-invalid' : '' ?>"
                       value="<?= old('kota') ?>" required maxlength="100">
                <div class="invalid-feedback"><?= session('errors.kota') ?? 'Wajib diisi.' ?></div>
              </div>
              <div class="col-12">
                <label class="form-label" for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea name="alamat" id="alamat" rows="3"
                          class="form-control <?= session('errors.alamat') ? 'is-invalid' : '' ?>"
                          required maxlength="500" placeholder="Jl. ..., Kecamatan ..., Kota ..."><?= old('alamat') ?></textarea>
                <div class="invalid-feedback"><?= session('errors.alamat') ?? 'Wajib diisi.' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="no_wa">No. WhatsApp <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text" style="background:var(--clr-lighter);border-color:var(--clr-border)">
                    <i class="bi bi-whatsapp text-success"></i>
                  </span>
                  <input type="text" name="no_wa" id="no_wa"
                         class="form-control <?= session('errors.no_wa') ? 'is-invalid' : '' ?>"
                         value="<?= old('no_wa') ?>" placeholder="08xxxxxxxxxx" required maxlength="20">
                  <div class="invalid-feedback"><?= session('errors.no_wa') ?? 'Wajib diisi.' ?></div>
                </div>
                <div class="form-text">Format: 08... atau +62...</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text" style="background:var(--clr-lighter);border-color:var(--clr-border)">
                    <i class="bi bi-envelope"></i>
                  </span>
                  <input type="email" name="email" id="email"
                         class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                         value="<?= old('email') ?>" required maxlength="150" placeholder="nama@email.com">
                  <div class="invalid-feedback"><?= session('errors.email') ?? 'Email tidak valid.' ?></div>
                </div>
              </div>
            </div>
          </div>

          <!-- ── PENDIDIKAN ── -->
          <div class="form-section">
            <div class="form-section-title">
              <i class="bi bi-mortarboard-fill"></i>
              Data Pendidikan
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="asal_kampus">Perguruan Tinggi <span class="text-danger">*</span></label>
                <input type="text" name="asal_kampus" id="asal_kampus"
                       class="form-control <?= session('errors.asal_kampus') ? 'is-invalid' : '' ?>"
                       value="<?= old('asal_kampus') ?>" required maxlength="150" placeholder="Universitas ...">
                <div class="invalid-feedback"><?= session('errors.asal_kampus') ?? 'Wajib diisi.' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="program_studi">Fakultas / Program Studi <span class="text-danger">*</span></label>
                <input type="text" name="program_studi" id="program_studi"
                       class="form-control <?= session('errors.program_studi') ? 'is-invalid' : '' ?>"
                       value="<?= old('program_studi') ?>" required maxlength="150" placeholder="Ilmu Hukum">
                <div class="invalid-feedback"><?= session('errors.program_studi') ?? 'Wajib diisi.' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="gelar">Gelar <span class="text-danger">*</span></label>
                <select name="gelar" id="gelar" class="form-select <?= session('errors.gelar') ? 'is-invalid' : '' ?>" required>
                  <option value="">— Pilih Gelar —</option>
                  <?php foreach (['S.H.', 'S.Sy.', 'S.H.I.', 'Lainnya'] as $g): ?>
                  <option value="<?= $g ?>" <?= old('gelar') === $g ? 'selected' : '' ?>><?= $g ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= session('errors.gelar') ?? 'Wajib dipilih.' ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="tahun_lulus">Tahun Lulus <span class="text-danger">*</span></label>
                <input type="number" name="tahun_lulus" id="tahun_lulus"
                       class="form-control <?= session('errors.tahun_lulus') ? 'is-invalid' : '' ?>"
                       value="<?= old('tahun_lulus') ?>" min="1970" max="<?= date('Y') ?>" required placeholder="<?= date('Y') ?>">
                <div class="invalid-feedback"><?= session('errors.tahun_lulus') ?? 'Wajib diisi.' ?></div>
              </div>
            </div>
          </div>

          <!-- ── PEKERJAAN ── -->
          <div class="form-section">
            <div class="form-section-title">
              <i class="bi bi-briefcase-fill"></i>
              Pekerjaan <span class="badge ms-1" style="background:var(--clr-lighter);color:var(--clr-muted);font-size:.7rem;font-weight:600;border-radius:50px;padding:.25rem .6rem">Opsional</span>
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="pekerjaan">Pekerjaan Saat Ini</label>
                <input type="text" name="pekerjaan" id="pekerjaan" class="form-control"
                       value="<?= old('pekerjaan') ?>" maxlength="100" placeholder="Paralegal, Staf Hukum, dll.">
              </div>
              <div class="col-md-6">
                <label class="form-label" for="instansi">Instansi / Kantor</label>
                <input type="text" name="instansi" id="instansi" class="form-control"
                       value="<?= old('instansi') ?>" maxlength="150" placeholder="Nama perusahaan / kantor">
              </div>
            </div>
          </div>

          <!-- ── DOKUMEN ── -->
          <div class="form-section">
            <div class="form-section-title">
              <i class="bi bi-folder-fill"></i>
              Unggah Dokumen
            </div>
            <div class="alert border-0 mb-4" style="background:#EFF6FF;border-radius:var(--radius-sm);padding:.85rem 1.1rem">
              <div class="d-flex gap-2 align-items-center">
                <i class="bi bi-info-circle-fill" style="color:#3b82f6;flex-shrink:0"></i>
                <div class="small" style="color:#1e40af">Pastikan dokumen jelas, tidak buram, dan sesuai format yang diminta. Ukuran file masing-masing maksimal 2 MB.</div>
              </div>
            </div>
            <div class="row g-3">
              <?php $docs = [
                ['pas_foto', 'Pas Foto 3×4',    'JPG / PNG', '1 MB',  true,  'bi-camera-fill'],
                ['ktp',      'Scan KTP',         'JPG / PNG / PDF', '2 MB', true, 'bi-credit-card-fill'],
                ['ijazah',   'Ijazah / SKL S1',  'PDF / JPG / PNG', '2 MB', true, 'bi-award-fill'],
                ['transkrip','Transkrip Nilai',   'PDF',             '2 MB', false,'bi-file-earmark-text-fill'],
              ]; ?>
              <?php foreach ($docs as [$name, $label, $format, $maxSize, $required, $icon]): ?>
              <div class="col-md-6">
                <div style="border:1.5px solid var(--clr-border);border-radius:var(--radius-sm);padding:1rem 1.1rem;transition:var(--transition);<?= session('errors.' . $name) ? 'border-color:#dc3545;' : '' ?>">
                  <label class="form-label d-flex align-items-center gap-2 mb-2" for="<?= $name ?>">
                    <i class="bi <?= $icon ?>" style="color:var(--clr-gold)"></i>
                    <?= $label ?>
                    <?= $required ? '<span class="text-danger">*</span>' : '<span class="badge ms-1" style="background:var(--clr-lighter);color:var(--clr-muted);font-size:.65rem;border-radius:50px;padding:.2rem .5rem">Opsional</span>' ?>
                  </label>
                  <input type="file" name="<?= $name ?>" id="<?= $name ?>"
                         class="form-control form-control-sm <?= session('errors.' . $name) ? 'is-invalid' : '' ?>"
                         <?= $required ? 'required' : '' ?>>
                  <div class="form-text mt-1"><i class="bi bi-paperclip me-1"></i><?= $format ?>, maks <?= $maxSize ?></div>
                  <div class="invalid-feedback"><?= session('errors.' . $name) ?? 'File tidak valid.' ?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- ── PERNYATAAN ── -->
          <div class="form-section" style="background:linear-gradient(135deg,rgba(11,37,71,.03),rgba(26,58,107,.02))">
            <div class="form-check" style="padding-left:2rem">
              <input class="form-check-input <?= session('errors.pernyataan') ? 'is-invalid' : '' ?>"
                     type="checkbox" name="pernyataan" id="pernyataan" value="1" required
                     <?= old('pernyataan') ? 'checked' : '' ?>>
              <label class="form-check-label" for="pernyataan" style="font-size:.88rem;line-height:1.6;color:var(--clr-text2)">
                Saya menyatakan bahwa data yang saya isi adalah <strong>benar</strong> dan dapat dipertanggungjawabkan. Saya menyetujui pemrosesan data pribadi saya oleh PERADI DPC Tangerang Raya untuk keperluan pendaftaran dan penyelenggaraan PKPA.
              </label>
              <div class="invalid-feedback">Anda harus menyetujui pernyataan ini.</div>
            </div>
          </div>

          <!-- ── Actions ── -->
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-2 mb-5">
            <a href="<?= base_url('pkpa') ?>" class="btn btn-outline-secondary px-4">
              <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
            <button type="submit" class="btn-submit">
              <i class="bi bi-send-fill me-2"></i>Kirim Pendaftaran
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->section('scripts') ?>
<script>
// NIK hanya angka
document.getElementById('nik').addEventListener('input', function() {
  this.value = this.value.replace(/\D/g, '').slice(0, 16);
});
// Hover file upload box
document.querySelectorAll('[type="file"]').forEach(input => {
  input.closest('[style*="border"]')?.addEventListener('mouseenter', function() {
    if (!this.querySelector('.is-invalid')) this.style.borderColor = 'var(--clr-navy2)';
  });
  input.closest('[style*="border"]')?.addEventListener('mouseleave', function() {
    if (!this.querySelector('.is-invalid')) this.style.borderColor = 'var(--clr-border)';
  });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
