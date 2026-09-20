<?php

namespace App\Controllers\Admin\Pkpa;

use App\Controllers\BaseController;
use App\Models\PkpaBatchModel;
use App\Models\PkpaRegistrationModel;

class Dashboard extends BaseController
{
    protected PkpaBatchModel        $batchModel;
    protected PkpaRegistrationModel $regModel;

    public function __construct()
    {
        $this->batchModel = new PkpaBatchModel();
        $this->regModel   = new PkpaRegistrationModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        // Semua angkatan untuk pilihan statistik
        $batches = $this->batchModel->orderBy('id', 'DESC')->findAll();

        // Statistik per angkatan aktif (default)
        $activeBatch = $this->batchModel->where('is_active', 1)->first();
        $stats       = [];
        $sisaKuota   = null;

        if ($activeBatch) {
            $stats     = $this->regModel->getStatsByBatch($activeBatch['id']);
            $sisaKuota = $this->batchModel->getSisaKuota($activeBatch['id']);
        }

        // Pendaftar 7 hari terakhir (untuk grafik sederhana)
        $chartData = $this->_getChartData($activeBatch['id'] ?? null);

        return view('admin/pkpa/dashboard', [
            'title'       => 'Dashboard Admin PKPA',
            'batches'     => $batches,
            'activeBatch' => $activeBatch,
            'stats'       => $stats,
            'sisaKuota'   => $sisaKuota,
            'chartData'   => $chartData,
        ]);
    }

    private function _getChartData(?int $batchId): array
    {
        if (! $batchId) return ['labels' => [], 'values' => []];

        $rows = db_connect()->table('pkpa_registrations')
            ->select('DATE(created_at) AS tgl, COUNT(*) AS jml')
            ->where('batch_id', $batchId)
            ->where('deleted_at IS NULL')
            ->where('created_at >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('DATE(created_at)')
            ->orderBy('tgl', 'ASC')
            ->get()->getResultArray();

        $labels = [];
        $values = [];
        foreach ($rows as $r) {
            $labels[] = date('d M', strtotime($r['tgl']));
            $values[] = (int) $r['jml'];
        }
        return ['labels' => $labels, 'values' => $values];
    }
}
