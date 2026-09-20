<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nama', 'email', 'password_hash', 'role', 'is_active', 'last_login_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nama'  => 'required|max_length[150]',
        'email' => 'required|valid_email|max_length[150]|is_unique[users.email,id,{id}]',
        'role'  => 'required|in_list[super_admin,admin]',
    ];

    // Cek kredensial login
    public function verifyLogin(string $email, string $password): ?array
    {
        $user = $this->where('email', $email)->where('is_active', 1)->first();
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }

    // Update waktu login terakhir
    public function touchLastLogin(int $userId): void
    {
        $this->update($userId, ['last_login_at' => date('Y-m-d H:i:s')]);
    }
}
