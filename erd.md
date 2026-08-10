# Entity Relationship Diagram (ERD) - Lapaktifikasi

Dokumen ini berisi Entity Relationship Diagram (ERD) untuk database aplikasi berdasarkan struktur dari file `lapak.sql`. Diagram ini memvisualisasikan seluruh entitas/tabel yang ada beserta relasinya yang didefinisikan menggunakan *Foreign Key* (FK).

## Mermaid Diagram

Berikut adalah diagram ERD dengan detail setiap atribut (kolom) pada setiap tabel beserta tipe datanya:

```mermaid
erDiagram
    users {
        bigint id PK
        varchar name
        varchar email
        timestamp email_verified_at
        varchar password
        tinyint must_change_password
        varchar profile_picture
        varchar remember_token
        bigint role_id FK
        tinyint is_banned
        text banned_reason
    }

    tbl_roles {
        bigint id PK
        varchar role
    }

    tbl_toko {
        bigint id_toko PK
        bigint user_id FK
        varchar nama_toko
        varchar slug
        varchar no_telp
        varchar akun_telegram
        varchar telegram_chat_id
        text informasi_toko
        varchar logo_toko
        decimal komisi_override
        bigint saldo
        enum status
        tinyint is_banned
        text banned_reason
        decimal rating_rata_rata
        int jumlah_review
    }

    tbl_produk {
        int id_produk PK
        bigint id_toko FK
        varchar nama_produk
        text deskripsi
        varchar gambar
        enum status
        varchar tipe_produk
        varchar kategori
    }

    tbl_produk_zip {
        bigint id PK
        bigint id_toko FK
        varchar nama
        text deskripsi
        decimal harga
        enum status
        varchar file
    }

    tbl_beli_produk {
        bigint id PK
        int qty
        enum status
        varchar order_id
        bigint produk_id FK
        bigint id_toko FK
        bigint user_id FK
        date tanggal_transaksi
    }

    tbl_produk_terjual {
        bigint id PK
        int jumlah_terjual
        bigint produk_id FK
    }
    
    tbl_screenshots_produk {
        bigint id PK
        varchar folder
        bigint produk_id FK
    }

    tbl_customer {
        bigint id PK
        varchar nomor_telepon
        bigint user_id FK
        bigint id_tier_saat_ini FK
        decimal total_belanja_akumulasi
        varchar kode_referral
        bigint direferensikan_oleh FK
        int jumlah_referral_sukses
    }

    tbl_customer_tier {
        bigint id_tier PK
        varchar nama_tier
        int urutan
        decimal minimal_belanja
        varchar warna_tema
        varchar icon_path
        decimal benefit_cashback_persen
        json benefit_deskripsi
    }

    tbl_customer_tier_log {
        bigint id PK
        bigint id_customer FK
        bigint id_tier_lama FK
        bigint id_tier_baru FK
    }

    tbl_laporan {
        bigint id PK
        bigint user_id FK
        varchar judul
        text deskripsi
        varchar gambar
        enum status
    }

    tbl_mutasi_saldo {
        bigint id PK
        bigint id_toko FK
        enum tipe
        bigint nominal
        bigint saldo_akhir
        varchar keterangan
        bigint id_beli_produk FK
        bigint dibuat_oleh FK
    }

    tbl_pembayaran {
        bigint id_pembayaran PK
        bigint id_pembelian FK
        varchar metode_pembayaran
        enum payment_gateway
        decimal jumlah_dibayar
        varchar midtrans_transaction_id
        timestamp tanggal_bayar
        timestamp wa_sent_at
        text wa_response
        int wa_retry_count
        timestamp wa_last_retry_at
        bigint wa_last_retry_by FK
    }

    tbl_pembelian {
        bigint id_pembelian PK
        varchar order_id
        bigint id_customer FK
        int id_varian FK
        bigint id_stok FK
        decimal harga_saat_beli
        bigint id_voucher_dipakai FK
        decimal nominal_diskon
        enum status
        enum payment_gateway
        varchar gateway_reference
        timestamp reserved_until
    }

    tbl_pembelian_log {
        bigint id_log PK
        bigint id_pembelian FK
        varchar status_lama
        varchar status_baru
        varchar sumber_perubahan
        text keterangan
    }

    tbl_review {
        bigint id_review PK
        bigint id_pembelian FK
        bigint id_toko FK
        bigint id_customer FK
        tinyint rating
        text komentar
    }

    tbl_seller_badge {
        bigint id_badge PK
        varchar nama_badge
        text deskripsi
        varchar kriteria_tipe
        decimal kriteria_nilai
        varchar icon_path
    }

    tbl_toko_badge {
        bigint id_toko FK
        bigint id_badge FK
        timestamp diperoleh_pada
    }

    tbl_tipe_layanan {
        int id_tipe PK
        int id_produk FK
        varchar nama_tipe
        enum status
    }

    tbl_varian_layanan {
        int id_varian PK
        int id_tipe FK
        varchar nama_varian
        int durasi_hari
        decimal harga
        text deskripsi
        enum status
        varchar file_path
    }

    tbl_stok_akun {
        bigint id_stok PK
        int id_varian FK
        varchar email_username
        text password_encrypted
        text catatan
        enum status
        bigint id_pembelian FK
    }

    tbl_voucher {
        bigint id_voucher PK
        varchar kode
        enum tipe_diskon
        decimal nilai_diskon
        decimal maksimal_potongan
        decimal minimal_transaksi
        int kuota_total
        int kuota_terpakai
        timestamp berlaku_dari
        timestamp berlaku_sampai
        enum scope
        bigint id_toko FK
        bigint dibuat_oleh FK
        tinyint is_active
    }

    tbl_voucher_klaim {
        bigint id_klaim PK
        bigint id_voucher FK
        bigint id_customer FK
        bigint id_pembelian FK
    }

    tbl_pembayaran_zip {
        bigint id PK
        decimal total
        varchar metode
        varchar order_id
    }
    
    tbl_setting_komisi {
        bigint id PK
        decimal komisi_default
        int digital_file_limit_mb
        tinyint is_maintenance
    }

    failed_jobs {
        bigint id PK
        varchar uuid
        text connection
        text queue
        longtext payload
        longtext exception
    }

    jobs {
        bigint id PK
        varchar queue
        longtext payload
        tinyint attempts
        int reserved_at
        int available_at
        int created_at
    }

    midtrans_webhook_logs {
        bigint id PK
        varchar order_id
        varchar status_code
        varchar signature_key
        json payload
    }

    pakasir_webhook_logs {
        bigint id PK
        varchar order_id
        decimal amount
        varchar status
        json payload
    }


    %% Relasi Tabel (Foreign Keys)
    tbl_roles ||--o{ users : "role_id"
    users ||--o{ tbl_toko : "user_id"
    tbl_toko ||--o{ tbl_produk : "id_toko"
    tbl_toko ||--o{ tbl_produk_zip : "id_toko"
    
    users ||--o{ tbl_beli_produk : "user_id"
    tbl_produk_zip ||--o{ tbl_beli_produk : "produk_id"
    tbl_toko ||--o{ tbl_beli_produk : "id_toko"
    
    tbl_produk_zip ||--o{ tbl_produk_terjual : "produk_id"
    tbl_produk_zip ||--o{ tbl_screenshots_produk : "produk_id"
    
    users ||--o{ tbl_customer : "user_id"
    tbl_customer_tier ||--o{ tbl_customer : "id_tier_saat_ini"
    tbl_customer ||--o{ tbl_customer : "direferensikan_oleh"
    
    tbl_customer ||--o{ tbl_customer_tier_log : "id_customer"
    tbl_customer_tier ||--o{ tbl_customer_tier_log : "id_tier_lama"
    tbl_customer_tier ||--o{ tbl_customer_tier_log : "id_tier_baru"
    
    users ||--o{ tbl_laporan : "user_id"
    
    tbl_toko ||--o{ tbl_mutasi_saldo : "id_toko"
    tbl_beli_produk ||--o{ tbl_mutasi_saldo : "id_beli_produk"
    users ||--o{ tbl_mutasi_saldo : "dibuat_oleh"
    
    tbl_pembelian ||--o{ tbl_pembayaran : "id_pembelian"
    users ||--o{ tbl_pembayaran : "wa_last_retry_by"
    
    tbl_customer ||--o{ tbl_pembelian : "id_customer"
    tbl_varian_layanan ||--o{ tbl_pembelian : "id_varian"
    tbl_stok_akun ||--o{ tbl_pembelian : "id_stok"
    tbl_voucher ||--o{ tbl_pembelian : "id_voucher_dipakai"
    
    tbl_pembelian ||--o{ tbl_pembelian_log : "id_pembelian"
    
    tbl_pembelian ||--o{ tbl_review : "id_pembelian"
    tbl_toko ||--o{ tbl_review : "id_toko"
    tbl_customer ||--o{ tbl_review : "id_customer"
    
    tbl_seller_badge ||--o{ tbl_toko_badge : "id_badge"
    tbl_toko ||--o{ tbl_toko_badge : "id_toko"
    
    tbl_produk ||--o{ tbl_tipe_layanan : "id_produk"
    tbl_tipe_layanan ||--o{ tbl_varian_layanan : "id_tipe"
    
    tbl_varian_layanan ||--o{ tbl_stok_akun : "id_varian"
    tbl_pembelian ||--o{ tbl_stok_akun : "id_pembelian"
    
    tbl_toko ||--o{ tbl_voucher : "id_toko"
    users ||--o{ tbl_voucher : "dibuat_oleh"
    
    tbl_voucher ||--o{ tbl_voucher_klaim : "id_voucher"
    tbl_customer ||--o{ tbl_voucher_klaim : "id_customer"
    tbl_pembelian ||--o{ tbl_voucher_klaim : "id_pembelian"
```

## Penjelasan Relasi Utama
- **Autentikasi & Akun**: `users` memiliki relasi utama ke `tbl_roles` (peran user), dan setiap user bisa memiliki banyak toko (`tbl_toko`) maupun menjadi pelanggan/customer (`tbl_customer`).
- **Produk & Toko**: Toko dapat membuat banyak produk digital atau layanan (relasi `tbl_toko` ke `tbl_produk` dan `tbl_produk_zip`).
- **Transaksi & Pembelian**: Pembelian melibatkan `tbl_pembelian`, yang menyimpan varian layanan (`tbl_varian_layanan`), menggunakan stok dari akun (`tbl_stok_akun`), dan memiliki alur pembayaran via `tbl_pembayaran`.
- **Fitur Spesial (Tier & Voucher)**: `tbl_customer` bisa berada di `tbl_customer_tier` tertentu dan mendapatkan voucher `tbl_voucher` yang kemudian akan dicatat di `tbl_voucher_klaim` setiap kali digunakan untuk pemesanan.
