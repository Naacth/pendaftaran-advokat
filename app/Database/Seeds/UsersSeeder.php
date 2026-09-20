<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder akun admin awal
 * Super Admin default: admin@peraditangerang.id / Admin@PERADI2026
 * WAJIB ganti password setelah pertama login!
 */
class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'nama'          => 'Super Admin PERADI',
                'email'         => 'admin@peraditangerang.id',
                // password: Admin@PERADI2026 — ganti segera setelah deploy!
                'password_hash' => password_hash('Admin@PERADI2026', PASSWORD_BCRYPT),
                'role'          => 'super_admin',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        // Hindari duplikat jika seeder dijalankan ulang
        foreach ($data as $row) {
            $exists = $this->db->table('users')
                ->where('email', $row['email'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('users')->insert($row);
            }
        }
    }
}
