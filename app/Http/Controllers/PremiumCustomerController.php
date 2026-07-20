<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Models\Pembelian;
use App\Models\CustomerModel;
use App\Models\Toko;
use App\Enums\PembelianStatus;
use App\Enums\StokStatus;
use Barryvdh\DomPDF\Facade\Pdf;

class PremiumCustomerController extends Controller
{
    // === 1. Listing Produk -> Tipe Layanan -> Varian ===
    public function katalog(Request $request)
    {
        $search = $request->input('search');
        $id_toko = $request->input('id_toko');

        $query = Produk::where('status', 'aktif')->where('tipe_produk', 'premium');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        if ($id_toko) {
            $query->where('id_toko', $id_toko);
        }

        $produk = $query->with([
            'toko',
            'tipeLayanan' => function ($query) {
                $query->where('status', 'aktif')
                    ->with([
                        'varianLayanan' => function ($vQuery) {
                            $vQuery->where('status', 'aktif');
                        }
                    ]);
            }
        ])
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

        $idCustomerUser = session('id');
        $customer = CustomerModel::where('user_id', $idCustomerUser)->first();

        $toko = $id_toko ? Toko::find($id_toko) : null;

        $reviews = null;
        $ratingDistribution = [];
        if ($id_toko && $toko) {
            $reviews = \App\Models\Review::with('customer.user')
                ->where('id_toko', $id_toko)
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString();

            $distributionRaw = \App\Models\Review::where('id_toko', $id_toko)
                ->selectRaw('rating, count(*) as count')
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->toArray();

            for ($i = 1; $i <= 5; $i++) {
                $ratingDistribution[$i] = $distributionRaw[$i] ?? 0;
            }
        }

        return view('premium_customer.katalog', compact('produk', 'customer', 'toko', 'reviews', 'ratingDistribution'));
    }

    // === 2. Riwayat Pembelian Customer ===
    public function riwayat(Request $request)
    {
        $idCustomerUser = session('id');
        $customer = CustomerModel::where('user_id', $idCustomerUser)->first();

        if (!$customer) {
            return redirect('/')->with('error', 'Profil pelanggan tidak ditemukan.');
        }

        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        
        $query = Pembelian::with(['varianLayanan.tipeLayanan.produk', 'pembayaran', 'review'])
            ->where('id_customer', $customer->id);

        if ($startDateInput && $endDateInput) {
            try {
                $startDate = \Illuminate\Support\Carbon::parse($startDateInput)->startOfDay();
                $endDate = \Illuminate\Support\Carbon::parse($endDateInput)->endOfDay();
                
                if ($startDate->greaterThan($endDate)) {
                    return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
                }
                
                if ($startDate->diffInDays($endDate) > 365) {
                    return redirect()->back()->with('error', 'Rentang tanggal maksimal adalah 1 tahun.');
                }
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid.');
            }
        }

        $pembelian = $query->orderBy('created_at', 'desc')->get();

        return view('premium_customer.riwayat', compact('pembelian'));
    }

    // === 3. Tampilkan Kredensial (Decrypt On-Demand) ===
    public function kredensial($order_id)
    {
        $pembelian = Pembelian::with('stokAkun')
            ->where('order_id', $order_id)
            ->firstOrFail();

        $this->authorize('view', $pembelian);

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

    // === 4. Download Invoice PDF ===
    public function downloadInvoice($order_id)
    {
        $pembelian = Pembelian::with([
            'customer.user',
            'varianLayanan.tipeLayanan.produk.toko',
            'pembayaran'
        ])
        ->where('order_id', $order_id)
        ->firstOrFail();

        $this->authorize('view', $pembelian);

        if ($pembelian->status !== PembelianStatus::SUCCESS) {
            return redirect()->back()->with('error', 'Invoice hanya tersedia untuk transaksi yang sudah sukses.');
        }

        $pdf = Pdf::loadView('invoice.pdf_invoice', compact('pembelian'));
        
        return $pdf->download("invoice-{$order_id}.pdf");
    }

    // === 5. Form Review Toko ===
    public function reviewShow($order_id)
    {
        $pembelian = Pembelian::with(['varianLayanan.tipeLayanan.produk.toko', 'review'])
            ->where('order_id', $order_id)
            ->firstOrFail();

        $this->authorize('view', $pembelian);

        if ($pembelian->status !== PembelianStatus::SUCCESS) {
            return redirect()->route('premium.riwayat')->with('error', 'Review hanya dapat diberikan untuk transaksi yang sukses.');
        }

        if ($pembelian->review) {
            return redirect()->route('premium.riwayat')->with('error', 'Kamu sudah memberikan review untuk transaksi ini.');
        }

        return view('premium_customer.review_form', compact('pembelian'));
    }

    // === 6. Simpan Review Toko ===
    public function reviewStore(Request $request, $order_id)
    {
        $pembelian = Pembelian::with(['varianLayanan.tipeLayanan.produk.toko', 'review', 'customer'])
            ->where('order_id', $order_id)
            ->firstOrFail();

        $this->authorize('view', $pembelian);

        if ($pembelian->status !== PembelianStatus::SUCCESS) {
            return redirect()->route('premium.riwayat')->with('error', 'Review hanya dapat diberikan untuk transaksi yang sukses.');
        }

        if ($pembelian->review) {
            return redirect()->route('premium.riwayat')->with('error', 'Kamu sudah memberikan review untuk transaksi ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Rating wajib diisi.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'komentar.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        // Sanitize komentar
        $komentar = $request->input('komentar') ? strip_tags($request->input('komentar')) : null;

        $toko = $pembelian->varianLayanan->tipeLayanan->produk->toko;

        // Save Review
        \App\Models\Review::create([
            'id_pembelian' => $pembelian->id_pembelian,
            'id_toko' => $toko->id_toko,
            'id_customer' => $pembelian->id_customer,
            'rating' => $request->input('rating'),
            'komentar' => $komentar,
        ]);

        // Recalculate average rating & reviews count
        $avgRating = \App\Models\Review::where('id_toko', $toko->id_toko)->avg('rating');
        $countReviews = \App\Models\Review::where('id_toko', $toko->id_toko)->count();

        $toko->update([
            'rating_rata_rata' => round($avgRating, 2),
            'jumlah_review' => $countReviews,
        ]);

        return redirect()->route('premium.riwayat')->with('success', 'Terima kasih! Review kamu berhasil disimpan.');
    }
}
