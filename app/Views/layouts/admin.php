<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-name" content="<?= csrf_token() ?>">
  <meta name="csrf-hash" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'Admin PKPA — PERADI') ?></title>

  <!-- Bootstrap 5.3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- DataTables Bootstrap5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Custom Admin CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
  <?= $this->renderSection('styles') ?>
</head>
<body class="admin-body"
  <?php if (session('success')): ?>data-flash-success="<?= esc(session('success')) ?>"<?php endif; ?>
  <?php if (session('error')):   ?>data-flash-error="<?= esc(session('error')) ?>"<?php endif; ?>
>

<div class="d-flex" id="wrapper">

  <!-- ── Sidebar ── -->
  <nav id="sidebar" class="admin-sidebar d-flex flex-column">
    <div class="sidebar-brand">
      <i class="bi bi-scales fs-4 text-accent me-2"></i>
      <span>PKPA Admin</span>
    </div>

    <ul class="sidebar-nav nav flex-column flex-grow-1">
      <li class="nav-item">
        <a class="nav-link <?= str_contains(current_url(), 'admin/pkpa') && !str_contains(current_url(), '/') ? 'active' : '' ?>"
           href="<?= base_url('admin/pkpa') ?>">
          <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
      </li>
      <li class="sidebar-label">Kelola PKPA</li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa/angkatan') ?>">
          <i class="bi bi-calendar3 me-2"></i>Angkatan
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa/pendaftar') ?>">
          <i class="bi bi-people me-2"></i>Pendaftar
        </a>
      </li>
      <li class="sidebar-label">Pengaturan</li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa/pengaturan') ?>">
          <i class="bi bi-gear me-2"></i>Pengaturan
        </a>
      </li>
      <?php if (session('user_role') === 'super_admin'): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa/users') ?>">
          <i class="bi bi-person-badge me-2"></i>User Admin
        </a>
      </li>
      <?php endif; ?>
    </ul>

    <div class="sidebar-footer">
      <div class="small text-truncate text-white-50"><?= esc(session('user_nama')) ?></div>
      <div class="small text-white-50 mb-2"><?= esc(session('user_role')) ?></div>
      <a href="<?= base_url('admin/logout') ?>" class="btn btn-outline-light btn-sm w-100">
        <i class="bi bi-box-arrow-left me-1"></i>Logout
      </a>
    </div>
  </nav>

  <!-- ── Main content ── -->
  <div id="page-content-wrapper" class="flex-grow-1 d-flex flex-column min-vh-100">

    <!-- Topbar -->
    <div class="admin-topbar d-flex align-items-center px-3 px-md-4">
      <button class="btn btn-sm btn-outline-secondary me-3" id="sidebar-toggle">
        <i class="bi bi-list fs-5"></i>
      </button>
      <h6 class="mb-0 fw-semibold text-truncate"><?= esc($title ?? 'Dashboard') ?></h6>
      <div class="ms-auto d-flex align-items-center gap-2">
        <a href="<?= base_url('pkpa') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-box-arrow-up-right me-1"></i>Lihat Halaman Publik
        </a>
      </div>
    </div>

    <!-- Konten -->
    <main class="p-3 p-md-4 flex-grow-1">
      <?= $this->renderSection('content') ?>
    </main>

    <footer class="admin-footer text-center py-2 small text-muted">
      &copy; <?= date('Y') ?> PERADI DPC Tangerang Raya
    </footer>
  </div>
</div>

<!-- Scripts -->
<script>const BASE_URL = '<?= rtrim(base_url(), '/') ?>';</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="<?= base_url('assets/js/swal-confirm.js') ?>"></script>
<script src="<?= base_url('assets/js/datatable-init.js') ?>"></script>
<script>
  // Sidebar toggle
  document.getElementById('sidebar-toggle').addEventListener('click', () => {
    document.getElementById('wrapper').classList.toggle('sidebar-collapsed');
  });

  // Active nav
  document.querySelectorAll('#sidebar .nav-link').forEach(link => {
    if (link.href && window.location.href.startsWith(link.href) && link.href !== BASE_URL + '/admin/pkpa') {
      link.classList.add('active');
    }
  });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
