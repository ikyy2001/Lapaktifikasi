<x-mail::message>
Dear **{{ $nama_customer }}**,

Terima kasih atas pembelian Anda! Pembayaran untuk pesanan pada invoice **{{ $order_id }}** telah berhasil terkonfirmasi.

**Rincian Pesanan**:
- **Produk**: {{ $nama_produk }}
- **Total Pembayaran**: IDR {{ number_format($total, 0, ',', '.') }}

Detail pesanan Anda (kredensial akun premium atau file digital) tidak kami kirimkan melalui email ini. Anda dapat melihat dan mengaksesnya secara langsung di menu **Riwayat Premium** di halaman profil akun Anda.

> **Pemberitahuan & Bantuan**:
> * Jika Anda merasa tidak melakukan transaksi/pembelian ini, harap segera menghubungi admin Lapaktifikasi.
> * Jika Anda menghadapi kendala teknis atau memiliki pertanyaan, silakan hubungi admin Lapaktifikasi untuk bantuan lebih lanjut.

Terima kasih,<br>
Admin, {{ config('app.name') }}
</x-mail::message>
