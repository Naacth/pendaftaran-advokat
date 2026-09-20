<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- ── HERO ── -->
<section class="pkpa-hero">
  <div class="container position-relative z-1">
    <div class="row align-items-center gy-4">
      <div class="col-lg-7">
        <span class="hero-badge"><i class="bi bi-award-fill"></i> PERADI DPC Tangerang Raya</span>
        <h1>Ayo Ikuti <span style="color:var(--clr-gold)">PKPA</span> dan Wujudkan Karir Advokat Anda!</h1>
        <p class="tagline mt-3">Pendidikan Khusus Profesi Advokat</p>
        <p class="lead mt-2">Langkah Awal Menuju Profesi Advokat yang <strong class="text-white">Profesional</strong>, <strong class="text-white">Berintegritas</strong> dan <strong class="text-white">Berkeadilan</strong></p>

        <div class="mt-4 d-flex flex-wrap gap-2">
          <?php if ($batch): ?>
            <?php if ($isOpen && ($sisaKuota === null || $sisaKuota > 0)): ?>
              <a href="<?= base_url('pkpa/daftar') ?>" class="btn btn-accent btn-lg px-4">
                <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
              </a>
            <?php elseif ($isOpen && $sisaKuota <= 0): ?>
              <button class="btn btn-secondary btn-lg px-4" disabled><i class="bi bi-x-circle me-2"></i>Kuota Penuh</button>
            <?php else: ?>
              <button class="btn btn-outline-warning btn-lg px-4" disabled>
                <i class="bi bi-clock me-2"></i>Buka <?= date('d M Y', strtotime($batch['buka_daftar'])) ?>
              </button>
            <?php endif; ?>
          <?php else: ?>
            <button class="btn btn-secondary btn-lg px-4" disabled>Belum Ada Pendaftaran</button>
          <?php endif; ?>
          <a href="<?= base_url('pkpa/cek-status') ?>" class="btn btn-outline-light btn-lg px-4">
            <i class="bi bi-search me-2"></i>Cek Status
          </a>
        </div>
      </div>

      <?php if ($batch): ?>
      <div class="col-lg-5">
        <div class="batch-card">
          <div class="text-accent fw-bold mb-3"><i class="bi bi-calendar3 me-2"></i><?= esc($batch['nama']) ?></div>
          <div class="row g-3">
            <div class="col-6">
              <div class="label">Buka Pendaftaran</div>
              <div class="value"><?= date('d M Y', strtotime($batch['buka_daftar'])) ?></div>
            </div>
            <div class="col-6">
              <div class="label">Tutup Pendaftaran</div>
              <div class="value"><?= date('d M Y', strtotime($batch['tutup_daftar'])) ?></div>
            </div>
            <?php if ($batch['tanggal_mulai']): ?>
            <div class="col-6">
              <div class="label">Mulai PKPA</div>
              <div class="value"><?= date('d M Y', strtotime($batch['tanggal_mulai'])) ?></div>
            </div>
            <?php endif; ?>
            <div class="col-6">
              <div class="label">Biaya</div>
              <div class="value text-warning">Rp <?= number_format($batch['biaya'], 0, ',', '.') ?></div>
            </div>
            <?php if ($batch['kuota']): ?>
            <div class="col-6">
              <div class="label">Sisa Kuota</div>
              <div class="value <?= ($sisaKuota <= 10) ? 'text-warning' : '' ?>">
                <?= $sisaKuota ?> / <?= $batch['kuota'] ?> peserta
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ── KEUNGGULAN ── -->
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-label">Mengapa PKPA PERADI?</span>
      <h2 class="section-title">Keunggulan Program Kami</h2>
    </div>
    <div class="row g-4">
      <?php $features = [
        ['bi-book-half',        'Materi Komprehensif',        'Kurikulum terstruktur yang mencakup seluruh aspek hukum profesi advokat sesuai standar PERADI nasional.'],
        ['bi-person-workspace', 'Dibimbing Praktisi & Akademisi', 'Pengajar berpengalaman dari kalangan advokat senior, hakim, dan akademisi hukum terkemuka.'],
        ['bi-patch-check-fill', 'Sertifikat Resmi PERADI',    'Sertifikat yang diakui secara nasional dan menjadi syarat menjadi advokat teregistrasi.'],
        ['bi-people-fill',      'Jaringan Profesional',       'Bergabung dengan komunitas advokat PERADI yang luas untuk memperluas peluang karir Anda.'],
      ]; ?>
      <?php foreach ($features as [$icon, $title, $desc]): ?>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi <?= $icon ?>"></i></div>
          <h5 class="fw-700 mb-2"><?= $title ?></h5>
          <p class="text-muted small mb-0"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── NILAI ── -->
<section class="py-5" style="background:var(--clr-light)">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-label">Nilai Kami</span>
      <h2 class="section-title">Landasan PERADI DPC Tangerang Raya</h2>
    </div>
    <div class="row g-3 justify-content-center">
      <?php $nilai = [
        ['bi-shield-check',   'Integritas',       'Menjunjung tinggi kejujuran dan etika profesi.'],
        ['bi-briefcase',      'Profesionalisme',  'Standar kompetensi tertinggi dalam setiap layanan.'],
        ['bi-people',         'Kolaborasi',       'Saling mendukung dalam komunitas profesi.'],
        ['bi-balance-scale',  'Keadilan',         'Memperjuangkan keadilan bagi seluruh lapisan masyarakat.'],
      ]; ?>
      <?php foreach ($nilai as [$icon, $nama, $desc]): ?>
      <div class="col-6 col-md-3">
        <div class="nilai-card">
          <div class="nilai-icon"><i class="bi <?= $icon ?>"></i></div>
          <div class="fw-bold"><?= $nama ?></div>
          <p class="small mb-0 mt-1 text-white-50"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── KONTAK PANITIA ── -->
<?php if (array_filter($settings)): ?>
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-label">Hubungi Kami</span>
      <h2 class="section-title">Kontak Panitia PKPA</h2>
      <p class="text-muted">Ada pertanyaan? Hubungi panitia kami langsung via WhatsApp</p>
    </div>
    <div class="row g-3 justify-content-center">
      <?php $contacts = [
        ['wa_ribka',  'Ribka'],
        ['wa_yuni',   'Yuni'],
        ['wa_robert', 'Robert'],
        ['wa_ruby',   'Ruby'],
      ]; ?>
      <?php foreach ($contacts as [$key, $nama]): ?>
        <?php if (! empty($settings[$key])): ?>
        <div class="col-6 col-md-3">
          <div class="wa-card">
            <div class="wa-avatar"><i class="bi bi-person-fill"></i></div>
            <div class="fw-semibold mb-2"><?= $nama ?></div>
            <a href="https://wa.me/<?= esc($settings[$key]) ?>?text=Halo%20<?= urlencode($nama) ?>%2C%20saya%20ingin%20bertanya%20tentang%20PKPA%20PERADI%20Tangerang%20Raya."
               target="_blank" class="btn-wa text-decoration-none">
              <i class="bi bi-whatsapp"></i> Chat WA
            </a>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?= $this->endSection() ?>
