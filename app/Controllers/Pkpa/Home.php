<?php

namespace App\Controllers\Pkpa;

use App\Controllers\BaseController;
use App\Models\PkpaBatchModel;
use App\Models\PkpaRegistrationModel;

class Home extends BaseController
{
    protected PkpaBatchModel        $batchModel;
    protected PkpaRegistrationModel $regModel;

    public function __construct()
    {
        $this->batchModel = new PkpaBatchModel();
        $this->regModel   = new PkpaRegistrationModel();
        helper(['form', 'url', 'text']);
    }

    // ── /pkpa — Halaman Info PKPA ─────────────────────────────────────
    public function index()
    {
        $batch     = $this->batchModel->getActiveBatch();
        $isOpen    = $this->batchModel->isPendaftaranOpen($batch);
        $sisaKuota = $batch ? $this->batchModel->getSisaKuota($batch['id']) : null;

        // Ambil kontak WA dari settings
        $settings  = $this->_getSettings(['wa_ribka','wa_yuni','wa_robert','wa_ruby']);

        return view('pkpa/index', [
            'title'      => 'PKPA PERADI DPC Tangerang Raya — Pendidikan Khusus Profesi Advokat',
            'batch'      => $batch,
            'isOpen'     => $isOpen,
            'sisaKuota'  => $sisaKuota,
            'settings'   => $settings,
        ]);
    }

    // ── /pkpa/cek-status ─────────────────────────────────────────────
    public function checkStatus()
    {
        $data = [
            'title'  => 'Cek Status Pendaftaran PKPA',
            'result' => null,
            'error'  => null,
        ];

        if ($this->request->getMethod() === 'post') {
            $noPendaftaran = trim($this->request->getPost('no_pendaftaran') ?? '');
            $verifikasi    = trim($this->request->getPost('verifikasi') ?? '');   // no_wa atau 4 digit terakhir NIK

            if (empty($noPendaftaran) || empty($verifikasi)) {
                $data['error'] = 'Semua field wajib diisi.';
                return view('pkpa/cek_status', $data);
            }

            $reg = $this->regModel->where('no_pendaftaran', $noPendaftaran)
                                  ->where('deleted_at IS NULL')
                                  ->orderBy('id', 'DESC')
                                  ->first();

            if (! $reg) {
                $data['error'] = 'Nomor pendaftaran tidak ditemukan.';
                return view('pkpa/cek_status', $data);
            }

            // Verifikasi identitas: cocokkan no_wa atau 4 digit akhir NIK
            $noWaMatch  = str_ends_with($reg['no_wa'], ltrim($verifikasi, '0'));
            $nikMatch   = substr($reg['nik'], -4) === $verifikasi;

            if (! $noWaMatch && ! $nikMatch) {
                $data['error'] = 'Data verifikasi tidak cocok.';
                return view('pkpa/cek_status', $data);
            }

            // Masking NIK (tampilkan hanya 4 digit terakhir)
            $reg['nik_masked'] = str_repeat('*', 12) . substr($reg['nik'], -4);
            $data['result']    = $reg;
        }

        return view('pkpa/cek_status', $data);
    }

    // ── Helper: ambil multiple settings ──────────────────────────────
    private function _getSettings(array $keys): array
    {
        $rows = db_connect()->table('pkpa_settings')
            ->whereIn('key', $keys)
            ->get()->getResultArray();

        $map = [];
        foreach ($rows as $r) {
            $map[$r['key']] = $r['value'];
        }
        return $map;
    }
}
