# Struktur Target: CSS Per-Komponen & Blade Partial — Lapaktifikasi Web

Prinsip: folder `public/css/` dan `resources/views/partials/` **mirroring** struktur `resources/views/` yang sudah ada, pakai snake_case (ngikutin konvensi project ini: `kelola_customer`, `setting_website`, `produk_digital`, dst). Jadi kalau nyari CSS section tertentu, tinggal samain path-nya sama view-nya.

---

## 🌲 Struktur Folder CSS Target

```text
public/css/
├── base/
│   ├── variables.css          # warna, font, spacing (dipakai global)
│   ├── reset.css
│   └── utilities.css          # .btn, .container, class util umum
│
├── layout/                    # dipakai di HAMPIR SEMUA halaman dashboard
│   ├── navbar.css
│   ├── sidebar_admin.css
│   ├── sidebar_seller.css
│   ├── sidebar_customer.css
│   ├── sidebar_premium_admin.css
│   └── footer.css
│
├── auth/
│   └── auth_layout.css        # split-card, hero image, form
│
├── landing/                   # welcome.blade.php
│   ├── hero.css
│   ├── produk_terlaris.css
│   ├── testimoni.css
│   ├── mitra_industri.css
│   └── cta.css
│
├── daftar_seller/
│   └── daftar_seller.css
│
├── join_partner/
│   └── join_partner.css
│
├── admin/
│   ├── kelola_seller.css
│   ├── kelola_customer.css
│   ├── saldo_toko.css
│   ├── setting_komisi.css
│   ├── setting_website.css
│   ├── mitra_industri.css
│   ├── testimoni.css
│   └── voucher.css
│
├── seller/
│   └── dashboard_toko.css
│
├── customer/
│   └── dashboard_customer.css
│
├── premium_admin/
│   └── dashboard_premium_admin.css
│
├── premium_customer/
│   └── dashboard_premium_customer.css
│
├── produk/
│   ├── katalog.css
│   └── form_produk.css
│
├── produk_digital/
│   └── kelola_digital.css
│
├── pembayaran/
│   └── checkout.css
│
├── invoice/
│   └── pdf_invoice.css        # ⚠️ dirender DomPDF, bukan browser — CSS support terbatas (no flexbox/grid), jangan disamain proses extract-nya
│
├── laporan/
│   └── laporan.css
│
├── pengaturan/
│   └── pengaturan.css
│
└── dashboard/
    └── dashboard_shared.css   # widget/card statistik yg dipakai lintas role (admin+seller+dst)
```

## 🌲 Struktur Folder Blade Partial Target (mirror 1:1)

```text
resources/views/
├── layout.blade.php                # master layout, tinggal @include semua partials/layout/*
├── auth/
│   └── layout.blade.php            # auth split-card master
│
└── partials/
    ├── layout/
    │   ├── navbar.blade.php
    │   ├── sidebar_admin.blade.php
    │   ├── sidebar_seller.blade.php
    │   ├── sidebar_customer.blade.php
    │   ├── sidebar_premium_admin.blade.php
    │   └── footer.blade.php
    │
    ├── auth/
    │   ├── hero_image.blade.php
    │   ├── login_form.blade.php
    │   ├── register_form.blade.php
    │   └── lupa_password_form.blade.php
    │
    ├── landing/
    │   ├── hero.blade.php
    │   ├── produk_terlaris.blade.php
    │   ├── testimoni.blade.php
    │   ├── mitra_industri.blade.php
    │   └── cta.blade.php
    │
    └── [module]/                   # diisi progresif — lihat urutan prioritas di bawah
        └── ...
```

---

## 📐 Aturan Konvensi

1. **Nama file CSS = nama file partial = nama section**, posisi folder relatif sama persis antara `public/css/...` dan `resources/views/partials/...`. Contoh: section testimoni admin → `public/css/admin/testimoni.css` + `resources/views/partials/admin/testimoni.blade.php`.
2. **`base/` dan `layout/` di-load duluan**, sebelum CSS per-modul — supaya utility & variable global gak ke-override.
3. **Jangan bikin partial/CSS untuk halaman yang belum disentuh.** Folder `[module]/` di atas isinya diisi *pas modul itu dikerjain*, bukan dibikin kosong semua di awal — biar gak ada file nganggur yang bikin bingung.
4. `invoice/` dikecualikan dari proses cut-paste biasa karena dirender DomPDF (constraint CSS beda dari browser) — treat terpisah, jangan disamain sama modul lain.

---

## 🎯 Urutan Prioritas Pengerjaan

Karena project ini besar, jangan sekaligus. Urutan yang paling masuk akal (efek domino terbesar duluan):

| # | Target | Kenapa duluan |
|---|--------|---------------|
| 1 | `welcome.blade.php` | Udah jalan (lihat prompt sequence sebelumnya) |
| 2 | `layout.blade.php` (master) — navbar, sidebar per-role, footer | **Leverage paling gede** — dipakai di HAMPIR SEMUA halaman dashboard (admin/seller/customer/premium_*). Sekali beres, ~90% halaman lain otomatis kebantu tanpa disentuh lagi |
| 3 | `auth/layout.blade.php` | Halaman terpisah (login/register/reset), scope kecil, cepat beres |
| 4 | `daftar_seller.blade.php` & `join_partner.blade.php` | Landing page sisanya, mirip pola welcome |
| 5 | Per-modul dashboard (admin → seller → customer → dst) | Nyicil pelan-pelan, prioritasin modul yang paling sering kamu edit duluan (misal `admin/voucher`, `seller/dashboard_toko`) |
| — | `invoice/` (DomPDF) | Terakhir, proses & aturannya beda sendiri |

---

Kalau oke sama struktur ini, saya bisa update prompt sequence sebelumnya (atau bikin sequence baru) buat target **#2: `layout.blade.php`** — itu yang paling worth it dikerjain berikutnya karena dampaknya ke seluruh dashboard.
