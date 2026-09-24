<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- ── HERO ── -->
<section class="pkpa-hero">
  <div class="hero-grid"></div>
  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-7">
        <span class="hero-badge">
          <i class="bi bi-award-fill"></i> PERADI DPC Tangerang Raya
        </span>
        <h1>Wujudkan Karir <span style="color:var(--clr-gold);white-space:nowrap">Advokat</span> Anda Bersama Kami</h1>
        <p class="tagline mt-3">Pendidikan Khusus Profesi Advokat</p>
        <p class="lead mt-2 mb-0">
          Langkah awal menuju profesi advokat yang
          <strong class="text-white">Profesional</strong>,
          <strong class="text-white">Berintegritas</strong> dan
          <strong class="text-white">Berkeadilan</strong> — bersertifikat resmi PERADI.
        </p>

        <div class="mt-4 d-flex flex-wrap gap-2 hero-cta-group">
          <?php if ($batch): ?>
            <?php if ($isOpen && ($sisaKuota === null || $sisaKuota > 0)): ?>
              <a href="<?= base_url('pkpa/daftar') ?>" class="btn btn-accent btn-lg px-4 py-3">
                <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
              </a>
            <?php elseif ($isOpen && $sisaKuota <= 0): ?>
              <button class="btn btn-secondary btn-lg px-4 py-3" disabled>
                <i class="bi bi-x-circle me-2"></i>Kuota Penuh
              </button>
            <?php else: ?>
              <button class="btn btn-outline-warning btn-lg px-4 py-3" disabled>
                <i class="bi bi-clock me-2"></i>Buka <?= date('d M Y', strtotime($batch['buka_daftar'])) ?>
              </button>
            <?php endif; ?>
          <?php else: ?>
            <button class="btn btn-secondary btn-lg px-4 py-3" disabled>
              <i class="bi bi-calendar-x me-2"></i>Belum Ada Pendaftaran
            </button>
          <?php endif; ?>
          <a href="<?= base_url('pkpa/cek-status') ?>" class="btn btn-outline-white btn-lg px-4 py-3">
            <i class="bi bi-search me-2"></i>Cek Status
          </a>
        </div>

        <div class="hero-stats">
          <div class="hero-stat-item">
            <div class="hero-stat-value">100%</div>
            <div class="hero-stat-label">Terstandarisasi</div>
          </div>
          <div style="width:1px;background:rgba(255,255,255,.12);height:40px;margin-top:.1rem"></div>
          <div class="hero-stat-item">
            <div class="hero-stat-value">PERADI</div>
            <div class="hero-stat-label">Bersertifikat Resmi</div>
          </div>
          <div style="width:1px;background:rgba(255,255,255,.12);height:40px;margin-top:.1rem"></div>
          <div class="hero-stat-item">
            <div class="hero-stat-value">Online</div>
            <div class="hero-stat-label">Pendaftaran Mudah</div>
          </div>
        </div>
      </div>

      <?php if ($batch): ?>
      <div class="col-lg-5">
        <div class="batch-card">
          <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge-gold"><i class="bi bi-calendar3 me-1"></i>Angkatan Aktif</span>
          </div>
          <div class="fw-bold mb-4" style="font-size:1.1rem;color:#fff"><?= esc($batch['nama']) ?></div>

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
              <div class="label">Biaya Program</div>
              <div class="value" style="color:var(--clr-gold2)">Rp <?= number_format($batch['biaya'], 0, ',', '.') ?></div>
            </div>
            <?php if ($batch['kuota']): ?>
            <div class="col-12">
              <div class="item-divider"></div>
              <div class="d-flex justify-content-between align-items-center">
                <div class="label mb-0">Sisa Kuota</div>
                <div class="value <?= ($sisaKuota <= 10) ? 'text-warning' : '' ?>">
                  <?= $sisaKuota ?> <span style="font-size:.75rem;font-weight:400;color:rgba(255,255,255,.5)">dari <?= $batch['kuota'] ?> tempat</span>
                </div>
              </div>
              <?php
                $terisi = $batch['kuota'] - $sisaKuota;
                $pct = ($terisi / $batch['kuota']) * 100;
              ?>
              <div class="progress mt-2" style="height:5px;background:rgba(255,255,255,.1);border-radius:10px">
                <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $pct > 90 ? '#ef4444' : ($pct > 75 ? 'var(--clr-gold)' : '#22c55e') ?>;border-radius:10px"></div>
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
<section class="py-6" style="padding:5rem 0">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="section-label"><i class="bi bi-stars"></i>Mengapa PKPA PERADI?</span>
      <h2 class="section-title mt-2">Keunggulan Program Kami</h2>
      <p class="section-subtitle">Dirancang untuk mempersiapkan Anda menjadi advokat yang kompeten dan siap berkarier di bidang hukum</p>
    </div>
    <div class="row g-4">
      <?php $features = [
        ['bi-book-half',        'Kurikulum Komprehensif',    'Materi terstruktur mencakup seluruh aspek hukum profesi advokat sesuai standar nasional PERADI.', '#3b82f6'],
        ['bi-person-workspace', 'Pengajar Berpengalaman',    'Dibimbing oleh advokat senior, hakim, dan akademisi hukum terkemuka yang aktif di bidangnya.', '#8b5cf6'],
        ['bi-patch-check-fill', 'Sertifikat Resmi PERADI',  'Sertifikat yang diakui secara nasional — syarat wajib menjadi advokat teregistrasi di seluruh Indonesia.', '#059669'],
        ['bi-people-fill',      'Jaringan Profesional Luas', 'Terhubung dengan ribuan advokat PERADI di seluruh Indonesia untuk memperluas peluang karir Anda.', '#d97706'],
      ]; ?>
      <?php foreach ($features as $i => [$icon, $title, $desc, $color]): ?>
      <div class="col-md-6 col-lg-3 reveal" style="transition-delay:<?= $i * 0.1 ?>s">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi <?= $icon ?>"></i></div>
          <h5><?= $title ?></h5>
          <p class="text-muted small mb-0" style="line-height:1.7"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── PROSES PENDAFTARAN ── -->
<section style="padding:5rem 0;background:var(--clr-light)">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="section-label"><i class="bi bi-list-ol"></i>Prosedur</span>
      <h2 class="section-title mt-2">Alur Pendaftaran</h2>
      <p class="section-subtitle">Proses pendaftaran yang mudah dan transparan dalam 4 langkah</p>
    </div>
    <div class="row g-4 align-items-start">
      <?php $steps = [
        ['bi-pencil-square', 'Isi Formulir Online', 'Lengkapi data pribadi, pendidikan, dan unggah dokumen persyaratan melalui form online kami.'],
        ['bi-hourglass-split', 'Verifikasi Panitia', 'Tim panitia akan memverifikasi berkas Anda dalam 1-2 hari kerja. Cek status via No. Pendaftaran.'],
        ['bi-cash-coin', 'Pembayaran Program', 'Setelah diverifikasi, lakukan pembayaran dan konfirmasikan ke panitia via WhatsApp.'],
        ['bi-mortarboard-fill', 'Ikuti PKPA', 'Selamat! Anda resmi menjadi peserta PKPA dan siap memulai perjalanan karir advokat Anda.'],
      ]; ?>
      <?php foreach ($steps as $i => [$icon, $title, $desc]): ?>
      <div class="col-md-6 col-lg-3 reveal" style="transition-delay:<?= $i * 0.1 ?>s">
        <div style="position:relative">
          <!-- Connector line (desktop) -->
          <?php if ($i < 3): ?>
          <div class="d-none d-lg-block" style="position:absolute;top:28px;left:calc(50% + 32px);right:calc(-50% + 32px);height:2px;background:linear-gradient(90deg,var(--clr-navy2),rgba(11,37,71,.2));z-index:0"></div>
          <?php endif; ?>

          <div class="text-center">
            <div style="width:56px;height:56px;background:linear-gradient(135deg,var(--clr-navy),var(--clr-navy2));border-radius:50%;display:grid;place-items:center;margin:0 auto 1rem;position:relative;z-index:1;box-shadow:0 8px 20px rgba(11,37,71,.25)">
              <i class="bi <?= $icon ?> text-white fs-5"></i>
            </div>
            <div style="width:22px;height:22px;background:var(--clr-gold);border-radius:50%;display:grid;place-items:center;margin:-18px auto 1rem;font-size:.7rem;font-weight:800;color:var(--clr-navy);position:relative;z-index:2;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.15)"><?= $i+1 ?></div>
            <h6 class="fw-700 mb-2" style="font-size:.92rem;color:var(--clr-navy)"><?= $title ?></h6>
            <p class="text-muted small mb-0" style="line-height:1.7"><?= $desc ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── NILAI ── -->
<section style="padding:5rem 0">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="section-label"><i class="bi bi-heart-fill"></i>Nilai Kami</span>
      <h2 class="section-title mt-2">Landasan PERADI DPC Tangerang Raya</h2>
    </div>
    <div class="row g-3 justify-content-center">
      <?php $nilai = [
        ['bi-shield-check',   'Integritas',       'Menjunjung tinggi kejujuran dan etika profesi dalam setiap tindakan.'],
        ['bi-briefcase',      'Profesionalisme',  'Standar kompetensi tertinggi dalam setiap layanan hukum.'],
        ['bi-people',         'Kolaborasi',       'Saling mendukung dan bersinergi dalam komunitas profesi.'],
        ['bi-scales',         'Keadilan',         'Memperjuangkan keadilan bagi seluruh lapisan masyarakat.'],
      ]; ?>
      <?php foreach ($nilai as $i => [$icon, $nama, $desc]): ?>
      <div class="col-6 col-md-3 reveal" style="transition-delay:<?= $i * 0.1 ?>s">
        <div class="nilai-card">
          <div class="nilai-icon"><i class="bi <?= $icon ?>"></i></div>
          <div class="fw-bold mb-1"><?= $nama ?></div>
          <p class="small mb-0 mt-1" style="color:rgba(255,255,255,.55);line-height:1.6"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── KONTAK PANITIA ── -->
<?php if (array_filter($settings)): ?>
<section style="padding:5rem 0;background:var(--clr-light)">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="section-label"><i class="bi bi-whatsapp"></i>Hubungi Kami</span>
      <h2 class="section-title mt-2">Tim Panitia PKPA</h2>
      <p class="section-subtitle">Ada pertanyaan? Panitia kami siap membantu Anda langsung via WhatsApp</p>
    </div>
    <div class="row g-3 justify-content-center">
      <?php $contacts = [
        ['wa_ribka',  'Ribka'],
        ['wa_yuni',   'Yuni'],
        ['wa_robert', 'Robert'],
        ['wa_ruby',   'Ruby'],
      ]; ?>
      <?php foreach ($contacts as $i => [$key, $nama]): ?>
        <?php if (!empty($settings[$key])): ?>
        <div class="col-6 col-md-3 reveal" style="transition-delay:<?= $i * 0.1 ?>s">
          <div class="wa-card">
            <div class="wa-avatar"><i class="bi bi-person-fill"></i></div>
            <div class="fw-semibold mb-1"><?= $nama ?></div>
            <div class="text-muted small mb-3">Panitia PKPA</div>
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

<!-- ── CTA BOTTOM ── -->
<?php if ($batch && $isOpen && ($sisaKuota === null || $sisaKuota > 0)): ?>
<section style="padding:5rem 0;background:linear-gradient(135deg,var(--clr-navy3) 0%,var(--clr-navy) 50%,var(--clr-navy2) 100%);position:relative;overflow:hidden">
  <div style="position:absolute;top:-30%;right:5%;width:400px;height:400px;background:radial-gradient(circle,rgba(212,168,67,.12) 0%,transparent 70%);border-radius:50%"></div>
  <div class="container text-center position-relative z-1 reveal">
    <span class="hero-badge mb-4"><i class="bi bi-clock-fill me-1"></i> Pendaftaran Sedang Dibuka!</span>
    <h2 style="font-size:clamp(1.7rem,4vw,2.6rem);font-weight:800;color:#fff;letter-spacing:-.03em;margin-bottom:1rem">
      Jangan Lewatkan Kesempatan Ini!
    </h2>
    <p style="color:rgba(255,255,255,.7);max-width:480px;margin:0 auto 2rem;line-height:1.7">
      Daftar sekarang dan mulai perjalanan menuju profesi advokat yang Anda impikan bersama PERADI DPC Tangerang Raya.
    </p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="<?= base_url('pkpa/daftar') ?>" class="btn btn-accent btn-lg px-5 py-3">
        <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
      </a>
      <a href="<?= base_url('pkpa/cek-status') ?>" class="btn btn-outline-white btn-lg px-4 py-3">
        <i class="bi bi-search me-2"></i>Cek Status
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<?= $this->endSection() ?>
