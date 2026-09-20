<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="container py-5">

  <!-- Header -->
  <div class="text-center mb-5">
    <span class="section-label">Form Perbaikan Data</span>
    <h1 class="section-title">Perbaiki Data PKPA</h1>
    <div class="alert alert-info d-inline-block text-start mt-3 shadow-sm border-0">
      <h6 class="alert-heading fw-bold"><i class="bi bi-chat-left-text-fill me-2"></i>Catatan Panitia:</h6>
      <p class="mb-0 mt-2"><?= nl2br(esc($reg['catatan_admin'])) ?></p>
    </div>
  </div>

  <!-- Tampilkan error validasi -->
  <?php if (session('errors')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-exclamation-triangle me-2"></i>Mohon perbaiki data berikut:</strong>
    <ul class="mb-0 mt-2">
      <?php foreach (session('errors') as $err): ?>
      <li><?= esc($err) ?></li>
      <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php endif; ?>

  <form action="<?= base_url('pkpa/perbaikan/' . $reg['public_token']) ?>" method="post" enctype="multipart/form-data"
        class="needs-validation" novalidate
        data-swal-confirm="Ya, simpan perbaikan"
        data-swal-title="Simpan perbaikan?"
        data-swal-text="Pastikan data sudah diperbaiki sesuai catatan panitia.">
    <?= csrf_field() ?>

    <!-- ── DATA PRIBADI ── -->
    <div class="form-section">
      <div class="form-section-title"><i class="bi bi-person-fill"></i> Data Pribadi</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label" for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" name="nama_lengkap" id="nama_lengkap"
                 class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>"
                 value="<?= old('nama_lengkap', $reg['nama_lengkap']) ?>" placeholder="Sesuai ijazah" required minlength="3" maxlength="150">
          <div class="invalid-feedback"><?= session('errors.nama_lengkap') ?? 'Nama lengkap wajib diisi (minimal 3 karakter).' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="nik">NIK (16 digit) <span class="text-danger">*</span></label>
          <input type="text" name="nik" id="nik"
                 class="form-control <?= session('errors.nik') ? 'is-invalid' : '' ?>"
                 value="<?= old('nik', $reg['nik']) ?>" placeholder="1234567890123456" required pattern="\d{16}" maxlength="16">
          <div class="invalid-feedback"><?= session('errors.nik') ?? 'NIK harus 16 digit angka.' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
          <input type="text" name="tempat_lahir" id="tempat_lahir"
                 class="form-control <?= session('errors.tempat_lahir') ? 'is-invalid' : '' ?>"
                 value="<?= old('tempat_lahir', $reg['tempat_lahir']) ?>" required maxlength="100">
          <div class="invalid-feedback"><?= session('errors.tempat_lahir') ?? 'Wajib diisi.' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
          <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                 class="form-control <?= session('errors.tanggal_lahir') ? 'is-invalid' : '' ?>"
                 value="<?= old('tanggal_lahir', $reg['tanggal_lahir']) ?>"
                 max="<?= date('Y-m-d', strtotime('-21 years')) ?>" required>
          <div class="invalid-feedback"><?= session('errors.tanggal_lahir') ?? 'Usia minimal 21 tahun.' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
          <div class="d-flex gap-3 pt-1">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L" <?= old('jenis_kelamin', $reg['jenis_kelamin']) === 'L' ? 'checked' : '' ?> required>
              <label class="form-check-label" for="jk_l">Laki-laki</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P" <?= old('jenis_kelamin', $reg['jenis_kelamin']) === 'P' ? 'checked' : '' ?>>
              <label class="form-check-label" for="jk_p">Perempuan</label>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="kota">Kota/Kabupaten <span class="text-danger">*</span></label>
          <input type="text" name="kota" id="kota"
                 class="form-control <?= session('errors.kota') ? 'is-invalid' : '' ?>"
                 value="<?= old('kota', $reg['kota']) ?>" required maxlength="100">
          <div class="invalid-feedback"><?= session('errors.kota') ?? 'Wajib diisi.' ?></div>
        </div>
        <div class="col-12">
          <label class="form-label" for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
          <textarea name="alamat" id="alamat" rows="3"
                    class="form-control <?= session('errors.alamat') ? 'is-invalid' : '' ?>"
                    required maxlength="500"><?= old('alamat', $reg['alamat']) ?></textarea>
          <div class="invalid-feedback"><?= session('errors.alamat') ?? 'Wajib diisi.' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="no_wa">No. WhatsApp <span class="text-danger">*</span></label>
          <input type="text" name="no_wa" id="no_wa"
                 class="form-control <?= session('errors.no_wa') ? 'is-invalid' : '' ?>"
                 value="<?= old('no_wa', $reg['no_wa']) ?>" placeholder="08xxxxxxxxxx" required maxlength="20">
          <div class="form-text">Format 08... atau +62...</div>
          <div class="invalid-feedback"><?= session('errors.no_wa') ?? 'Wajib diisi.' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" id="email"
                 class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                 value="<?= old('email', $reg['email']) ?>" required maxlength="150">
          <div class="invalid-feedback"><?= session('errors.email') ?? 'Email tidak valid.' ?></div>
        </div>
      </div>
    </div>

    <!-- ── PENDIDIKAN ── -->
    <div class="form-section">
      <div class="form-section-title"><i class="bi bi-mortarboard-fill"></i> Data Pendidikan</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label" for="asal_kampus">Perguruan Tinggi <span class="text-danger">*</span></label>
          <input type="text" name="asal_kampus" id="asal_kampus"
                 class="form-control <?= session('errors.asal_kampus') ? 'is-invalid' : '' ?>"
                 value="<?= old('asal_kampus', $reg['asal_kampus']) ?>" required maxlength="150">
          <div class="invalid-feedback"><?= session('errors.asal_kampus') ?? 'Wajib diisi.' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="program_studi">Fakultas / Program Studi <span class="text-danger">*</span></label>
          <input type="text" name="program_studi" id="program_studi"
                 class="form-control <?= session('errors.program_studi') ? 'is-invalid' : '' ?>"
                 value="<?= old('program_studi', $reg['program_studi']) ?>" required maxlength="150">
          <div class="invalid-feedback"><?= session('errors.program_studi') ?? 'Wajib diisi.' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="gelar">Gelar <span class="text-danger">*</span></label>
          <select name="gelar" id="gelar" class="form-select <?= session('errors.gelar') ? 'is-invalid' : '' ?>" required>
            <option value="">-- Pilih Gelar --</option>
            <?php foreach (['S.H.', 'S.Sy.', 'S.H.I.', 'Lainnya'] as $g): ?>
            <option value="<?= $g ?>" <?= old('gelar', $reg['gelar']) === $g ? 'selected' : '' ?>><?= $g ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback"><?= session('errors.gelar') ?? 'Wajib dipilih.' ?></div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="tahun_lulus">Tahun Lulus <span class="text-danger">*</span></label>
          <input type="number" name="tahun_lulus" id="tahun_lulus"
                 class="form-control <?= session('errors.tahun_lulus') ? 'is-invalid' : '' ?>"
                 value="<?= old('tahun_lulus', $reg['tahun_lulus']) ?>" min="1970" max="<?= date('Y') ?>" required>
          <div class="invalid-feedback"><?= session('errors.tahun_lulus') ?? 'Wajib diisi.' ?></div>
        </div>
      </div>
    </div>

    <!-- ── PEKERJAAN ── -->
    <div class="form-section">
      <div class="form-section-title"><i class="bi bi-briefcase-fill"></i> Pekerjaan (Opsional)</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label" for="pekerjaan">Pekerjaan Saat Ini</label>
          <input type="text" name="pekerjaan" id="pekerjaan" class="form-control"
                 value="<?= old('pekerjaan', $reg['pekerjaan']) ?>" maxlength="100">
        </div>
        <div class="col-md-6">
          <label class="form-label" for="instansi">Instansi / Kantor</label>
          <input type="text" name="instansi" id="instansi" class="form-control"
                 value="<?= old('instansi', $reg['instansi']) ?>" maxlength="150">
        </div>
      </div>
    </div>

    <!-- ── DOKUMEN ── -->
    <div class="form-section">
      <div class="form-section-title"><i class="bi bi-folder-fill"></i> Unggah Dokumen</div>
      <div class="row g-3">
        <?php $docsDef = [
          ['pas_foto', 'Pas Foto 3×4', 'JPG/PNG', '1 MB', true],
          ['ktp',      'Scan KTP',     'JPG/PNG/PDF', '2 MB', true],
          ['ijazah',   'Ijazah / SKL S1', 'PDF/JPG/PNG', '2 MB', true],
          ['transkrip','Transkrip Nilai', 'PDF', '2 MB', false],
        ]; ?>
        <?php foreach ($docsDef as [$name, $label, $format, $maxSize, $required]): ?>
        <div class="col-md-6">
          <label class="form-label" for="<?= $name ?>">
            <?= $label ?>
          </label>
          <input type="file" name="<?= $name ?>" id="<?= $name ?>"
                 class="form-control <?= session('errors.' . $name) ? 'is-invalid' : '' ?>">
          <div class="form-text">
            Format: <?= $format ?>, maks <?= $maxSize ?>. 
            <span class="text-info fw-semibold">Biarkan kosong jika dokumen ini tidak perlu diperbaiki.</span>
          </div>
          <?php if (isset($docsMap[$name])): ?>
            <div class="small text-muted mt-1">
              <i class="bi bi-file-earmark-check text-success me-1"></i>Dokumen lama: <?= esc($docsMap[$name]['nama_asli']) ?>
            </div>
          <?php endif; ?>
          <div class="invalid-feedback"><?= session('errors.' . $name) ?? 'File tidak valid.' ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ── PERNYATAAN ── -->
    <div class="form-section">
      <div class="form-check">
        <input class="form-check-input <?= session('errors.pernyataan') ? 'is-invalid' : '' ?>"
               type="checkbox" name="pernyataan" id="pernyataan" value="1" required
               <?= old('pernyataan') ? 'checked' : '' ?>>
        <label class="form-check-label" for="pernyataan">
          Saya menyatakan bahwa data yang saya isi adalah <strong>benar</strong> dan dapat dipertanggungjawabkan. Saya menyetujui pemrosesan data pribadi saya oleh PERADI DPC Tangerang Raya untuk keperluan pendaftaran dan penyelenggaraan PKPA.
        </label>
        <div class="invalid-feedback">Anda harus menyetujui pernyataan ini.</div>
      </div>
    </div>

    <div class="text-end">
      <a href="<?= base_url('pkpa/cek-status') ?>" class="btn btn-outline-secondary me-2 px-4">Batal</a>
      <button type="submit" class="btn btn-primary px-5" style="background:var(--clr-navy);border-color:var(--clr-navy)">
        <i class="bi bi-save-fill me-2"></i>Simpan Perbaikan
      </button>
    </div>

  </form>
</div>

<?= $this->section('scripts') ?>
<script>
// Validasi client: NIK hanya angka
document.getElementById('nik').addEventListener('input', function() {
  this.value = this.value.replace(/\D/g, '').slice(0, 16);
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
