<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder contoh angkatan PKPA
 * Membuat 1 angkatan aktif sebagai data awal
 */
class PkpaBatchesSeeder extends Seeder
{
    public function run(): void
    {
        $now  = date('Y-m-d H:i:s');
        $year = date('Y');

        $data = [
            [
                'kode'            => 'XX' . $year,
                'nama'            => 'PKPA Angkatan XX Tahun ' . $year,
                'buka_daftar'     => $year . '-10-01',
                'tutup_daftar'    => $year . '-10-31',
                'tanggal_mulai'   => $year . '-11-01',
                'tanggal_selesai' => $year . '-12-31',
                'kuota'           => 100,
                'biaya'           => 5000000.00,   // ganti sesuai panitia
                'is_active'       => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        // Hindari duplikat jika seeder dijalankan ulang
        foreach ($data as $row) {
            $exists = $this->db->table('pkpa_batches')
                ->where('kode', $row['kode'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('pkpa_batches')->insert($row);
            }
        }
    }
}
