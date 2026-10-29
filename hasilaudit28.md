# Laporan Audit Codebase Lapaktifikasi

**Tanggal audit:** 28 Oktober 2026
**Metode:** pembacaan langsung seluruh `app/`, `routes/`, `database/migrations/`, `config/`, `resources/views/`, `public/`, verifikasi `composer audit`, dan `php artisan route:list` (278 route terdaftar, 142 bernama). Setiap temuan diverifikasi silang terhadap skema migration/enum — bukan asumsi.

**Status:** dokumen ini murni temuan. Tidak ada file aplikasi yang diubah.

---

## Daftar Isi

- [Ringkasan Prioritas](#ringkasan-prioritas)
- [CRITICAL](#-critical--harus-segera)
- [HIGH](#-high--fitur-rusak--lubang-keamanan)
- [MEDIUM](#-medium--bug-race-condition-xss)
- [LOW](#-low--kebersihan)
- [Update Keamanan Dependensi](#-update-keamanan-dependensi-composer-audit)
- [Catatan Cakupan](#catatan-cakupan)

---

## 🔴 CRITICAL — harus segera

### C1. Backdoor admin hardcoded di registrasi

**Lokasi:**
- `app/Http/Controllers/AuthController.php:164-165` (registrasi manual)
- `app/Http/Controllers/AuthController.php:209-210` (Google OAuth callback)
- `app/Http/Controllers/Api/AuthController.php:122-123` (registrasi API)

```php
'profile_picture' => (($email == "g4lihanggoro@gmail.com") ? 'avatar-admin.png' : $this->setIconProfile()),
'role_id'        => (($email == "g4lihanggoro@gmail.com") ? 1 : 2)
```

**Dampak:** Siapa pun yang mendaftar dengan email tersebut otomatis mendapat **admin penuh (`role_id = 1`)**. Terbuka di endpoint publik `POST /auth/register` maupun jalur OAuth Google. `role_id` tidak diterima dari client, tetapi justru diberikan murni berdasarkan string yang dikirim client.

**Perbaikan:** Hapus kondisional ini. Jadikan admin lewat seeder atau perintah CLI, bukan di jalur registrasi publik.

---

### C2. Dump database berisi password hash terekspos publik

**Lokasi:** `public/assets/db_tokoku.sql` (15,1 KB, dapat diunduh langsung)

Berisi 10 statement `INSERT`, termasuk hash bcrypt dan `remember_token` untuk:
- `g4lihanggoro@gmail.com`
- `makoto@mail.com`
- `kazuma@mail.com`

File terkait di direktori yang sama: `laravel9-crud-dan-autentikasi.zip`, `web-film.zip` (artefak scaffold, tidak relevan dengan aplikasi).

**Akar masalah:** Root `.htaccess` sudah memblokir `.env`, `.git`, `composer.json/lock`, `artisan`, `app/`, `config/`, `database/`, `vendor/`, dll — **tetapi tidak ada aturan yang mencakup `public/assets`**, yang memang harus dapat diakses publik untuk aset statis.

**Perbaikan:**
1. Hapus `db_tokoku.sql` dan kedua file `.zip` dari `public/assets/`
2. **Rotate semua password dan token** yang bocor di dump tersebut — anggap sudah kompromi
3. Tambahkan aturan `.htaccess` untuk memblokir ekstensi sensitif di `public/assets` (`.sql`, `.zip`, `.env`, `.bak`, `.log`)

---

### C3. Upload file tanpa validasi tipe → Remote Code Execution

**Lokasi:**
- `app/Http/Controllers/DigitalAdminController.php:179` (rule), `:205-206` (upload create), `:238` (rule), `:268-269` (upload update)
- `app/Http/Controllers/Api/SellerInventoryController.php:194` (rule), `:213` (upload), `:247` (rule), `:269` (upload)

**Rule validasi:**
```php
'file_digital' => 'required|file|max:' . $limit_kb,
```

Perhatikan: **tidak ada `mimes:`** — hanya batas ukuran.

**Kode upload:**
```php
$fileName = time() . '_' . $file->getClientOriginalName();
$file->move(public_path('assets/file_digital'), $fileName);
```

**Dampak:** Seller dapat mengunggah `shell.php` ke webroot publik (`public/assets/file_digital/`) lalu mengaksesnya untuk mengeksekusi PHP arbitrer. Ini jalur eskalasi paling langsung dari akun seller biasa ke kompromi server penuh.

**Perbaikan:**
1. Tambahkan `mimes:` yang ketat dan eksplisit pada rule validasi
2. Simpan file digital **di luar webroot** (`storage/app/private/`) dan sajikan lewat controller dengan otorisasi
3. Jangan pertahankan ekstensi dari nama file client — generate ekstensi sendiri

---

### C4. Mass assignment pada `Voucher` → voucher toko bisa menjadi voucher global

**Lokasi:**
- `app/Http/Controllers/Api/SellerVoucherController.php:84-88` (update)
- `app/Http/Controllers/Api/SellerVoucherController.php:47-54` (create)
- `app/Http/Controllers/Api/AdminController.php:754` (update)
- `app/Http/Controllers/DigitalAdminController.php:273` (update)

```php
$data = $request->all();
$data['kode'] = strtoupper(trim($data['kode']));
$voucher->update($data);
```

**Akar masalah:** `Voucher::$fillable` (`app/Models/Voucher.php:17-31`) memuat `scope`, `id_toko`, `kuota_terpakai`, `dibuat_oleh`, `is_active`. Karena `$request->all()` diteruskan langsung, seluruh kolom itu dapat ditulis ulang oleh client.

**Dampak:** `PUT /seller/voucher/{id}` dengan body `{"scope":"global","id_toko":null}` membuat voucher milik seller A dapat dipakai pada produk seller B. `Api/CheckoutController.php:99` hanya menolak ketidakcocokan ketika `$voucher->scope === 'toko_spesifik'`, sehingga tidak ada yang menghentikannya. Payout seller B ikut terpotong karena `PembelianObserver.php:46-58` mengkredit `harga_saat_beli - komisi` dari nominal yang sudah didiskon.

**Perbaikan:** Ganti `$request->all()` dengan `$request->only([...])` yang eksplisit, dan jangan pernah mengizinkan `scope`/`id_toko`/`kuota_terpakai` diisi dari request untuk endpoint seller.

---

## 🟠 HIGH — fitur rusak / lubang keamanan

### H1. `must_change_password` tidak pernah tersimpan (fitur mati total)

**Lokasi:**
- Kolom: `database/migrations/2026_07_14_000006_add_must_change_password_to_users_table.php:15`
- Middleware: `app/Http/Middleware/RedirectIfMustChangePassword.php:19` — **aktif** di grup `web` (`Kernel.php:40`)
- Penulisan nilai: `KelolaSellerController.php:65` dan `Api/AdminController.php:120` (`'must_change_password' => true`)
- `app/Models/User.php:23-31` — `$fillable` berisi `name`, `email`, `password`, `profile_picture`, `role_id`, `is_banned`, `banned_reason` — **`must_change_password` TIDAK ADA**

**Dampak:** Nilai `true` dibuang diam-diam oleh mass-assignment protection. Akun seller yang dibuatkan admin **tidak pernah dipaksa mengganti password**. Ini fitur keamanan yang tidak terintegrasi sama sekali — kode ada, middleware ada, kolom ada, tetapi tidak pernah menyala.

**Catatan:** Efek samping positifnya, tidak ada risiko lockout saat ini. Setelah diperbaiki, pastikan jalur ganti password berfungsi sebelum mengaktifkan.

---

### H2. Login API (`/api/v1`) melewati maintenance mode & banned check

**Lokasi:** `app/Http/Kernel.php:44-47`

```php
'api' => [
    // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

Grup `web` (`Kernel.php:33-43`) memuat `RedirectIfMustChangePassword`, `CheckMaintenanceMode`, dan `CheckBannedUser`. **Ketiganya absen dari grup `api`.**

**Dampak:** User yang di-ban dari web masih dapat memakai aplikasi mobile/API sepuasnya. Maintenance mode juga tidak berlaku di API.

---

### H3. Ban dari panel web tidak mencabut token API

**Lokasi:**
- `app/Http/Controllers/KelolaCustomerController.php:26` (`banCustomer`), `:40` (`unbanCustomer`)
- `app/Http/Controllers/KelolaSellerController.php:201, 207` (`banSeller`), `:219, 225` (`unbanSeller`)

Semuanya hanya men-set `is_banned` dan tidak menyentuh token.

**Pembanding yang benar:** `app/Http/Controllers/Api/AdminController.php:201` (`$toko->user->tokens()->delete()`) dan `:309` (`$user->tokens()->delete()`).

**Dampak:** Admin yang mem-ban user lewat panel web mengira user sudah tidak punya akses, padahal token Sanctum user tersebut masih valid dan tetap bisa dipakai di mobile. Dua jalur kode yang seharusnya identik justru berbeda perilaku.

**Perbaikan:** Tambahkan `$user->tokens()->delete()` pada versi web, atau lebih baik ekstrak logika ban ke satu service agar tidak divergen lagi.

---

### H4. `/webpush/test` tanpa autentikasi (+ kebocoran pesan error)

**Lokasi:** `routes/web.php:87` → `WebPushController::sendTestNotification` (`app/Http/Controllers/WebPushController.php:128`)

```php
Route::post('/test', [\App\Http\Controllers\WebPushController::class, 'sendTestNotification'])->name('webpush.test');
```

Tidak ada middleware `auth`. Endpoint (route grup `webpush`, `routes/web.php:83-87`) menerima `endpoint`, `keys.p256dh`, `keys.auth` sembarang dari siapa pun, lalu server melakukan **request keluar ke endpoint tersebut** melalui `sendOneNotification`.

**Dampak:**
1. Siapa pun dapat memaksa server menghubungi host arbitrer (SSRF terbatas pada protokol push)
2. Penyalahgunaan sebagai reflector/spam push
3. `:198`, `:295`, `:361` mengembalikan `$e->getMessage()` mentah ke response — bocoran informasi internal (path, konfigurasi, versi)

**Perbaikan:** Tambahkan `auth` (atau minimal rate-limit ketat), dan jangan kembalikan pesan exception mentah ke client — catat ke log, kembalikan pesan generik.

**Catatan:** Perhatikan juga `sendTestToDevice` (`:317`) yang memakai `$subscription->device_name` (`:333`, `:345`) — perlu dikonfirmasi apakah kolom `device_name` benar ada pada model `PushSubscription`.

---

### H5. Voucher "persen" selalu dihitung sebagai nominal (jalur web)

**Lokasi:** `app/Http/Controllers/ProductController.php:385`

```php
if ($voucher->tipe_diskon === 'persentase') {
    $potongan = ($harga_varian * (float) $voucher->nilai_diskon) / 100;
    ...
} else {
    $potongan = (float) $voucher->nilai_diskon;   // ← SELALU masuk sini
}
```

**Akar masalah:** Enum DB adalah `('persen', 'nominal')` — `database/migrations/2026_07_20_183349_01_create_vouchers_table.php:17`. String `'persentase'` **tidak pernah ada** di database, sehingga kondisi di atas tidak pernah `true`.

**Dampak:** Voucher diskon 50% dihitung sebagai potongan **Rp50**, bukan 50%. Bukan bug kosmetik — ini bug harga.

**Pembanding:** Jalur API sudah benar — `app/Http/Controllers/Api/CheckoutController.php:115` memeriksa `'persen' || 'persentase'`.

**Kesimpulan penting:** **Checkout web dan checkout API memberikan harga berbeda untuk voucher yang sama.**

---

### H6. Admin voucher via API selalu gagal (nilai enum tidak valid)

**Lokasi:** `app/Http/Controllers/Api/AdminController.php:48, 695, 719, 732, 761, 770`

```php
$totalVoucherAdmin = Voucher::where('scope', 'semua_toko')->count();   // :48
$data['scope'] = 'semua_toko';                                          // :719
$vouchers = Voucher::where('scope', 'semua_toko')...                    // :695
```

**Akar masalah:** Enum DB adalah `('global', 'toko_spesifik')` — `database/migrations/2026_07_20_183349_01_create_vouchers_table.php:25`. Nilai `'semua_toko'` **tidak ada**.

**Dampak:** Dengan `config/database.php:59` `'strict' => true`, `POST /admin/voucher` menghasilkan error 500 (data truncated), dan `GET /admin/voucher` selalu mengembalikan list kosong walaupun voucher global sebenarnya ada.

**Pembanding:** Versi web sudah benar — `app/Http/Controllers/AdminVoucherController.php:42, 56` memakai `'global'`.

Perhatikan juga validator API yang menerima nilai tak dikenal: `Api/AdminController.php:706, 738` memakai `'in:persen,nominal,persentase'`.

---

### H7. `Pembayaran::sum('total')` — dashboard admin API error

**Lokasi:** `app/Http/Controllers/Api/AdminController.php:45`

```php
$totalRevenue = Pembayaran::sum('total');
```

**Akar masalah:** Tabel `tbl_pembayaran` tidak memiliki kolom `total`. Kolomnya adalah `id_pembayaran`, `id_pembelian`, `metode_pembayaran`, `jumlah_dibayar`, `midtrans_transaction_id`, `tanggal_bayar` — `database/migrations/2026_07_10_000007_create_tbl_pembayaran.php`. Kolom `total` hanya ada di tabel legacy `tbl_pembayaran_zip`.

**Dampak:** `GET /admin/dashboard` melempar `SQLSTATE[42S22] Unknown column 'total'` — seluruh dashboard admin gagal.

**Pembanding:** `DashboardController.php:60` memakai `sum('jumlah_dibayar')` — benar.

---

### H8. `tbl_produk` di-query lewat kolom `id` yang tidak ada

**Lokasi query:**
- `app/Http/Controllers/Api/CatalogController.php:204, 209` → `where('id', $identifier)`
- `app/Http/Controllers/Api/SellerProductController.php:57, 120, 163` → `where('id', $id)->where('id_toko', ...)`
- `app/Http/Controllers/Api/SellerInventoryController.php:62, 93` → `Produk::where('id', $request->id_produk)`

**Lokasi validasi:**
- `app/Http/Controllers/Api/SellerInventoryController.php:55, 85` → `'id_produk' => 'required|exists:tbl_produk,id'`

**Akar masalah:** Primary key sebenarnya adalah `id_produk`:
- `app/Models/Produk.php:15` → `protected $primaryKey = 'id_produk';`
- `database/migrations/2026_07_10_000001_create_tbl_produk.php:19` → `$table->increments('id_produk');`

**Dampak:** Pada database hasil migration: `GET /produk/{id}` (publik), seller product show/update/destroy, `POST /seller/tipe`, dan `POST /seller/varian` semuanya gagal dengan `Unknown column 'id'`. Selain itu `Api/CatalogController.php:252` → `where('produk_id', $produk->id)` juga resolve ke `null` secara senyap karena atribut `id` tidak ada pada model.

**⚠️ PERLU KONFIRMASI:** Sisi web konsisten memakai `id_produk` (`DigitalAdminController.php:63, 103`, `PremiumAdminController.php:65, 105`). Jika tabel produksi Anda sebenarnya memakai kolom `id` (diubah manual di luar migration), maka yang salah justru `Produk::$primaryKey` dan relasi `TipeLayanan::produk()`. **Arah perbaikannya berlawanan** — mohon konfirmasi skema DB live sebelum diperbaiki.

---

### H9. Pendapatan dashboard seller salah hitung

**Lokasi:** `app/Http/Controllers/Api/SellerController.php:26-31`

```php
$pendapatan = MutasiSaldo::where('id_toko', $toko->id_toko)
    ->where('tipe', 'masuk_penjualan')
    ->orWhere(function($q) use ($toko) {
        $q->where('id_toko', $toko->id_toko)->where('nominal', '>', 0);
    })
    ->sum('nominal');
```

**Dua masalah sekaligus:**

1. **Nilai `'masuk_penjualan'` tidak pernah ditulis di kode mana pun.** Enum yang sah adalah `('kredit_penjualan', 'potong_withdraw', 'penyesuaian_admin')` — `database/migrations/2026_07_14_000003_create_tbl_mutasi_saldo.php:17`. Observer menulis `'kredit_penjualan'` (`app/Observers/PembelianObserver.php:67`, juga `BeliProdukObserver.php:62`, `RepairBalancesCommand.php:74`).

2. **`orWhere` tanpa pengelompokan** mereduksi predikat menjadi:
   `(id_toko = X AND tipe = 'masuk_penjualan') OR (id_toko = X AND nominal > 0)`

**Dampak:** Dashboard seller melaporkan **jumlah seluruh mutasi positif sepanjang masa** toko tersebut sebagai "pendapatan". Angka ini tidak pernah mencerminkan penjualan aktual dan tidak bisa bernilai nol.

---

## 🟡 MEDIUM — bug, race condition, XSS

### M1. Undefined variable `$status`

**Lokasi:** `app/Http/Controllers/PembayaranController.php:106`

```php
Mail::to($user->email)->send(new MailProdukBeli(
    $user->name,
    $produk->nama_produk,
    $produk->deskripsi,
    $status->gross_amount,   // ← $status tidak terdefinisi
    $order_id
));
```

Variabel yang benar adalah `$statusData` — dipakai di `:51` dan `:86` pada file yang sama.

**Dampak:** Email konfirmasi pada jalur `syncTransactionStatus` legacy selalu gagal terkirim. Karena berada di dalam `try/catch` (`:110-112`), kegagalannya **senyap** — hanya tercatat di log.

---

### M2. Upload gambar: ekstensi diambil dari nama file client

**Pola `getClientOriginalName()`** (mempertahankan ekstensi client):
- `app/Http/Controllers/Api/SellerProductController.php:96, 145`
- `app/Http/Controllers/Api/CustomerPremiumController.php:360`
- `app/Http/Controllers/Api/AdminController.php:800, 832`
- `app/Http/Controllers/ProductController.php:109, 200`
- `app/Http/Controllers/ProductDigitalController.php:109`
- `app/Http/Controllers/LaporanController.php:43`
- `app/Http/Controllers/MitraIndustriController.php:30, 58`

**Pola `getClientOriginalExtension()`:**
- `app/Http/Controllers/Api/ProfileController.php:69`
- `app/Http/Controllers/Api/SellerController.php:120`
- `app/Http/Controllers/Api/AdminController.php:621, 631, 641`

**Dampak:** Validasi `image|mimes:jpeg,png,jpg,webp` memeriksa MIME hasil deteksi konten, **bukan** ekstensi nama file. Sebuah polyglot JPEG yang valid namun bernama `x.php` lolos validasi dan tertulis sebagai `.php` di direktori publik → eksekusi kode.

**Tambahan:** `app/Http/Controllers/Api/AdminController.php:590` mengizinkan `svg`:
```php
'favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,svg|max:1024',
```
SVG dapat memuat JavaScript → **stored XSS** lewat favicon.

**Catatan:** Helper `getTargetFile()`/`getName()` pada Symfony sudah men-strip komponen direktori, jadi **path traversal tidak terjadi** — yang bermasalah murni ekstensi.

---

### M3. TOCTOU pada penarikan saldo → saldo bisa negatif

**Lokasi:** `app/Http/Controllers/Api/AdminController.php:362-379`

```php
if ($nominal > (float) $toko->saldo) {          // :362 — dibaca TANPA lock
    return $this->sendError('Nominal penarikan melebihi saldo toko saat ini.', [], 400);
}

$mutasi = DB::transaction(function () use ($toko, $nominal, $request) {
    $tokoLocked = Toko::where('id_toko', $toko->id_toko)->lockForUpdate()->first();
    $saldo_akhir = $tokoLocked->saldo - $nominal;   // :368 — TIDAK dicek ulang
    ...
});
```

**Dampak:** Dua request `POST /admin/saldo-toko/{id}/withdraw` yang berjalan bersamaan sama-sama lolos pengecekan pra-lock, lalu keduanya melakukan pengurangan, membuat `tbl_toko.saldo` menjadi negatif.

**Perbaikan:** Pindahkan pengecekan saldo **ke dalam** `DB::transaction` setelah `lockForUpdate()`, dan periksa ulang terhadap `$tokoLocked->saldo`.

---

### M4. Kuota voucher tidak atomik

**Lokasi:** `app/Http/Controllers/Api/CheckoutController.php:96` membaca `kuota_terpakai`, sedangkan incrementnya baru terjadi saat pembayaran sukses — `app/Observers/PembelianObserver.php:137` (`$voucher->increment('kuota_terpakai')`).

**Dampak:** Yang dikunci hanya `checkout_varian_{id_varian}`, bukan voucher. Beberapa checkout bersamaan dengan kode voucher yang sama dapat melampaui `kuota_total`.

---

### M5. Null dereference menghilangkan ownership check

**Lokasi:**
- `app/Http/Controllers/Api/CheckoutController.php:177-178` dan `:241-242`
- `app/Http/Controllers/Api/CustomerPremiumController.php:210-211, 240-241, 279-280, 299-300`

```php
$customer = CustomerModel::where('user_id', $request->user()->id)->first();
if ($pembelian->id_customer != $customer->id) return $this->sendError('Unauthorized', [], 403);
```

**Dampak:** Jika baris `tbl_customer` untuk user tersebut tidak ada (terhapus, atau dibuat sebelum alur registrasi sekarang), terjadi fatal error `Attempt to read property "id" on null` — **500 sebelum guard dijalankan**. Guard-nya gagal-terbuka secara senyap.

**Pembanding yang benar:** `Api/CheckoutController.php:45-47` memeriksa `if (!$customer)` lebih dulu dan mengembalikan 404.

---

### M6. `generateTransaction` tanpa cek status pesanan

**Lokasi:** `app/Http/Controllers/Api/CheckoutController.php:172-195`

Tidak ada pemeriksaan `$pembelian->status`.

**Dampak:** Transaksi gateway baru dapat dibuat untuk pesanan yang sudah `success` (**double charge**) atau yang sudah `expired` (stoknya sudah dilepas).

---

### M7. Voucher bocor antar toko

**Lokasi:**
- `app/Http/Controllers/Api/CustomerPremiumController.php:45-54` (`getMemberData`) — menampilkan **semua** voucher aktif ke setiap customer, termasuk voucher `toko_spesifik` milik toko lain dan milik toko yang di-ban
- `app/Http/Controllers/Api/CustomerPremiumController.php:105-132` (`klaimVoucher`) — memakai `Voucher::find($id_voucher)` tanpa pengecekan kelayakan scope

**Dampak:** Kebocoran informasi bisnis antar seller, dan klaim voucher yang seharusnya tidak berhak.

---

### M8. GET yang menulis ke database

**Lokasi:**
- `app/Http/Controllers/Api/CatalogController.php:73-79` — `$toko->update([...])` (menghitung kunjungan) di dalam endpoint **publik tanpa autentikasi** `GET /toko/{id}`
- `app/Http/Controllers/Api/CustomerPremiumController.php:150-172` (`getRiwayat`) juga menulis di GET

**Dampak:** Race condition pada `tbl_toko`, dan counter kunjungan dapat digelembungkan oleh siapa pun tanpa login. Melanggar semantik HTTP (GET seharusnya idempoten dan aman).

---

### M9. CORS terbuka penuh

**Lokasi:** `config/cors.php`

```php
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'supports_credentials' => false,
```

**Dampak:** Setiap origin dapat memanggil API. Tidak ada kebocoran kredensial karena `supports_credentials => false`, tetapi tetap memperlebar permukaan serangan (mis. CSRF-like terhadap endpoint yang mengandalkan header, eksfiltrasi data publik).

---

### M10. Midtrans dipaksa sandbox

**Lokasi:** `app/Services/Gateways/MidtransGateway.php:16`

```php
\Midtrans\Config::$isProduction = false;
```

**Dampak:** Hardcoded, bukan dari config. Di lingkungan produksi, pembayaran riil tidak akan berjalan — semua transaksi masuk mode sandbox.

---

### M11. VAPID private key hardcoded

**Lokasi:** `config/webpush.php`

```php
'private_key' => env('VAPID_PRIVATE_KEY', 'EYMOhQXTCTX7HSnXG5X87zxNRMehJAm2lX2IWNKuEFo'),
```

**Dampak:** Private key VAPID tersimpan sebagai default `env()` sehingga ikut masuk ke repository. Siapa pun dengan akses repo dapat mengirim push notification atas nama aplikasi.

**Perbaikan:** Hapus default value, wajibkan lewat `.env`, dan rotate key yang sudah bocor.

---

### M12. Email admin hardcoded di service

**Lokasi:** `app/Services/PaymentProcessingService.php:108` → `$message->to('g4lihanggoro@gmail.com')`

**Dampak:** Notifikasi alert pembayaran bergantung pada satu alamat email pribadi yang di-hardcode. Jika email itu berubah atau tidak lagi dipantau, alert hilang tanpa jejak. Sulit dites dan tidak dapat dikonfigurasi.

**Perbaikan:** Pindahkan ke config/env (mis. `MAIL_ADMIN_ALERT`).

---

### M13. Middleware `ResetHeaders` terdaftar tetapi tidak pernah dipakai

**Lokasi:** `app/Http/Kernel.php:72`

```php
'reset.headers' => \App\Http\Middleware\ResetHeaders::class,
```

Alias terdaftar, kelasnya ada (`app/Http/Middleware/ResetHeaders.php`), tetapi **tidak dipasang ke grup atau route mana pun** (sudah dicek di seluruh `routes/`). Fitur mati.

---

### M14. Unescaped output (`{!! !!}`) pada data yang dapat dikendalikan pengguna

| File | Baris | Konten |
|---|---|---|
| `resources/views/produk/index.blade.php` | 89 | `{!! $item->toko->nama_toko ?? '<span…>' !!}` |
| `resources/views/produk_digital/index.blade.php` | 89 | `{!! $item->toko->nama_toko ?? '<span…>' !!}` |
| `resources/views/customer/news/show.blade.php` | 100 | `{!! $news->konten !!}` |
| `resources/views/admin/news/create.blade.php` | 96 | `{!! old('konten') !!}` |
| `resources/views/admin/news/edit.blade.php` | 97 | `{!! old('konten', $news->konten) !!}` |
| `resources/views/components/stat-card.blade.php` | 15 | `{!! $icon !!}` |
| `resources/views/components/stat_card.blade.php` | 15 | `{!! $icon !!}` |

**Analisis:**

- **`nama_toko` → STORED XSS (paling serius di grup ini).** Hanya divalidasi `'nama_toko' => 'required|string|max:150'` — `KelolaSellerController.php:28, 92`, `Api/AdminController.php:104, 148`, `Api/SellerController.php:100`. Tidak ada sanitasi. Seller dapat menyisipkan `<script>` ke nama toko mereka, yang kemudian dieksekusi di panel admin yang menampilkan daftar produk.
- **`old('konten')`** mencerminkan input POST kembali ke halaman tanpa escape — self-XSS pada admin, tetap sebaiknya diperbaiki.
- **`$news->konten`** berasal dari Quill editor admin. Perhatikan `news/show.blade.php:98` sudah sengaja memakai `nl2br(e($news->konten))` yang aman — baris `:100` tampak duplikat/sisa yang seharusnya dihapus.
- **`stat-card.blade.php` & `stat_card.blade.php`** adalah dua file duplikat dengan isi identik.

---

## 🟢 LOW / kebersihan

### L1. Route duplikat `menu_produk`

- `routes/web.php:128` → `Route::resource('menu_produk', ProductController::class, ['only' => ['index']]);`
- `routes/web.php:293` → `Route::resource('menu_produk', ProductController::class, ['except' => ['index', 'show']]);`
- Pola sama untuk `menu_produk_digital` (`:129` dan `:296`)

`route:list` menunjukkan keduanya terdaftar tanpa tabrakan method+URI (sudah saya verifikasi programatik — **0 duplikat**), tetapi pola ini membingungkan dan rawan menimbulkan konflik saat route baru ditambahkan.

### L2. View mati dengan route name tidak terdefinisi

**`resources/views/premium_admin/produk/index.blade.php`** tidak dirender oleh controller mana pun (sudah dicek — `PremiumAdminController` hanya merender `inventaris.index` dan `histori.index`). Isinya memanggil:
- `:64` → `route('premium.produk.update', $item->id_produk)`
- `:119` → `route('premium.produk.store')`

Kedua route name tersebut **tidak terdefinisi** di `routes/web.php`. Halaman ini pasti error jika diaktifkan.

**`resources/views/produk/extract_screenshots.blade.php`** memanggil `btnPilih()` yang tidak didefinisikan di mana pun. Controller-nya sudah dihapus — lihat komentar di `ProductController.php:459` ("`extract_screenshots()` dan `proses_extract_screenshots()` dihapus").

### L3. Route bernama tanpa UI

33 route bernama tidak direferensikan di view/JS. **Perlu dicatat:** sebagian besar tetap hidup karena dipanggil lewat `url()` hardcoded, bukan `route()`. Yang benar-benar perlu diperiksa:
- `premium.tipe.destroy`, `premium.varian.destroy`, `digital.tipe.destroy`, `digital.varian.destroy` — tidak ada tombol delete di UI untuk tipe/varian
- `admin.setting_komisi`, `admin.saldo_toko` — dipanggil via `url('setting_komisi')` / `url('saldo_toko')`, jadi masih berfungsi

### L4. `robots.txt` membocorkan URL development

**Lokasi:** `public/robots.txt`

```
Sitemap: http://localhost:8000/sitemap.xml
Sitemap: https://lapaktifikasi.com/sitemap.xml
```

Baris pertama membocorkan konfigurasi development dan mengirim crawler ke URL yang tidak ada.

### L5. Aset PWA — sudah diverifikasi aman

Semua aset di `PRECACHE_ASSETS` (`public/sw.js:4-14`) **ada**:
`manifest.json`, `favicon.png`, `icon-192x192.png`, `icon-512x512.png`, `shortcut-katalog.png`, `shortcut-riwayat.png`, `shortcut-mitra.png`, `shortcut-bantuan.png`. Route `/offline` juga terdefinisi (`routes/web.php:75-77`). Tidak ada masalah di sini.

### L6. Test suite tidak dapat dijalankan di environment ini

`php artisan test` → **19 gagal, 11 lulus** (21 assertion). **Semua** kegagalan berpesan sama:

```
could not find driver (Connection: sqlite, SQL: PRAGMA foreign_keys = ON;)
```

Ini murni masalah environment — ekstensi `pdo_sqlite` tidak terpasang. **Bukan bukti logika aplikasi rusak.** Perlu dicatat agar tidak disalahartikan sebagai temuan bug.

---

## 📦 Update Keamanan Dependensi (`composer audit`)

**Total: 34 advisory di 9 paket.**

### Versi terpasang

| Paket | Versi |
|---|---|
| `laravel/framework` | 10.50.2 |
| `guzzlehttp/guzzle` | 7.14.1 |
| `dompdf/dompdf` | v2.0.8 |
| `league/commonmark` | 2.8.3 |
| `livewire/livewire` | v4.4.0 |
| `symfony/http-foundation` | v6.4.42 |
| `phpseclib/phpseclib` | 3.0.55 |
| `league/flysystem` | 3.35.2 |
| `laravel/sanctum` | v3.3.3 |

### Yang benar-benar dapat dieksploitasi di aplikasi ini

| Paket | CVE | Alasan relevan |
|---|---|---|
| `symfony/http-foundation` | CVE-2026-108082 — ReDoS di `HeaderUtils::split()` (high) | Dipakai pada **setiap request**; header dikendalikan client |
| `guzzlehttp/guzzle` | CVE-2026-69246 — noncanonical host bypass (high) | Guzzle dipakai untuk verifikasi gateway pembayaran & pengiriman WhatsApp (Fonnte) |
| `dompdf/dompdf` | CVE-2026-59943, 59942, 59941, 56722 (4× medium) + CVE-2026-55555, 55554 (2× low) | DomPDF dipakai untuk invoice — **6 dari 34 advisory** ada di paket ini, dan `v2.0.8` jauh tertinggal |
| `laravel/framework` | CVE-2026-48019 / CRLF injection pada rule `email` (high) | Validasi email dipakai di registrasi & login |

### Yang sudah diverifikasi TIDAK dapat dijangkau

Jangan buru-buru panik pada empat ini:

- **`laravel/framework` CVE-2026-114509 — TokenGuard authentication bypass (critical).** `config/auth.php` hanya mendefinisikan guard `web` (session) dan `sanctum`. **Tidak ada guard dengan `driver: token`**, sehingga jalur CVE ini tidak aktif di aplikasi ini.
- **`livewire/livewire` — Authorization bypass pada `#[Authorize]`.** Atribut tersebut tidak digunakan di codebase.
- **`league/commonmark` — 13 dari 34 advisory.** Hanya dipakai melalui `Illuminate\Mail\Markdown::parse()` di template email bawaan Laravel (`resources/views/vendor/mail/html/*.blade.php`), dengan konten yang disusun oleh aplikasi sendiri, bukan input pengguna.
- **`phpseclib/phpseclib` CVE-2026-84308 — non-constant-time X25519.** Hanya pada jalur WebPush VAPID, bukan komparasi rahasia yang dapat diserang timing.

### Urutan update yang disarankan

1. **`dompdf`** — permukaan terbesar (6 advisory) dan menyentuh data invoice pelanggan
2. **`symfony/http-foundation`** — ReDoS, tersentuh setiap request
3. **`guzzlehttp/guzzle`** — terlibat dalam verifikasi pembayaran
4. **`laravel/framework`** — CRLF injection pada validasi email

---

## Ringkasan Prioritas

**Urutan perbaikan yang direkomendasikan:**

| # | ID | Temuan | Alasan prioritas |
|---|---|---|---|
| 1 | **C1** | Backdoor admin `role_id = 1` | Perbaikan 2 baris, dampak maksimal, terbuka publik |
| 2 | **C2** | `db_tokoku.sql` terekspos | Hapus file + **rotate semua password & token** yang bocor |
| 3 | **C3** | Upload `file_digital` tanpa `mimes:` (RCE) | Jalur eskalasi langsung seller → server |
| 4 | **C4** | Mass assignment `Voucher` | Voucher toko jadi global, merugikan seller lain |
| 5 | **H5–H8** | Enum & kolom tidak cocok | Ini yang membuat fitur **benar-benar rusak hari ini** |
| 6 | **H1–H3** | `must_change_password` + grup `api` + cabut token saat ban | Fitur keamanan mati / bypass |
| 7 | **H4** | `/webpush/test` tanpa auth | SSRF terbatas + kebocoran pesan error |
| 8 | — | Update dependensi | `dompdf` prioritas utama |

**Gelombang yang disarankan:**

- **Gelombang 1 (keamanan kritis, perubahan kecil & terisolasi):** C1, C2, C3, C4 — dapat direview cepat
- **Gelombang 2 (bug fungsional terverifikasi):** H5, H6, H7, H9, M1 — sudah dibuktikan baris-per-baris terhadap migration
- **Gelombang 3 (integrasi & race condition):** H1, H2, H3, H4, M2–M8
- **Gelombang 4 (kebersihan & dependensi):** L1–L4, M9–M14, update paket

---

## Catatan Cakupan

Agar jelas apa yang sudah dan belum diverifikasi:

**Sudah diverifikasi baris-per-baris terhadap migration:**
H5, H7, H8, H9, M1 — termasuk pencocokan nilai enum dan nama kolom dengan definisi skema.

**⚠️ Membutuhkan konfirmasi skema DB live:**
**H8** adalah satu-satunya temuan yang arah perbaikannya bergantung pada kondisi database Anda. Migration mendefinisikan `id_produk`, tetapi API memakai `id`. Jika tabel produksi sudah diubah manual di luar migration agar memakai kolom `id`, maka yang salah justru `Produk::$primaryKey` dan relasi `TipeLayanan`. **Mohon konfirmasi skema DB live sebelum H8 diperbaiki.**

**Yang TIDAK ditemukan (sudah dicek, aman):**

- **Tidak ada IDOR** pada endpoint seller/customer API. Setiap jalur seller di-scope lewat `Toko::where('user_id', ...)` lalu `id_toko` atau rantai relasi; setiap jalur customer di-scope lewat `id_customer`/`user_id`. Middleware role di `routes/api.php` (`only.customer`, `only.seller`, `admin.only`, semuanya setelah `auth:sanctum`) sudah terpasang dengan benar.
- **Tidak ada form PUT/DELETE yang lupa `@method`** — diverifikasi programatik terhadap 104 route terpetakan: **0 temuan**.
- **Semua `<form method="POST">` memiliki `@csrf`** — 0 temuan.
- **Tidak ada `fetch()` write tanpa token CSRF** — 0 temuan.
- **Semua route name yang direferensikan di view terdefinisi**, kecuali pada view mati (`L2`).
- **Tidak ada path traversal pada upload** — helper Symfony `getTargetFile()`/`getName()` sudah men-strip komponen direktori. Masalahnya murni ekstensi (`M2`).
- **Semua aset precache Service Worker ada** (`L5`).
- **Tidak ada guard `token` di `config/auth.php`**, sehingga CVE TokenGuard Laravel tidak terjangkau.
- **Semua referensi route di view yang diuji resolve** kecuali pada view mati.

**Keterbatasan:**

- Satu agen audit paralel (Blade/JS) dihentikan setelah 21 menit tanpa output. Seluruh cakupan relevannya dikerjakan langsung dan sudah tercakup di laporan ini (lihat daftar "Yang TIDAK ditemukan" di atas).
- Test suite tidak dapat dijalankan karena `pdo_sqlite` tidak tersedia di environment ini (`L6`).

---

*Laporan ini dihasilkan dari audit statis codebase. Tidak ada file aplikasi yang diubah selama proses audit.*
