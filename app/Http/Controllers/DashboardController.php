<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeliProdukModel;
use App\Models\User;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use App\Models\PembayaranModel;
use App\Enums\PembelianStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $hari_ini = date('Y-m-d');
        $total_order_hari_ini = $this->getTotalOrderHariIni($hari_ini);
        $total_penjualan_hari_ini = $this->getTotalPenjualanHariIni($hari_ini);
        $total_barang_terjual_hari_ini = $this->getTotalBarangTerjualHariIni($hari_ini);
        $nama_order_hari_ini = $this->getNamaOrderHariIni($hari_ini);

        return view('dashboard.index', compact(
            'total_order_hari_ini',
            'total_penjualan_hari_ini',
            'total_barang_terjual_hari_ini',
            'nama_order_hari_ini'
        ));
    }

    protected function getTotalOrderHariIni($hari_ini)
    {
        // Legacy orders today
        $legacyCount = BeliProdukModel::where('tanggal_transaksi', $hari_ini)->count();
        
        // Premium orders today
        $premiumCount = Pembelian::whereDate('created_at', $hari_ini)->count();

        return $legacyCount + $premiumCount;
    }

    protected function getTotalPenjualanHariIni($hari_ini)
    {
        // Legacy sales today
        $legacySales = PembayaranModel::whereIn('order_id', BeliProdukModel::where('tanggal_transaksi', $hari_ini)->pluck('order_id'))->sum('total');

        // Premium sales today
        $premiumSales = Pembayaran::whereDate('tanggal_bayar', $hari_ini)->sum('jumlah_dibayar');

        $total = $legacySales + $premiumSales;

        return number_format($total, 0, ',', '.');
    }

    protected function getTotalBarangTerjualHariIni($hari_ini)
    {
        // Legacy items sold today
        $legacyQty = BeliProdukModel::where('status', 'success')
            ->where('tanggal_transaksi', $hari_ini)
            ->sum('qty');

        // Premium items sold today (count of successful purchases paid today)
        $premiumQty = Pembelian::where('status', PembelianStatus::SUCCESS)
            ->whereHas('pembayaran', function ($query) use ($hari_ini) {
                $query->whereDate('tanggal_bayar', $hari_ini);
            })
            ->count();

        return $legacyQty + $premiumQty;
    }

    protected function getNamaOrderHariIni($hari_ini)
    {
        $orders = [];

        // Legacy orders today
        $legacyOrders = BeliProdukModel::with('users')
            ->where('tanggal_transaksi', $hari_ini)
            ->get();

        foreach ($legacyOrders as $order) {
            $orders[] = [
                'nama_customer' => $order->users->name ?? $order->users->email ?? '-',
                'order_id' => $order->order_id,
                'status' => ucfirst($order->status),
            ];
        }

        // Premium orders today
        $premiumOrders = Pembelian::with('customer.user')
            ->whereDate('created_at', $hari_ini)
            ->get();

        foreach ($premiumOrders as $order) {
            $orders[] = [
                'nama_customer' => $order->customer->user->name ?? $order->customer->user->email ?? '-',
                'order_id' => $order->order_id,
                'status' => ucfirst($order->status->value),
            ];
        }

        return $orders;
    }
}
