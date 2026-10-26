# Incer News

Situs berita Incer News yang dibangun dengan Laravel 12, Filament 4, dan tampilan publik Blade + Tailwind. Dependensi dikunci ke PHP 8.3 agar bisa berjalan di Hostinger.

## Menjalankan di komputer

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
# Isi ADMIN_PASSWORD di .env sebelum seed.
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Beranda ada di http://127.0.0.1:8000. Dashboard redaksi ada di http://127.0.0.1:8000/admin. Hanya email di `ADMIN_EMAIL` / `ADMIN_EMAILS` yang boleh masuk panel. Jika `MFA_REQUIRED=true` (default di luar tes), redaksi wajib mengaktifkan aplikasi autentikator setelah login. Jika `ADMIN_PASSWORD` kosong di lingkungan lokal, seed memakai `ganti-sandi-ini`. Di production seed akan gagal jika password kosong.

Perintah seed mengunduh gambar dari situs yang sedang berjalan, lalu menyimpannya sebagai WebP dengan lebar maksimal 1200 piksel.

## Hostinger

Aplikasi di server harus memakai MySQL di mesin yang sama:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda
APP_INDEXING=true
MFA_REQUIRED=true
SESSION_SECURE_COOKIE=true
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
```

Jangan memakai host `srv*.hstgr.io` dari dalam aplikasi. Document root mengarah ke folder `public`.

Setelah unggah, di server:

1. `npm ci && npm run build` (atau unggah folder `public/build` yang sudah jadi — folder itu tidak ikut git)
2. `php artisan migrate --force`
3. `php artisan storage:link`
4. `php artisan filament:assets`
5. Isi `ADMIN_PASSWORD` lalu seed hanya jika database masih kosong

PHP di Hostinger untuk situs ini adalah 8.3.
