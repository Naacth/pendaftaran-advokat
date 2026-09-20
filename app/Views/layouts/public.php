<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-name" content="<?= csrf_token() ?>">
  <meta name="csrf-hash" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'PKPA PERADI DPC Tangerang Raya') ?></title>
  <meta name="description" content="Pendidikan Khusus Profesi Advokat — PERADI DPC Tangerang Raya. Daftar online sekarang.">

  <!-- Bootstrap 5.3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
<nav class="navbar navbar-expand-lg navbar-dark pkpa-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('pkpa') ?>">
      <span class="navbar-logo-icon"><i class="bi bi-scales"></i></span>
      <div>
        <div class="fw-bold lh-sm">PKPA PERADI</div>
        <small class="text-white-50 d-none d-sm-block" style="font-size:.7rem">DPC Tangerang Raya</small>
      </div>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('pkpa') ?>">Info PKPA</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('pkpa/cek-status') ?>">Cek Status</a></li>
        <li class="nav-item ms-lg-2">
          <a class="btn btn-accent btn-sm px-3" href="<?= base_url('pkpa/daftar') ?>">
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
<footer class="pkpa-footer py-4 mt-5">
  <div class="container text-center">
    <p class="mb-1 text-white-50 small">
      &copy; <?= date('Y') ?> PERADI DPC Tangerang Raya — <em>Integritas · Profesionalisme · Kolaborasi · Keadilan</em>
    </p>
    <p class="mb-0 text-white-50 small">
      <a href="<?= base_url('admin/login') ?>" class="text-white-50 text-decoration-none">Admin</a>
    </p>
  </div>
</footer>

<!-- Scripts -->
<script>const BASE_URL = '<?= rtrim(base_url(), '/') ?>';</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="<?= base_url('assets/js/swal-confirm.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
