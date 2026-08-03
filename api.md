# Dokumentasi Lengkap API Lapaktifikasi

Dokumentasi ini menguraikan seluruh endpoint API yang tersedia untuk diakses oleh aplikasi mobile. Seluruh API menggunakan format JSON untuk *Request Body* dan *Response* kecuali untuk proses upload file/gambar.

## 1. Informasi Dasar

- **Base URL:** `https://domain-anda.com/api/v1`
- **Tipe Konten (Header):**
  - `Accept: application/json`
  - `Content-Type: application/json` (Kecuali untuk form upload gambar, gunakan `multipart/form-data`)

### 1.1 Format Response Standar
API ini memiliki standarisasi bentuk *response* untuk mempermudah integrasi di sisi *client* (mobile app).

**Response Sukses (HTTP 200 / 201):**
```json
{
  "status": "success",
  "message": "Pesan keberhasilan operasi",
  "data": {
    "key": "value"
  }
}
```

**Response Gagal / Error (HTTP 400 / 401 / 403 / 404 / 500):**
```json
{
  "status": "error",
  "message": "Pesan utama error"
}
```

**Response Gagal Validasi (HTTP 422):**
```json
{
  "status": "error",
  "message": "Validasi gagal",
  "errors": {
    "field_name": ["Alasan error validasi 1", "Alasan error validasi 2"]
  }
}
```

### 1.2 Autentikasi (Sanctum)
Endpoint yang bersifat privat memerlukan Token (Bearer) pada Header.
- **Header:** `Authorization: Bearer <TOKEN>`

---

## 2. Modul Autentikasi (`/auth`)

### 2.1 Login
- **Method:** `POST`
- **Endpoint:** `/auth/login`
- **Akses:** Public
- **Request Body (JSON):**
  ```json
  {
    "email": "user@example.com",
    "password": "passwordanda123"
  }
  ```
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Berhasil login",
    "data": {
      "user": {
        "id": 1,
        "name": "Nama User",
        "email": "user@example.com",
        "role_id": 2
      },
      "token": "1|hRk8v...",
      "role": "customer"
    }
  }
  ```
- **Response Error (401):**
  ```json
  {
    "status": "error",
    "message": "Email atau password salah"
  }
  ```

### 2.2 Register
- **Method:** `POST`
- **Endpoint:** `/auth/register`
- **Akses:** Public
- **Request Body (JSON):**
  ```json
  {
    "name": "Nama Lengkap",
    "email": "userbaru@example.com",
    "password": "passwordminimal10",
    "ref": "REF-ABCDEF" 
  }
  ```
- **Response Sukses (201):**
  ```json
  {
    "status": "success",
    "message": "Berhasil mendaftar",
    "data": {
      "user": {
        "id": 2,
        "name": "Nama Lengkap",
        "email": "userbaru@example.com",
        "role_id": 2
      },
      "token": "2|XYZ..."
    }
  }
  ```

### 2.3 Logout
- **Method:** `POST`
- **Endpoint:** `/auth/logout`
- **Akses:** Private (Bearer Token)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Berhasil logout",
    "data": []
  }
  ```

### 2.4 Lupa Password
- **Method:** `POST`
- **Endpoint:** `/auth/forgot-password`
- **Akses:** Public
- **Request Body (JSON):**
  ```json
  {
    "email": "user@example.com"
  }
  ```
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Link reset password telah dikirim ke email",
    "data": []
  }
  ```
- **Response Error (404):**
  ```json
  {
    "status": "error",
    "message": "Email tidak terdaftar"
  }
  ```

### 2.5 Reset Password
- **Method:** `POST`
- **Endpoint:** `/auth/reset-password`
- **Akses:** Public
- **Request Body (JSON):**
  ```json
  {
    "token": "token_dari_email",
    "email": "user@example.com",
    "password": "password_baru123",
    "password_confirmation": "password_baru123"
  }
  ```
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Password berhasil diubah",
    "data": []
  }
  ```

---

## 3. Profil User (`/profile`)

### 3.1 Ambil Profil
- **Method:** `GET`
- **Endpoint:** `/profile`
- **Akses:** Private (Bearer Token)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Profil berhasil diambil",
    "data": {
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "no_whatsapp": "081234567890",
        "profile_picture": "avatar-1.png"
      },
      "customer_details": {
        "id": 1,
        "kode_referral": "REF-XYZ"
      }
    }
  }
  ```

### 3.2 Update Profil
- **Method:** `POST`
- **Endpoint:** `/profile/update`
- **Akses:** Private (Bearer Token)
- **Header:** Gunakan `Content-Type: multipart/form-data`
- **Form Data (Body):**
  - `name`: string
  - `no_whatsapp`: string
  - `password`: string (opsional)
  - `profile_picture`: file image (jpeg, png, jpg max 2MB) (opsional)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Profil berhasil diperbarui",
    "data": {
      "user": {
        "id": 1,
        "name": "John Doe Updated",
        "no_whatsapp": "081234567890",
        "profile_picture": "169000000_1.jpg"
      }
    }
  }
  ```

---

## 4. Modul Katalog & Publik

### 4.1 Daftar Toko
- **Method:** `GET`
- **Endpoint:** `/toko`
- **Akses:** Public
- **Query Parameter:** `?per_page=12` (opsional)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Daftar toko berhasil diambil",
    "data": {
      "current_page": 1,
      "data": [
        {
          "id_toko": 1,
          "nama_toko": "Toko Mantap",
          "deskripsi": "Jual akun premium",
          "logo": "logo1.png",
          "rating_rata_rata": 4.5
        }
      ],
      "total": 1
    }
  }
  ```

### 4.2 Katalog Produk
- **Method:** `GET`
- **Endpoint:** `/katalog` atau `/toko/{id_toko}/produk`
- **Akses:** Public
- **Query Parameter:** `?search=kata kunci` & `?id_toko=1`
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Katalog produk berhasil diambil",
    "data": {
      "produk": [
        {
          "id_produk": 1,
          "nama_produk": "Netflix Premium",
          "deskripsi": "Garansi 1 Bulan",
          "tipe_layanan": [
            {
              "id_tipe": 1,
              "nama_tipe": "Sharing",
              "varian_layanan": [
                {
                  "id_varian": 1,
                  "nama_varian": "1 Profil",
                  "harga": 30000,
                  "stok_tersedia": 5
                }
              ]
            }
          ]
        }
      ],
      "toko": {
        "id_toko": 1,
        "nama_toko": "Toko Mantap"
      }
    }
  }
  ```

### 4.3 Detail Produk
- **Method:** `GET`
- **Endpoint:** `/produk/{id}`
- **Akses:** Public
- **Response Sukses (200):** Mengembalikan objek `produk` beserta data `tipe_layanan` dan `varian_layanan` di dalamnya. Sama seperti format di `/katalog` namun hanya 1 objek.

### 4.4 Cek Stok Varian
- **Method:** `GET`
- **Endpoint:** `/produk/varian/{id_varian}/stok`
- **Akses:** Public
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Stok berhasil dicek",
    "data": {
      "id_varian": 1,
      "stok_tersedia": 12
    }
  }
  ```

---

## 5. Modul Checkout & Pembayaran (Customer Only)

### 5.1 Checkout (Proses Pembelian)
- **Method:** `POST`
- **Endpoint:** `/checkout`
- **Akses:** Private (Bearer Token, Khusus Customer)
- **Request Body (JSON):**
  ```json
  {
    "id_varian": 1,
    "kode_voucher": "MERDEKA50" 
  }
  ```
*(kode_voucher bersifat opsional)*
- **Response Sukses (201):**
  ```json
  {
    "status": "success",
    "message": "Checkout berhasil, lanjutkan ke pembayaran",
    "data": {
      "order_id": "01HXYZ...",
      "id_varian": 1,
      "harga_saat_beli": 25000,
      "nominal_diskon": 5000,
      "status": "pending",
      "reserved_until": "2026-07-30T10:15:00.000000Z"
    }
  }
  ```
- **Response Error (400) - Stok Habis / Voucher Gagal:**
  ```json
  {
    "status": "error",
    "message": "Stok Habis" 
  }
  ```

### 5.2 Generate Instruksi Pembayaran (Midtrans / Pakasir)
- **Method:** `POST`
- **Endpoint:** `/pembayaran/generate/{order_id}`
- **Akses:** Private (Bearer Token)
- **Request Body (JSON):**
  ```json
  {
    "gateway": "midtrans" 
  }
  ```
*(gateway: "midtrans" atau "pakasir")*
- **Response Sukses (200) - Midtrans:**
  ```json
  {
    "status": "success",
    "message": "Generate Midtrans token success",
    "data": {
      "snapToken": "abc123token"
    }
  }
  ```
- **Response Sukses (200) - Pakasir:**
  ```json
  {
    "status": "success",
    "message": "Generate Pakasir url success",
    "data": {
      "redirect_url": "https://app.pakasir.com/pay/..."
    }
  }
  ```

### 5.3 Cek Status Pembayaran (Sync)
- **Method:** `GET`
- **Endpoint:** `/pembayaran/status/{order_id}`
- **Akses:** Private (Bearer Token)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Status pembayaran",
    "data": {
      "order_id": "01J...XYZ",
      "status": "success",
      "harga": 50000,
      "updated_at": "2026-07-30T10:00:00.000000Z"
    }
  }
  ```
*(Status bisa berisi: pending, success, failed, expired)*

---

## 6. Modul Customer Premium

### 6.1 Data Member (Tier & Voucher)
- **Method:** `GET`
- **Endpoint:** `/customer/member`
- **Akses:** Private (Bearer Token)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Data member berhasil diambil",
    "data": {
      "customer": { "id": 1, "tier": { "nama_tier": "Bronze" } },
      "progress": { "total_belanja": 150000, "target": 500000 },
      "tiers": [...],
      "vouchers": [...],
      "claimed_vouchers": [1, 5]
    }
  }
  ```

### 6.2 Referral
- **Method:** `GET`
- **Endpoint:** `/customer/referral`
- **Akses:** Private (Bearer Token)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Data referral berhasil diambil",
    "data": {
      "kode_referral": "REF-ABC12",
      "share_url": "https://domain.com/pendaftaran?ref=REF-ABC12",
      "bonus_akumulasi": 50000,
      "referred_customers": [...]
    }
  }
  ```

### 6.3 Klaim Voucher
- **Method:** `POST`
- **Endpoint:** `/customer/voucher/{id_voucher}/klaim`
- **Akses:** Private (Bearer Token)
- **Request Body:** Kosong `{}`
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Voucher berhasil diklaim!",
    "data": []
  }
  ```
- **Response Error (400):**
  ```json
  {
    "status": "error",
    "message": "Anda sudah mengklaim voucher ini."
  }
  ```

### 6.4 Riwayat Pembelian
- **Method:** `GET`
- **Endpoint:** `/customer/riwayat`
- **Akses:** Private (Bearer Token)
- **Query Parameter:** `?start_date=2026-01-01` & `?end_date=2026-12-31` (opsional)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Riwayat pembelian berhasil diambil",
    "data": {
      "riwayat": [
        {
          "order_id": "01J...",
          "status": "success",
          "harga_saat_beli": 25000,
          "created_at": "2026-07-29T10:00:00.000000Z",
          "varian_layanan": { ... }
        }
      ]
    }
  }
  ```

### 6.5 Mengambil Kredensial (Akun Premium)
- **Method:** `GET`
- **Endpoint:** `/customer/kredensial/{order_id}`
- **Akses:** Private (Bearer Token)
- **Response Sukses (200):**
  ```json
  {
    "status": "success",
    "message": "Kredensial berhasil diambil",
    "data": {
      "email_username": "akun@email.com",
      "password": "PasswordDecrypted123",
      "catatan": "Jangan ganti password"
    }
  }
  ```
- **Response Error (403):**
  ```json
  {
    "status": "error",
    "message": "Pembayaran belum diselesaikan atau transaksi expired."
  }
  ```

### 6.6 Memberikan Review
- **Method:** `POST`
- **Endpoint:** `/customer/review/{order_id}`
- **Akses:** Private (Bearer Token)
- **Request Body (JSON):**
  ```json
  {
    "rating": 5,
    "komentar": "Pelayanan sangat cepat!"
  }
  ```
- **Response Sukses (201):**
  ```json
  {
    "status": "success",
    "message": "Review berhasil disimpan",
    "data": []
  }
  ```

### 6.7 Laporan Keluhan
**Daftar Laporan:**
- **Method:** `GET`
- **Endpoint:** `/customer/laporan`
- **Akses:** Private (Bearer Token)
- **Response Sukses (200):** Mengembalikan array `laporan`.

**Buat Laporan:**
- **Method:** `POST`
- **Endpoint:** `/customer/laporan`
- **Akses:** Private (Bearer Token)
- **Header:** `Content-Type: multipart/form-data`
- **Form Data:**
  - `judul`: string (wajib)
  - `deskripsi`: string (wajib)
  - `gambar`: file image (opsional)
- **Response Sukses (201):**
  ```json
  {
    "status": "success",
    "message": "Laporan berhasil dibuat",
    "data": {
      "laporan": {
        "id": 1,
        "judul": "Akun bermasalah",
        "status": "pending"
      }
    }
  }
  ```

---

## 7. Modul Seller Khusus
*Akses: Private (Bearer Token) dengan role_id = 3 (Seller)*

### 7.1 Dashboard & Mutasi & Profil & Badge
- **Dashboard:** `GET /seller/dashboard`
  - **Response (200):**
    ```json
    {
      "status": "success",
      "message": "Dashboard seller berhasil diambil",
      "data": {
        "pendapatan": 1500000,
        "total_produk": 10,
        "pesanan_selesai": 50,
        "rating": 4.8,
        "saldo": 200000
      }
    }
    ```
- **Mutasi Saldo:** `GET /seller/mutasi` (Mengembalikan array riwayat pencairan/pendapatan)
- **Ambil Profil Toko:** `GET /seller/profil`
- **Update Profil Toko:** `POST /seller/profil` (`multipart/form-data`: `nama_toko`, `deskripsi`, `logo`)
- **Badges:** `GET /seller/badges` (Mengembalikan data `toko` dan `all_badges`)

### 7.2 Manajemen Produk Premium (CRUD)

**1. Daftar Produk:**
- **Method:** `GET`
- **Endpoint:** `/seller/produk`
- **Response (200):** Array produk ber-paginasi.

**2. Tambah Produk:**
- **Method:** `POST`
- **Endpoint:** `/seller/produk`
- **Header:** `Content-Type: multipart/form-data`
- **Form Data:**
  - `nama_produk`: string
  - `deskripsi`: string
  - `status`: string ("aktif" / "nonaktif")
  - `gambar`: file image
- **Response (201):**
  ```json
  {
    "status": "success",
    "message": "Produk berhasil ditambahkan",
    "data": { "id_produk": 1, "nama_produk": "..." }
  }
  ```

**3. Edit Produk:**
- **Method:** `POST` (Gunakan POST karena upload gambar via multipart/form-data tidak selalu support PUT di Laravel, atau sisipkan `_method: PUT` di form-data).
- **Endpoint:** `/seller/produk/{id_produk}`
- **Form Data:** Sama seperti Tambah Produk.
- **Response (200):** `Produk berhasil diperbarui`

**4. Hapus Produk:**
- **Method:** `DELETE`
- **Endpoint:** `/seller/produk/{id_produk}`
- **Response (200):** `Produk berhasil dihapus`

### 7.3 Manajemen Voucher Toko (CRUD)

**1. Daftar Voucher:**
- **Method:** `GET`
- **Endpoint:** `/seller/voucher`
- **Response (200):** Array voucher ber-paginasi.

**2. Tambah Voucher:**
- **Method:** `POST`
- **Endpoint:** `/seller/voucher`
- **Request Body (JSON):**
  ```json
  {
    "kode": "PROMO10",
    "nama_voucher": "Promo Awal Bulan",
    "deskripsi": "Diskon 10%",
    "tipe_diskon": "persen",
    "nilai_diskon": 10,
    "maksimal_potongan": 15000,
    "minimal_transaksi": 50000,
    "kuota_total": 100,
    "berlaku_dari": "2026-08-01",
    "berlaku_sampai": "2026-08-10",
    "is_active": true
  }
  ```
  *(tipe_diskon bisa berisi "persen" atau "nominal")*
- **Response Sukses (201):**
  ```json
  {
    "status": "success",
    "message": "Voucher berhasil ditambahkan",
    "data": { ... }
  }
  ```

**3. Edit Voucher:**
- **Method:** `PUT`
- **Endpoint:** `/seller/voucher/{id_voucher}`
- **Request Body (JSON):** (Sama seperti Tambah Voucher)
- **Response (200):** `Voucher berhasil diperbarui`

**4. Hapus Voucher:**
- **Method:** `DELETE`
- **Endpoint:** `/seller/voucher/{id_voucher}`
- **Response (200):** `Voucher berhasil dihapus`

---

## 8. Modul Administrator
*Akses: Private (Bearer Token) dengan role_id = 1 (Admin)*

### 8.1 Dashboard Admin
- **Method:** `GET`
- **Endpoint:** `/admin/dashboard`
- **Response (200):**
  ```json
  {
    "status": "success",
    "message": "Dashboard admin berhasil diambil",
    "data": {
      "total_toko": 15,
      "total_laporan": 10,
      "laporan_pending": 3,
      "total_voucher_admin": 2
    }
  }
  ```

### 8.2 Kelola Seller
**Daftar Toko:**
- **Method:** `GET`
- **Endpoint:** `/admin/kelola-seller`
- **Response (200):** Array toko.

**Toggle Status (Aktif/Nonaktif):**
- **Method:** `POST`
- **Endpoint:** `/admin/kelola-seller/{id_toko}/toggle-status`
- **Response (200):**
  ```json
  {
    "status": "success",
    "message": "Status toko berhasil diubah",
    "data": { "id_toko": 1, "status": "nonaktif" }
  }
  ```

### 8.3 Laporan dari Customer
**Daftar Laporan:**
- **Method:** `GET`
- **Endpoint:** `/admin/laporan`
- **Response (200):** Array laporan.

**Update Status Laporan:**
- **Method:** `PUT`
- **Endpoint:** `/admin/laporan/{id_laporan}/status`
- **Request Body (JSON):**
  ```json
  {
    "status": "proses"
  }
  ```
  *(Status: pending / proses / selesai)*
- **Response (200):**
  ```json
  {
    "status": "success",
    "message": "Status laporan berhasil diubah",
    "data": { "id": 1, "status": "proses" }
  }
  ```

### 8.4 Setting Komisi Sistem
**Lihat Persentase Komisi:**
- **Method:** `GET`
- **Endpoint:** `/admin/setting-komisi`
- **Response (200):**
  ```json
  {
    "status": "success",
    "message": "Setting komisi berhasil diambil",
    "data": { "persentase_komisi": 10 }
  }
  ```

**Update Persentase Komisi:**
- **Method:** `POST`
- **Endpoint:** `/admin/setting-komisi`
- **Request Body (JSON):**
  ```json
  {
    "persentase_komisi": 15
  }
  ```
- **Response (200):** `Setting komisi berhasil diperbarui`

### 8.5 Kelola Voucher Global (Admin)
- **Daftar Voucher:** `GET /admin/voucher` (Sama dengan list voucher seller)
- **Tambah Voucher:** `POST /admin/voucher` (Body JSON sama persis dengan Tambah Voucher Seller)
- **Update Voucher:** `PUT /admin/voucher/{id_voucher}`
- **Hapus Voucher:** `DELETE /admin/voucher/{id_voucher}`
*(Catatan: Semua voucher yang dibuat di endpoint ini otomatis memiliki parameter `scope: semua_toko`).*
