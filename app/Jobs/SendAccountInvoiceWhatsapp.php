<?php

namespace App\Jobs;

use App\Models\Pembelian;
use App\Models\Pembayaran;
use App\Services\FonnteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class SendAccountInvoiceWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 20;

    public function __construct(
        public int $idPembelian
    ) {}

    public function handle(FonnteService $fonnte): void
    {
        $pembelian = Pembelian::with([
            'customer',
            'varianLayanan.tipeLayanan.produk',
            'stokAkun',
        ])->find($this->idPembelian);

        if (! $pembelian) {
            Log::warning('SendAccountInvoiceWhatsapp: pembelian tidak ditemukan', [
                'id_pembelian' => $this->idPembelian,
            ]);

            return;
        }

        $nomorTelepon = $pembelian->customer?->nomor_telepon;

        if ($nomorTelepon === null || trim((string) $nomorTelepon) === '') {
            Log::warning('SendAccountInvoiceWhatsapp: nomor telepon kosong', [
                'id_pembelian' => $this->idPembelian,
                'order_id' => $pembelian->order_id,
            ]);

            return;
        }

        if (! $pembelian->stokAkun) {
            Log::warning('SendAccountInvoiceWhatsapp: stok akun tidak ditemukan', [
                'id_pembelian' => $this->idPembelian,
                'order_id' => $pembelian->order_id,
            ]);

            return;
        }

        try {
            $password = Crypt::decryptString($pembelian->stokAkun->getRawOriginal('password_encrypted'));
        } catch (\Throwable $e) {
            Log::error('SendAccountInvoiceWhatsapp: gagal decrypt password', [
                'id_pembelian' => $this->idPembelian,
                'order_id' => $pembelian->order_id,
                'error' => $e->getMessage(),
            ]);

            return;
        }

        $pembayaran = Pembayaran::where('id_pembelian', $this->idPembelian)->first();

        if (! $pembayaran) {
            Log::warning('SendAccountInvoiceWhatsapp: pembayaran tidak ditemukan', [
                'id_pembelian' => $this->idPembelian,
                'order_id' => $pembelian->order_id,
            ]);

            return;
        }

        $message = $this->buildMessage($pembelian, $password);
        $response = $fonnte->sendText($nomorTelepon, $message);
        $isSuccess = ($response['status'] ?? false) === true;

        $pembayaran->update([
            'wa_sent_at' => $isSuccess ? now() : null,
            'wa_response' => json_encode($response),
        ]);
    }

    private function buildMessage(Pembelian $pembelian, string $password): string
    {
        $varian = $pembelian->varianLayanan;
        $tipe = $varian?->tipeLayanan;
        $produk = $tipe?->produk;

        $namaProduk = $produk?->nama_produk ?? '-';
        $namaTipe = $tipe?->nama_tipe ?? '-';
        $namaVarian = $varian?->nama_varian ?? '-';
        $totalHarga = 'Rp ' . number_format((float) $pembelian->harga_saat_beli, 0, ',', '.');
        $emailUsername = $pembelian->stokAkun->email_username;
        $durasiHari = (int) ($varian?->durasi_hari ?? 0);
        $tanggalAktifSampai = Carbon::now()->addDays($durasiHari)->format('d/m/Y');

        return implode("\n", [
            '✅ *INVOICE TOKOKU — LUNAS*',
            '',
            'Order ID: ' . $pembelian->order_id,
            'Produk: ' . $namaProduk,
            'Tipe: ' . $namaTipe,
            'Varian: ' . $namaVarian,
            'Total: ' . $totalHarga,
            'Status: *LUNAS*',
            '',
            '--- Detail Akun ---',
            'Email/Username: ' . $emailUsername,
            'Password: ' . $password,
            'Aktif sampai: ' . $tanggalAktifSampai,
            '',
            '⚠️ Mohon *jangan ganti password* akun agar layanan tetap berjalan dengan baik.',
            '',
            'Terima kasih telah berbelanja di Tokoku! 🙏',
        ]);
    }
}
