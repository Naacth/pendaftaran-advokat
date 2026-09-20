<?php

namespace App\Models;

use CodeIgniter\Model;

class PkpaBatchModel extends Model
{
    protected $table         = 'pkpa_batches';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'kode', 'nama', 'buka_daftar', 'tutup_daftar',
        'tanggal_mulai', 'tanggal_selesai', 'kuota',
        'biaya', 'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'kode'         => 'required|max_length[30]|is_unique[pkpa_batches.kode,id,{id}]',
        'nama'         => 'required|max_length[150]',
        'buka_daftar'  => 'required|valid_date[Y-m-d]',
        'tutup_daftar' => 'required|valid_date[Y-m-d]',
        'kuota'        => 'permit_empty|integer|greater_than[0]',
        'biaya'        => 'required|decimal',
    ];

    protected $validationMessages = [
        'kode' => ['is_unique' => 'Kode angkatan sudah digunakan.'],
    ];

    // ── Public: ambil angkatan aktif (tampil di publik, meski belum buka) ─
    public function getActiveBatch(): ?array
    {
        return $this->where('is_active', 1)
                    ->where('deleted_at IS NULL')
                    ->first();
    }

    // ── Public: cek apakah pendaftaran angkatan sedang terbuka ───────────
    public function isPendaftaranOpen(?array $batch): bool
    {
        if (! $batch) return false;
        $today = date('Y-m-d');
        return $batch['buka_daftar'] <= $today && $batch['tutup_daftar'] >= $today;
    }

    // ── Admin: sisa kuota angkatan ──────────────────────────────────────
    public function getSisaKuota(int $batchId): ?int
    {
        $batch = $this->find($batchId);
        if (! $batch || $batch['kuota'] === null) {
            return null; // tanpa batas
        }

        $terisi = db_connect()
            ->table('pkpa_registrations')
            ->where('batch_id', $batchId)
            ->whereNotIn('status_pendaftaran', ['ditolak'])
            ->where('deleted_at IS NULL')
            ->countAllResults();

        return max(0, $batch['kuota'] - $terisi);
    }

    // ── DataTables server-side ──────────────────────────────────────────
    public function datatable(array $req): array
    {
        $cols = [null, 'kode', 'nama', 'buka_daftar', 'tutup_daftar', 'kuota', 'biaya', 'is_active'];

        $builder = $this->db->table('pkpa_batches')
            ->select('id, kode, nama, buka_daftar, tutup_daftar, tanggal_mulai, tanggal_selesai, kuota, biaya, is_active, created_at')
            ->where('deleted_at', null);

        $recordsTotal = $builder->countAllResults(false);

        $search = trim($req['search']['value'] ?? '');
        if ($search !== '') {
            $builder->groupStart()
                ->like('kode', $search)
                ->orLike('nama', $search)
            ->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);

        $idx = (int) ($req['order'][0]['column'] ?? 1);
        $dir = ($req['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
        $builder->orderBy($cols[$idx] ?? 'id', $dir)
                ->limit((int) min($req['length'] ?? 10, 100), (int) ($req['start'] ?? 0));

        return [
            'draw'            => (int) $req['draw'],
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $builder->get()->getResultArray(),
        ];
    }
}
