<?php

namespace App\Controllers\Admin\Pkpa;

use App\Controllers\BaseController;
use App\Models\PkpaBatchModel;
use App\Models\PkpaRegistrationModel;
use App\Models\PkpaDocumentModel;
use App\Models\ActivityLogModel;

class Registrations extends BaseController
{
    protected PkpaBatchModel        $batchModel;
    protected PkpaRegistrationModel $regModel;
    protected PkpaDocumentModel     $docModel;
    protected ActivityLogModel      $logModel;

    private string $uploadPath;

    public function __construct()
    {
        $this->batchModel = new PkpaBatchModel();
        $this->regModel   = new PkpaRegistrationModel();
        $this->docModel   = new PkpaDocumentModel();
        $this->logModel   = new ActivityLogModel();
        $this->uploadPath = WRITEPATH . 'uploads/pkpa/';
        helper(['url', 'form']);
    }

    // ── Daftar Pendaftar ──────────────────────────────────────────────
    public function index()
    {
        $batches = $this->batchModel->orderBy('id', 'DESC')->findAll();
        return view('admin/pkpa/registrations', [
            'title'   => 'Daftar Pendaftar PKPA',
            'batches' => $batches,
        ]);
    }

    // ── DataTables AJAX ───────────────────────────────────────────────
    public function datatable()
    {
        $req  = $this->request->getPost();
        $data = $this->regModel->datatable($req);

        $statusColors = [
            'menunggu_verifikasi' => 'warning',
            'perlu_perbaikan'     => 'info',
            'diverifikasi'        => 'success',
            'ditolak'             => 'danger',
        ];
        $bayarColors  = [
            'belum_bayar'         => 'secondary',
            'menunggu_konfirmasi' => 'warning',
            'lunas'               => 'success',
        ];

        $start = (int) ($this->request->getPost('start') ?? 0);

        foreach ($data['data'] as $index => &$row) {
            $row['DT_RowIndex'] = $start + $index + 1;

            $statusColor = $statusColors[$row['status_pendaftaran']] ?? 'secondary';
            $bayarColor  = $bayarColors[$row['status_pembayaran']]   ?? 'secondary';

            $row['status_pendaftaran_badge'] = '<span class="badge bg-' . $statusColor . '">' . esc(str_replace('_', ' ', $row['status_pendaftaran'])) . '</span>';
            $row['status_pembayaran_badge']  = '<span class="badge bg-' . $bayarColor  . '">' . esc(str_replace('_', ' ', $row['status_pembayaran']))  . '</span>';
            $row['created_at_fmt']           = date('d/m/Y H:i', strtotime($row['created_at']));

            $row['aksi'] = '
                <a href="' . base_url('admin/pkpa/pendaftar/' . $row['id']) . '" class="btn btn-sm btn-primary me-1" title="Detail">
                    <i class="bi bi-eye"></i>
                </a>
                <button class="btn btn-sm btn-danger btn-hapus-reg"
                    data-id="' . esc($row['id']) . '"
                    data-nama="' . esc($row['nama_lengkap']) . '"
                    title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>';
        }

        return $this->response->setJSON(array_merge($data, [
            'csrf_hash' => csrf_hash(),
        ]));
    }

    // ── Detail Pendaftar ──────────────────────────────────────────────
    public function show(int $id)
    {
        $reg = $this->regModel->getWithBatch($id);
        if (! $reg) {
            return redirect()->to(base_url('admin/pkpa/pendaftar'))
                ->with('error', 'Data pendaftar tidak ditemukan.');
        }

        $docs = $this->docModel->getByRegistration($id);

        return view('admin/pkpa/detail', [
            'title' => 'Detail Pendaftar — ' . $reg['nama_lengkap'],
            'reg'   => $reg,
            'docs'  => $docs,
        ]);
    }

    // ── Ubah Status Pendaftaran ───────────────────────────────────────
    public function changeStatus(int $id)
    {
        $reg = $this->regModel->find($id);
        if (! $reg) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan.', 'csrf_hash' => csrf_hash()]);
        }

        $status    = $this->request->getPost('status');
        $catatan   = $this->request->getPost('catatan');
        $allowedStatus = ['diverifikasi', 'perlu_perbaikan', 'ditolak', 'menunggu_verifikasi'];

        if (! in_array($status, $allowedStatus, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status tidak valid.', 'csrf_hash' => csrf_hash()]);
        }

        $updateData = [
            'status_pendaftaran' => $status,
            'catatan_admin'      => $catatan ?: null,
        ];

        if ($status === 'diverifikasi') {
            $updateData['verified_by'] = session('user_id');
            $updateData['verified_at'] = date('Y-m-d H:i:s');
        }

        $ok = $this->regModel->update($id, $updateData);

        if ($ok) {
            $this->logModel->log($status, 'pkpa_registrations', $id, $catatan);
        }

        return $this->response->setJSON([
            'success'   => $ok,
            'message'   => $ok ? 'Status berhasil diperbarui.' : 'Gagal memperbarui status.',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    // ── Konfirmasi Pembayaran ─────────────────────────────────────────
    public function confirmPayment(int $id)
    {
        $reg = $this->regModel->find($id);
        if (! $reg) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan.', 'csrf_hash' => csrf_hash()]);
        }

        $ok = $this->regModel->update($id, ['status_pembayaran' => 'lunas']);

        if ($ok) {
            $this->logModel->log('konfirmasi_bayar', 'pkpa_registrations', $id);
        }

        return $this->response->setJSON([
            'success'   => $ok,
            'message'   => $ok ? 'Pembayaran berhasil dikonfirmasi.' : 'Gagal mengkonfirmasi.',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    // ── Soft Delete ───────────────────────────────────────────────────
    public function delete(int $id)
    {
        $reg = $this->regModel->find($id);
        if (! $reg) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan.', 'csrf_hash' => csrf_hash()]);
        }

        $ok = $this->regModel->delete($id);
        if ($ok) {
            $this->logModel->log('hapus', 'pkpa_registrations', $id, 'Soft delete oleh admin');
        }

        return $this->response->setJSON([
            'success'   => $ok,
            'message'   => $ok ? 'Data pendaftar "' . $reg['nama_lengkap'] . '" berhasil dihapus.' : 'Gagal menghapus.',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    // ── Serve dokumen (proteksi: hanya admin) ────────────────────────
    public function document(int $regId, int $docId)
    {
        $doc = $this->docModel->find($docId);
        if (! $doc || $doc['registration_id'] !== $regId) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan.');
        }

        $path = $this->uploadPath . $regId . '/' . $doc['nama_file'];
        if (! file_exists($path)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan di server.');
        }

        return $this->response
            ->setHeader('Content-Type', $doc['mime'])
            ->setHeader('Content-Disposition', 'inline; filename="' . esc($doc['nama_asli']) . '"')
            ->setBody(file_get_contents($path));
    }

    // ── Ekspor CSV ────────────────────────────────────────────────────
    public function export()
    {
        $batchId   = $this->request->getGet('batch_id');
        $status    = $this->request->getGet('status');
        $pembayaran= $this->request->getGet('pembayaran');

        $builder = $this->regModel->db->table('pkpa_registrations r')
            ->select('r.no_pendaftaran, r.nama_lengkap, r.nik, r.tempat_lahir, r.tanggal_lahir,
                      r.jenis_kelamin, r.alamat, r.kota, r.no_wa, r.email,
                      r.asal_kampus, r.program_studi, r.gelar, r.tahun_lulus,
                      r.pekerjaan, r.instansi,
                      r.status_pendaftaran, r.status_pembayaran, r.catatan_admin,
                      r.created_at, b.nama AS angkatan')
            ->join('pkpa_batches b', 'b.id = r.batch_id')
            ->where('r.deleted_at IS NULL');

        if ($batchId)   $builder->where('r.batch_id', (int) $batchId);
        if ($status)    $builder->where('r.status_pendaftaran', $status);
        if ($pembayaran)$builder->where('r.status_pembayaran', $pembayaran);

        $rows = $builder->orderBy('r.id', 'ASC')->get()->getResultArray();

        $filename = 'pendaftar-pkpa-' . date('Ymd-His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF"); // BOM UTF-8

        if (! empty($rows)) {
            fputcsv($out, array_keys($rows[0]));
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
        }

        fclose($out);
        exit;
    }
}
