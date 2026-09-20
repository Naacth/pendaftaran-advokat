<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * DatabaseSeeder — entry point utama
 * Jalankan: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('UsersSeeder');
        $this->call('PkpaSettingsSeeder');
        $this->call('PkpaBatchesSeeder');
    }
}
