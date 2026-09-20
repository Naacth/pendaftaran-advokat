<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'FK ke tabel users (admin); NULL jika aksi sistem',
            ],
            'aksi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'verifikasi, tolak, hapus, perbaikan, konfirmasi_bayar, dll',
            ],
            'target_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'Nama tabel/entitas yang dimodifikasi, mis: pkpa_registrations',
            ],
            'target_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID record yang dimodifikasi',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Detail tambahan (mis. alasan penolakan)',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['target_type', 'target_id'], false, false, 'idx_target');
        $this->forge->addKey('user_id', false, false, 'idx_user');
        $this->forge->addKey('created_at', false, false, 'idx_created');

        $this->forge->createTable('activity_logs', true, [
            'ENGINE'  => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('activity_logs', true);
    }
}
