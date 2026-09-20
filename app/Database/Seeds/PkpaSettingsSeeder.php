<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder pengaturan sistem PKPA
 * Isi dari poster PKPA PERADI DPC Tangerang Raya:
 *   - 4 kontak WhatsApp panitia (Ribka, Yuni, Robert, Ruby)
 *   - Info rekening pembayaran (placeholder — isi sesuai panitia)
 *   - Teks pernyataan form pendaftaran
 *   - Teks syarat & ketentuan
 */
class PkpaSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $settings = [
            // ── Kontak WhatsApp Panitia (dari poster) ──────────────────────────
            [
                'key'        => 'wa_ribka',
                'value'      => '6281234560001',   // ganti dengan nomor asli Ribka
                'label'      => 'WhatsApp Panitia — Ribka',
                'updated_at' => $now,
            ],
            [
                'key'        => 'wa_yuni',
                'value'      => '6281234560002',   // ganti dengan nomor asli Yuni
                'label'      => 'WhatsApp Panitia — Yuni',
                'updated_at' => $now,
            ],
            [
                'key'        => 'wa_robert',
                'value'      => '6281234560003',   // ganti dengan nomor asli Robert
                'label'      => 'WhatsApp Panitia — Robert',
                'updated_at' => $now,
            ],
            [
                'key'        => 'wa_ruby',
                'value'      => '6281234560004',   // ganti dengan nomor asli Ruby
                'label'      => 'WhatsApp Panitia — Ruby',
                'updated_at' => $now,
            ],

            // ── Rekening Pembayaran ────────────────────────────────────────────
            [
                'key'        => 'rekening_bank',
                'value'      => 'Bank BCA',
                'label'      => 'Nama Bank',
                'updated_at' => $now,
            ],
            [
                'key'        => 'rekening_nomor',
                'value'      => '1234567890',      // ganti nomor rekening resmi
                'label'      => 'Nomor Rekening',
                'updated_at' => $now,
            ],
            [
                'key'        => 'rekening_atas_nama',
                'value'      => 'PERADI DPC Tangerang Raya',
                'label'      => 'Atas Nama Rekening',
                'updated_at' => $now,
            ],

            // ── Teks Pernyataan Form Pendaftaran ──────────────────────────────
            [
                'key'   => 'teks_pernyataan',
                'value' => 'Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan. '
                         . 'Saya menyetujui pemrosesan data pribadi saya oleh PERADI DPC Tangerang Raya '
                         . 'untuk keperluan pendaftaran dan penyelenggaraan PKPA sesuai ketentuan yang berlaku.',
                'label'      => 'Teks Pernyataan (checkbox pendaftaran)',
                'updated_at' => $now,
            ],

            // ── Teks Syarat & Ketentuan ───────────────────────────────────────
            [
                'key'   => 'teks_syarat',
                'value' => "Persyaratan Peserta PKPA:\n"
                         . "1. Warga Negara Indonesia\n"
                         . "2. Sarjana Hukum (S.H.) atau gelar hukum setara\n"
                         . "3. Berusia minimal 21 tahun\n"
                         . "4. Melampirkan: KTP, Ijazah S1, Pas Foto 3×4, dan Transkrip Nilai\n"
                         . "5. Melunasi biaya pendaftaran sesuai angkatan yang diikuti",
                'label'      => 'Teks Syarat & Ketentuan',
                'updated_at' => $now,
            ],
        ];

        // Upsert: insert jika belum ada, skip jika sudah ada
        foreach ($settings as $row) {
            $exists = $this->db->table('pkpa_settings')
                ->where('key', $row['key'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('pkpa_settings')->insert($row);
            }
        }
    }
}
