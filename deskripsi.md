# Deskripsi Produk: Lapaktifikasi

**Lapaktifikasi** adalah platform *digital marketplace* dan e-commerce modern yang dirancang khusus untuk memfasilitasi jual-beli **produk digital** dan **layanan akun premium**. Platform ini menghubungkan pembeli (Customer), penjual (Seller), dan pengelola sistem (Admin) dalam satu ekosistem yang aman, terstruktur, serta terintegrasi secara *real-time*.

Dengan sistem penyampaian kredensial otomatis (automated credential delivery), manajemen inventaris stok terenkripsi, integrasi *payment gateway* multi-opsi, serta API RESTful v1 untuk integrasi aplikasi mobile, Lapaktifikasi menghadirkan pengalaman bertransaksi digital yang cepat, praktis, dan terpercaya.

---

## Fitur Utama yang Disertakan

Berikut adalah beberapa fitur utama yang disertakan dalam platform Lapaktifikasi:

### 1. Ekosistem Multi-Role & Autentikasi Safe & Fast
- **Multi-Role Access Control**: Pemisahan hak akses yang ketat antara **Customer**, **Seller**, dan **Admin**.
- **Autentikasi Modern**: Mendukung Login/Register via Email & Password, Social Login (OAuth Google), serta fitur Lupa/Reset Password dengan proteksi *rate limiting*.
- **API Mobile Ready**: Didukung oleh Laravel Sanctum untuk integrasi aman dengan aplikasi Android / iOS.

---

### 2. Fitur Pembeli (Customer)
- **Katalog Multi-Toko & Produk Digital**: Kemudahan menjelajahi produk premium, aplikasi digital, akun streaming, maupun layanan berlangganan dari berbagai toko.
- **Sistem Checkout & Pembayaran Otomatis**: Integrasi payment gateway (*Midtrans* dan *Pakasir*) dengan dukungan QRIS, E-Wallet, Virtual Account, serta penanganan webhook otomatis.
- **Pengiriman Kredensial & File Otomatis**: Pembeli langsung mendapatkan akses kredensial (username/password) atau link unduhan produk digital secara instan setelah pembayaran sukses.
- **Sistem Voucher & Diskon**: Dapat mengklaim dan menggunakan voucher promo (baik dari Admin maupun Toko spesifik) saat bertransaksi.
- **Program Referral & Member**: Fitur link referral untuk mengajak pengguna baru serta melihat status loyalitas member.
- **Riwayat & Unduh Invoice PDF**: Pengguna dapat melihat histori transaksi, mengunduh bukti pembayaran / invoice PDF, dan memberikan ulasan/rating produk.
- **Fitur Laporan & Komplain Transaksi**: Perlindungan pembeli untuk melaporkan kendala pesanan secara langsung kepada tim Admin.

---

### 3. Fitur Penjual (Seller / Toko)
- **Dashboard & Mutasi Saldo**: Monitoring total penjualan, transaksi harian, dan ringkasan riwayat mutasi saldo toko secara transparan.
- **Manajemen Produk & Variasi**: Mengelola produk digital dan layanan premium beserta tipe, varian harga, serta deskripsi lengkap.
- **Manajemen Inventaris Stok Terenkripsi**:
  - Dukungan *bulk upload* stok akun/kredensial secara simultan.
  - Enkripsi data kredensial sensitif di database untuk menjamin keamanan stok penjual.
- **Manajemen Voucher Toko**: Fitur pembuatan voucher diskon khusus yang dapat dibatasi berdasarkan periode atau kuota penggunaan.
- **Profil Toko & Sistem Badge**: Pengaturan identitas toko serta menampilkan *badge* reputasi atau kepercayaan yang diberikan oleh Admin.

---

### 4. Fitur Pengelola (Admin Panel)
- **Super Dashboard & Laporan Penjualan**: Gambaran umum performa platform, statistik transaksi, dan rekapitulasi penjualan dari seluruh toko.
- **Kelola Seller & Badge Custom**: Fitur aktivasi/nonaktifkan akun seller, verifikasi toko, dan pemberian *badge* kepercayaan khusus.
- **Pengaturan Komisi Platform (Setting Komisi)**: Penyesuaian persentase atau besaran potongan komisi platform per transaksi secara fleksibel.
- **Manajemen Saldo & Pencairan Dana (Withdrawal)**: Pengelolaan dan pemrosesan pencairan saldo toko ke rekening penjual.
- **Kelola Voucher Admin**: Pembuatan promo/voucher skala nasional yang dapat digunakan di seluruh toko.
- **Penanganan Laporan & Transaksi**: Memproses laporan masalah dari customer serta otomatisasi pengiriman ulang notifikasi (misal: WhatsApp notification retry).

---

### 5. Arsitektur & Keamanan Sistem
- **Enkripsi Kredensial Digital**: Data akun premium yang disimpan di database dienkripsi menggunakan standar enkripsi kuat.
- **Autentikasi Multi-Sisi**: Menggunakan middleware khusus (`only.customer`, `only.seller`, `admin.only`) untuk menjamin isolasi data.
- **Webhook & Callback Automation**: Penanganan callback pembayaran tanpa jeda waktu manual.
- **Keamanan Lanjutan**: Dilengkapi proteksi CSRF, Throttle Rate Limiting pada login & API status check, serta pemisahan kredensial environment sensitif.
