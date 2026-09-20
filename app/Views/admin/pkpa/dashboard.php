<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row g-3 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="stat-card">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="stat-label">Total Pendaftar</div>
        <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
      </div>
      <div class="stat-value text-primary"><?= number_format($stats['total'] ?? 0) ?></div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="stat-label">Menunggu Verifikasi</div>
        <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
      </div>
      <div class="stat-value text-warning"><?= number_format($stats['menunggu_verifikasi'] ?? 0) ?></div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="stat-label">Diverifikasi</div>
        <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle-fill"></i></div>
      </div>
      <div class="stat-value text-success"><?= number_format($stats['diverifikasi'] ?? 0) ?></div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="stat-label">Pembayaran Lunas</div>
        <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-wallet2"></i></div>
      </div>
      <div class="stat-value text-info"><?= number_format($stats['lunas'] ?? 0) ?></div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
        <h6 class="mb-0 fw-bold">Pendaftar 7 Hari Terakhir</h6>
        <?php if ($activeBatch): ?>
          <span class="badge bg-light text-dark border"><?= esc($activeBatch['nama']) ?></span>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <canvas id="regChart" height="100"></canvas>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">Info Angkatan Aktif</h6>
      </div>
      <div class="card-body">
        <?php if ($activeBatch): ?>
          <div class="mb-3">
            <label class="small text-muted mb-1 d-block">Nama Angkatan</label>
            <div class="fw-semibold"><?= esc($activeBatch['nama']) ?> (<?= esc($activeBatch['kode']) ?>)</div>
          </div>
          <div class="mb-3">
            <label class="small text-muted mb-1 d-block">Periode Pendaftaran</label>
            <div><?= date('d M Y', strtotime($activeBatch['buka_daftar'])) ?> &mdash; <?= date('d M Y', strtotime($activeBatch['tutup_daftar'])) ?></div>
          </div>
          <div class="mb-3">
            <label class="small text-muted mb-1 d-block">Biaya</label>
            <div class="text-danger fw-semibold">Rp <?= number_format($activeBatch['biaya'], 0, ',', '.') ?></div>
          </div>
          <div class="mb-0">
            <label class="small text-muted mb-1 d-block">Sisa Kuota</label>
            <?php if ($sisaKuota !== null): ?>
              <div class="progress" style="height: 20px;">
                <?php
                  $terisi = $activeBatch['kuota'] - $sisaKuota;
                  $pct = ($terisi / $activeBatch['kuota']) * 100;
                  $color = $pct > 90 ? 'bg-danger' : ($pct > 75 ? 'bg-warning' : 'bg-success');
                ?>
                <div class="progress-bar <?= $color ?>" role="progressbar" style="width: <?= $pct ?>%" 
                     aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100">
                  <?= $terisi ?> terisi
                </div>
              </div>
              <div class="small mt-1 text-end"><?= $sisaKuota ?> dari <?= $activeBatch['kuota'] ?> tersisa</div>
            <?php else: ?>
              <span class="badge bg-secondary">Tanpa Batas Kuota</span>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="text-center text-muted py-4">
            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
            Tidak ada angkatan aktif.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('regChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode($chartData['labels'] ?? []) ?>,
        datasets: [{
          label: 'Jumlah Pendaftar',
          data: <?= json_encode($chartData['values'] ?? []) ?>,
          borderColor: '#0B2A66',
          backgroundColor: 'rgba(11, 42, 102, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
      }
    });
  }
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
