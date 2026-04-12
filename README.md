# Poliklinik App v2 - Fast Track

Aplikasi manajemen poliklinik berbasis Laravel 11 untuk role Admin, Dokter, dan Pasien.

## Fitur Utama

1. Dashboard pasien dengan antrean aktif.
2. Sistem antrean realtime (Laravel Reverb + Laravel Echo).
3. Manajemen stok obat oleh admin.
4. Pemeriksaan pasien oleh dokter dengan pengurangan stok otomatis.
5. Riwayat pendaftaran poli pasien + detail pemeriksaan.
6. Export Excel:
	- Admin: data dokter, pasien, obat.
	- Dokter: jadwal periksa, riwayat pasien.
7. Upload bukti pembayaran oleh pasien dan verifikasi pembayaran oleh admin.

## Tech Stack

- Laravel 11
- PHP 8.2+
- MySQL/SQLite
- Vite + Tailwind CSS + daisyUI
- Laravel Reverb + Laravel Echo
- maatwebsite/excel

## Instalasi

1. Clone repository dan masuk ke folder project.
2. Install dependency backend:
	- `composer install`
3. Install dependency frontend:
	- `npm install`
4. Siapkan file environment:
	- copy `.env.example` ke `.env`
5. Generate app key:
	- `php artisan key:generate`
6. Migrasi database:
	- `php artisan migrate`
7. Seed data awal:
	- `php artisan db:seed`
8. Buat symbolic link storage:
	- `php artisan storage:link`

## Menjalankan Aplikasi

Gunakan satu command berikut:

- `composer run dev`

Command tersebut menjalankan:

1. Laravel server (`php artisan serve`)
2. Reverb websocket server (`php artisan reverb:start`)
3. Vite dev server (`npm run dev`)

## Akun Demo

- Admin
  - Email: `admin@gmail.com`
  - Password: `admin`
- Dokter
  - Email: `dokter@gmail.com`
  - Password: `dokter`
- Pasien
  - Email: `pasien@gmail.com`
  - Password: `pasien`

## Alur Singkat Penggunaan

### Pasien

1. Login sebagai pasien.
2. Daftar antrean di dashboard pasien.
3. Pantau nomor dilayani secara realtime.
4. Lihat riwayat pendaftaran dan detail pemeriksaan.
5. Upload bukti pembayaran di menu Pembayaran.

### Dokter

1. Login sebagai dokter.
2. Buka menu Pemeriksaan Pasien.
3. Isi catatan, pilih obat, simpan pemeriksaan.
4. Stok obat berkurang otomatis dan antrean terupdate realtime.

### Admin

1. Login sebagai admin.
2. Kelola data obat dan stok.
3. Verifikasi bukti pembayaran pasien.
4. Lakukan export Excel dari menu dashboard/halaman terkait.

## Dokumentasi Tambahan

Panduan detail tersedia di:

- `PANDUAN_PENGGUNAAN_FASTTRACK.md`

## License

Project ini menggunakan lisensi MIT.
