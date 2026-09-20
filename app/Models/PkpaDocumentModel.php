<?php

namespace App\Models;

use CodeIgniter\Model;

class PkpaDocumentModel extends Model
{
    protected $table         = 'pkpa_documents';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'registration_id', 'jenis', 'nama_asli',
        'nama_file', 'mime', 'ukuran', 'status', 'catatan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil semua dokumen milik satu pendaftaran
    public function getByRegistration(int $registrationId): array
    {
        return $this->where('registration_id', $registrationId)->findAll();
    }

    // Cek apakah semua dokumen wajib sudah diunggah
    public function hasRequiredDocs(int $registrationId): bool
    {
        $required = ['pas_foto', 'ktp', 'ijazah'];
        foreach ($required as $jenis) {
            $exists = $this->where('registration_id', $registrationId)
                           ->where('jenis', $jenis)
                           ->countAllResults();
            if (! $exists) return false;
        }
        return true;
    }

    // Update status per-dokumen
    public function updateStatus(int $docId, string $status, ?string $catatan = null): bool
    {
        return $this->update($docId, [
            'status'  => $status,
            'catatan' => $catatan,
        ]);
    }
}
