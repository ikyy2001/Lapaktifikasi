<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CustomerModel;
use App\Models\CustomerTier;
use App\Models\Pembelian;
use App\Models\Voucher;
use App\Models\VoucherKlaim;
use App\Models\Review;
use App\Models\Laporan;
use App\Enums\PembelianStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CustomerPremiumController extends ApiController
{
    /**
     * Data keanggotaan customer (Member / Tier / Progress)
     */
    public function getMemberData(Request $request)
    {
        $idCustomerUser = $request->user()->id;
        $customer = CustomerModel::with('tier')->where('user_id', $idCustomerUser)->first();

        if (!$customer) {
            return $this->sendError('Profil pelanggan tidak ditemukan.', [], 404);
        }

        $progressInfo = $customer->progressKeTierBerikutnya();
        $allTiers = CustomerTier::orderBy('urutan', 'asc')->get();

        $vouchers = Voucher::with('toko')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('berlaku_sampai')->orWhere('berlaku_sampai', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('kuota_total')->orWhereColumn('kuota_terpakai', '<', 'kuota_total');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $claimedVoucherIds = VoucherKlaim::where('id_customer', $customer->id)
            ->pluck('id_voucher')
            ->toArray();

        return $this->sendResponse([
            'customer' => $customer,
            'progress' => $progressInfo,
            'tiers' => $allTiers,
            'vouchers' => $vouchers,
            'claimed_vouchers' => $claimedVoucherIds
        ], 'Data member berhasil diambil');
    }

    /**
     * Data referral customer
     */
    public function getReferralData(Request $request)
    {
        $idCustomerUser = $request->user()->id;
        $customer = CustomerModel::where('user_id', $idCustomerUser)->first();

        if (!$customer) {
            return $this->sendError('Profil pelanggan tidak ditemukan.', [], 404);
        }

        if (empty($customer->kode_referral)) {
            $customer->kode_referral = 'REF-' . strtoupper(Str::random(6));
            $customer->save();
        }

        $shareUrl = url('/pendaftaran?ref=' . $customer->kode_referral);
        $bonusAmount = (float) config('referral.bonus_akumulasi', 50000);

        $referredCustomers = CustomerModel::with('user')
            ->where('direferensikan_oleh', $customer->id)
            ->get();

        return $this->sendResponse([
            'kode_referral' => $customer->kode_referral,
            'share_url' => $shareUrl,
            'bonus_akumulasi' => $bonusAmount,
            'referred_customers' => $referredCustomers
        ], 'Data referral berhasil diambil');
    }

    /**
     * Klaim Voucher
     */
    public function klaimVoucher(Request $request, $id_voucher)
    {
        $idCustomerUser = $request->user()->id;
        $customer = CustomerModel::where('user_id', $idCustomerUser)->first();

        if (!$customer) return $this->sendError('Profil pelanggan tidak ditemukan.', [], 404);

        $voucher = Voucher::find($id_voucher);

        if (!$voucher || !$voucher->is_active) return $this->sendError('Voucher tidak aktif atau tidak ditemukan.', [], 400);

        $now = now();
        if ($voucher->berlaku_dari && $now->lt($voucher->berlaku_dari)) return $this->sendError('Voucher belum berlaku.', [], 400);
        if ($voucher->berlaku_sampai && $now->gt($voucher->berlaku_sampai)) return $this->sendError('Voucher telah kedaluwarsa.', [], 400);
        if ($voucher->kuota_total !== null && $voucher->kuota_terpakai >= $voucher->kuota_total) return $this->sendError('Kuota voucher telah habis.', [], 400);

        $alreadyClaimed = VoucherKlaim::where('id_voucher', $id_voucher)->where('id_customer', $customer->id)->exists();
        if ($alreadyClaimed) return $this->sendError('Anda sudah mengklaim voucher ini.', [], 400);

        VoucherKlaim::create([
            'id_voucher' => $id_voucher,
            'id_customer' => $customer->id,
            'id_pembelian' => null,
            'created_at' => now(),
        ]);

        return $this->sendResponse([], 'Voucher berhasil diklaim!');
    }

    /**
     * Riwayat Pembelian
     */
    public function getRiwayat(Request $request)
    {
        $idCustomerUser = $request->user()->id;
        $customer = CustomerModel::where('user_id', $idCustomerUser)->first();

        if (!$customer) return $this->sendError('Profil pelanggan tidak ditemukan.', [], 404);

        $query = Pembelian::with(['varianLayanan.tipeLayanan.produk', 'pembayaran', 'review'])
            ->where('id_customer', $customer->id);

        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        if ($startDateInput && $endDateInput) {
            try {
                $startDate = \Illuminate\Support\Carbon::parse($startDateInput)->startOfDay();
                $endDate = \Illuminate\Support\Carbon::parse($endDateInput)->endOfDay();
                
                if ($startDate->greaterThan($endDate)) {
                    return $this->sendError('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.', [], 400);
                }
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return $this->sendError('Format tanggal tidak valid.', [], 400);
            }
        }

        $pembelian = $query->orderBy('created_at', 'desc')->get();

        return $this->sendResponse(['riwayat' => $pembelian], 'Riwayat pembelian berhasil diambil');
    }

    /**
     * Kredensial Akun (Decrypt On-Demand)
     */
    public function getKredensial(Request $request, $order_id)
    {
        $pembelian = Pembelian::with('stokAkun')->where('order_id', $order_id)->first();

        if (!$pembelian) return $this->sendError('Pesanan tidak ditemukan', [], 404);

        $customer = CustomerModel::where('user_id', $request->user()->id)->first();
        if ($pembelian->id_customer != $customer->id) return $this->sendError('Unauthorized', [], 403);

        if ($pembelian->status !== PembelianStatus::SUCCESS) {
            return $this->sendError('Pembayaran belum diselesaikan atau transaksi expired.', [], 403);
        }

        if (!$pembelian->stokAkun) {
            return $this->sendError('Kredensial akun tidak ditemukan. Harap hubungi admin.', [], 404);
        }

        return $this->sendResponse([
            'email_username' => $pembelian->stokAkun->email_username,
            'password' => $pembelian->stokAkun->password_encrypted,
            'catatan' => $pembelian->stokAkun->catatan,
        ], 'Kredensial berhasil diambil');
    }

    /**
     * Buat Review
     */
    public function storeReview(Request $request, $order_id)
    {
        $pembelian = Pembelian::with(['varianLayanan.tipeLayanan.produk.toko', 'review'])->where('order_id', $order_id)->first();
        if (!$pembelian) return $this->sendError('Pesanan tidak ditemukan', [], 404);

        $customer = CustomerModel::where('user_id', $request->user()->id)->first();
        if ($pembelian->id_customer != $customer->id) return $this->sendError('Unauthorized', [], 403);

        if ($pembelian->status !== PembelianStatus::SUCCESS) {
            return $this->sendError('Review hanya dapat diberikan untuk transaksi yang sukses.', [], 400);
        }

        if ($pembelian->review) {
            return $this->sendError('Kamu sudah memberikan review untuk transaksi ini.', [], 400);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $toko = $pembelian->varianLayanan->tipeLayanan->produk->toko;

        Review::create([
            'id_pembelian' => $pembelian->id_pembelian,
            'id_toko' => $toko->id_toko,
            'id_customer' => $pembelian->id_customer,
            'rating' => $request->rating,
            'komentar' => $request->komentar ? strip_tags($request->komentar) : null,
        ]);

        $avgRating = Review::where('id_toko', $toko->id_toko)->avg('rating');
        $countReviews = Review::where('id_toko', $toko->id_toko)->count();

        $toko->update([
            'rating_rata_rata' => round($avgRating, 2),
            'jumlah_review' => $countReviews,
        ]);

        return $this->sendResponse([], 'Review berhasil disimpan', 201);
    }

    /**
     * Get Daftar Laporan Customer
     */
    public function getLaporan(Request $request)
    {
        $userId = $request->user()->id;
        $laporan = Laporan::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return $this->sendResponse(['laporan' => $laporan], 'Daftar laporan berhasil diambil');
    }

    /**
     * Buat Laporan Customer
     */
    public function storeLaporan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $targetDir = public_path('assets/img/laporan');
            if (!file_exists($targetDir)) mkdir($targetDir, 0755, true);
            $file->move($targetDir, $filename);
            $gambarPath = $filename;
        }

        $laporan = Laporan::create([
            'user_id' => $request->user()->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
            'status' => 'pending'
        ]);

        return $this->sendResponse(['laporan' => $laporan], 'Laporan berhasil dibuat', 201);
    }
}
