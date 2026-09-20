<?php

namespace App\Controllers\Admin\Pkpa;

use App\Controllers\BaseController;
use App\Models\PkpaBatchModel;

class Batches extends BaseController
{
    protected PkpaBatchModel $batchModel;

    public function __construct()
    {
        $this->batchModel = new PkpaBatchModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        return view('admin/pkpa/batches', [
            'title' => 'Kelola Angkatan PKPA',
        ]);
    }

    // ── DataTables AJAX ───────────────────────────────────────────────
    public function datatable()
    {
        $req  = $this->request->getPost();
        $data = $this->batchModel->datatable($req);

        $start = (int) ($this->request->getPost('start') ?? 0);

        // Tambah kolom aksi & badge
        foreach ($data['data'] as $index => &$row) {
            $row['DT_RowIndex'] = $start + $index + 1;
            
            $row['status_badge'] = $row['is_active']
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-secondary">Nonaktif</span>';

            $row['aksi'] = '
                <button class="btn btn-sm btn-warning btn-edit-batch me-1"
                    data-id="' . esc($row['id']) . '"
                    data-kode="' . esc($row['kode']) . '"
                    data-nama="' . esc($row['nama']) . '"
                    data-buka="' . esc($row['buka_daftar']) . '"
                    data-tutup="' . esc($row['tutup_daftar']) . '"
                    data-mulai="' . esc($row['tanggal_mulai'] ?? '') . '"
                    data-selesai="' . esc($row['tanggal_selesai'] ?? '') . '"
                    data-kuota="' . esc($row['kuota'] ?? '') . '"
                    data-biaya="' . esc($row['biaya']) . '"
                    data-aktif="' . esc($row['is_active']) . '"
                    title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-hapus-batch"
                    data-id="' . esc($row['id']) . '"
                    data-nama="' . esc($row['nama']) . '"
                    title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>';
        }

        return $this->response->setJSON(array_merge($data, [
            'csrf_hash' => csrf_hash(),
        ]));
    }

    // ── Simpan (insert/update) ────────────────────────────────────────
    public function save()
    {
        $id = (int) $this->request->getPost('id');

        $data = [
            'kode'            => strtoupper(trim($this->request->getPost('kode'))),
            'nama'            => trim($this->request->getPost('nama')),
            'buka_daftar'     => $this->request->getPost('buka_daftar'),
            'tutup_daftar'    => $this->request->getPost('tutup_daftar'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai') ?: null,
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai') ?: null,
            'kuota'           => $this->request->getPost('kuota') ?: null,
            'biaya'           => $this->request->getPost('biaya'),
            'is_active'       => (int) (bool) $this->request->getPost('is_active'),
        ];

        // Jika set aktif, nonaktifkan semua yang lain
        if ($data['is_active']) {
            $this->batchModel->where('id !=', $id)->set(['is_active' => 0])->update();
        }

        $ok = $id
            ? $this->batchModel->update($id, $data)
            : (bool) $this->batchModel->insert($data);

        return $this->response->setJSON([
            'success'   => $ok,
            'message'   => $ok ? 'Angkatan berhasil disimpan.' : implode(', ', $this->batchModel->errors()),
            'csrf_hash' => csrf_hash(),
        ]);
    }

    // ── Soft delete ────────────────────────────────────────────────────
    public function delete(int $id)
    {
        $batch = $this->batchModel->find($id);
        if (! $batch) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan.', 'csrf_hash' => csrf_hash()]);
        }

        $ok = $this->batchModel->delete($id);
        return $this->response->setJSON([
            'success'   => $ok,
            'message'   => $ok ? 'Angkatan "' . $batch['nama'] . '" berhasil dihapus.' : 'Gagal menghapus.',
            'csrf_hash' => csrf_hash(),
        ]);
    }
}
