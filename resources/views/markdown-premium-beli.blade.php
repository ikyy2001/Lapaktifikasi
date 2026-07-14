<x-mail::message>
Dear **{{ $nama_customer }}**,

Terima kasih atas pembelian Anda! Pembayaran untuk pesanan Akun Premium Anda pada invoice **{{ $order_id }}** telah berhasil terkonfirmasi.

**Rincian Pesanan**:
- **Produk**: {{ $nama_produk }}
- **Total Pembayaran**: IDR {{ number_format($total, 0, ',', '.') }}

Demi keamanan akun, detail kredensial premium Anda (username/email, password, dan catatan akses) tidak kami kirimkan melalui email ini. Anda dapat melihat kredensial tersebut secara langsung di menu **Riwayat Premium** di halaman profil akun Anda.

> **Pemberitahuan Keamanan & Bantuan**:
> * Jika Anda merasa tidak melakukan transaksi/pembelian ini, harap segera menghubungi admin Tokoku.
> * Jika Anda menghadapi kendala teknis atau memiliki pertanyaan, silakan hubungi admin Tokoku untuk bantuan lebih lanjut.

Terima kasih,<br>
Admin, {{ config('app.name') }}
</x-mail::message>
