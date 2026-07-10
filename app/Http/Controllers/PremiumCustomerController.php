<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Models\Pembelian;
use App\Models\CustomerModel;
use App\Enums\PembelianStatus;
use App\Enums\StokStatus;

class PremiumCustomerController extends Controller
{
    // === 1. Listing Produk -> Tipe Layanan -> Varian ===
    public function katalog()
    {
        // Get all active products with their active types and active variations
        $produk = Produk::where('status', 'aktif')
            ->with(['tipeLayanan' => function ($query) {
                $query->where('status', 'aktif')
                    ->with(['varianLayanan' => function ($vQuery) {
                        $vQuery->where('status', 'aktif');
                    }]);
            }])
            ->get();

        // Calculate available stock for each variation
        // SELECT COUNT(*) FROM tbl_stok_akun WHERE id_varian = :id_varian AND status = 'tersedia'
        foreach ($produk as $prod) {
            foreach ($prod->tipeLayanan as $tipe) {
                foreach ($tipe->varianLayanan as $varian) {
                    $varian->stok_tersedia = StokAkun::where('id_varian', $varian->id_varian)
                        ->where('status', StokStatus::TERSEDIA)
                        ->count();
                }
            }
        }

        return view('premium_customer.katalog', compact('produk'));
    }

    // === 2. Riwayat Pembelian Customer ===
    public function riwayat()
    {
        $idCustomerUser = session('id');
        $customer = CustomerModel::where('user_id', $idCustomerUser)->first();

        if (!$customer) {
            return redirect('/')->with('error', 'Profil pelanggan tidak ditemukan.');
        }

        $pembelian = Pembelian::with(['varianLayanan.tipeLayanan.produk', 'pembayaran'])
            ->where('id_customer', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('premium_customer.riwayat', compact('pembelian'));
    }

    // === 3. Tampilkan Kredensial (Decrypt On-Demand) ===
    public function kredensial($id_pembelian)
    {
        $idCustomerUser = session('id');
        $customer = CustomerModel::where('user_id', $idCustomerUser)->first();

        if (!$customer) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $pembelian = Pembelian::with('stokAkun')
            ->where('id_pembelian', $id_pembelian)
            ->where('id_customer', $customer->id)
            ->firstOrFail();

        // JANGAN PERNAH expose password KECUALI jika status sudah 'success' dan miliknya sendiri.
        if ($pembelian->status !== PembelianStatus::SUCCESS) {
            return response()->json(['error' => 'Pembayaran belum diselesaikan atau transaksi expired.'], 403);
        }

        if (!$pembelian->stokAkun) {
            return response()->json(['error' => 'Kredensial akun tidak ditemukan. Harap hubungi admin.'], 404);
        }

        // Laravel automatically decrypts password_encrypted due to encrypted cast on StokAkun model
        return response()->json([
            'email_username' => $pembelian->stokAkun->email_username,
            'password' => $pembelian->stokAkun->password_encrypted,
            'catatan' => $pembelian->stokAkun->catatan,
        ]);
    }
}
