<?php

namespace App\Models;

use CodeIgniter\Model;

class PkpaRegistrationModel extends Model
{
    protected $table         = 'pkpa_registrations';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'batch_id', 'no_pendaftaran', 'public_token',
        'nama_lengkap', 'nik', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'alamat', 'kota', 'no_wa', 'email',
        'asal_kampus', 'program_studi', 'gelar', 'tahun_lulus',
        'pekerjaan', 'instansi',
        'status_pendaftaran', 'status_pembayaran',
        'catatan_admin', 'verified_by', 'verified_at', 'ip_address',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'batch_id'      => 'required|integer',
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
        'tahun_lulus'   => 'required|integer|greater_than[1969]',
        'pekerjaan'     => 'permit_empty|max_length[100]',
        'instansi'      => 'permit_empty|max_length[150]',
    ];

    // ── Generate nomor pendaftaran ─────────────────────────────────────
    public function generateNoPendaftaran(string $kode): string
    {
        $prefix = 'PKPA-' . strtoupper($kode) . '-';
        $last   = $this->db->table($this->table)
            ->like('no_pendaftaran', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        $seq = 1;
        if ($last) {
            $parts = explode('-', $last->no_pendaftaran);
            $seq   = ((int) end($parts)) + 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    // ── Cek duplikat NIK dalam angkatan ───────────────────────────────
    public function isDuplicateNik(int $batchId, string $nik, ?int $excludeId = null): bool
    {
        $builder = $this->db->table($this->table)
            ->where('batch_id', $batchId)
            ->where('nik', $nik)
            ->where('deleted_at IS NULL');

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    // ── Ambil pendaftar via token publik ──────────────────────────────
    public function getByToken(string $token): ?array
    {
        return $this->where('public_token', $token)->where('deleted_at IS NULL')->first();
    }

    // ── Ambil dengan data angkatan ────────────────────────────────────
    public function getWithBatch(int $id): ?array
    {
        return $this->db->table('pkpa_registrations r')
            ->select('r.*, b.nama AS batch_nama, b.kode AS batch_kode, b.biaya AS batch_biaya')
            ->join('pkpa_batches b', 'b.id = r.batch_id')
            ->where('r.id', $id)
            ->where('r.deleted_at IS NULL')
            ->get()->getRowArray();
    }

    // ── DataTables server-side ────────────────────────────────────────
    public function datatable(array $req): array
    {
        $cols = [
            null,
            'r.no_pendaftaran',
            'r.nama_lengkap',
            'r.no_wa',
            'r.email',
            'b.nama',
            'r.status_pendaftaran',
            'r.status_pembayaran',
            'r.created_at',
        ];

        $builder = $this->db->table('pkpa_registrations r')
            ->select('r.id, r.no_pendaftaran, r.nama_lengkap, r.no_wa, r.email,
                      r.status_pendaftaran, r.status_pembayaran, r.created_at, b.nama AS batch')
            ->join('pkpa_batches b', 'b.id = r.batch_id')
            ->where('r.deleted_at IS NULL');

        if (! empty($req['batch_id']))   $builder->where('r.batch_id', (int) $req['batch_id']);
        if (! empty($req['status']))     $builder->where('r.status_pendaftaran', $req['status']);
        if (! empty($req['pembayaran'])) $builder->where('r.status_pembayaran', $req['pembayaran']);
        if (! empty($req['tgl_dari']))   $builder->where('DATE(r.created_at) >=', $req['tgl_dari']);
        if (! empty($req['tgl_sampai'])) $builder->where('DATE(r.created_at) <=', $req['tgl_sampai']);

        $recordsTotal = $builder->countAllResults(false);

        $search = trim($req['search']['value'] ?? '');
        if ($search !== '') {
            $builder->groupStart()
                ->like('r.nama_lengkap', $search)
                ->orLike('r.no_pendaftaran', $search)
                ->orLike('r.email', $search)
                ->orLike('r.no_wa', $search)
                ->orLike('r.nik', $search)
            ->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);

        $idx = (int) ($req['order'][0]['column'] ?? 8);
        $dir = ($req['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
        $builder->orderBy($cols[$idx] ?? 'r.created_at', $dir)
                ->limit((int) min($req['length'] ?? 25, 100), (int) ($req['start'] ?? 0));

        return [
            'draw'            => (int) $req['draw'],
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $builder->get()->getResultArray(),
        ];
    }

    // ── Stats untuk dashboard ─────────────────────────────────────────
    public function getStatsByBatch(int $batchId): array
    {
        $rows = $this->db->table('pkpa_registrations')
            ->select('status_pendaftaran, status_pembayaran, COUNT(*) as jumlah')
            ->where('batch_id', $batchId)
            ->where('deleted_at IS NULL')
            ->groupBy('status_pendaftaran, status_pembayaran')
            ->get()->getResultArray();

        $stats = [
            'total'               => 0,
            'menunggu_verifikasi' => 0,
            'perlu_perbaikan'     => 0,
            'diverifikasi'        => 0,
            'ditolak'             => 0,
            'lunas'               => 0,
        ];

        foreach ($rows as $row) {
            $stats['total']                        += (int) $row['jumlah'];
            $stats[$row['status_pendaftaran']]     += (int) $row['jumlah'];
            if ($row['status_pembayaran'] === 'lunas') {
                $stats['lunas'] += (int) $row['jumlah'];
            }
        }

        return $stats;
    }
}
