# Dokumentasi Fitur Lengkap Ecosystem Lapaktifikasi

Dokumen ini berisi daftarkan seluruh fitur yang tersedia dalam platform **Lapaktifikasi** (*digital marketplace & e-commerce*) yang terbagi untuk 3 role utama (**Admin**, **Seller**, dan **Customer**), serta fitur arsitektur & keamanan ekosistem.

---

## 1. Fitur Pengelola (Admin Panel / Super Admin)

Super Admin memiliki kendali penuh atas manajemen platform, pengelolaan seller, kebijakan komisi, transaksi, serta penanganan komplain customer.

### 📊 Dashboard & Monitoring Platform
- **Super Dashboard Analytics**: Menampilkan statistik statistik real-time total transaksi, total penjualan (GMV), total komisi platform, dan grafik pertumbuhan transaksi.
- **Monitoring Penjualan Global**: Rekapitulasi transaksi harian/bulanan dari seluruh toko seller.
- **Monitoring Pembayaran & Status Order**: Meninjau status pesanan (Pending, Paid, Expired, Failed).

### 🏪 Kelola Seller & Badge Verifikasi Toko
- **Daftar & Detail Seller**: Melihat daftar seluruh seller/toko yang terdaftar di platform beserta detail profil & kinerjanya.
- **Tambah / Daftarkan Seller Baru**: Pendaftaran akun seller baru secara langsung melalui Admin Panel.
- **Update Status Seller (Activation Toggle)**: Mengaktifkan (*Activate*) atau menonaktifkan (*Deactivate*) toko seller.
- **Management Badge Default**: Memasang (*attach*) dan melepas (*detach*) badge reputasi resmi (seperti *Verified Seller*, *Top Merchant*, *Trusted Seller*) ke toko seller.
- **Custom Badge Generator**: Membuat *custom badge* khusus per toko (terdiri dari nama badge, ikon custom, dan deskripsi).

### ⚙️ Pengaturan Komisi Platform & Batas File (Setting Komisi)
- **Atur Persentase Komisi Platform**: Mengkonfigurasi persentase pemotongan komisi platform secara terpusat untuk setiap transaksi.
- **Atur Batas Ukuran File Digital (Digital File Limit)**: Mengatur batasan maksimal file upload produk digital (dalam Megabyte/MB).

### 💰 Kelola Saldo Toko & Pencairan Dana (Withdrawal)
- **Monitoring Saldo Toko**: Melihat saldo berjalan seluruh toko seller secara terpusat.
- **Detail Mutasi Saldo**: Melihat riwayat debit/kredit mutasi saldo per toko.
- **Proses Penarikan Dana (Withdrawal)**: Memproses penarikan saldo toko ke rekening penjual.

### 🎟️ Kelola Voucher Admin (Global Discount Voucher)
- **Pembuatan Voucher Platform**: Membuat kode promo diskon skala nasional/platform yang dapat digunakan customer di seluruh toko.
- **Pengaturan Syarat & Ketentuan Voucher**:
  - Tipe diskon: Nominal Rp atau Persentase (%).
  - Minimal pembelian & Maksimal potongan diskon.
  - Kuota batas penggunaan voucher & Periode masa berlaku.
- **Toggle Status Voucher**: Mengaktifkan atau mematikan voucher diskon admin secara instant.

### 📦 Manajemen Inventaris Admin (Premium & Digital)
- **Manajemen Produk & Layanan Admin**: Mengelola tipe layanan, varian layanan, dan stok akun premium milik platform.
- **Manajemen File Digital Admin**: Mengelola upload file digital, tipe, varian, dan stok file untuk produk digital platform.

### 🛠️ Pengaduan & Laporan Komplain Customer (Dispute Resolution)
- **Kelola Laporan Customer**: Melihat daftar laporan/komplain kendala pesanan dari pembeli.
- **Update Status Laporan**: Mengubah status komplain (*Pending*, *In Progress*, *Resolved*, *Rejected*) disertai catatan resolusi admin.

### 📲 WhatsApp Notification & Retry Engine
- **Monitoring Log Pengiriman WA**: Memantau pengiriman pesan notifikasi WhatsApp (kredensial/invoice) ke pembeli via gateway (Fonnte).
- **Manual Retry WA Notification**: Fitur pengiriman ulang pesan WhatsApp secara manual jika pengiriman otomatis mengalami kegagalan/delay.

---

## 2. Fitur Penjual (Seller / Toko)

Seller adalah mitra penjual yang mengelola toko, menambahkan produk (premium & digital), menginput stok kredensial, serta membuat promo khusus toko.

### 📈 Dashboard & Ringkasan Toko
- **Seller Dashboard Analytics**: Ringkasan total pendapatan toko, total transaksi sukses, statistik penjualan harian/bulanan, dan statistik stok produk.
- **Peringatan Stok**: Peringatan real-time jika stok akun/varian produk toko hampir habis atau kosong.

### 💵 Mutasi Saldo & Pendapatan Toko
- **Riwayat Mutasi Saldo Transparan**: Pencatatan otomatis mutasi saldo (Kredit saat barang lunas, Debit saat potongan komisi platform atau withdrawal).
- **Rincian Potongan Komisi**: Menampilkan rincian komisi platform yang dipotong pada setiap transaksi.

### 🏪 Pengaturan Profil Toko & Reputation Badges
- **Manajemen Identitas Toko**: Mengubah nama toko, deskripsi toko, logo/banner toko, alamat, dan nomor kontak toko.
- **Display Seller Badges**: Menampilkan badge reputasi/kepercayaan yang diberikan oleh Admin pada halaman profil toko.

### 📱 Manajemen Produk Premium (Akun Streaming & Aplikasi)
- **CRUD Produk Premium**: Menambah, mengedit, dan menghapus katalog produk premium (misal: Netflix, Spotify, Canva, Windows, dll.).
- **Upload Screenshot & Visual Media**: Mengunggah gambar utama produk dan gambar screenshot galeri pendukung.
- **Manajemen Tipe & Varian Layanan**:
  - Mengatur tipe durasi (misal: 1 Bulan, 3 Bulan, 1 Tahun).
  - Mengatur varian akun (Private Account, Sharing Account, Profile Slot, dll.) beserta harganya.

### 💾 Manajemen Produk Digital (Software, Source Code, Ebook, File)
- **CRUD Produk Digital**: Menambah, mengedit, dan menghapus produk berbentuk file digital.
- **Upload File Produk Digital**: Mengunggah file (ZIP, RAR, PDF, dll.) dengan validasi batas ukuran file sesuai pengaturan komisi platform.
- **Pengaturan Varian & Harga File**: Mengatur opsi file/versi file digital beserta variasi harganya.

### 🔐 Manajemen Inventaris Stok Terenkripsi (Secured Credential Management)
- **Input Stok Kredensial**: Menambahkan stok kredensial (Username, Password, Catatan Tambahan) per varian layanan.
- **Bulk Upload Stok**: Fitur pengunggahan stok akun dalam jumlah banyak secara simultan (*mass upload*).
- **Enkripsi Kredensial Otomatis**: Kredensial sensitif dienkripsi secara otomatis di database menggunakan standar enkripsi kuat (*AES-256 via Laravel Crypt*).
- **Dekripsi & Inpeksi Stok**: Seller dapat membuka (*decrypt*) dan melihat kredensial stok yang diinput.
- **Monitoring Status Stok**: Menampilkan status stok (*Tersedia*, *Terjual*, *Reserved*).

### 🏷️ Manajemen Voucher Toko (Store Discount Voucher)
- **Pembuatan Voucher Toko**: Membuat voucher diskon khusus yang hanya berlaku untuk produk-produk di toko seller sendiri.
- **Pengaturan Kuota & Syarat**: Mengatur persentase/nominal diskon, minimal belanja, jumlah kuota klaim, dan tanggal aktif/kadaluarsa.
- **Toggle Status Voucher Toko**: Mematikan atau mengaktifkan kembali voucher toko kapan saja.

### 📜 Histori Penjualan Toko
- **Daftar Pesanan Masuk**: Melihat daftar seluruh transaksi pesanan yang masuk ke toko secara detail.
- **Rincian Transaksi**: Melihat varian yang dipesan, harga, identitas pembeli, dan status pengiriman kredensial.

---

## 3. Fitur Pembeli (Customer)

Customer adalah pengguna yang membeli produk digital atau layanan premium di platform.

### 🛍️ Katalog Produk & Eksplorasi Toko
- **Katalog Produk Multi-Toko**: Menjelajahi katalog produk premium dan digital dari seluruh seller di platform.
- **Halaman Detail Toko**: Melihat halaman khusus toko, daftar produk toko, informasi profil toko, dan badge kepercayaan toko.
- **Pencarian & Filter Produk**: Pencarian produk berdasarkan nama, tipe, varian, maupun toko seller.
- **Informasi Stok Real-time**: Menampilkan ketersediaan stok akun/varian produk secara akurat sebelum checkout.

### 🛒 Checkout & Pembayaran Otomatis Multi-Gateway
- **Sistem Checkout Cepat**: Menghitung otomatis total harga (subtotal + kode unik/biaya layanan - potongan voucher).
- **Integrasi Multi Payment Gateway**:
  - **Midtrans**: QRIS instan, Virtual Account Bank (BCA, Mandiri, BNI, BRI, Permata), E-Wallet (GoPay, ShopeePay), dan Kartu Kredit.
  - **Pakasir**: QRIS & E-Wallet instan.
- **Countdown Timer Pembayaran**: Menampilkan waktu tersisa untuk menyelesaikan pembayaran sebelum transaksi kadaluarsa (*Expired*).
- **Reservasi Stok Otomatis**: Memblokir/mengamankan stok saat checkout untuk mencegah *double booking*.

### ⚡ Pengiriman Kredensial & File Otomatis (Automated Instant Delivery)
- **Instan Delivery Kredensial Premium**: Kredensial akun (Username/Email & Password terdekripsi) langsung tampil di layar akun customer setelah pembayaran berhasil disetujui (*Paid*).
- **Instan Download File Digital**: Tombol unduh file produk digital langsung aktif di halaman transaksi begitu pembayaran lunas.
- **Notifikasi Otomatis via WhatsApp**: Menerima pesan WA otomatis berisi ringkasan invoice, link kredensial akun, atau link download file digital.

### 🎫 Klaim & Penggunaan Voucher Promo
- **Input / Klaim Kode Voucher**: Mengklaim voucher diskon Admin (nasional) atau voucher Toko spesifik pada halaman checkout.
- **Kalkulasi Potongan Otomatis**: Potongan harga langsung memotong total pembayaran secara real-time.

### 🎖️ Member Tier Loyalitas Customer (Loyalty Program)
- **Status Tier Customer**: Sistem tingkatan level member (*Tier*) berdasarkan total belanja/transaksi (seperti Bronze, Silver, Gold, Platinum).
- **Log Histori Tier**: Catatan riwayat pencapaian dan kenaikan tier member (`customer_tier_logs`).

### 🔗 Program Referral
- **Link / Kode Referral Unik**: Setiap customer memiliki link referral unik untuk mengajak pengguna baru.
- **Pelacakan Referral**: Halaman khusus untuk memantau jumlah orang yang mendaftar melalui link referral.

### 📑 Riwayat Pembelian & Download Invoice PDF
- **Histori Transaksi Lengkap**: Memantau daftar seluruh riwayat transaksi pembelian (Pending, Paid, Expired, Failed).
- **Download Invoice PDF**: Mengunduh bukti pembayaran / invoice resmi dalam format PDF.

### ⭐ Ulasan & Rating Produk (Product Reviews)
- **Berikan Rating & Review**: Memberikan ulasan tekstual dan rating bintang (1 s/d 5) pada produk yang telah sukses dibeli.
- **Update Rating Toko**: Rating secara otomatis memperbarui rata-rata rating toko dan produk terkait.

### 🚨 Laporan Komplain & Helpdesk (Customer Dispute)
- **Kirim Laporan Masalah**: Fitur mengajukan komplain jika kredensial bermasalah (misal: *Password salah*, *Akun terkena suspen*, dll.).
- **Pantau Status Laporan**: Memantau perkembangan laporan yang sedang diproses oleh Admin.

---

## 4. Fitur Autentikasi & Pengaturan Akun

### 🔑 Autentikasi Multi-Role & Social Login
- **Multi-Role Access**: Pemisahan peran (*Admin*, *Seller*, *Customer*) dengan hak akses dan dashboard yang berbeda.
- **Register & Login Email**: Pendaftaran dan login standar menggunakan email dan kata sandi.
- **Social Login (Google OAuth)**: Login/Register instan 1-klik menggunakan akun Google.
- **Lupa & Reset Password via Email**: Fitur kirim email reset password dilengkapi token enkripsi yang aman.
- **Keamanan Login (Rate Limiting Throttle)**: Proteksi pembatasan percobaan login untuk mencegah serangan *brute force*.

### 👤 Pengaturan Profil & Keamanan
- **Edit Profil**: Memperbarui nama, nomor WhatsApp/telepon, dan foto profil.
- **Ubah Password**: Fitur ganti password dengan konfirmasi password lama.

---

## 5. Integrasi API RESTful v1 (Mobile Ready)

Platform dilengkapi API RESTful V1 berbasis **Laravel Sanctum** untuk mendukung integrasi dengan Aplikasi Android / iOS / PWA.

- **Auth API**: Login, Register, Logout, Forgot Password, Reset Password via Token API.
- **Catalog API**: Get List Toko, Get Catalog Produk, Get Detail Produk, Check Stock Varian.
- **Customer API**: Checkout, Generate Payment, Status Pembayaran, Get Member Tier, Get Referral, Get Riwayat Pembelian, Get Kredensial, Klaim Voucher, Store Review, Send Laporan.
- **Seller API**: Get Dashboard Seller, Get Mutasi Saldo, Get Profil Seller, Update Profil, Get Badges, CRUD Produk Seller, CRUD Voucher Seller.
- **Admin API**: Get Dashboard Admin, Get Seller List, Toggle Seller Status, Get & Update Laporan, Get & Update Setting Komisi, CRUD Voucher Admin.

---

## 6. Arsitektur & Keamanan Sistem (System Architecture & Security)

- **Enkripsi Kredensial Sensitif**: Data password akun premium yang disimpan di tabel database dienkripsi menggunakan enkripsi Laravel `Crypt::encryptString`.
- **Sistem Webhook Real-time**: Webhook handler untuk Midtrans & Pakasir yang memproses perubahan status transaksi secara instan & otomatis.
- **Webhook Audit Logs**: Pencatatan log webhook Midtrans & Pakasir (`midtrans_webhook_logs`, `pakasir_webhook_logs`) untuk transparansi dan audit error.
- **Middleware Proteksi Akses**:
  - `admin.only`: Memastikan hanya Admin yang dapat mengakses rute khusus pengelola.
  - `only.seller`: Memastikan rute toko hanya diakses oleh Seller.
  - `only.customer`: Memastikan rute transaksi customer hanya diakses oleh Pembeli.
  - `prevent.customer`: Mencegah role customer mengakses area dashboard management.
- **Proteksi Transaksi**: Isolasi data per toko (*Multi-tenant Store Isolation*) sehingga seller hanya dapat melihat dan mengelola data milik tokonya sendiri.
