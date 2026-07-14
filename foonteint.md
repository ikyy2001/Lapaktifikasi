# PRD: Integrasi WhatsApp Invoice Otomatis (Fonnte) — Trivelops

Dokumentasi Foonte MD ada di: https://docs.fonnte.com/

---

## 1. Konteks Project

Tokoku adalah platform jual akun premium (Spotify, Netflix, dll) dengan model **parent account + slot**:

- `tbl_produk` → produk utama (Spotify, Netflix)
- `tbl_tipe_layanan` → tipe layanan per produk (Private, Sharing, Family)
- `tbl_varian_layanan` → varian durasi & harga per tipe (1 Bulan, 3 Bulan, dst)
- `tbl_stok_akun` → stok kredensial akun per varian (email_username, password_encrypted, status: tersedia/reserved/terjual)
- `tbl_customer` → data customer (nomor_telepon, relasi ke `users`)
- `tbl_pembelian` → order (order_id ULID, id_customer, id_varian, id_stok, harga_saat_beli, status: pending/success/expired/failed/cancelled)
- `tbl_pembayaran` → record pembayaran (id_pembelian, metode_pembayaran, jumlah_dibayar, midtrans_transaction_id)

Pembayaran diproses via **Midtrans** (notification/webhook masuk ke server, lalu status transaksi dicek).

Password di `tbl_stok_akun.password_encrypted` sudah terenkripsi pakai `Illuminate\Support\Facades\Crypt` (format standar Laravel: iv/value/mac/tag base64 JSON).

## 2. Tujuan Fitur

Begitu Midtrans mengonfirmasi pembayaran sukses (`settlement` atau `capture` dengan `fraud_status: accept`), sistem harus **otomatis, tanpa campur tangan manual**:

1. Update `tbl_pembelian.status` jadi `success`
2. Insert record ke `tbl_pembayaran`
3. Update `tbl_stok_akun` slot terkait jadi `status: terjual` + isi `tanggal_terjual`
4. Kirim pesan WhatsApp ke `tbl_customer.nomor_telepon` berisi invoice + kredensial akun (email/username + password hasil decrypt) via **Fonnte API**
5. Kalau transaksi `expire`/`cancel`/`deny` → update status pembelian sesuai, dan **lepas slot** stok balik ke `tersedia` (bukan dijual)
6. Kalau pengiriman WA gagal (API Fonnte down, dsb) → dicatat statusnya, jangan bikin proses pembayaran ikut gagal, dan bisa di-retry

## 3. Kebutuhan Environment

- `.env` perlu ditambah:
  ```
  FONNTE_TOKEN=xxxxxxxxxxxxxxxxxxxxx
  ```
- Queue worker **wajib jalan** di server (`php artisan queue:work` atau via Supervisor di production), karena pengiriman WA dijalankan lewat queued job, bukan langsung sinkron di request Midtrans.

## 4. Perubahan Database yang Dibutuhkan

Tambah 2 kolom baru di `tbl_pembayaran` (via migration, jangan edit langsung ke SQL dump):

| Kolom | Tipe | Keterangan |
|---|---|---|
| `wa_sent_at` | timestamp, nullable | diisi saat WA berhasil terkirim |
| `wa_response` | text, nullable | simpan JSON response dari Fonnte, buat debug kalau gagal |

## 5. Komponen yang Harus Dibuat/Diubah

- `app/Services/FonnteService.php` — service pembungkus API Fonnte
- `app/Jobs/SendAccountInvoiceWhatsapp.php` — job async pengirim invoice + kredensial
- `database/migrations/xxxx_add_wa_columns_to_tbl_pembayaran.php` — migration kolom baru
- Controller/handler notifikasi Midtrans yang sudah ada di project (agent harus cari file ini dulu, biasanya nama seperti `MidtransController`, `PaymentController`, atau method `notificationHandler`/`callback`) — **diedit**, bukan dibuat baru

## 6. Business Rules Wajib Dipatuhi

1. **Idempotency** — Midtrans bisa kirim notification lebih dari sekali untuk order yang sama. Cek dulu `tbl_pembelian.status` sebelum diproses; kalau sudah `success`, langsung return, jangan proses ulang.
2. **Nomor telepon kosong** — kalau `tbl_customer.nomor_telepon` NULL, job tidak boleh error/retry terus; cukup skip dan catat di log (`Log::warning`) biar bisa dicek admin manual.
3. **Decrypt password** — wajib pakai `Crypt::decryptString()`, jangan pernah kirim `password_encrypted` mentah.
4. **Retry job** — job harus punya `$tries = 3` dan `$backoff` (jeda antar percobaan), karena API eksternal bisa gagal sementara.
5. **Update `wa_sent_at`/`wa_response`** — job selalu update kolom ini di `tbl_pembayaran` terkait, baik sukses maupun gagal, biar ada jejak audit.
6. **Transaksi database** — update status pembelian + insert pembayaran + update stok akun harus dibungkus `DB::transaction()` supaya konsisten.
7. **Jangan hardcode isi pesan WA** — taruh template pesan di satu tempat (misal method di Job atau file blade/text terpisah) biar gampang diubah nanti, jangan disebar di banyak file.

## 7. Testing Checklist (manual, setelah semua prompt selesai)

- [ ] Order baru dibuat → status `pending`, slot stok `reserved`
- [ ] Simulasikan notifikasi Midtrans `settlement` → status pembelian jadi `success`, slot stok jadi `terjual`, WA masuk ke nomor test dengan kredensial yang benar
- [ ] Kirim notifikasi Midtrans yang sama dua kali (simulasi duplicate) → WA **tidak** terkirim dobel
- [ ] Simulasikan `expire`/`cancel` → slot stok balik `tersedia`, **tidak ada** WA terkirim
- [ ] Matikan sementara token Fonnte (invalid) → job gagal dengan rapi, `wa_response` kesimpan, tidak bikin proses pembayaran ikut error
- [ ] Cek `tbl_customer.nomor_telepon` NULL → job skip tanpa error berulang

