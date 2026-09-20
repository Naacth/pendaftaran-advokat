<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Login Admin') ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body">

<div class="login-wrapper">
  <div class="login-card">
    <div class="text-center">
      <div class="login-logo"><i class="bi bi-scales"></i></div>
      <h4 class="fw-bold mb-1" style="color: var(--navy)">Admin PKPA</h4>
      <p class="text-muted small mb-4">PERADI DPC Tangerang Raya</p>
    </div>

    <?php if (session('error')): ?>
      <div class="alert alert-danger py-2 text-center small"><?= esc(session('error')) ?></div>
    <?php endif; ?>
    <?php if (session('success')): ?>
      <div class="alert alert-success py-2 text-center small"><?= esc(session('success')) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('admin/login') ?>" method="post">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label small fw-semibold">Email</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
          <input type="email" name="email" class="form-control border-start-0 ps-0" 
                 required autofocus value="<?= old('email') ?>">
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label small fw-semibold">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
          <input type="password" name="password" class="form-control border-start-0 ps-0" required>
        </div>
      </div>
      <button type="submit" class="btn btn-navy w-100 py-2 fw-semibold">Login</button>
    </form>
    
    <div class="text-center mt-4 pt-3 border-top">
      <a href="<?= base_url('pkpa') ?>" class="text-decoration-none small text-muted">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Halaman Publik
      </a>
    </div>
  </div>
</div>

</body>
</html>
