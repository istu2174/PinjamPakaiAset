# SIPANDA — Sistem Pinjam Pakai Aset Dispora Palembang

Aplikasi Laravel 12 untuk peminjaman aset (KIB B) Dinas Kepemudaan dan Olahraga Kota Palembang, dengan Priority Scheduling untuk antrean peminjaman dan Decision Tree C4.5 untuk penilaian kondisi aset.

## Menjalankan di lokal (Windows + XAMPP)

Kebutuhan: XAMPP (PHP 8.2+ dan MySQL/MariaDB), Composer, Node.js.

1. Aktifkan extension `gd`, `zip`, dan `intl` di `xampp\php\php.ini` (hapus `;` di depan `extension=gd`, `extension=zip`, `extension=intl`).
2. Nyalakan MySQL di XAMPP Control Panel.
3. Jalankan:

   ```bash
   composer install
   # Kalau pakai PHP 8.5: phpoffice/phpspreadsheet 1.x belum resmi mendukungnya, jadi tambahkan
   # composer install --ignore-platform-req=php+
   npm install
   npm run build

   cp .env.example .env
   php artisan key:generate
   mysql -u root -e "CREATE DATABASE sipanda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
   php artisan migrate --seed
   php artisan storage:link
   ```

4. Jalankan server, lalu buka http://127.0.0.1:8000:

   ```bash
   php artisan serve
   ```

Opsional: `php artisan schedule:work` menjalankan reminder pengembalian harian (08:00).

## Akun bawaan (seeder)

Semua akun memakai password `password`.

| Email | Role |
|---|---|
| admin@sipanda.test | admin |
| pengelola@sipanda.test | pengelola |
| peminjam.pemerintah@sipanda.test | peminjam (prioritas tinggi) |
| peminjam.organisasi@sipanda.test | peminjam (prioritas sedang) |
| peminjam.umum@sipanda.test | peminjam (prioritas rendah) |
| peminjam.internal@sipanda.test | peminjam (tidak direkap) |

## Test

```bash
php artisan test
```
