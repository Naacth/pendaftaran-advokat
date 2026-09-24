<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Selamat datang kembali, <?= esc(session('user_nama')) ?> 👋</p>
  </div>
  <?php if ($activeBatch): ?>
  <div class="d-flex align-items-center gap-2">
    <span class="badge" style="background:#E6FFF2;color:#0A5C36;border:1px solid #87E8B8;border-radius:50px;font-size:.75rem;padding:.35rem .85rem;font-weight:600">
      <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle"></i><?= esc($activeBatch['nama']) ?> — Aktif
    </span>
  </div>
  <?php endif; ?>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <?php $statCards = [
    ['total', 'Total Pendaftar', 'bi-people-fill', '#3b82f6', 'rgba(59,130,246,.08)', 'rgba(59,130,246,.15)'],
    ['menunggu_verifikasi', 'Menunggu Verifikasi', 'bi-hourglass-split', '#f59e0b', 'rgba(245,158,11,.08)', 'rgba(245,158,11,.15)'],
    ['diverifikasi', 'Diverifikasi', 'bi-check-circle-fill', '#10b981', 'rgba(16,185,129,.08)', 'rgba(16,185,129,.15)'],
    ['lunas', 'Pembayaran Lunas', 'bi-wallet2', '#6366f1', 'rgba(99,102,241,.08)', 'rgba(99,102,241,.15)'],
  ]; ?>
  <?php foreach ($statCards as [$key, $label, $icon, $color, $bgCard, $bgIcon]): ?>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card" style="border-color:rgba(0,0,0,.05)">
      <div style="position:absolute;top:0;right:0;width:80px;height:80px;border-radius:0 var(--radius-lg) 0 80px;background:<?= $bgCard ?>"></div>
      <div class="d-flex align-items-start justify-content-between mb-3">
        <div class="stat-label"><?= $label ?></div>
        <div class="stat-icon" style="background:<?= $bgIcon ?>;color:<?= $color ?>">
          <i class="bi <?= $icon ?>"></i>
        </div>
      </div>
      <div class="stat-value" style="color:<?= $color ?>"><?= number_format($stats[$key] ?? 0) ?></div>
      <div class="stat-trend mt-2" style="color:<?= $color ?>">
        <i class="bi bi-bar-chart-fill" style="font-size:.75rem"></i>
        <span style="color:#6b7a99;font-weight:400;font-size:.72rem">Angkatan aktif</span>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts & Info -->
<div class="row g-4">
  <!-- Chart -->
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-graph-up-arrow" style="color:var(--navy2)"></i>
          <span>Pendaftar 7 Hari Terakhir</span>
        </div>
        <?php if ($activeBatch): ?>
        <span class="badge" style="background:var(--bg);color:#374151;border:1px solid var(--border);border-radius:6px;font-size:.72rem;font-weight:600"><?= esc($activeBatch['nama']) ?></span>
        <?php endif; ?>
      </div>
      <div class="card-body p-4">
        <canvas id="regChart" height="90"></canvas>
      </div>
    </div>
  </div>

  <!-- Batch Info -->
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-calendar3" style="color:var(--navy2)"></i>
        <span>Angkatan Aktif</span>
      </div>
      <div class="card-body p-4">
        <?php if ($activeBatch): ?>
          <!-- Info rows -->
          <?php $batchItems = [
            ['Nama Angkatan', esc($activeBatch['nama']) . ' (' . esc($activeBatch['kode']) . ')'],
            ['Buka Pendaftaran', date('d M Y', strtotime($activeBatch['buka_daftar']))],
            ['Tutup Pendaftaran', date('d M Y', strtotime($activeBatch['tutup_daftar']))],
            ['Biaya', 'Rp ' . number_format($activeBatch['biaya'], 0, ',', '.')],
          ]; ?>
          <?php foreach ($batchItems as $i => [$label, $val]): ?>
          <div class="<?= $i < count($batchItems) - 1 ? 'mb-3 pb-3' : 'mb-3 pb-3' ?>" style="<?= $i < count($batchItems) - 1 ? 'border-bottom:1px solid var(--border)' : '' ?>">
            <div style="font-size:.72rem;color:#6b7a99;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.3rem"><?= $label ?></div>
            <div style="font-size:.88rem;font-weight:600;color:var(--navy)"><?= $val ?></div>
          </div>
          <?php endforeach; ?>

          <!-- Quota progress -->
          <?php if ($sisaKuota !== null): ?>
          <?php
            $terisi = $activeBatch['kuota'] - $sisaKuota;
            $pct = $activeBatch['kuota'] > 0 ? ($terisi / $activeBatch['kuota']) * 100 : 0;
            $barColor = $pct > 90 ? '#ef4444' : ($pct > 75 ? '#f59e0b' : '#10b981');
          ?>
          <div>
            <div class="d-flex justify-content-between mb-1">
              <div style="font-size:.72rem;color:#6b7a99;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Kuota Terisi</div>
              <div style="font-size:.78rem;font-weight:700;color:var(--navy)"><?= $terisi ?> / <?= $activeBatch['kuota'] ?></div>
            </div>
            <div class="progress" style="height:8px;background:var(--bg);border-radius:10px">
              <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $barColor ?>;border-radius:10px;transition:width .6s ease"></div>
            </div>
            <div style="font-size:.72rem;color:#6b7a99;margin-top:.35rem;text-align:right"><?= $sisaKuota ?> tempat tersisa</div>
          </div>
          <?php else: ?>
          <div>
            <div style="font-size:.72rem;color:#6b7a99;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.4rem">Kuota</div>
            <span class="badge" style="background:var(--bg);color:#374151;border:1px solid var(--border);border-radius:6px;font-size:.75rem;padding:.3rem .65rem">Tanpa Batas</span>
          </div>
          <?php endif; ?>
        <?php else: ?>
          <div class="text-center py-5">
            <div style="width:60px;height:60px;background:var(--bg);border-radius:50%;display:grid;place-items:center;margin:0 auto 1rem;border:2px solid var(--border)">
              <i class="bi bi-calendar-x" style="font-size:1.3rem;color:#6b7a99"></i>
            </div>
            <div style="color:#6b7a99;font-size:.88rem">Tidak ada angkatan aktif</div>
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
        label: 'Pendaftar',
        data: <?= json_encode($chartData['values'] ?? []) ?>,
        borderColor: '#1a3a6b',
        backgroundColor: (context) => {
          const chart = context.chart;
          const { ctx: c, chartArea } = chart;
          if (!chartArea) return 'transparent';
          const gradient = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
          gradient.addColorStop(0, 'rgba(26,58,107,.15)');
          gradient.addColorStop(1, 'rgba(26,58,107,.01)');
          return gradient;
        },
        borderWidth: 2.5,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#fff',
        pointBorderColor: '#1a3a6b',
        pointBorderWidth: 2.5,
        pointRadius: 5,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      interaction: { intersect: false, mode: 'index' },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0B2547',
          titleColor: 'rgba(255,255,255,.7)',
          bodyColor: '#fff',
          borderColor: 'rgba(255,255,255,.1)',
          borderWidth: 1,
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: (context) => `  ${context.parsed.y} pendaftar`
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { size: 11 }, color: '#6b7a99' }
        },
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1, font: { size: 11 }, color: '#6b7a99' },
          grid: { color: '#f0f3f9', lineWidth: 1 }
        }
      }
    }
  });
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
