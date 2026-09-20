<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePkpaSettingsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'comment'    => 'Key pengaturan, mis: wa_ribka, wa_yuni, rekening, syarat',
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'comment'    => 'Label tampilan di halaman pengaturan admin',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('key');

        $this->forge->createTable('pkpa_settings', true, [
            'ENGINE'  => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('pkpa_settings', true);
    }
}
