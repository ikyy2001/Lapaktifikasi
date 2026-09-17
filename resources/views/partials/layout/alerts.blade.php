@auth
    <!-- Maintenance Banner for Admin/Seller/Customer -->
    @if(Auth::user()->role_id == \App\Enums\Role::ADMIN->value)
        @php
            $isMaintActive = \Illuminate\Support\Facades\Cache::remember('is_maintenance_flag', 300, function () {
                return \App\Models\SettingKomisi::value('is_maintenance');
            });
        @endphp
        @if($isMaintActive)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-3 text-amber-800 text-sm font-semibold">
                    <i class="bi bi-tools text-xl"></i>
                    <span><strong>MODE MAINTENANCE AKTIF:</strong> Seller dan Customer saat ini diblokir dari akses dashboard.</span>
                </div>
                <a href="{{ url('setting_komisi') }}" class="px-4 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-full text-xs font-bold whitespace-nowrap transition-colors">
                    Kelola Status
                </a>
            </div>
        @endif
    @endif

    <!-- Pending Pembelian Alert for Customer -->
    @if(Auth::user()->role_id == \App\Enums\Role::CUSTOMER->value)
        @php
            $customer = \Illuminate\Support\Facades\Cache::remember('customer_user_' . Auth::id(), 300, function () {
                return \App\Models\CustomerModel::where('user_id', Auth::id())->first();
            });
            $pendingPembelian = null;
            if ($customer) {
                $pendingPembelian = \Illuminate\Support\Facades\Cache::remember('pending_pembelian_' . $customer->id, 5, function () use ($customer) {
                    return \App\Models\Pembelian::where('id_customer', $customer->id)
                        ->where('status', \App\Enums\PembelianStatus::PENDING)
                        ->where('reserved_until', '>', now())
                        ->orderBy('created_at', 'desc')
                        ->first();
                });
            }
        @endphp
        @if($pendingPembelian)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-3 text-amber-800 text-sm font-semibold">
                    <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                    <span>Anda memiliki transaksi pembayaran yang belum diselesaikan (Order ID: {{ $pendingPembelian->order_id }}).</span>
                </div>
                <a href="{{ route('bukti_pembayaran.status', ['order_id' => $pendingPembelian->order_id]) }}"
                    class="px-4 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-full text-xs font-bold whitespace-nowrap transition-colors">
                    Selesaikan Pembayaran
                </a>
            </div>
        @endif
    @endif
@endauth
