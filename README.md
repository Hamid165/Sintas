# CareHub - Sistem Informasi Panti Asuhan (Sintas)

CareHub (Cahaya Asuhan Ruang Empati) adalah sebuah sistem informasi berbasis web responsif yang dirancang khusus untuk mempermudah operasional dan administrasi Panti Asuhan. Aplikasi ini mencakup pencatatan anak asuh, sirkulasi transparansi donasi dan keuangan, rekap inventaris barang, hingga publikasi kegiatan (CMS Berita).

---

## 🚀 Fitur & Menu Aplikasi Layar Admin

Aplikasi ini memiliki beberapa modul (menu) utama, antara lain:

1. **Dashboard**
   Berisi ringkasan statistik (seperti jumlah anak, total pemasukan vs pengeluaran, dll) untuk memantau status panti secara cepat (Overview).
2. **Manajemen Anak**
   Fitur *CRUD* (Create, Read, Update, Delete) untuk mendata profil lengkap anak panti.
3. **Keuangan**
   Pencatatan riwayat transaksi panti. Mencakup:
   - Pencatatan Pemasukan (Donasi uang, dana operasional).
   - Pencatatan Pengeluaran (Kebutuhan panti belanja dll).
   - *Export File* laporan.
4. **Inventaris**
   Pencatatan dan sirkulasi gudang/barang (Barang Masuk / Keluar, peminjaman barang inventaris umum).
5. **Artikel & CMS**
   Pengelolaan artikel atau blog berita publikasi kegiatan panti asuhan ke masyarakat luas.
6. **Profil**
   Manajemen profil/data akun admin pengelola panti, berikut keamanan (ganti password).

---

## 🛠️ Stack Teknologi & Library yang Digunakan

Aplikasi ini cukup modern dan ringan karena memadukan Server-Side API dengan pendekatan Vanilla Javascript DOM Manipulation di Front-End:

- **Framework Backend**: [Laravel 13](https://laravel.com/) (PHP ^8.3).
  - *Library bawaan backend:* `laravel/sanctum` (API Token), `laravel/tinker`, `fakerphp/faker` (untuk seeder data dummy), dan `phpunit` (untuk testing).
- **Styling (CSS Framework)**: Sepenuhnya menggunakan **Tailwind CSS v4** (Build Tool: Vite) untuk menyusun antarmuka yang modern. **(Tidak menggunakan Bootstrap)**.
- **Javascript / Interaksi**:
  - **Vanilla JavaScript (ES6+)** & AJAX dengan mekanisme *Native Fetch API*.
  - Tidak menggunakan jQuery, Vue, ataupun React. Sengaja dibuat *Native JS* agar sangat ringan dan performa *Load* sangat cepat.
- **Autentikasi (Login Auth)**:
  Sistem login menggunakan **Laravel Sanctum**. Authentication ditangani via Token API (`/api/login`), dan token autentikasi disimpan statis di dalam *Local Storage Browser*.
- **Asset Eksternal (CDN)**:
  - **Icon Set**: [Lucide Icons](https://lucide.dev) via CDN (`https://unpkg.com/lucide@latest`).
  - **Font**: Google Fonts (Plus Jakarta Sans).
  - **Library Export Data**: Menggunakan library **SheetJS (xlsx)** via CDN (`https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js`) untuk mengekspor data tabel secara langsung menjadi file berformat **Excel (.xlsx)**.

---

## ⚙️ Cara Instalasi (Instruksi Setup Lokal)

Ikuti instruksi di bawah untuk menjalankan CareHub di komputer kamu (Laragon / XAMPP):

1. **Clone atau Ekstrak Project**
   Pastikan kamu sudah membuka folder project `sintas-app-panti-asuhan` di dalam terminal (CMD, PowerShell, atau Terminal VS Code).
2. **Install Dependensi Software**
   Kamu butuh `composer` dan `npm`. Jalankan:

   ```bash
   composer install
   npm install
   ```
3. **Konfigurasi Environment**
   Kamu perlu menyalin file `.env.example` lalu ubah namanya menjadi `.env`.

   - Di Windows (CMD): `copy .env.example .env`
   - Buka file `.env`, lalu cari dan atur koneksi database:
     ```env
     DB_CONNECTION=mysql 
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=nama_database_kamu
     DB_USERNAME=root
     DB_PASSWORD=
     ```

   *(Pastikan kamu telah membuat database kosong di MySQL / PhpMyAdmin sebelumnya).*
4. **Generate Application Key**
   Buat keamanan enkripsi kunci utama aplikasi (App Key):

   ```bash
   php artisan key:generate
   ```
5. **Migrasi dan Data Dummy (Seeding)**
   Eksekusi pembuatan struktur tabel ke database (termasuk dummy data admin):

   ```bash
   php artisan migrate --seed
   ```
6. **Jalankan Aplikasi**
   Karena aplikasi ini menggunakan Laravel 13 dan Tailwind (Vite), kamu bisa langsung menjalankan perintah ini agar server PHP lokal dan Build Assets Vite berjalan sejajar:

   ```bash
   composer run dev
   ```

   Email	admin@CareHub.com
   Password	password123

   *(Atau secara terpisah)*:
   Buka terminal 1 jalankan `php artisan serve`, buka terminal 2 jalankan `npm run dev`.
7. **Akses Aplikasi**
   Silakan buka Web Browser (Chrome, Edge, Firefox), dan akses alamat:
   `http://127.0.0.1:8000` atau `http://localhost:8000`
