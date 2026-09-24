<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-name" content="<?= csrf_token() ?>">
  <meta name="csrf-hash" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'PKPA PERADI DPC Tangerang Raya') ?></title>
  <meta name="description" content="Pendidikan Khusus Profesi Advokat — PERADI DPC Tangerang Raya. Raih karier advokat profesional Anda. Daftar online sekarang.">
  <meta name="keywords" content="PKPA, PERADI, Tangerang Raya, advokat, pendaftaran, pendidikan hukum">
  <meta name="author" content="PERADI DPC Tangerang Raya">
  <meta property="og:title" content="PKPA PERADI DPC Tangerang Raya">
  <meta property="og:description" content="Pendidikan Khusus Profesi Advokat — PERADI DPC Tangerang Raya">
  <meta property="og:type" content="website">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
  <!-- Bootstrap 5.3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/pkpa.css') ?>">
  <?= $this->renderSection('styles') ?>
</head>
<body
  <?php if (session('success')): ?>data-flash-success="<?= esc(session('success')) ?>"<?php endif; ?>
  <?php if (session('error')):   ?>data-flash-error="<?= esc(session('error')) ?>"<?php endif; ?>
  <?php if (session('info')):    ?>data-flash-info="<?= esc(session('info')) ?>"<?php endif; ?>
>

<!-- ── Navbar ── -->
<nav class="navbar navbar-expand-lg navbar-dark pkpa-navbar sticky-top" id="pkpa-navbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('pkpa') ?>">
      <div class="navbar-logo-icon">
        <i class="bi bi-scales"></i>
      </div>
      <div>
        <div class="fw-bold lh-sm" style="font-size:.95rem">PKPA PERADI</div>
        <small class="text-white-50 d-none d-sm-block" style="font-size:.65rem;letter-spacing:.3px">DPC Tangerang Raya</small>
      </div>
    </a>

    <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('pkpa') ?>">
            <i class="bi bi-house-door me-1 d-lg-none"></i>Info PKPA
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('pkpa/cek-status') ?>">
            <i class="bi bi-search me-1 d-lg-none"></i>Cek Status
          </a>
        </li>
        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
          <a class="btn btn-accent btn-sm px-3 py-2" href="<?= base_url('pkpa/daftar') ?>">
            <i class="bi bi-pencil-square me-1"></i>Daftar Sekarang
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ── Konten utama ── -->
<main>
  <?= $this->renderSection('content') ?>
</main>

<!-- ── Footer ── -->
<footer class="pkpa-footer py-5 mt-auto">
  <div class="container">
    <div class="row gy-4 align-items-start">
      <div class="col-md-5">
        <div class="footer-brand">
          <div class="footer-logo">
            <i class="bi bi-scales"></i>
          </div>
          <div>
            <div class="fw-bold text-white" style="font-size:.95rem">PKPA PERADI</div>
            <div class="text-white-50 small">DPC Tangerang Raya</div>
          </div>
        </div>
        <p class="text-white-50 small mb-0 mt-2" style="max-width:320px;line-height:1.7">
          Pendidikan Khusus Profesi Advokat — jalur resmi menuju karier advokat yang profesional dan berintegritas di bawah naungan PERADI.
        </p>
      </div>
      <div class="col-md-3">
        <div class="fw-semibold text-white-50 mb-3 small" style="text-transform:uppercase;letter-spacing:.8px;font-size:.72rem">Navigasi</div>
        <ul class="list-unstyled mb-0">
          <li class="mb-2"><a href="<?= base_url('pkpa') ?>" class="text-white-50 text-decoration-none small footer-link"><i class="bi bi-chevron-right me-1 small"></i>Info PKPA</a></li>
          <li class="mb-2"><a href="<?= base_url('pkpa/daftar') ?>" class="text-white-50 text-decoration-none small footer-link"><i class="bi bi-chevron-right me-1 small"></i>Daftar Sekarang</a></li>
          <li><a href="<?= base_url('pkpa/cek-status') ?>" class="text-white-50 text-decoration-none small footer-link"><i class="bi bi-chevron-right me-1 small"></i>Cek Status</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <div class="fw-semibold text-white-50 mb-3 small" style="text-transform:uppercase;letter-spacing:.8px;font-size:.72rem">Nilai PERADI</div>
        <div class="d-flex flex-wrap gap-2">
          <?php foreach(['Integritas','Profesionalisme','Kolaborasi','Keadilan'] as $v): ?>
          <span class="badge" style="background:rgba(212,168,67,.12);color:var(--clr-gold2);border:1px solid rgba(212,168,67,.2);font-size:.72rem;padding:.3rem .7rem;border-radius:50px;font-weight:600"><?= $v ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <hr class="mt-4 mb-3" style="border-color:rgba(255,255,255,.06)">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
      <p class="mb-0 text-white-50 small">
        &copy; <?= date('Y') ?> PERADI DPC Tangerang Raya. All rights reserved.
      </p>
      <a href="<?= base_url('admin/login') ?>" class="text-white-50 text-decoration-none small" style="font-size:.75rem;opacity:.4">
        <i class="bi bi-lock me-1"></i>Admin
      </a>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script>const BASE_URL = '<?= rtrim(base_url(), '/') ?>';</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="<?= base_url('assets/js/swal-confirm.js') ?>"></script>
<script>
// Navbar scroll effect
const navbar = document.getElementById('pkpa-navbar');
window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 60);
});

// Scroll reveal animation
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
  });
}, { threshold: 0.12 });
reveals.forEach(el => observer.observe(el));

// Footer link hover
document.querySelectorAll('.footer-link').forEach(link => {
  link.addEventListener('mouseenter', () => link.style.color = 'rgba(212,168,67,.85)');
  link.addEventListener('mouseleave', () => link.style.color = '');
});
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
