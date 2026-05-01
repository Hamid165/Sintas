# Dokumentasi Arsitektur & Fitur Aplikasi (CareHub Panti Asuhan)

Aplikasi manajemen panti asuhan (CareHub) ini dibangun dengan arsitektur modern yang memisahkan backend API dan frontend SPA-like (Single Page Application) meskipun menggunakan Blade template. Aplikasi ini dirancang untuk sangat reaktif, cepat, dan *real-time*.

## 🛠️ Stack Teknologi & Library yang Digunakan

### 1. Core Framework & Bahasa

- **Backend:** Laravel (v13.x / PHP 8.3+) sebagai pondasi utama logika backend dan REST API.
- **Frontend Styling:** Tailwind CSS v4 (via Vite) untuk styling antarmuka (UI) secara cepat dan konsisten.
- **Frontend Logic:** Vanilla JavaScript (ES6+), tidak menggunakan React/Vue agar aplikasi tetap ringan, namun direkayasa terasa seperti Single Page Application (SPA).
- **Database:** MySQL

### 2. Autentikasi & Otorisasi

- **Laravel Sanctum:** Digunakan untuk autentikasi dan manajemen *API Token* secara aman. Cocok untuk arsitektur SPA dan disiapkan untuk integrasi mobile (CareHub Mobile) ke depannya.
- **Spatie Laravel Permission:** Library andalan untuk mengatur *Role-Based Access Control* (RBAC). Digunakan untuk memberikan hak akses spesifik ke role seperti Admin, Bendahara, dan Sekretariat.

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

## 🚀 Daftar Fitur & Modul Sistem

### 1. Modul Autentikasi & Otorisasi (Manual Coding)

- **Login:** Autentikasi dibuat manual menggunakan validasi *Auth::attempt* dan Session/Token API bawaan Laravel (tidak menggunakan Laravel Breeze/Jetstream agar UI lebih fleksibel).
- **Role-Based Access Control (RBAC):** Akses menu dibatasi berdasarkan *Role* (Admin, Bendahara, Sekretariat, Karyawan) melalui *Middleware* Laravel dan integrasi dengan `spatie/laravel-permission`.
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

---

## 🏗️ Pola Desain (Design Pattern)

- **SPA Feel:** Meskipun menggunakan rendering awal *Blade* (`.blade.php`), semua operasi CRUD selanjutnya (Tambah, Edit, Hapus, Pagination, Sorting) dilakukan via **AJAX (Fetch API)**. Sehingga halaman tidak pernah *loading/berkedip* putih.
- **Client-Side Exporting:** Beban pembuatan dokumen PDF dan Excel dilempar ke *browser client* pengguna untuk menghemat RAM dan CPU di server.
- **Event-Driven Broadcasting:** Implementasi standar *Observer Pattern* melalui Laravel Events (`ShouldBroadcastNow`) untuk komunikasi *backend* ke *frontend*.

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
│       └── admin/                     # Tampilan halaman utama per modul
│           ├── anak/index.blade.php
│           ├── audit/index.blade.php
│           ├── inventori/index.blade.php
│           ├── keuangan/index.blade.php
│           └── kunjungan/index.blade.php
└── routes/
    └── web.php                        # Daftar routing halaman web & Middleware
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
