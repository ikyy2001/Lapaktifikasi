# Product Requirements Document (PRD)
**Project Name:** Lapaktifikasi Marketplace  
**Document Version:** 1.0  
**Date:** 5 Agustus 2026  

---

## 1. Executive Summary
**Lapaktifikasi** adalah sebuah platform marketplace *multi-tenant* yang memfasilitasi transaksi jual-beli produk digital (seperti source code, lisensi, e-book) maupun layanan premium/fisik antara Penjual (Seller) dan Pembeli (Customer). Platform ini dilengkapi dengan fitur gamifikasi pelanggan seperti sistem *Tier* (Membership) dan *Referral*, serta manajemen pembayaran otomatis, voucher diskon, dan sistem *ban* untuk menjaga keamanan ekosistem.

## 2. Visi dan Tujuan Produk
**Visi:** Menjadi platform marketplace digital terpercaya yang memudahkan developer dan kreator (Seller) untuk menjual produk mereka dengan sistem bagi hasil dan manajemen otomatis yang transparan.
**Tujuan:**
- Memberikan wadah bagi Seller untuk mengelola etalase toko dan inventaris produk secara mandiri.
- Memberikan pengalaman belanja yang aman, cepat, dan *rewarding* bagi Customer.
- Menyediakan dashboard komprehensif bagi Admin untuk mengontrol seluruh arus transaksi, komisi, dan *user behavior* (melalui sistem ban & maintenance).

## 3. Target Pengguna (User Personas)
Terdapat 3 tipe pengguna (Role) utama dalam platform:

1. **Admin (Role ID: 1)**
   - Pemilik platform.
   - Bertugas mengelola seller, komisi, saldo/penarikan dana toko, memonitor transaksi, mengatur voucher global, dan menjaga keamanan sistem (Ban/Unban pengguna).
2. **Customer / Pembeli (Role ID: 2)**
   - Pengguna akhir yang mencari dan membeli produk premium atau digital.
   - Memiliki profil khusus, tingkatan (Tier/Level) berdasarkan akumulasi belanja, dan sistem referral untuk mengundang teman.
3. **Seller / Pemilik Toko (Role ID: 3)**
   - Kreator atau developer yang menjual produk.
   - Bertanggung jawab mengelola profil toko, inventaris barang (Premium & Digital), melihat mutasi saldo, dan mengatur voucher spesifik tokonya sendiri.

---

## 4. Fitur Utama (Core Features)

### 4.1. Modul Autentikasi & Akun
- **Pendaftaran & Login:** Mendukung registrasi email standar dan SSO (Google Auth).
- **Manajemen Password:** Fasilitas ubah password dan *Forgot Password* via email (Reset Link).
- **Role-Based Access Control (RBAC):** Middleware ketat yang memisahkan akses Admin, Seller, dan Customer, termasuk mode Maintenance yang memblokir akses selain Admin.

### 4.2. Modul Toko (Seller Storefront)
- **Toko & Katalog:** Setiap Seller memiliki 1 entitas toko dengan URL unik (slug berbasis nama toko, misal: `/toko/toko-keren/produk`).
- **Badge Kepercayaan:** Admin dapat memberikan badge (misal: "Official Store", "Recommended") kepada toko. Seller dapat melihat koleksi badge mereka.
- **Informasi Toko:** Mencakup logo, deskripsi, nomor telepon, dan integrasi username Telegram untuk komunikasi langsung.

### 4.3. Modul Manajemen Produk
- Terbagi menjadi dua kategori utama: **Produk Premium** dan **Produk Digital**.
- **Tipe & Varian Layanan:** Produk dapat memiliki banyak tipe dan varian (contoh: Varian Basic, Pro, Enterprise) dengan harga yang berbeda.
- **Manajemen Stok:** Sistem pengurangan stok otomatis saat ada pesanan tertunda (reserved) dan pengembalian stok jika pesanan dibatalkan/kadaluarsa (Mencegah masalah N+1 dan stok minus).

### 4.4. Modul Transaksi & Pembayaran
- **Integrasi Payment Gateway:** Menggunakan **Midtrans** untuk pembuatan *Snap Token* dan notifikasi *Callback* otomatis (Webhook).
- **Alur Transaksi (Checkout):** Pengecekan kelengkapan profil (No WA/HP) -> Aplikasi Voucher -> Lock Stok (15 Menit) -> Generate Invoice -> Proses Pembayaran.
- **Distribusi Saldo:** Pendapatan dari transaksi sukses akan otomatis dimasukkan ke "Saldo Toko" setelah dipotong persentase komisi platform (Bisa custom komisi per-toko atau global).

### 4.5. Modul Gamifikasi Customer
- **Sistem Tier (Membership):** Customer memiliki Tier (contoh: Bronze, Silver, Gold). Tier naik secara otomatis seiring bertambahnya *Akumulasi Total Belanja*.
- **Sistem Referral:** Setiap Customer memiliki kode referral. Kode ini bisa dibagikan untuk mendapatkan *reward* (tercatat di `jumlah_referral_sukses`).
- **Klaim Voucher:** Voucher bisa diterapkan berdasarkan syarat (Minimal transaksi, Kuota, Berlaku di toko tertentu atau global).

### 4.6. Modul Moderasi & Keamanan
- **Ban System:** Admin dapat melakukan blokir (Ban) pada akun Customer nakal atau Toko/Seller yang melanggar ketentuan. Menyertakan alasan (reason) yang akan ditampilkan di layar pengguna yang diblokir.
- **Maintenance Mode:** Tombol darurat (Panic Button) bagi Admin untuk menutup akses web secara sementara bagi Customer dan Seller saat ada perbaikan infrastruktur.

---

## 5. Arsitektur & Teknologi

- **Backend:** PHP 8.x dengan Framework Laravel 10/11.
- **Database:** MySQL / MariaDB (Relational Database).
- **Frontend / UI:** Blade Templating Engine, Bootstrap 4, Stisla Admin Template, jQuery.
- **Styling UI:** Custom CSS *Glassmorphism* dan animasi interaktif modern.
- **Payment Gateway:** Midtrans (Snap & Core API).
- **Server Environment:** Berjalan pada OS Linux/Windows dengan Nginx/Apache.

## 6. Struktur Database Utama (Key Entities)
- `users`: Data autentikasi, role, status `is_banned`.
- `tbl_customer`: Relasi 1-1 ke `users` role 2. Berisi detail tier, total belanja, dan kode referral.
- `tbl_toko`: Relasi 1-1 ke `users` role 3. Berisi nama toko, slug, saldo, dan status ban toko.
- `tbl_produk`, `tbl_tipe_layanan`, `tbl_varian_layanan`, `tbl_stok_akun`: Relasi berantai untuk katalogisasi inventaris.
- `tbl_pembelian`: Mencatat transaksi (order_id unik, voucher, diskon, status).
- `tbl_voucher` & `tbl_voucher_klaim`: Mengatur kode promo dan riwayat penggunaannya.

## 7. Kriteria Penerimaan (Acceptance Criteria) Umum
- **Mobile Responsive:** Seluruh halaman, mulai dari katalog hingga dashboard, harus dapat beradaptasi dan beroperasi tanpa hambatan di perangkat seluler.
- **Keamanan Transaksi:** Fitur *Race condition* pada stok harus tertangani dengan mekanisme *Database Lock* (`lockForUpdate`).
- **Performa:** Render halaman katalog harus ringan, bebas N+1 query problem, dan *smooth scrolling* di smartphone.

---
*Dokumen ini merupakan deskripsi spesifikasi teknis dan produk Lapaktifikasi, digunakan sebagai acuan pengembangan dan pemeliharaan jangka panjang.*
