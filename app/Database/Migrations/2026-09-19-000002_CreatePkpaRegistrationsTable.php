<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePkpaRegistrationsTable extends Migration
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
            'batch_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'no_pendaftaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
                'comment'    => 'Format: PKPA-{KODEANGKATAN}-{0001}',
            ],
            'public_token' => [
                'type'       => 'CHAR',
                'constraint' => 40,
                'null'       => false,
                'comment'    => 'Token untuk URL sukses & perbaikan (bukan ID publik)',
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'nik' => [
                'type'       => 'CHAR',
                'constraint' => 16,
                'null'       => false,
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => false,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'kota' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'no_wa' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'comment'    => 'Dinormalisasi ke format 62...',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'asal_kampus' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'program_studi' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'gelar' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'tahun_lulus' => [
                'type'       => 'SMALLINT',
                'constraint' => 4,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pekerjaan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'instansi' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'status_pendaftaran' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'menunggu_verifikasi',
                    'perlu_perbaikan',
                    'diverifikasi',
                    'ditolak',
                ],
                'null'    => false,
                'default' => 'menunggu_verifikasi',
            ],
            'status_pembayaran' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'belum_bayar',
                    'menunggu_konfirmasi',
                    'lunas',
                ],
                'null'    => false,
                'default' => 'belum_bayar',
            ],
            'catatan_admin' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'verified_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'FK ke tabel users admin',
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
                'comment'    => 'IPv4 atau IPv6 saat submit',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Soft delete',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('no_pendaftaran');
        $this->forge->addUniqueKey('public_token');
        $this->forge->addUniqueKey(['batch_id', 'nik'], 'uq_batch_nik');
        $this->forge->addKey(['status_pendaftaran', 'status_pembayaran'], false, false, 'idx_status');
        $this->forge->addKey('created_at', false, false, 'idx_created');
        $this->forge->addKey('nama_lengkap', false, false, 'idx_nama');

        $this->forge->createTable('pkpa_registrations', true, [
            'ENGINE'  => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Foreign key ke pkpa_batches
        $this->db->query('ALTER TABLE pkpa_registrations
            ADD CONSTRAINT fk_reg_batch FOREIGN KEY (batch_id)
            REFERENCES pkpa_batches(id)');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE pkpa_registrations DROP FOREIGN KEY fk_reg_batch');
        $this->forge->dropTable('pkpa_registrations', true);
    }
}
