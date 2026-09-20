<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePkpaDocumentsTable extends Migration
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
            'registration_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'pas_foto',
                    'ktp',
                    'ijazah',
                    'transkrip',
                    'bukti_bayar',
                    'lainnya',
                ],
                'null' => false,
            ],
            'nama_asli' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'comment'    => 'Nama file asli dari upload user',
            ],
            'nama_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'comment'    => 'Nama acak (random name) yang disimpan di server',
            ],
            'mime' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'ukuran' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Ukuran file dalam bytes',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu', 'valid', 'tidak_valid'],
                'null'       => false,
                'default'    => 'menunggu',
            ],
            'catatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Catatan admin per dokumen',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('registration_id', false, false, 'idx_reg');

        $this->forge->createTable('pkpa_documents', true, [
            'ENGINE'  => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Foreign key ke pkpa_registrations dengan CASCADE DELETE
        $this->db->query('ALTER TABLE pkpa_documents
            ADD CONSTRAINT fk_doc_reg FOREIGN KEY (registration_id)
            REFERENCES pkpa_registrations(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE pkpa_documents DROP FOREIGN KEY fk_doc_reg');
        $this->forge->dropTable('pkpa_documents', true);
    }
}
