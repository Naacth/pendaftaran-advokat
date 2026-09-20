<?php

namespace App\Controllers\Pkpa;

use App\Controllers\BaseController;
use App\Models\PkpaBatchModel;
use App\Models\PkpaRegistrationModel;
use App\Models\PkpaDocumentModel;

class Registration extends BaseController
{
    protected PkpaBatchModel        $batchModel;
    protected PkpaRegistrationModel $regModel;
    protected PkpaDocumentModel     $docModel;

    // Direktori upload (di luar public/)
    private string $uploadPath;

    public function __construct()
    {
        $this->batchModel = new PkpaBatchModel();
        $this->regModel   = new PkpaRegistrationModel();
        $this->docModel   = new PkpaDocumentModel();
        $this->uploadPath = WRITEPATH . 'uploads/pkpa/';
        helper(['form', 'url', 'filesystem']);
    }

    // ── GET /pkpa/daftar ─────────────────────────────────────────────
    public function form()
    {
        $batch = $this->batchModel->getActiveBatch();

        if (! $batch) {
            return redirect()->to(base_url('pkpa'))
                ->with('info', 'Saat ini belum ada angkatan yang membuka pendaftaran.');
        }

        $sisaKuota = $this->batchModel->getSisaKuota($batch['id']);
        if ($sisaKuota !== null && $sisaKuota <= 0) {
            return redirect()->to(base_url('pkpa'))
                ->with('info', 'Kuota pendaftaran untuk angkatan ini sudah penuh.');
        }

        return view('pkpa/daftar', [
            'title'     => 'Form Pendaftaran PKPA — PERADI DPC Tangerang Raya',
            'batch'     => $batch,
            'validation'=> \Config\Services::validation(),
        ]);
    }

    // ── POST /pkpa/daftar ────────────────────────────────────────────
    public function store()
    {
        $batch = $this->batchModel->getActiveBatch();
        if (! $batch) {
            return redirect()->to(base_url('pkpa'))->with('error', 'Pendaftaran tidak tersedia.');
        }

        // Cek kuota
        $sisaKuota = $this->batchModel->getSisaKuota($batch['id']);
        if ($sisaKuota !== null && $sisaKuota <= 0) {
            return redirect()->to(base_url('pkpa'))->with('error', 'Kuota sudah penuh.');
        }

        // Validasi
        $rules = $this->_getValidationRules();
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        // Cek duplikat NIK
        if ($this->regModel->isDuplicateNik((int) $batch['id'], $post['nik'])) {
            return redirect()->back()->withInput()
                ->with('error', 'NIK sudah terdaftar pada angkatan ini.');
        }

        // Normalisasi no_wa
        $noWa = preg_replace('/\D/', '', $post['no_wa']);
        if (str_starts_with($noWa, '0')) $noWa = '62' . substr($noWa, 1);
        if (str_starts_with($noWa, '+')) $noWa  = ltrim($noWa, '+');

        $token          = bin2hex(random_bytes(20));
        $noPendaftaran  = $this->regModel->generateNoPendaftaran($batch['kode']);

        $regData = [
            'batch_id'      => $batch['id'],
            'no_pendaftaran'=> $noPendaftaran,
            'public_token'  => $token,
            'nama_lengkap'  => $post['nama_lengkap'],
            'nik'           => $post['nik'],
            'tempat_lahir'  => $post['tempat_lahir'],
            'tanggal_lahir' => $post['tanggal_lahir'],
            'jenis_kelamin' => $post['jenis_kelamin'],
            'alamat'        => $post['alamat'],
            'kota'          => $post['kota'],
            'no_wa'         => $noWa,
            'email'         => $post['email'],
            'asal_kampus'   => $post['asal_kampus'],
            'program_studi' => $post['program_studi'],
            'gelar'         => $post['gelar'],
            'tahun_lulus'   => (int) $post['tahun_lulus'],
            'pekerjaan'     => $post['pekerjaan'] ?? null,
            'instansi'      => $post['instansi'] ?? null,
            'ip_address'    => $this->request->getIPAddress(),
        ];

        $this->regModel->insert($regData);
        $regId = $this->regModel->getInsertID();

        // Upload dokumen
        $this->_handleUploads($regId);

        return redirect()->to(base_url('pkpa/daftar/sukses/' . $token))
            ->with('success', 'Pendaftaran berhasil dikirim!');
    }

    // ── GET /pkpa/daftar/sukses/{token} ──────────────────────────────
    public function success(string $token)
    {
        $reg = $this->regModel->getByToken($token);
        if (! $reg) {
            return redirect()->to(base_url('pkpa'))->with('error', 'Halaman tidak ditemukan.');
        }

        $batch    = $this->batchModel->find($reg['batch_id']);
        $settings = $this->_getWaSettings();

        return view('pkpa/sukses', [
            'title'    => 'Pendaftaran Berhasil — PKPA PERADI DPC Tangerang Raya',
            'reg'      => $reg,
            'batch'    => $batch,
            'settings' => $settings,
        ]);
    }

    // ── GET /pkpa/perbaikan/{token} ───────────────────────────────────
    public function edit(string $token)
    {
        $reg = $this->regModel->getByToken($token);
        if (! $reg || $reg['status_pendaftaran'] !== 'perlu_perbaikan') {
            return redirect()->to(base_url('pkpa'))
                ->with('error', 'Link perbaikan tidak valid atau sudah tidak aktif.');
        }

        $docs  = $this->docModel->getByRegistration($reg['id']);
        $batch = $this->batchModel->find($reg['batch_id']);

        return view('pkpa/perbaikan', [
            'title'      => 'Perbaikan Data Pendaftaran PKPA',
            'reg'        => $reg,
            'batch'      => $batch,
            'docs'       => $docs,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // ── POST /pkpa/perbaikan/{token} ──────────────────────────────────
    public function update(string $token)
    {
        $reg = $this->regModel->getByToken($token);
        if (! $reg || $reg['status_pendaftaran'] !== 'perlu_perbaikan') {
            return redirect()->to(base_url('pkpa'))->with('error', 'Aksi tidak valid.');
        }

        // Update status ke menunggu_verifikasi kembali
        $this->regModel->update($reg['id'], [
            'status_pendaftaran' => 'menunggu_verifikasi',
            'catatan_admin'      => null,
        ]);

        // Upload ulang dokumen jika ada
        $this->_handleUploads($reg['id']);

        return redirect()->to(base_url('pkpa/daftar/sukses/' . $token))
            ->with('success', 'Data perbaikan berhasil dikirim. Panitia akan memverifikasi kembali.');
    }

    // ── Upload helper ─────────────────────────────────────────────────
    private function _handleUploads(int $regId): void
    {
        $fileFields = [
            'pas_foto'  => 'pas_foto',
            'ktp'       => 'ktp',
            'ijazah'    => 'ijazah',
            'transkrip' => 'transkrip',
        ];

        $dir = $this->uploadPath . $regId . '/';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ($fileFields as $field => $jenis) {
            $file = $this->request->getFile($field);
            if (! $file || ! $file->isValid() || $file->hasMoved()) continue;

            $randomName = $file->getRandomName();
            $file->move($dir, $randomName);

            $this->docModel->insert([
                'registration_id' => $regId,
                'jenis'           => $jenis,
                'nama_asli'       => $file->getClientName(),
                'nama_file'       => $randomName,
                'mime'            => $file->getClientMimeType(),
                'ukuran'          => $file->getSize(),
            ]);
        }
    }

    private function _getWaSettings(): array
    {
        $rows = db_connect()->table('pkpa_settings')
            ->whereIn('key', ['wa_ribka','wa_yuni','wa_robert','wa_ruby'])
            ->get()->getResultArray();
        $map = [];
        foreach ($rows as $r) $map[$r['key']] = $r['value'];
        return $map;
    }

    // ── Validation rules ──────────────────────────────────────────────
    private function _getValidationRules(): array
    {
        return [
            'nama_lengkap'  => 'required|min_length[3]|max_length[150]',
            'nik'           => 'required|exact_length[16]|numeric',
            'tempat_lahir'  => 'required|max_length[100]',
            'tanggal_lahir' => 'required|valid_date[Y-m-d]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'alamat'        => 'required|max_length[500]',
            'kota'          => 'required|max_length[100]',
            'no_wa'         => 'required|max_length[20]',
            'email'         => 'required|valid_email|max_length[150]',
            'asal_kampus'   => 'required|max_length[150]',
            'program_studi' => 'required|max_length[150]',
            'gelar'         => 'required|max_length[50]',
            'tahun_lulus'   => 'required|integer|greater_than[1969]|less_than_equal_to[' . date('Y') . ']',
            'pekerjaan'     => 'permit_empty|max_length[100]',
            'instansi'      => 'permit_empty|max_length[150]',
            'pas_foto'      => 'uploaded[pas_foto]|max_size[pas_foto,1024]|is_image[pas_foto]|mime_in[pas_foto,image/jpg,image/jpeg,image/png]',
            'ktp'           => 'uploaded[ktp]|max_size[ktp,2048]|mime_in[ktp,image/jpg,image/jpeg,image/png,application/pdf]',
            'ijazah'        => 'uploaded[ijazah]|max_size[ijazah,2048]|mime_in[ijazah,application/pdf,image/jpg,image/jpeg,image/png]',
            'transkrip'     => 'permit_empty|max_size[transkrip,2048]|mime_in[transkrip,application/pdf]',
            'pernyataan'    => 'required|in_list[1]',
        ];
    }

    // ── Form Perbaikan Data (Jika status perlu_perbaikan) ────────────────
    public function perbaikan(string $token)
    {
        $reg = $this->regModel->where('public_token', $token)->first();

        if (! $reg || $reg['status_pendaftaran'] !== 'perlu_perbaikan') {
            return redirect()->to(base_url('pkpa/cek-status'))
                ->with('error', 'Token perbaikan tidak valid atau status bukan Perlu Perbaikan.');
        }

        $batch = $this->batchModel->find($reg['batch_id']);
        $docs  = $this->docModel->where('registration_id', $reg['id'])->findAll();
        
        $docsMap = [];
        foreach ($docs as $d) $docsMap[$d['jenis_dokumen']] = $d;

        return view('pkpa/perbaikan', [
            'title'   => 'Perbaikan Data Pendaftaran',
            'batch'   => $batch,
            'reg'     => $reg,
            'docsMap' => $docsMap,
        ]);
    }

    public function updatePerbaikan(string $token)
    {
        $reg = $this->regModel->where('public_token', $token)->first();

        if (! $reg || $reg['status_pendaftaran'] !== 'perlu_perbaikan') {
            return redirect()->to(base_url('pkpa/cek-status'))
                ->with('error', 'Sesi perbaikan tidak valid.');
        }

        $rules = [
            'nama_lengkap'  => 'required|min_length[3]|max_length[150]',
            'nik'           => 'required|exact_length[16]|numeric',
            'tempat_lahir'  => 'required|max_length[100]',
            'tanggal_lahir' => 'required|valid_date',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'alamat'        => 'required|max_length[500]',
            'kota'          => 'required|max_length[100]',
            'no_wa'         => 'required|max_length[20]',
            'email'         => 'required|valid_email|max_length[150]',
            'asal_kampus'   => 'required|max_length[150]',
            'program_studi' => 'required|max_length[150]',
            'gelar'         => 'required|max_length[50]',
            'tahun_lulus'   => 'required|numeric|exact_length[4]',
        ];

        // Validasi opsional file jika diisi (opsional karena file lama masih ada)
        $fileRules = [
            'pas_foto'  => 'max_size[pas_foto,1024]|is_image[pas_foto]|ext_in[pas_foto,png,jpg,jpeg]',
            'ktp'       => 'max_size[ktp,2048]|ext_in[ktp,png,jpg,jpeg,pdf]',
            'ijazah'    => 'max_size[ijazah,2048]|ext_in[ijazah,png,jpg,jpeg,pdf]',
            'transkrip' => 'max_size[transkrip,2048]|ext_in[transkrip,pdf]',
        ];

        // Gabungkan rules jika file diupload
        foreach ($fileRules as $key => $rule) {
            $file = $this->request->getFile($key);
            if ($file && $file->isValid()) {
                $rules[$key] = $rule;
            }
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->transStart();

        $updateData = [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'nik'           => $this->request->getPost('nik'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat'        => $this->request->getPost('alamat'),
            'kota'          => $this->request->getPost('kota'),
            'no_wa'         => $this->request->getPost('no_wa'),
            'email'         => $this->request->getPost('email'),
            'asal_kampus'   => $this->request->getPost('asal_kampus'),
            'program_studi' => $this->request->getPost('program_studi'),
            'gelar'         => $this->request->getPost('gelar'),
            'tahun_lulus'   => $this->request->getPost('tahun_lulus'),
            'pekerjaan'     => $this->request->getPost('pekerjaan') ?: null,
            'instansi'      => $this->request->getPost('instansi') ?: null,
            // Kembalikan status ke menunggu_verifikasi
            'status_pendaftaran' => 'menunggu_verifikasi',
        ];

        $this->regModel->update($reg['id'], $updateData);

        // Handle upload dokumen yang baru (timpa yang lama)
        $this->uploadDocs($reg['id'], [
            'pas_foto', 'ktp', 'ijazah', 'transkrip'
        ], true); // pass true for update mode

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan perbaikan.');
        }

        return redirect()->to(base_url('pkpa/cek-status'))->with('success', 'Perbaikan data berhasil disimpan. Silakan tunggu verifikasi ulang.');
    }
}
