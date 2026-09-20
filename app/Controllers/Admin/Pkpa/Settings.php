<?php

namespace App\Controllers\Admin\Pkpa;

use App\Controllers\BaseController;

class Settings extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function index()
    {
        $rows = db_connect()->table('pkpa_settings')->orderBy('id', 'ASC')->get()->getResultArray();
        $settings = [];
        foreach ($rows as $r) $settings[$r['key']] = $r;

        return view('admin/pkpa/settings', [
            'title'    => 'Pengaturan PKPA',
            'settings' => $settings,
        ]);
    }

    public function save()
    {
        $db  = db_connect();
        $now = date('Y-m-d H:i:s');

        // Ambil semua key yang boleh diubah
        $editableKeys = ['wa_ribka', 'wa_yuni', 'wa_robert', 'wa_ruby',
                         'rekening_bank', 'rekening_nomor', 'rekening_atas_nama',
                         'teks_pernyataan', 'teks_syarat'];

        foreach ($editableKeys as $key) {
            $val = $this->request->getPost($key);
            if ($val === null) continue;

            $db->table('pkpa_settings')
                ->where('key', $key)
                ->set(['value' => trim($val), 'updated_at' => $now])
                ->update();
        }

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Pengaturan berhasil disimpan.',
            'csrf_hash' => csrf_hash(),
        ]);
    }
}
