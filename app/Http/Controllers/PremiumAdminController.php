<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Models\Toko;
use App\Enums\StokStatus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use App\Enums\PembelianStatus;
use App\Jobs\SendAccountInvoiceWhatsapp;

class PremiumAdminController extends Controller
{
    // === Halaman Inventaris Gabungan ===
    public function inventaris_index()
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
            $produk = Produk::where('status', 'aktif')->where('tipe_produk', 'premium')->get();
            $tipeSemua = TipeLayanan::with('produk')->get();
            $tipeAktif = TipeLayanan::where('status', 'aktif')->with('produk')->get();
            $varianSemua = VarianLayanan::with('tipeLayanan.produk')->get();
            $varianAktif = VarianLayanan::where('status', 'aktif')->with('tipeLayanan.produk')->get();
            $stok = StokAkun::with('varianLayanan.tipeLayanan.produk')->get();
        } else {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            $produk = Produk::where('status', 'aktif')
                ->where('tipe_produk', 'premium')
                ->where('id_toko', $toko->id_toko)
                ->get();
            $tipeSemua = TipeLayanan::whereHas('produk', function ($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko);
            })->with('produk')->get();
            $tipeAktif = TipeLayanan::where('status', 'aktif')
                ->whereHas('produk', function ($q) use ($toko) {
                    $q->where('id_toko', $toko->id_toko);
                })
                ->get();
            $varianSemua = VarianLayanan::whereHas('tipeLayanan.produk', function ($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko);
            })->with('tipeLayanan.produk')->get();
            $varianAktif = VarianLayanan::where('status', 'aktif')
                ->whereHas('tipeLayanan.produk', function ($q) use ($toko) {
                    $q->where('id_toko', $toko->id_toko);
                })
                ->get();
            $stok = StokAkun::whereHas('varianLayanan.tipeLayanan.produk', function ($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko);
            })->with('varianLayanan.tipeLayanan.produk')->get();
        }
        return view('premium_admin.inventaris.index', compact('produk', 'tipeSemua', 'tipeAktif', 'varianSemua', 'varianAktif', 'stok'));
    }

    public function tipe_store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'id_produk' => 'required|exists:tbl_produk,id_produk',
            'nama_tipe' => 'required|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Scope validation
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            $prod = Produk::where('id_produk', $request->id_produk)->where('id_toko', $toko->id_toko)->first();
            if (!$prod) {
                abort(403, 'Unauthorized access.');
            }
        }

        TipeLayanan::create($request->only('id_produk', 'nama_tipe', 'status'));

        Session::flash('success', 'Berhasil menambahkan tipe layanan.');
        return redirect()->route('premium.inventaris.index', ['tab' => 'tipe']);
    }

    public function tipe_update(Request $request, $id)
    {
        $user = Auth::user();
        $tipe = TipeLayanan::findOrFail($id);

        // Scope check existing
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            if ($tipe->produk->id_toko != $toko->id_toko) {
                abort(403, 'Unauthorized access.');
            }
        }

        $rules = [
            'id_produk' => 'required|exists:tbl_produk,id_produk',
            'nama_tipe' => 'required|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Scope validation for new product
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            $prod = Produk::where('id_produk', $request->id_produk)->where('id_toko', $toko->id_toko)->first();
            if (!$prod) {
                abort(403, 'Unauthorized access.');
            }
        }

        $tipe->update($request->only('id_produk', 'nama_tipe', 'status'));

        Session::flash('success', 'Berhasil memperbarui tipe layanan.');
        return redirect()->route('premium.inventaris.index', ['tab' => 'tipe']);
    }

    public function tipe_destroy($id)
    {
        $user = Auth::user();
        $tipe = TipeLayanan::findOrFail($id);

        // Scope check
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            if ($tipe->produk->id_toko != $toko->id_toko) {
                abort(403, 'Unauthorized access.');
            }
        }

        try {
            $varianIds = VarianLayanan::where('id_tipe', $tipe->id_tipe)->pluck('id_varian');
            $hasPurchases = \App\Models\Pembelian::whereIn('id_varian', $varianIds)->exists();

            if ($hasPurchases) {
                StokAkun::whereIn('id_varian', $varianIds)->where('status', \App\Enums\StokStatus::TERSEDIA)->delete();
                VarianLayanan::where('id_tipe', $tipe->id_tipe)->update(['status' => 'nonaktif']);
                $tipe->update(['status' => 'nonaktif']);

                Session::flash('success', 'Tipe layanan ini memiliki riwayat transaksi. Statusnya diubah menjadi non-aktif agar data pembeli tetap aman.');
                return redirect()->route('premium.inventaris.index', ['tab' => 'tipe']);
            }

            StokAkun::whereIn('id_varian', $varianIds)->delete();
            VarianLayanan::where('id_tipe', $tipe->id_tipe)->delete();
            $tipe->delete();
            Session::flash('success', 'Berhasil menghapus tipe layanan.');
        } catch (\Exception $e) {
            $tipe->update(['status' => 'nonaktif']);
            Session::flash('success', 'Tipe layanan diubah menjadi non-aktif demi keamanan data riwayat transaksi.');
        }

        return redirect()->route('premium.inventaris.index', ['tab' => 'tipe']);
    }

    // === 2. CRUD Varian Layanan ===

    public function varian_store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'id_tipe' => 'required|exists:tbl_tipe_layanan,id_tipe',
            'nama_varian' => 'required|max:50',
            'durasi_hari' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable',
            'status' => 'required|in:aktif,nonaktif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Scope check
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            $tipe = TipeLayanan::where('id_tipe', $request->id_tipe)
                ->whereHas('produk', function($q) use ($toko) {
                    $q->where('id_toko', $toko->id_toko);
                })->first();
            if (!$tipe) {
                abort(403, 'Unauthorized access.');
            }
        }

        VarianLayanan::create($request->only('id_tipe', 'nama_varian', 'durasi_hari', 'harga', 'deskripsi', 'status'));

        Session::flash('success', 'Berhasil menambahkan varian layanan.');
        return redirect()->route('premium.inventaris.index', ['tab' => 'varian']);
    }

    public function varian_update(Request $request, $id)
    {
        $user = Auth::user();
        $varian = VarianLayanan::findOrFail($id);

        // Scope check existing
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            if ($varian->tipeLayanan->produk->id_toko != $toko->id_toko) {
                abort(403, 'Unauthorized access.');
            }
        }

        $rules = [
            'id_tipe' => 'required|exists:tbl_tipe_layanan,id_tipe',
            'nama_varian' => 'required|max:50',
            'durasi_hari' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable',
            'status' => 'required|in:aktif,nonaktif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Scope check new tipe
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            $tipe = TipeLayanan::where('id_tipe', $request->id_tipe)
                ->whereHas('produk', function($q) use ($toko) {
                    $q->where('id_toko', $toko->id_toko);
                })->first();
            if (!$tipe) {
                abort(403, 'Unauthorized access.');
            }
        }

        $varian->update($request->only('id_tipe', 'nama_varian', 'durasi_hari', 'harga', 'deskripsi', 'status'));

        Session::flash('success', 'Berhasil memperbarui varian layanan.');
        return redirect()->route('premium.inventaris.index', ['tab' => 'varian']);
    }

    public function varian_destroy($id)
    {
        $user = Auth::user();
        $varian = VarianLayanan::findOrFail($id);

        // Scope check
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            if ($varian->tipeLayanan->produk->id_toko != $toko->id_toko) {
                abort(403, 'Unauthorized access.');
            }
        }

        try {
            $hasPurchases = \App\Models\Pembelian::where('id_varian', $varian->id_varian)->exists();

            if ($hasPurchases) {
                StokAkun::where('id_varian', $varian->id_varian)->where('status', \App\Enums\StokStatus::TERSEDIA)->delete();
                $varian->update(['status' => 'nonaktif']);

                Session::flash('success', 'Varian ini memiliki riwayat transaksi. Status varian diubah menjadi non-aktif agar data pembeli tetap aman.');
                return redirect()->route('premium.inventaris.index', ['tab' => 'varian']);
            }

            StokAkun::where('id_varian', $varian->id_varian)->delete();
            $varian->delete();
            Session::flash('success', 'Berhasil menghapus varian layanan.');
        } catch (\Exception $e) {
            $varian->update(['status' => 'nonaktif']);
            Session::flash('success', 'Varian diubah menjadi non-aktif agar data transaksi tetap aman.');
        }

        return redirect()->route('premium.inventaris.index', ['tab' => 'varian']);
    }

    // === 3. CRUD Stok Akun (Single + Bulk) ===

    public function stok_store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'id_varian' => 'required|exists:tbl_varian_layanan,id_varian',
            'email_username' => 'required|max:150',
            'password' => 'required',
            'catatan' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Scope check
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            $varian = VarianLayanan::where('id_varian', $request->id_varian)
                ->whereHas('tipeLayanan.produk', function($q) use ($toko) {
                    $q->where('id_toko', $toko->id_toko);
                })->first();
            if (!$varian) {
                abort(403, 'Unauthorized access.');
            }
        }

        StokAkun::create([
            'id_varian' => $request->id_varian,
            'email_username' => $request->email_username,
            'password_encrypted' => $request->password,
            'catatan' => $request->catatan,
            'status' => StokStatus::TERSEDIA,
        ]);

        Session::flash('success', 'Berhasil menambahkan stok akun.');
        return redirect()->route('premium.inventaris.index', ['tab' => 'stok']);
    }

    public function stok_bulk_store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'id_varian' => 'required|exists:tbl_varian_layanan,id_varian',
            'bulk_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Scope check
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            $varian = VarianLayanan::where('id_varian', $request->id_varian)
                ->whereHas('tipeLayanan.produk', function($q) use ($toko) {
                    $q->where('id_toko', $toko->id_toko);
                })->first();
            if (!$varian) {
                abort(403, 'Unauthorized access.');
            }
        }

        $lines = explode("\n", str_replace("\r", "", $request->bulk_data));
        $count = 0;

        foreach ($lines as $line) {
            $parts = explode("|", trim($line));
            if (count($parts) >= 2) {
                StokAkun::create([
                    'id_varian' => $request->id_varian,
                    'email_username' => trim($parts[0]),
                    'password_encrypted' => trim($parts[1]),
                    'catatan' => isset($parts[2]) ? trim($parts[2]) : null,
                    'status' => StokStatus::TERSEDIA,
                ]);
                $count++;
            }
        }

        Session::flash('success', "Berhasil menambahkan {$count} stok akun secara bulk.");
        return redirect()->route('premium.inventaris.index', ['tab' => 'stok']);
    }

    public function stok_decrypt($id)
    {
        $user = Auth::user();
        $stok = StokAkun::findOrFail($id);

        // Scope check
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            if ($stok->varianLayanan->tipeLayanan->produk->id_toko != $toko->id_toko) {
                abort(403, 'Unauthorized access.');
            }
        }
        
        return response()->json([
            'email_username' => $stok->email_username,
            'password' => $stok->password_encrypted,
            'catatan' => $stok->catatan
        ]);
    }

    public function stok_destroy($id)
    {
        $user = Auth::user();
        $stok = StokAkun::findOrFail($id);

        // Scope check
        if ($user->role_id != 1) {
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            if ($stok->varianLayanan->tipeLayanan->produk->id_toko != $toko->id_toko) {
                abort(403, 'Unauthorized access.');
            }
        }

        $stok->delete();
        Session::flash('success', 'Berhasil menghapus stok akun.');
        return redirect()->route('premium.inventaris.index', ['tab' => 'stok']);
    }

    // === 4. Halaman Histori Penjualan ===
    public function histori_index(Request $request)
    {
        $user = Auth::user();
        
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        $statusInput = $request->input('status');

        $startDate = null;
        $endDate = null;

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
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid.');
            }
        }

        if ($user->role_id == 1) {
            // Admin sees all Pembelian (orders)
            $query = \App\Models\Pembelian::with(['customer.user', 'logs', 'pembayaran', 'stokAkun.varianLayanan.tipeLayanan.produk']);

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($statusInput) {
                $query->where('status', $statusInput);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();
            
            return view('premium_admin.histori.index', compact('orders', 'user'));
        } else {
            // Seller sees only their sold premium accounts
            $toko = Toko::where('user_id', $user->id)->firstOrFail();
            $query = StokAkun::whereHas('varianLayanan.tipeLayanan.produk', function($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko);
            })
            ->with(['varianLayanan.tipeLayanan.produk', 'pembelian.customer.user', 'pembelian.logs', 'pembelian.pembayaran'])
            ->where('status', StokStatus::TERJUAL);

            if ($startDate && $endDate) {
                $query->whereBetween('tanggal_terjual', [$startDate, $endDate]);
            }

            $stokTerjual = $query->orderBy('tanggal_terjual', 'desc')->get();

            return view('premium_admin.histori.index', compact('stokTerjual', 'user'));
        }
    }

    // === 5. Retry Kirim Notifikasi WhatsApp Manual ===
    public function retryWa(Request $request, $id_pembayaran)
    {
        $pembayaran = Pembayaran::with('pembelian')->findOrFail($id_pembayaran);
        $pembelian = $pembayaran->pembelian;

        if ($pembelian->status !== PembelianStatus::SUCCESS) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi WhatsApp hanya dapat dikirim untuk transaksi yang berstatus SUCCESS.'
            ], 400);
        }

        // Rate limit 60 seconds
        if ($pembayaran->wa_last_retry_at && $pembayaran->wa_last_retry_at->diffInSeconds(now()) < 60) {
            $secondsLeft = 60 - $pembayaran->wa_last_retry_at->diffInSeconds(now());
            return response()->json([
                'success' => false,
                'message' => "Mohon tunggu {$secondsLeft} detik lagi sebelum mencoba mengirim ulang."
            ], 429);
        }

        // Update retry stats
        $pembayaran->wa_retry_count = $pembayaran->wa_retry_count + 1;
        $pembayaran->wa_last_retry_at = now();
        $pembayaran->wa_last_retry_by = Auth::id();
        $pembayaran->save();

        // Dispatch WA job
        SendAccountInvoiceWhatsapp::dispatch($pembelian->id_pembelian);

        // Audit Trail Log
        \App\Models\PembelianLog::create([
            'id_pembelian' => $pembelian->id_pembelian,
            'status_lama' => $pembelian->status->value ?? 'success',
            'status_baru' => $pembelian->status->value ?? 'success',
            'sumber_perubahan' => 'manual_admin',
            'keterangan' => 'Retry pengiriman WA ke-' . $pembayaran->wa_retry_count,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job pengiriman notifikasi WhatsApp berhasil didispatch!',
            'wa_retry_count' => $pembayaran->wa_retry_count,
            'wa_last_retry_at' => $pembayaran->wa_last_retry_at->toDateTimeString(),
        ]);
    }

    // === 6. Check Payment Status Manual ke Gateway Server ===
    public function checkPaymentStatus(Request $request, $order_id)
    {
        $user = Auth::user();
        if ($user->role_id != 1) {
            return response()->json(['success' => false, 'message' => 'Hanya admin yang dapat mengecek status pembayaran secara manual.'], 403);
        }

        $pembelian = \App\Models\Pembelian::where('order_id', $order_id)->firstOrFail();
        $gatewayName = $pembelian->payment_gateway ?? 'midtrans';

        try {
            $gateway = \App\Services\Gateways\PaymentGatewayFactory::make($gatewayName);
            $statusData = $gateway->verifyStatus($order_id, (int) $pembelian->harga_saat_beli);

            $paymentProcessor = app(\App\Services\PaymentProcessingService::class);

            if ($statusData['status'] === PembelianStatus::SUCCESS) {
                $paymentProcessor->markAsSuccess($pembelian, [
                    'payment_type' => $statusData['payment_type'] ?? $gatewayName,
                    'payment_gateway' => $gatewayName,
                    'gross_amount' => $statusData['gross_amount'] ?? $pembelian->harga_saat_beli,
                    'transaction_id' => $statusData['transaction_id'] ?? null,
                ]);
            } elseif (in_array($statusData['status'], [PembelianStatus::EXPIRED, PembelianStatus::FAILED], true)) {
                $paymentProcessor->markAsFailed($pembelian, $statusData['status']->value ?? 'failed', $gatewayName);
            }

            $pembelian->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil disinkronisasi dengan server ' . ucfirst($gatewayName) . '.',
                'gateway' => $gatewayName,
                'status' => $pembelian->status->value ?? $pembelian->status,
                'raw_status' => $statusData['raw_status'] ?? null,
                'data' => $statusData,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin checkPaymentStatus error', [
                'order_id' => $order_id,
                'gateway' => $gatewayName,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi status dengan gateway: ' . $e->getMessage()
            ], 500);
        }
    }
}
