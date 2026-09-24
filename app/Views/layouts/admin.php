<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-name" content="<?= csrf_token() ?>">
  <meta name="csrf-hash" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'Admin PKPA — PERADI') ?></title>
  <meta name="robots" content="noindex, nofollow">

  <!-- Bootstrap 5.3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- DataTables Bootstrap5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
  <nav id="sidebar" class="admin-sidebar">

    <!-- Brand -->
    <a class="sidebar-brand text-decoration-none" href="<?= base_url('admin/pkpa') ?>">
      <div class="sidebar-brand-icon">
        <i class="bi bi-scales"></i>
      </div>
      <div class="sidebar-brand-text">
        <span class="sidebar-brand-name">PKPA Admin</span>
        <span class="sidebar-brand-sub">PERADI DPC Tangerang Raya</span>
      </div>
    </a>

    <!-- Nav Menu -->
    <ul class="sidebar-nav nav flex-column flex-grow-1">
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa') ?>" id="nav-dashboard">
          <i class="bi bi-speedometer2"></i>Dashboard
        </a>
      </li>

      <li class="sidebar-label">Kelola PKPA</li>

      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa/angkatan') ?>">
          <i class="bi bi-calendar3"></i>Angkatan
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa/pendaftar') ?>">
          <i class="bi bi-people"></i>Pendaftar
        </a>
      </li>

      <li class="sidebar-label">Pengaturan</li>

      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa/pengaturan') ?>">
          <i class="bi bi-gear"></i>Pengaturan
        </a>
      </li>
      <?php if (session('user_role') === 'super_admin'): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/pkpa/users') ?>">
          <i class="bi bi-person-badge"></i>User Admin
        </a>
      </li>
      <?php endif; ?>
    </ul>

    <!-- Sidebar Footer / User -->
    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="sidebar-user-avatar">
          <i class="bi bi-person-fill"></i>
        </div>
        <div>
          <div class="sidebar-user-name"><?= esc(session('user_nama')) ?></div>
          <div class="sidebar-user-role"><?= esc(session('user_role')) ?></div>
        </div>
      </div>
      <a href="<?= base_url('admin/logout') ?>" class="btn-logout">
        <i class="bi bi-box-arrow-left"></i>Logout
      </a>
    </div>
  </nav>

  <!-- ── Main Content ── -->
  <div id="page-content-wrapper" class="flex-grow-1 d-flex flex-column min-vh-100">

    <!-- Topbar -->
    <div class="admin-topbar">
      <button class="topbar-toggle border-0 me-3 flex-shrink-0" id="sidebar-toggle" title="Toggle Sidebar">
        <i class="bi bi-list fs-5"></i>
      </button>

      <div class="flex-grow-1">
        <div class="topbar-title"><?= esc($title ?? 'Dashboard') ?></div>
        <div class="topbar-breadcrumb">
          <span class="text-muted">PERADI DPC Tangerang Raya</span>
          <i class="bi bi-chevron-right mx-1 small"></i>
          <span><?= esc($title ?? 'Dashboard') ?></span>
        </div>
      </div>

      <div class="ms-auto d-flex align-items-center gap-2">
        <a href="<?= base_url('pkpa') ?>" target="_blank"
           class="btn btn-sm d-flex align-items-center gap-1"
           style="background:var(--bg);border:1.5px solid var(--border);color:#374151;border-radius:var(--radius-sm);font-size:.8rem;font-weight:600;padding:.4rem .9rem">
          <i class="bi bi-box-arrow-up-right" style="font-size:.8rem"></i>
          <span class="d-none d-md-inline">Lihat Publik</span>
        </a>
      </div>
    </div>

    <!-- Konten -->
    <main class="admin-main flex-grow-1">
      <?= $this->renderSection('content') ?>
    </main>

    <footer class="admin-footer">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-1">
        <span>&copy; <?= date('Y') ?> PERADI DPC Tangerang Raya</span>
        <span class="text-muted" style="font-size:.72rem">Panel Admin PKPA v1.0</span>
      </div>
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
  // Sidebar toggle (desktop)
  document.getElementById('sidebar-toggle').addEventListener('click', () => {
    const wrapper = document.getElementById('wrapper');
    if (window.innerWidth < 992) {
      wrapper.classList.toggle('sidebar-open');
    } else {
      wrapper.classList.toggle('sidebar-collapsed');
    }
  });

  // Active nav — match current URL
  const currentUrl = window.location.href;
  document.querySelectorAll('#sidebar .nav-link').forEach(link => {
    if (link.href && currentUrl.startsWith(link.href) && link.href.length > BASE_URL.length + 12) {
      link.classList.add('active');
    }
    // Special case: dashboard exact match
    if (link.id === 'nav-dashboard' && (currentUrl === link.href || currentUrl === link.href + '/')) {
      link.classList.add('active');
    }
  });

  // Mobile: close sidebar on overlay click
  document.getElementById('wrapper').addEventListener('click', function(e) {
    if (window.innerWidth < 992 && this.classList.contains('sidebar-open')) {
      if (!e.target.closest('#sidebar')) {
        this.classList.remove('sidebar-open');
      }
    }
  });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
