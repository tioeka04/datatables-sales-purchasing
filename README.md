# Sales & Purchasing — DataTables Edition

Tugas Day 13: implementasi **DataTables** dan **DataTables Extension** di Laravel 12,
melanjutkan domain *Sales & Purchasing* dari tugas sebelumnya (master Product & Supplier).

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

## Catatan

- Pada mode server-side, tombol export hanya mengekspor baris yang sedang dimuat di halaman.
  Untuk mengekspor semua data, pilih **Semua** di dropdown *Tampilkan data* lalu klik tombol export.
- Untuk memakai MySQL, ubah `DB_*` di `.env` lalu jalankan `php artisan migrate --seed`.
