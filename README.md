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
6. **Buku Tamu / Kunjungan**
   Buku tamu digital yang merekam nama tamu, instansi, tanggal kunjungan, serta laporan dan dokumentasi kegiatan.
7. **Audit Dokumen (Sekretariat & Keuangan)**
   Modul pencatatan *Surat Masuk* dan *Surat Keluar*, serta sinkronisasi dengan bukti transaksi keuangan.
8. **Manajemen SDM / Struktur Organisasi**
   Pendataan pengurus, relawan, dan staf panti asuhan beserta jabatan organisasinya.
9. **Hak Akses (RBAC & Manajemen Role)**
   Pengaturan tingkatan akses pengguna (Admin, Bendahara, Sekretaris, Karyawan) secara dinamis untuk membatasi halaman dan data yang bisa diakses.
10. **Profil**
    Manajemen profil/data akun admin pengelola panti, berikut keamanan (ganti password).


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

---

---

# 📚 DOKUMENTASI TEKNIS & ARSITEKTUR LANJUTAN

*Bagian di bawah ini merupakan penjelasan lebih mendalam (Deep-Dive) mengenai arsitektur Real-Time, Library Backend/Frontend, dan Struktur Folder kode sumber.*

## 🛠️ Stack Teknologi Tambahan (Deep Dive)

### 1. Core Framework & Bahasa

- **Backend:** Laravel (v13.x / PHP 8.3+) sebagai pondasi utama logika backend dan REST API.
- **Frontend Styling:** Tailwind CSS v4 (via Vite) untuk styling antarmuka (UI) secara cepat dan konsisten.
- **Frontend Logic:** Vanilla JavaScript (ES6+), tidak menggunakan React/Vue agar aplikasi tetap ringan, namun direkayasa terasa seperti Single Page Application (SPA).
- **Database:** MySQL

### 2. Autentikasi & Otorisasi

- **Laravel Sanctum:** Digunakan untuk autentikasi dan manajemen *API Token* secara aman. Cocok untuk arsitektur SPA dan disiapkan untuk integrasi mobile (CareHub Mobile) ke depannya.
- **Spatie Laravel Permission (`spatie/laravel-permission`):** Library utama untuk mengatur *Role-Based Access Control* (RBAC) secara penuh. Digunakan untuk:
  - Mendefinisikan *Permission* per aksi (contoh: `view_anak`, `create_anak`, `delete_keuangan`).
  - Meng-assign *Role* ke user (`admin`, `bendahara`, `sekretariat`, `karyawan`).
  - Mengontrol visibilitas menu sidebar via `@can()` di Blade.
  - Mengontrol akses rute web via middleware `permission:view_xxx`.
  - Mengontrol akses API CRUD via middleware `permission:create_xxx` / `delete_xxx`.

### 3. Arsitektur Real-Time (WebSockets)

Agar semua klien melihat pembaruan data secara bersamaan tanpa me-refresh halaman:

- **Pusher Channels (Cloud):** Layanan WebSocket eksternal agar mudah di-*deploy* di *shared hosting*.
- **`pusher/pusher-php-server` (Backend):** Composer library untuk mengirimkan (*trigger*) sinyal event (seperti `AnakUpdated`) dari server Laravel ke Pusher.
- **`laravel-echo` & `pusher-js` (Frontend):** NPM library untuk mendengarkan (subscribe) channel Pusher di browser dan memicu notifikasi.

### 4. Library Frontend Eksternal (via NPM/CDN)

- **`axios`:** HTTP Client yang digunakan bersama Fetch API untuk mempermudah panggilan API secara asinkron (AJAX) dari klien ke backend.
- **Lucide Icons:** Library ikon berdesain modern dan elegan (di-load di sisi klien).
- **Google Fonts (Plus Jakarta Sans):** Font modern dan bersih yang digunakan sebagai tipografi utama aplikasi.
- **`jsPDF` & `jsPDF-AutoTable`:** Library untuk mem-parsing tabel HTML menjadi dokumen PDF langsung dari browser pengguna, sehingga tidak memberatkan *resource* server.
- **`SheetJS (xlsx)`:** Library untuk mengekspor data array JSON menjadi file Excel (`.xlsx`) dan `.csv` secara *client-side*.

### 5. Environment & Dev Tools (Peralatan Developer)

- **FakerPHP (`fakerphp/faker`):** Library untuk melakukan *seeding* / men-generate data palsu (nama, tanggal, alamat) guna keperluan *testing* database.
- **Laravel Pint (`laravel/pint`):** Alat bawaan Laravel untuk memformat kode PHP agar rapi dan mengikuti standar penulisan yang benar.
- **Laravel Sail (`laravel/sail`):** Interface Docker untuk mempermudah pengembangan aplikasi di lingkungan lokal berbasis kontainer.
- **PHPUnit & Mockery:** Alat pengujian (*testing*) logika backend untuk memastikan kode bebas bug.
- **Collision (`nunomaduro/collision`):** Menampilkan pesan error yang sangat detail dan cantik di terminal saat terjadi *crash* di backend.
- **Concurrently (`concurrently`):** NPM package untuk menjalankan beberapa *server command* (`php artisan serve`, Vite, Queue) secara bersamaan hanya dengan 1 kali klik.

---

## 🚀 Daftar Fitur & Modul Sistem (Alur Teknis)

### 1. Modul Autentikasi & Otorisasi 

- **Login:** Autentikasi dibuat manual menggunakan validasi *Auth::attempt* dan Session/Token API bawaan Laravel (tidak menggunakan Laravel Breeze/Jetstream agar UI lebih fleksibel).
- **Role-Based Access Control (RBAC) — Full Integration:**
  - Admin dapat mengatur permission per-role melalui panel **Hak Akses (RBAC)** secara dinamis.
  - Perubahan permission **langsung berlaku** tanpa restart server (permission cache di-flush otomatis via `forgetCachedPermissions()` setelah setiap update).
  - **Sidebar menu** muncul/hilang otomatis berdasarkan `@can('view_xxx')` dari Spatie.
  - **Tombol CRUD** (Tambah/Edit/Hapus) di setiap halaman dikontrol via `window.__can()` — variabel permission di-inject dari server ke JavaScript saat halaman dimuat.
  - **Route Web** dilindungi middleware `permission:view_xxx` / `permission:create_xxx`.
  - **API Endpoint** CRUD dilindungi middleware `permission:create/edit/delete_xxx` sehingga mutasi data dari luar pun ikut terkontrol.
  - Middleware `role` dan `permission` menggunakan **Spatie's official middleware** (`RoleMiddleware`, `PermissionMiddleware`) yang dikonfigurasi di `bootstrap/app.php`.
- **Lupa Password:** Sistem OTP (One Time Password) sederhana yang dikirim/divalidasi manual melalui API endpoint tanpa library tambahan yang rumit.

### 2. Modul Manajemen Anak Asuh (Real-time)

- Fitur CRUD lengkap untuk biodata anak.
- Dukungan *upload* foto profil.
- Jika satu user menambah/mengedit data anak, user lain di ruangan/komputer berbeda akan langsung melihat perubahannya (*Real-time Pusher `AnakUpdated`*).

### 3. Modul Manajemen Keuangan (Real-time)

- Pencatatan Pemasukan & Pengeluaran kas panti.
- Filter berdasarkan kategori (Donasi, Sembako, Kebutuhan Pokok, dll).
- Real-time *toast notification* ("Ada data keuangan baru masuk") jika bendahara lain melakukan *input*.
- Ekspor rekap keuangan ke PDF, Excel, dan CSV.

### 4. Modul Manajemen Inventaris (Real-time)

- Pencatatan aset panti asuhan, jumlah stok, kategori, dan kondisi barang (Baik/Rusak).
- Sinkronisasi instan (*Real-time Pusher `InventarisUpdated`*) jika ada pengambilan/pengurangan stok dari staf logistik.

### 5. Modul Kunjungan Tamu (Real-time)

- Buku tamu digital yang merekam nama tamu, instansi, tanggal kunjungan, foto kegiatan, dan laporan kegiatan.
- Dilengkapi dengan *pop-up modal* detail laporan yang elegan.

### 6. Modul Audit (Sekretariat & Keuangan)

- **Audit Sekretariat:** Modul pencatatan *Surat Masuk* dan *Surat Keluar* dengan fitur pengurutan (sorting) pintar berdasarkan tanggal/kode surat.
- **Audit Keuangan:** Validasi bukti dokumen transaksi yang terintegrasi langsung dengan nomor dokumen *Surat Masuk/Keluar*.
- Seluruh modul audit juga diintegrasikan dengan Pusher agar proses cek dokumen lintas-divisi terjadi secara langsung tanpa perlu lapor manual.

### 7. Dashboard Eksekutif (Live Monitoring)

- Halaman utama yang merangkum seluruh statistik: Total Anak, Total Inventaris, Saldo Keuangan, dan Total Kunjungan.
- *Dashboard* ini bersifat "hidup", di mana angkanya akan berubah seketika (*live update*) mengikuti aktivitas di semua modul yang dilakukan oleh user mana pun.

### 8. Manajemen SDM / Struktur Organisasi
- Modul pendataan kepengurusan panti asuhan, mencakup data relawan, staf, pengasuh, dan jabatan masing-masing dalam struktur organisasi.

### 9. Hak Akses (RBAC & Manajemen Role)
- Panel RBAC khusus Admin untuk mengatur permission setiap role secara dinamis (toggle ON/OFF per menu & aksi CRUD).
- Perubahan permission berlaku **real-time** — cache Spatie otomatis di-flush setelah setiap penyimpanan.
- Integrasi penuh dari **sidebar → route → API**: role yang diberi akses akan langsung mendapat menu, bisa mengakses halaman, dan bisa melakukan operasi CRUD sesuai permission yang diberikan.
- Permission yang tersedia: `view`, `create`, `edit`, `delete` untuk setiap modul: `anak`, `keuangan`, `kunjungan`, `inventori`, `surat`, `audit`, `sdm`.

### 10. Profil Pengguna
- Halaman profil **dinamis** — menampilkan nama role sesuai pengguna yang sedang login (Admin, Bendahara, Sekretariat, Karyawan).
- Mendukung ganti nama, email, foto profil, dan password.

---



## 📂 Struktur Folder & File Utama

Berikut adalah pemetaan file-file krusial yang menyusun logika aplikasi, dibagi berdasarkan fungsinya:

### 1. Struktur REST API (Backend Data)

Menangani seluruh operasi CRUD secara *background* (AJAX/Fetch) tanpa me-refresh halaman.

```text
sintas-app-panti-asuhan/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/                   # Logika pengolahan data JSON
│   │           ├── AnakController.php
│   │           ├── ArtikelController.php
│   │           ├── InventarisController.php
│   │           ├── KeuanganController.php
│   │           ├── KunjunganTamuController.php
│   │           └── ProfilController.php
│   └── Models/                        # Representasi Tabel Database (Eloquent)
│       ├── Anak.php
│       ├── Artikel.php
│       ├── AuditKeuangan.php
│       ├── Inventaris.php
│       ├── Keuangan.php
│       ├── KunjunganTamu.php
│       ├── Profil.php
│       ├── SuratKeluar.php
│       └── SuratMasuk.php
└── routes/
    └── api.php                        # Daftar endpoint API (/api/...)
```

### 2. Struktur Tampilan Web & Autentikasi (Frontend UI)

Menangani sistem *Routing* halaman, antarmuka pengguna (Blade), dan keamanan (Login & Role).

```text
sintas-app-panti-asuhan/
├── app/
│   ├── Http/
│   │   └── Controllers/               # Controller khusus render halaman (View)
│   │       ├── AuditKeuanganController.php
│   │       ├── AuditSekreteriatController.php
│   │       ├── AuthController.php     # Logika Login manual
│   │       ├── DashboardController.php
│   │       ├── ExportController.php
│   │       └── RoleController.php
│   └── Models/
│       └── User.php                   # Model yang berelasi dengan Spatie RBAC
├── resources/
│   ├── css/
│   │   └── app.css                    # Styling utama Tailwind CSS v4
│   └── views/
│       ├── layouts/
│       │   └── admin.blade.php        # Layout utama (sidebar @can, window.__perms JS injection)
│       └── admin/                     # Tampilan halaman utama per modul
│           ├── anak/index.blade.php
│           ├── audit/index.blade.php
│           ├── inventori/index.blade.php
│           ├── keuangan/index.blade.php
│           ├── kunjungan/index.blade.php
│           ├── profil.blade.php       # Profil dinamis (role dari Auth::user()->role)
│           └── sdm/role.blade.php     # Panel RBAC Admin
└── routes/
    ├── web.php                        # Routing halaman + middleware permission:view/create_xxx
    └── api.php                        # API endpoint + middleware permission:create/edit/delete_xxx
```

### 3. Struktur Real-Time (Pusher & WebSocket)

Bertanggung jawab atas sinkronisasi data antar-klien secara langsung tanpa jeda.

```text
sintas-app-panti-asuhan/
├── app/
│   └── Events/                        # Kelas pemicu (trigger) sinyal ke Pusher
│       ├── AnakUpdated.php
│       ├── AuditKeuanganUpdated.php
│       ├── InventarisUpdated.php
│       ├── KeuanganUpdated.php
│       ├── KunjunganUpdated.php
│       ├── SuratKeluarUpdated.php
│       └── SuratMasukUpdated.php
├── resources/
│   └── js/
│       └── bootstrap.js               # Konfigurasi koneksi klien: Pusher-js & Laravel Echo
└── routes/
    └── channels.php                   # Otorisasi & definisi nama saluran (Channel) Pusher
```
