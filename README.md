# 🛒 Lapaktifikasi Web - Marketplace & E-Commerce Produk Digital

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Midtrans](https://img.shields.io/badge/Midtrans-Payment_Gateway-0099EE?style=for-the-badge)](https://midtrans.com)
[![License](https://img.shields.io/badge/License-MIT-green.style=for-the-badge)](LICENSE)

**Lapaktifikasi Web** adalah platform *e-commerce / marketplace* produk digital dan layanan berbasis Web & RESTful API. Platform ini dirancang untuk memfasilitasi transaksi jual-beli akun digital, lisensi software, produk game, voucher, serta berbagai layanan digital lainnya secara cepat, aman, dan terintegrasi.

---

## 🌟 Fitur Utama

- **🔑 Autentikasi & Multi-Role**:
  - Manajemen peran pengguna: **Admin**, **Seller (Penjual)**, dan **Customer (Pembeli)**.
  - Autentikasi API berbasis **Laravel Sanctum** untuk aplikasi mobile.
  - Integrasi Socialite untuk OAuth (Google/Social Login).
- **🏪 Manajemen Toko & Seller**:
  - Pendaftaran dan pembuatan Toko (*Store Management*).
  - Sistem reputasi seller (*Seller Badge*).
  - Manajemen komisi platform (*Setting Komisi*).
- **📦 Manajemen Produk & Stok**:
  - Manajemen Produk Digital (Varian Layanan, Tipe Layanan, Screenshot Produk).
  - Manajemen Stok Akun digital otomatis (*Auto Delivery / Automatic Account Inventory*).
  - Sistem Rating & Review Produk dari customer.
- **💳 Transaksi & Pembayaran**:
  - Integrasi Payment Gateway **Midtrans** (Auto Notification Webhook & Multi-Payment Channel).
  - Sistem Mutasi Saldo & Wallet Internal.
  - Klaim Voucher & Diskon Promosi.
  - Generator QR Code transaksi.
- **📄 Laporan & Invoice PDF**:
  - Cetak Invoice Pembelian & Laporan Transaksi berbasis PDF (**Laravel DomPDF**).
- **📱 RESTful API untuk Mobile App**:
  - Endpoint API lengkap & terstruktur untuk konsumsi aplikasi Android / iOS. Dokumentasi API dapat dilihat pada file [`api.md`](api.md).

---

## 🛠️ Teknologi & Stack

- **Framework**: [Laravel 10.x](https://laravel.com/)
- **Bahasa Pemrograman**: PHP ^8.1
- **Database**: MySQL / MariaDB
- **Frontend / Asset Bundler**: Vite, Blade Templates, Tailwind / Bootstrap CSS
- **Paket & Dependensi Utama**:
  - `barryvdh/laravel-dompdf`: Generator laporan & invoice PDF
  - `midtrans/midtrans-php`: SDK Integrasi Payment Gateway Midtrans
  - `laravel/sanctum`: Autentikasi token API RESTful
  - `laravel/socialite`: Login via akun pihak ketiga (Social OAuth)
  - `simplesoftwareio/simple-qrcode`: Generator QR Code

---

## 📋 Persyaratan Sistem (System Requirements)

Sebelum memulai instalasi, pastikan sistem Anda telah memenuhi persyaratan berikut:

- **PHP** `>= 8.1` (Ekstensi: `OpenSSL`, `PDO`, `Mbstring`, `Tokenizer`, `XML`, `Ctype`, `JSON`, `BCMath`, `GD`)
- **Composer** `>= 2.0`
- **Node.js** `>= 16.x` & **NPM**
- **Database Server**: MySQL `>= 5.7` / MariaDB `>= 10.3`

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal (development):

### 1. Clone Repository
```bash
git clone https://github.com/ikyy2001/Lapaktifikasi.git
cd lapaktifikasi_web
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Install Dependensi Node.js (Frontend)
```bash
npm install
```

### 4. Konfigurasi Environment File (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan konfigurasi database serta kredensial lainnya:
```env
APP_NAME=Lapaktifikasi
APP_ENV=local
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lapaktifikasi_db
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=your-midtrans-server-key
MIDTRANS_CLIENT_KEY=your-midtrans-client-key
MIDTRANS_IS_PRODUCTION=false
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Database Seeder
Pastikan database sudah dibuat di MySQL (`lapaktifikasi_db`), lalu jalankan:
```bash
php artisan migrate --seed
```

### 7. Tautkan Storage (Storage Link)
```bash
php artisan storage:link
```

### 8. Jalankan Application Server & Asset Compiler
Buka dua jendela terminal terpisah:

**Terminal 1 (Backend Server):**
```bash
php artisan serve
```
Aplikasi backend dapat diakses di: `http://127.0.0.1:8000`

**Terminal 2 (Frontend Development Server):**
```bash
npm run dev
```

---

## 📖 Dokumentasi API

Seluruh endpoint API RESTful untuk integrasi aplikasi mobile terdokumentasi secara rinci di file **[`api.md`](api.md)**.

Ringkasan modul API yang tersedia:
- `POST /api/v1/auth/login` - Login pengguna & generate token Sanctum
- `POST /api/v1/auth/register` - Pendaftaran akun baru
- Modul Toko, Produk, Varian Layanan, & Stok Akun
- Modul Pembelian, Checkout Midtrans, & Webhook Notification
- Modul Wallet/Mutasi Saldo & Voucher

---

## 📁 Struktur Direktori Utama

```text
lapaktifikasi_web/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controller logika bisnis Web & API
│   │   └── Middleware/      # Custom Middleware (Sanctum, Auth, Role)
│   └── Models/              # Eloquent Models (User, Toko, Produk, Pembelian, dll)
├── bootstrap/               # Inisialisasi framework
├── config/                  # Berkas konfigurasi aplikasi & paket
├── database/
│   ├── factories/           # Factory untuk data testing
│   ├── migrations/          # Schema tabel database
│   └── seeders/             # Data awal database
├── public/                  # Public entry point (index.php, storage asset)
├── resources/
│   ├── views/               # Blade Templates
│   ├── css/ & js/           # Source code frontend
├── routes/
│   ├── api.php              # Definisi rute RESTful API (Sanctum)
│   └── web.php              # Definisi rute antarmuka Web
├── storage/                 # Log, cache, & file upload user
├── api.md                   # Dokumentasi lengkap RESTful API
└── composer.json            # Daftar dependensi PHP & package Laravel
```

---

## 📄 Lisensi

Proyek ini dilindungi di bawah lisensi [MIT License](LICENSE).
