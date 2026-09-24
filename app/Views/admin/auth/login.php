<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-name" content="<?= csrf_token() ?>">
  <meta name="csrf-hash" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'Login Admin — PKPA PERADI') ?></title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body">

<div class="login-wrapper">
  <!-- Decorative elements -->
  <div style="position:absolute;top:10%;left:5%;width:1px;height:60px;background:linear-gradient(to bottom,transparent,rgba(212,168,67,.3),transparent);z-index:0"></div>
  <div style="position:absolute;bottom:10%;right:8%;width:1px;height:80px;background:linear-gradient(to bottom,transparent,rgba(255,255,255,.1),transparent);z-index:0"></div>

  <div class="login-card">
    <!-- Logo & Title -->
    <div class="text-center mb-4">
      <div class="login-logo">
        <i class="bi bi-scales"></i>
      </div>
      <h4 class="login-title mb-1">Panel Admin</h4>
      <p class="login-subtitle">PKPA PERADI DPC Tangerang Raya</p>
    </div>

    <!-- Alerts -->
    <?php if (session('error')): ?>
    <div class="alert border-0 mb-3 py-2 px-3 text-center small"
         style="background:#FFF1F0;color:#820014;border-radius:10px;border-left:3px solid #ef4444 !important">
      <i class="bi bi-exclamation-triangle-fill me-1"></i><?= esc(session('error')) ?>
    </div>
    <?php endif; ?>
    <?php if (session('success')): ?>
    <div class="alert border-0 mb-3 py-2 px-3 text-center small"
         style="background:#E6FFF2;color:#0A5C36;border-radius:10px">
      <i class="bi bi-check-circle-fill me-1"></i><?= esc(session('success')) ?>
    </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= base_url('admin/login') ?>" method="post">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.83rem;color:#374151">Alamat Email</label>
        <div class="input-group">
          <span class="input-group-text" style="background:var(--bg);border:1.5px solid var(--border);border-right:none;border-radius:var(--radius-sm) 0 0 var(--radius-sm)">
            <i class="bi bi-envelope" style="color:#6b7a99"></i>
          </span>
          <input type="email" name="email"
                 class="form-control"
                 style="border-left:none;border-radius:0 var(--radius-sm) var(--radius-sm) 0"
                 required autofocus value="<?= old('email') ?>"
                 placeholder="admin@peradi.id">
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold" style="font-size:.83rem;color:#374151">Password</label>
        <div class="input-group">
          <span class="input-group-text" style="background:var(--bg);border:1.5px solid var(--border);border-right:none;border-radius:var(--radius-sm) 0 0 var(--radius-sm)">
            <i class="bi bi-lock" style="color:#6b7a99"></i>
          </span>
          <input type="password" name="password" id="password"
                 class="form-control"
                 style="border-left:none;border-right:none;border-radius:0"
                 required placeholder="••••••••">
          <button type="button" class="input-group-text btn-toggle-pw"
                  style="background:var(--bg);border:1.5px solid var(--border);border-left:none;border-radius:0 var(--radius-sm) var(--radius-sm) 0;cursor:pointer"
                  id="toggle-pw" title="Tampilkan password">
            <i class="bi bi-eye" style="color:#6b7a99" id="pw-icon"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-navy w-100 py-2 fw-bold" style="border-radius:var(--radius-sm);font-size:.92rem">
        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Panel Admin
      </button>
    </form>

    <div class="text-center mt-4 pt-3" style="border-top:1px solid var(--border)">
      <a href="<?= base_url('pkpa') ?>" class="text-decoration-none small" style="color:#6b7a99">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Halaman Publik
      </a>
    </div>
  </div>
</div>

<script>
  document.getElementById('toggle-pw').addEventListener('click', function() {
    const pw = document.getElementById('password');
    const icon = document.getElementById('pw-icon');
    if (pw.type === 'password') {
      pw.type = 'text';
      icon.className = 'bi bi-eye-slash';
    } else {
      pw.type = 'password';
      icon.className = 'bi bi-eye';
    }
  });
</script>
</body>
</html>
