# PRD — Halaman Pendaftaran PKPA
**PERADI DPC Tangerang Raya (peraditangerang.id)**

| | |
|---|---|
| Versi | 1.0 (Draft) |
| Tanggal | 19 September 2026 |
| Penyusun | Fariz |
| Framework | CodeIgniter 4 (PHP 8.1+) |
| Database | MySQL 8 / MariaDB 10.6+ |
| Front-end | Bootstrap 5.3.x (versi stabil terbaru), SweetAlert2, DataTables (integrasi Bootstrap 5) |

> Catatan: PRD ini disusun dari materi poster PKPA dan ketentuan teknis yang diminta. Hal yang perlu dikonfirmasi ke panitia ada di bagian **13. Pertanyaan Terbuka**.

---

## 1. Latar Belakang

PERADI DPC Tangerang Raya menyelenggarakan **PKPA (Pendidikan Khusus Profesi Advokat)**. Saat ini calon peserta belum punya kanal pendaftaran online yang terstruktur. Halaman pendaftaran dibutuhkan agar:

- calon peserta bisa mendaftar, mengunggah dokumen, dan memantau status secara mandiri;
- panitia bisa memverifikasi, mengelola angkatan, dan merekap peserta tanpa spreadsheet manual.

Poster memuat 4 kontak WhatsApp panitia (Ribka, Yuni, Robert, Ruby) untuk pertanyaan, yang akan ditampilkan juga di halaman.

## 2. Tujuan & Indikator Keberhasilan

| Tujuan | Indikator |
|---|---|
| Pendaftaran 100% online | Form dapat diselesaikan < 10 menit |
| Data pendaftar rapi & tidak dobel | 0 duplikat NIK dalam satu angkatan |
| Verifikasi lebih cepat | Panitia memproses pendaftar dari satu layar (tanpa pindah aplikasi) |
| Mengurangi pertanyaan berulang | Fitur cek status + tombol WhatsApp panitia |

## 3. Peran Pengguna

| Peran | Deskripsi | Akses |
|---|---|---|
| **Calon Peserta** (publik) | Sarjana yang ingin mengikuti PKPA | Halaman info, form daftar, cek status, unggah perbaikan |
| **Admin/Panitia** | Petugas DPC | Dashboard, daftar pendaftar, verifikasi, kelola angkatan, ekspor |
| **Super Admin** | Pengelola sistem | Semua akses admin + kelola user admin & pengaturan |

> Jika website sudah punya sistem login/user admin, **gunakan yang ada** dan hanya tambahkan role/permission untuk modul PKPA.

## 4. Ruang Lingkup

**In scope (MVP)**
1. Halaman informasi PKPA (konten dari poster).
2. Form pendaftaran + unggah dokumen.
3. Nomor pendaftaran otomatis + halaman konfirmasi.
4. Cek status pendaftaran.
5. Panel admin: kelola angkatan, daftar pendaftar (DataTables server-side), detail & verifikasi, ekspor Excel/CSV.
6. Notifikasi via tautan WhatsApp (klik-untuk-chat, bukan bot).

**Out of scope (fase berikutnya)**
- Payment gateway otomatis (MVP: unggah bukti transfer + verifikasi manual).
- Pengiriman email/WA otomatis, sertifikat digital, absensi, modul materi/ujian.

## 5. Alur Pengguna

**Calon peserta**
```
Buka /pkpa → baca info & persyaratan → klik "Daftar Sekarang"
→ isi data pribadi → data pendidikan → unggah dokumen → centang pernyataan
→ klik "Kirim Pendaftaran" → SweetAlert konfirmasi → simpan
→ halaman sukses (No. Pendaftaran) → cek status kapan saja di /pkpa/cek-status
```

**Admin**
```
Login → Dashboard → Daftar Pendaftar (filter angkatan/status)
→ Detail pendaftar → cek dokumen → Verifikasi / Minta Perbaikan / Tolak
(semua aksi simpan lewat SweetAlert konfirmasi)
```

**Status pendaftaran**
`menunggu_verifikasi` → `perlu_perbaikan` ⇄ `menunggu_verifikasi` → `diverifikasi` | `ditolak`
**Status pembayaran:** `belum_bayar` → `menunggu_konfirmasi` → `lunas`

## 6. Kebutuhan Fungsional

### 6.1 Halaman Publik

**FR-01 Halaman Info PKPA** `/pkpa`
- Hero: judul "Ayo Ikuti PKPA", subjudul "Pendidikan Khusus Profesi Advokat — PERADI DPC Tangerang Raya", tagline "Langkah Awal Menuju Profesi Advokat yang Profesional, Berintegritas dan Berkeadilan".
- Keunggulan: materi komprehensif, dibimbing praktisi & akademisi berkualitas, sertifikat resmi PERADI, peluang karier/jaringan profesional.
- Nilai: Integritas, Profesionalisme, Kolaborasi, Keadilan.
- Info angkatan aktif (nama, periode daftar, jadwal, biaya, sisa kuota) dari tabel `pkpa_batches`.
- Tombol "Daftar Sekarang" (nonaktif otomatis jika tidak ada angkatan dibuka / kuota penuh / lewat tanggal tutup).
- Kartu kontak: 4 tombol WhatsApp (`https://wa.me/62…`) dari tabel pengaturan (bisa diubah admin).

**FR-02 Form Pendaftaran** `/pkpa/daftar` (multi-section dalam satu halaman, atau stepper Bootstrap)

| Bagian | Field | Tipe | Wajib | Validasi |
|---|---|---|---|---|
| Angkatan | Angkatan | hidden/readonly (angkatan aktif) | Ya | Harus terbuka & kuota tersedia |
| Data Pribadi | Nama lengkap (sesuai ijazah) | text | Ya | 3–150 karakter |
| | NIK | text | Ya | 16 digit angka; unik per angkatan |
| | Tempat lahir | text | Ya | maks 100 |
| | Tanggal lahir | date | Ya | usia ≥ 21 tahun *(konfirmasi)* |
| | Jenis kelamin | radio (L/P) | Ya | in_list |
| | Alamat lengkap | textarea | Ya | maks 500 |
| | Kota/Kabupaten | text/select | Ya | |
| | No. WhatsApp | text | Ya | format `08…` / `+62…`, dinormalisasi ke `62…` |
| | Email | email | Ya | valid email |
| Pendidikan | Asal perguruan tinggi | text | Ya | |
| | Fakultas/Program studi | text | Ya | |
| | Gelar | select (S.H., S.Sy., S.H.I., lainnya) | Ya | *konfirmasi syarat gelar* |
| | Tahun lulus | number | Ya | 1970 – tahun berjalan |
| Pekerjaan | Pekerjaan saat ini | text | Tidak | |
| | Instansi/Kantor | text | Tidak | |
| Dokumen | Pas foto 3x4 | file (jpg/png) | Ya | maks 1 MB |
| | KTP | file (jpg/png/pdf) | Ya | maks 2 MB |
| | Ijazah / SKL S1 | file (pdf/jpg/png) | Ya | maks 2 MB |
| | Transkrip nilai | file (pdf) | Tidak *(konfirmasi)* | maks 2 MB |
| Pernyataan | Data yang diisi benar & setuju pemrosesan data pribadi | checkbox | Ya | harus dicentang |

- Semua tombol simpan/kirim memakai **SweetAlert confirm** (lihat 7.3).
- Validasi ganda: client-side (Bootstrap `was-validated`) dan server-side (CI4 Validation).
- Pesan error validasi ditampilkan per field (`is-invalid` + `invalid-feedback`).
- Perlindungan bot: honeypot CI4 + throttling (maks 5 submit/menit/IP); opsional Cloudflare Turnstile/reCAPTCHA.

**FR-03 Konfirmasi Pendaftaran** `/pkpa/daftar/sukses/{token}`
- Menampilkan **No. Pendaftaran** (format `PKPA-{KODEANGKATAN}-{0001}`), ringkasan data, langkah berikutnya, tombol WhatsApp panitia, dan tombol cetak/unduh bukti pendaftaran (PDF, opsional P1).

**FR-04 Cek Status** `/pkpa/cek-status`
- Input: No. Pendaftaran + No. WhatsApp (atau 4 digit terakhir NIK).
- Output: status pendaftaran, status pembayaran, catatan panitia. Tidak menampilkan data sensitif penuh (NIK dimasking).
- Batasi percobaan (throttle) untuk mencegah enumerasi.

**FR-05 Perbaikan Data** `/pkpa/perbaikan/{token}` (P1)
- Hanya aktif saat status `perlu_perbaikan`. Peserta mengunggah ulang dokumen/mengubah field yang diminta; kembali ke `menunggu_verifikasi`.

**FR-06 Unggah Bukti Pembayaran** (P1)
- Setelah pendaftaran diverifikasi, peserta mengunggah bukti transfer dari halaman cek status.

### 6.2 Panel Admin

Prefix: `/admin/pkpa` (filter `auth` + role).

**FR-10 Dashboard**
- Kartu ringkasan per angkatan: total pendaftar, menunggu verifikasi, diverifikasi, ditolak, lunas, sisa kuota.
- Grafik pendaftar per hari (opsional).

**FR-11 Kelola Angkatan** (CRUD)
- Field: nama, kode, tanggal buka/tutup pendaftaran, tanggal mulai/selesai, kuota, biaya, status aktif.
- Hanya satu angkatan `is_active` yang tampil di halaman publik.
- Tabel angkatan memakai **DataTables server-side**.

**FR-12 Daftar Pendaftar** (**DataTables server-side**)
- Kolom: No, No. Pendaftaran, Nama, No. WA, Email, Angkatan, Status Pendaftaran, Status Pembayaran, Tanggal Daftar, Aksi.
- Fitur: pencarian global (nama, NIK, email, no. WA, no. pendaftaran), filter angkatan/status/pembayaran/rentang tanggal, sort per kolom, paginasi (10/25/50/100), tombol ekspor.
- Aksi per baris: Detail, Verifikasi cepat, Hapus (soft delete).

**FR-13 Detail & Verifikasi Pendaftar**
- Tampilkan seluruh data + pratinjau dokumen (dilayani lewat controller, bukan URL publik).
- Tiap dokumen bisa ditandai `valid` / `tidak_valid` + catatan.
- Aksi: **Verifikasi**, **Minta Perbaikan** (wajib catatan), **Tolak** (wajib alasan), **Konfirmasi Pembayaran**.
- Semua aksi lewat SweetAlert confirm; hasil ditampilkan dengan SweetAlert success/error (toast).
- Setiap perubahan tercatat di `activity_logs`.

**FR-14 Ekspor**
- Ekspor Excel/CSV mengikuti filter aktif (Excel via PhpSpreadsheet, opsional P1; CSV di MVP).

**FR-15 Pengaturan**
- Kontak WhatsApp panitia, teks persyaratan, info rekening pembayaran, teks pernyataan.

**FR-16 Manajemen User Admin** (Super Admin) — CRUD user, role, aktif/nonaktif, reset password.

## 7. Kebutuhan Teknis

### 7.1 Stack & Library

| Komponen | Ketentuan |
|---|---|
| Framework | CodeIgniter 4 (stabil terbaru), PHP 8.1+ |
| DB | MySQL, engine InnoDB, charset `utf8mb4`, collation `utf8mb4_unicode_ci` |
| CSS/JS | Bootstrap 5.3.x terbaru (CSS + JS bundle), Bootstrap Icons |
| Alert | SweetAlert2 |
| Tabel | DataTables + `dataTables.bootstrap5`, mode `serverSide: true` |
| JS | jQuery (dibutuhkan DataTables) |
| Aset | Boleh via CDN atau `public/assets/vendor` (disarankan lokal, versi di-*pin*) |

### 7.2 Struktur Modul (CI4)

```
app/
├── Config/Routes.php
├── Controllers/
│   ├── Pkpa/Home.php            # info, cek status
│   ├── Pkpa/Registration.php    # form, store, sukses, perbaikan
│   └── Admin/Pkpa/
│       ├── Dashboard.php
│       ├── Batches.php
│       ├── Registrations.php    # datatable, show, verify, export
│       └── Settings.php
├── Models/
│   ├── PkpaBatchModel.php
│   ├── PkpaRegistrationModel.php
│   ├── PkpaDocumentModel.php
│   └── ActivityLogModel.php
├── Filters/AdminAuth.php
├── Validation/PkpaRules.php
├── Views/
│   ├── layouts/{public,admin}.php
│   ├── pkpa/{index,daftar,sukses,cek_status}.php
│   └── admin/pkpa/{dashboard,batches,registrations,detail}.php
public/assets/js/{swal-confirm.js,datatable-init.js}
writable/uploads/pkpa/           # file dokumen (di luar public)
```

### 7.3 Standar SweetAlert (WAJIB untuk semua tombol simpan)

Aturan:
1. **Setiap** form/tombol yang menyimpan, mengubah, memverifikasi, atau menghapus data harus melewati `Swal.fire` konfirmasi terlebih dahulu.
2. Form memakai atribut `data-swal-confirm` (judul/teks opsional lewat `data-swal-title`, `data-swal-text`).
3. Setelah dikonfirmasi: tombol di-*disable* + spinner untuk mencegah double submit.
4. Hasil dari server (flashdata / JSON) ditampilkan dengan `Swal.fire` ikon `success`/`error`.
5. Tombol hapus memakai warna `danger` dan teks yang menyebut nama data yang dihapus.

Contoh `public/assets/js/swal-confirm.js`:

```js
document.addEventListener('submit', async (e) => {
  const form = e.target.closest('form[data-swal-confirm]');
  if (!form || form.dataset.confirmed === '1') return;

  e.preventDefault();
  if (!form.checkValidity()) {            // biarkan validasi Bootstrap tampil dulu
    form.classList.add('was-validated');
    form.reportValidity();
    return;
  }

  const result = await Swal.fire({
    title: form.dataset.swalTitle || 'Simpan data?',
    text: form.dataset.swalText || 'Pastikan data yang Anda isi sudah benar.',
    icon: form.dataset.swalIcon || 'question',
    showCancelButton: true,
    confirmButtonText: form.dataset.swalConfirm || 'Ya, simpan',
    cancelButtonText: 'Batal',
    reverseButtons: true,
  });

  if (result.isConfirmed) {
    form.dataset.confirmed = '1';
    const btn = form.querySelector('[type="submit"]');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    }
    form.submit();
  }
});
```

Contoh pemakaian di view:

```php
<form action="<?= base_url('pkpa/daftar') ?>" method="post" enctype="multipart/form-data"
      class="needs-validation" novalidate
      data-swal-confirm="Ya, kirim" data-swal-title="Kirim pendaftaran?"
      data-swal-text="Data tidak dapat diubah kecuali diminta perbaikan oleh panitia.">
  <?= csrf_field() ?>
  ...
  <button type="submit" class="btn btn-primary">Kirim Pendaftaran</button>
</form>
```

Untuk aksi non-form (mis. tombol Hapus di DataTables), gunakan helper `swalConfirm({title, text}).then(ok => ...)` yang memanggil `fetch/$.ajax` (method POST + token CSRF) lalu menampilkan hasilnya dan `table.ajax.reload(null, false)`.

### 7.4 Standar DataTables Server-Side

Aturan: **semua tabel** (pendaftar, angkatan, user, log) memakai `serverSide: true`. Tidak boleh memuat seluruh data ke browser.

Inisialisasi (JS):

```js
const table = $('#tbl-pendaftar').DataTable({
  processing: true,
  serverSide: true,
  ajax: {
    url: BASE_URL + '/admin/pkpa/pendaftar/datatable',
    type: 'POST',
    data: (d) => {
      d.batch_id = $('#f-batch').val();
      d.status = $('#f-status').val();
      d.pembayaran = $('#f-bayar').val();
      d[CSRF.name] = CSRF.hash;          // token CSRF CI4
    },
    dataSrc: (json) => { CSRF.hash = json.csrf_hash; return json.data; },
  },
  columns: [
    { data: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'no_pendaftaran' },
    { data: 'nama_lengkap' },
    { data: 'no_wa' },
    { data: 'email' },
    { data: 'batch' },
    { data: 'status_pendaftaran' },
    { data: 'status_pembayaran' },
    { data: 'created_at' },
    { data: 'aksi', orderable: false, searchable: false },
  ],
  order: [[8, 'desc']],
  pageLength: 25,
  language: { url: BASE_URL + '/assets/vendor/datatables/id.json' },
});
```

Model (CI4) — `PkpaRegistrationModel::datatable()`:

```php
public function datatable(array $req): array
{
    $cols = [null, 'r.no_pendaftaran', 'r.nama_lengkap', 'r.no_wa', 'r.email',
             'b.nama', 'r.status_pendaftaran', 'r.status_pembayaran', 'r.created_at'];

    $builder = $this->db->table('pkpa_registrations r')
        ->select('r.id, r.no_pendaftaran, r.nama_lengkap, r.no_wa, r.email,
                  r.status_pendaftaran, r.status_pembayaran, r.created_at, b.nama AS batch')
        ->join('pkpa_batches b', 'b.id = r.batch_id')
        ->where('r.deleted_at', null);

    if (!empty($req['batch_id']))   $builder->where('r.batch_id', (int) $req['batch_id']);
    if (!empty($req['status']))     $builder->where('r.status_pendaftaran', $req['status']);
    if (!empty($req['pembayaran'])) $builder->where('r.status_pembayaran', $req['pembayaran']);

    $recordsTotal = $builder->countAllResults(false);

    $search = trim($req['search']['value'] ?? '');
    if ($search !== '') {
        $builder->groupStart()
            ->like('r.nama_lengkap', $search)
            ->orLike('r.no_pendaftaran', $search)
            ->orLike('r.email', $search)
            ->orLike('r.no_wa', $search)
            ->orLike('r.nik', $search)
        ->groupEnd();
    }
    $recordsFiltered = $builder->countAllResults(false);

    // whitelist kolom order agar aman dari SQL injection
    $idx = (int) ($req['order'][0]['column'] ?? 8);
    $dir = ($req['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
    $builder->orderBy($cols[$idx] ?? 'r.created_at', $dir)
            ->limit((int) $req['length'], (int) $req['start']);

    return [
        'draw'            => (int) $req['draw'],
        'recordsTotal'    => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data'            => $builder->get()->getResultArray(),
    ];
}
```

Ketentuan respons JSON: `draw`, `recordsTotal`, `recordsFiltered`, `data`, ditambah `csrf_hash` baru. Kolom `aksi` dan badge status dirender di controller (HTML di-escape) atau lewat `columns.render` di JS. Batas `length` maksimum 100.

## 8. Rancangan Database (MySQL)

```sql
-- 8.1 Angkatan
CREATE TABLE pkpa_batches (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode          VARCHAR(30)  NOT NULL UNIQUE,          -- mis. XX2026
  nama          VARCHAR(150) NOT NULL,                 -- mis. PKPA Angkatan XX
  buka_daftar   DATE NOT NULL,
  tutup_daftar  DATE NOT NULL,
  tanggal_mulai DATE NULL,
  tanggal_selesai DATE NULL,
  kuota         INT UNSIGNED NULL,                     -- NULL = tanpa batas
  biaya         DECIMAL(12,2) NOT NULL DEFAULT 0,
  is_active     TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NULL, updated_at DATETIME NULL, deleted_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8.2 Pendaftar
CREATE TABLE pkpa_registrations (
  id                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  batch_id           INT UNSIGNED NOT NULL,
  no_pendaftaran     VARCHAR(30)  NOT NULL UNIQUE,
  public_token       CHAR(40)     NOT NULL UNIQUE,     -- untuk URL sukses/perbaikan
  nama_lengkap       VARCHAR(150) NOT NULL,
  nik                CHAR(16)     NOT NULL,
  tempat_lahir       VARCHAR(100) NOT NULL,
  tanggal_lahir      DATE NOT NULL,
  jenis_kelamin      ENUM('L','P') NOT NULL,
  alamat             TEXT NOT NULL,
  kota               VARCHAR(100) NOT NULL,
  no_wa              VARCHAR(20)  NOT NULL,
  email              VARCHAR(150) NOT NULL,
  asal_kampus        VARCHAR(150) NOT NULL,
  program_studi      VARCHAR(150) NOT NULL,
  gelar              VARCHAR(50)  NOT NULL,
  tahun_lulus        SMALLINT UNSIGNED NOT NULL,
  pekerjaan          VARCHAR(100) NULL,
  instansi           VARCHAR(150) NULL,
  status_pendaftaran ENUM('menunggu_verifikasi','perlu_perbaikan','diverifikasi','ditolak')
                     NOT NULL DEFAULT 'menunggu_verifikasi',
  status_pembayaran  ENUM('belum_bayar','menunggu_konfirmasi','lunas')
                     NOT NULL DEFAULT 'belum_bayar',
  catatan_admin      TEXT NULL,
  verified_by        INT UNSIGNED NULL,
  verified_at        DATETIME NULL,
  ip_address         VARCHAR(45) NULL,
  created_at DATETIME NULL, updated_at DATETIME NULL, deleted_at DATETIME NULL,
  UNIQUE KEY uq_batch_nik (batch_id, nik),
  KEY idx_status (status_pendaftaran, status_pembayaran),
  KEY idx_created (created_at),
  KEY idx_nama (nama_lengkap),
  CONSTRAINT fk_reg_batch FOREIGN KEY (batch_id) REFERENCES pkpa_batches(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8.3 Dokumen
CREATE TABLE pkpa_documents (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL,
  jenis           ENUM('pas_foto','ktp','ijazah','transkrip','bukti_bayar','lainnya') NOT NULL,
  nama_asli       VARCHAR(255) NOT NULL,
  nama_file       VARCHAR(255) NOT NULL,               -- nama acak (random name)
  mime            VARCHAR(100) NOT NULL,
  ukuran          INT UNSIGNED NOT NULL,
  status          ENUM('menunggu','valid','tidak_valid') NOT NULL DEFAULT 'menunggu',
  catatan         VARCHAR(255) NULL,
  created_at DATETIME NULL, updated_at DATETIME NULL,
  KEY idx_reg (registration_id),
  CONSTRAINT fk_doc_reg FOREIGN KEY (registration_id)
    REFERENCES pkpa_registrations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8.4 Pengaturan (key-value)
CREATE TABLE pkpa_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT NULL,
  updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8.5 Log aktivitas
CREATE TABLE activity_logs (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NULL,
  aksi        VARCHAR(50)  NOT NULL,                   -- verifikasi, tolak, hapus, dll
  target_type VARCHAR(50)  NOT NULL,
  target_id   BIGINT UNSIGNED NOT NULL,
  keterangan  TEXT NULL,
  ip_address  VARCHAR(45) NULL,
  created_at  DATETIME NULL,
  KEY idx_target (target_type, target_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Tabel `users` admin: gunakan yang sudah ada di website; jika belum ada, tambah `users(id, nama, email UNIQUE, password_hash, role ENUM('super_admin','admin'), is_active, last_login_at, timestamps)`.

Migrasi & seeder dibuat lewat CI4 Migration (`php spark make:migration`), seeder awal: 1 super admin, 1 angkatan contoh, isi `pkpa_settings` (4 kontak WA dari poster).

## 9. Routing

```php
// Publik
$routes->group('pkpa', ['namespace' => 'App\Controllers\Pkpa'], static function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('daftar', 'Registration::form');
    $routes->post('daftar', 'Registration::store', ['filter' => 'throttle']);
    $routes->get('daftar/sukses/(:segment)', 'Registration::success/$1');
    $routes->get('cek-status', 'Home::checkStatus');
    $routes->post('cek-status', 'Home::checkStatus', ['filter' => 'throttle']);
    $routes->get('perbaikan/(:segment)', 'Registration::edit/$1');
    $routes->post('perbaikan/(:segment)', 'Registration::update/$1');
});

// Admin
$routes->group('admin/pkpa', ['namespace' => 'App\Controllers\Admin\Pkpa', 'filter' => 'adminAuth'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('angkatan', 'Batches::index');
    $routes->post('angkatan/datatable', 'Batches::datatable');
    $routes->post('angkatan/simpan', 'Batches::save');
    $routes->post('angkatan/hapus/(:num)', 'Batches::delete/$1');

    $routes->get('pendaftar', 'Registrations::index');
    $routes->post('pendaftar/datatable', 'Registrations::datatable');
    $routes->get('pendaftar/(:num)', 'Registrations::show/$1');
    $routes->post('pendaftar/(:num)/status', 'Registrations::changeStatus/$1');
    $routes->post('pendaftar/(:num)/pembayaran', 'Registrations::confirmPayment/$1');
    $routes->post('pendaftar/hapus/(:num)', 'Registrations::delete/$1');
    $routes->get('pendaftar/(:num)/dokumen/(:num)', 'Registrations::document/$1/$2');
    $routes->get('pendaftar/ekspor', 'Registrations::export');

    $routes->get('pengaturan', 'Settings::index');
    $routes->post('pengaturan/simpan', 'Settings::save');
});
```

## 10. Kebutuhan Non-Fungsional

**Keamanan**
- CSRF aktif (`Config\Filters`), token dirotasi & dikirim ulang pada respons AJAX.
- Semua query lewat Query Builder/Model (parameter ter-bind); kolom sorting di-*whitelist*.
- Output di-escape (`esc()`); tidak ada `echo` data mentah.
- Upload: validasi ekstensi + MIME (`is_image`, `mime_in`, `max_size`), nama file diacak (`getRandomName()`), disimpan di `writable/uploads/` (bukan `public/`), disajikan lewat controller yang mengecek hak akses.
- Password admin: `password_hash()` (bcrypt/argon2), sesi aman (`httponly`, `samesite`), *rate limit* login.
- Throttler CI4 untuk form publik dan cek status; honeypot aktif.
- Env produksi: `CI_ENVIRONMENT = production`, HTTPS wajib, `.env` di luar public.
- Kontrol akses berbasis role di setiap endpoint admin.

**Privasi / Data Pribadi**
- Data (NIK, dokumen identitas) termasuk data pribadi → hanya dapat diakses admin berwenang, dimasking di tampilan yang tidak perlu, dan dicantumkan dasar persetujuan (checkbox pernyataan) sesuai UU PDP.
- Kebijakan retensi data ditentukan panitia (lihat pertanyaan terbuka).

**Performa**: halaman publik < 3 detik pada koneksi 4G; query DataTables memakai indeks; paginasi maksimal 100 baris.

**UI/UX**: responsif *mobile-first* (banyak pendaftar mengakses dari HP), warna mengikuti identitas poster (biru tua `#0B2A66` s.d. `#123A8C`, aksen emas `#C9A24B`, latar putih), aksesibel (label, kontras, fokus terlihat).

**Ketersediaan & Backup**: backup database harian, backup folder `writable/uploads` mingguan.

**Logging**: log error CI4 aktif; aksi admin tercatat di `activity_logs`.

## 11. Kriteria Penerimaan (Acceptance Criteria)

1. Calon peserta dapat mengisi form, mengunggah dokumen, dan menerima No. Pendaftaran.
2. Klik **Kirim Pendaftaran** selalu memunculkan SweetAlert konfirmasi; tanpa konfirmasi, data tidak tersimpan.
3. Seluruh tombol simpan/ubah/hapus/verifikasi di halaman admin memunculkan SweetAlert konfirmasi.
4. NIK yang sama tidak dapat mendaftar dua kali pada angkatan yang sama (pesan error jelas).
5. Pendaftaran ditolak otomatis bila angkatan belum dibuka, sudah ditutup, atau kuota penuh.
6. Semua tabel admin memakai DataTables **server-side**; pencarian, sorting, filter, dan paginasi berjalan dengan 10.000+ baris tanpa lag berarti.
7. File dokumen tidak dapat diakses langsung lewat URL publik.
8. Admin dapat memverifikasi, meminta perbaikan, atau menolak dengan catatan; status terlihat oleh peserta di halaman cek status.
9. Tampilan rapi di layar 360 px hingga desktop.
10. Uji keamanan dasar lulus: CSRF, XSS, SQL injection, upload file berbahaya.

## 12. Rencana Pengerjaan (Estimasi)

| Tahap | Cakupan | Estimasi |
|---|---|---|
| 1 | Migrasi DB, model, seeder, layout Bootstrap, helper SweetAlert & DataTables | 2 hari |
| 2 | Halaman info, form pendaftaran, upload, halaman sukses | 3 hari |
| 3 | Cek status, filter throttle/honeypot | 1 hari |
| 4 | Admin: login/role, angkatan, daftar pendaftar (server-side), detail & verifikasi | 4 hari |
| 5 | Ekspor, pengaturan, activity log | 2 hari |
| 6 | Pengujian, perbaikan, deploy & backup | 2 hari |

## 13. Pertanyaan Terbuka (perlu konfirmasi panitia)

1. Syarat resmi peserta: gelar/latar pendidikan apa saja yang diterima, batas usia, dan dokumen wajib lain?
2. Apakah pendaftaran sudah termasuk pembayaran? Biaya, rekening tujuan, dan kebijakan pengembalian dana?
3. Kuota per angkatan dan apakah ada daftar tunggu?
4. Apakah peserta boleh mengedit data setelah kirim, atau hanya saat diminta perbaikan?
5. Format No. Pendaftaran yang diinginkan?
6. Berapa lama data & dokumen pendaftar disimpan?
7. Apakah sistem login admin di peraditangerang.id sudah ada, dan bagaimana struktur layout/tema yang dipakai agar modul ini menyatu?
8. Perlukah notifikasi otomatis (email/WhatsApp) pada fase berikutnya?
