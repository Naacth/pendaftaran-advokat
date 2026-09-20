<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table         = 'activity_logs';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id', 'aksi', 'target_type', 'target_id', 'keterangan', 'ip_address',
    ];

    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = false;  // tidak ada updated_at di tabel ini

    // Catat aksi admin
    public function log(
        string $aksi,
        string $targetType,
        int    $targetId,
        ?string $keterangan = null,
        ?int   $userId      = null
    ): bool {
        return $this->insert([
            'user_id'     => $userId ?? session('user_id'),
            'aksi'        => $aksi,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'keterangan'  => $keterangan,
            'ip_address'  => service('request')->getIPAddress(),
        ]) !== false;
    }
}
