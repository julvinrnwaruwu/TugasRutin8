# Tugas Rutin 8 — CRUD Inventaris

## Menjalankan di XAMPP
1. Ekstrak folder ini ke `C:\xampp\htdocs\Tugas_Rutin_8_CRUD_Inventaris`.
2. Jalankan Apache dan MySQL di XAMPP.
3. Buka phpMyAdmin (`http://localhost/phpmyadmin`) lalu import `database/inventaris_db.sql`.
4. Buka `http://localhost/Tugas_Rutin_8_CRUD_Inventaris/public/`.

Default database di `config/database.php`: host `127.0.0.1`, user `root`, password kosong. Sesuaikan jika konfigurasi MySQL kamu berbeda.

## Isi dan fitur
- Tiga tabel utama: `categories`, `suppliers`, `products`, dengan foreign key.
- Lima data seed per tabel utama.
- PDO Singleton, prepared statements, JOIN produk-kategori-supplier.
- Create, read, update, delete dengan konfirmasi hapus.
- Dropdown kategori/supplier, validasi input, `htmlspecialchars()`.
- Flash message + redirect pattern, pencarian, pagination, log aktivitas.
- UI responsif.

Tabel `activity_logs` merupakan tabel tambahan untuk bonus log aktivitas. Project dibuat untuk lingkungan tugas lokal; jangan gunakan kredensial default untuk server publik.
