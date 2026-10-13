# Sales & Purchasing — DataTables Edition

Tugas Day 13: implementasi **DataTables** dan **DataTables Extension** di Laravel 12,
melanjutkan domain *Sales & Purchasing* dari tugas sebelumnya (master Product & Supplier).

## Demo online (statis)

Folder [`docs/`](docs) berisi versi statis yang bisa dicoba langsung di browser (GitHub Pages):
**https://tioeka04.github.io/datatables-sales-purchasing/**

Demo ini memakai 1.000 data dummy dari `docs/products.json` dengan DataTables sisi klien. Tambah/edit/hapus
disimpan di `localStorage` browser, tanpa server. Versi lengkap dengan server-side processing ada di aplikasi Laravel.

## Fitur

- **DataTables server-side processing** (`yajra/laravel-datatables-oracle`): paging, sorting, dan
  searching dilakukan di database. Seeder membuat 1.000 produk untuk mendemonstrasikannya.
- **Buttons extension**: Copy, CSV, Excel, PDF, Print.
- **Column visibility (ColVis)**: tombol *Kolom* untuk menampilkan/menyembunyikan kolom
  (kolom *Harga Beli* disembunyikan secara default).
- **Filter** kategori, supplier, dan status stok yang ikut dikirim ke server.
- **CRUD produk** lewat modal (AJAX): tambah, edit, dan hapus (dengan konfirmasi) + validasi
  Form Request yang ditampilkan per field.

## Struktur tugas

| Kebutuhan tugas | File |
| --- | --- |
| Proyek Laravel baru | seluruh repo ini |
| Model `Product` | `app/Models/Product.php` (+ `Supplier`) |
| Migration `products` | `database/migrations/*_create_products_table.php` |
| Seeder data dummy | `database/seeders/ProductSeeder.php`, `SupplierSeeder.php` |
| `ProductsController` | `app/Http/Controllers/ProductsController.php` |
| View `products.index` | `resources/views/products/index.blade.php` |
| Form tambah / ubah / hapus | modal di `products/index.blade.php`, handler `store`, `update`, `destroy` |
| Validasi | `app/Http/Requests/ProductRequest.php` |

## Menjalankan di lokal

Syarat: PHP >= 8.2, Composer. Database default memakai SQLite (tidak perlu setup MySQL).

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite      # Windows PowerShell: New-Item database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Buka http://127.0.0.1:8000 (otomatis ke `/products`). Halaman memuat jQuery, Bootstrap, DataTables,
dan plugin export lewat CDN, jadi butuh koneksi internet.

Menjalankan test:

```bash
php artisan test
```

## Deploy gratis ke Render

Repo sudah menyertakan `Dockerfile`, `docker-entrypoint.sh`, dan `render.yaml`.

1. Buat akun di https://render.com (login dengan GitHub).
2. **New > Web Service**, pilih repo ini, runtime **Docker**, plan **Free**.
3. Tambahkan environment variable `APP_KEY` (hasil `php artisan key:generate --show`) dan
   `APP_URL` (URL Render, mis. `https://datatables-sales-purchasing.onrender.com`).
4. Deploy. Saat start, container membuat SQLite baru dan mengisi 1.000 data dummy.

Catatan: pada free plan service tidur setelah ~15 menit tanpa trafik (request pertama lambat),
dan data hasil tambah/edit/hapus kembali ke data seeder saat container restart. Cukup untuk demo.

## Catatan

- Pada mode server-side, tombol export hanya mengekspor baris yang sedang dimuat di halaman.
  Untuk mengekspor semua data, pilih **Semua** di dropdown *Tampilkan data* lalu klik tombol export.
- Untuk memakai MySQL, ubah `DB_*` di `.env` lalu jalankan `php artisan migrate --seed`.
