@extends('layout')

@section('title', 'Ajak Teman & Program Referral')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/customer-referral.css') }}">
@endpush

@section('content')

<div class="container referral-container">

    <!-- HERO CARD -->
    <div class="glass-card-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <span class="badge px-3 py-2 mb-3" style="background: rgba(6, 182, 212, 0.18); color: #22d3ee; border: 1px solid rgba(6, 182, 212, 0.4); font-size: 0.8rem; letter-spacing: 0.5px;">
                    <i class="bi bi-rocket-takeoff-fill mr-1"></i> Program Referral Tier Boost
                </span>
                <h2 class="font-weight-bold text-white mb-3" style="font-size: 2rem; letter-spacing: -0.5px;">Ajak Teman, Naik Tier Lebih Cepat!</h2>
                <p class="text-secondary mb-0" style="line-height: 1.7; font-size: 0.95rem; max-width: 580px;">
                    Bagikan kode referral kamu kepada teman. Setiap kali teman yang diajak mendaftar dan menyelesaikan <strong class="text-white">transaksi pertamanya</strong>, kamu secara otomatis mendapatkan bonus akumulasi sebesar <strong style="color: #22d3ee; font-family: 'JetBrains Mono', monospace;">Rp {{ number_format($bonusAmount, 0, ',', '.') }}</strong> untuk melesatkan progress level membership kamu!
                </p>
            </div>

            <div class="col-lg-5">
                <div class="referral-code-box">
                    <small class="stat-title d-block mb-1">Kode Referral Kamu</small>
                    <div class="code-display mb-3" id="refCodeText">{{ $customer->kode_referral }}</div>

                    <div class="share-input-group">
                        <input type="text" id="shareUrlInput" class="form-control" value="{{ $shareUrl }}" readonly>
                        <button class="btn-copy-lg" type="button" onclick="copyShareUrl()">
                            <i class="bi bi-clipboard-fill"></i> <span>Salin</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS ROW WITH HIERARCHY -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="stat-card-subtle">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-title">Total Teman Diajak</span>
                    <i class="bi bi-people-fill text-muted" style="font-size: 1.2rem;"></i>
                </div>
                <div class="stat-number-lg">{{ $referredCustomers->count() }} <span class="h6 font-weight-normal text-muted">Orang</span></div>
            </div>
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
            <div class="stat-card-subtle">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-title">Referral Sukses</span>
                    <i class="bi bi-patch-check-fill text-success" style="font-size: 1.2rem;"></i>
                </div>
                <div class="stat-number-lg text-success">{{ $customer->jumlah_referral_sukses }} <span class="h6 font-weight-normal text-muted">Orang</span></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card-featured">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-title" style="color: #22d3ee;">Total Bonus Akumulasi Tier</span>
                    <span class="badge badge-info px-2 py-1" style="background: rgba(6, 182, 212, 0.2); color: #38bdf8;">HIGHLIGHT</span>
                </div>
                <div class="stat-number-featured">Rp {{ number_format($customer->jumlah_referral_sukses * $bonusAmount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- HIGH CONTRAST TABLE / MOBILE STACK -->
    <div class="glass-card-table">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="font-weight-bold text-white mb-0" style="font-size: 1.15rem;">Daftar Teman Yang Kamu Ajak</h4>
            <span class="badge badge-dark p-2" style="background: rgba(255, 255, 255, 0.06); color: #cbd5e1; border: 1px solid rgba(255, 255, 255, 0.1);">
                {{ $referredCustomers->count() }} Pengguna Terdaftar
            </span>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Teman</th>
                    <th>Email</th>
                    <th class="text-right">Status Transaksi Pertama</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referredCustomers as $refCust)
                    @php
                        $hasCompletedFirst = $refCust->jumlah_referral_sukses > 0 || \App\Models\Pembelian::where('id_customer', $refCust->id)->where('status', \App\Enums\PembelianStatus::SUCCESS)->exists();
                    @endphp
                    <tr>
                        <td data-label="Nama Teman">
                            <strong class="text-white">{{ $refCust->nama_customer ?? $refCust->user?->name ?? 'Pengguna Baru' }}</strong>
                        </td>
                        <td data-label="Email">
                            <span class="text-secondary">{{ $refCust->user?->email ?? '-' }}</span>
                        </td>
                        <td data-label="Status Transaksi" class="text-right">
                            @if($hasCompletedFirst)
                                <span class="badge px-3 py-2" style="background: rgba(34, 197, 94, 0.18); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); font-size: 0.8rem; font-weight: 600;">
                                    <i class="bi bi-check-circle-fill mr-1"></i> Sukses (+Rp {{ number_format($bonusAmount, 0, ',', '.') }})
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-size: 0.8rem; font-weight: 600;">
                                    <i class="bi bi-clock-history mr-1"></i> Belum Berbelanja
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <i class="bi bi-people text-muted d-block mb-2" style="font-size: 2.5rem;"></i>
                            Belum ada teman yang mendaftar menggunakan kode referral kamu.<br>
                            Bagikan link referral kamu sekarang untuk mulai mengumpulkan bonus!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    function copyShareUrl() {
        var copyText = document.getElementById("shareUrlInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);

        Swal.fire({
            title: "Link Tersalin!",
            text: "Link referral berhasil disalin ke clipboard.",
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
            background: "#0f172a",
            color: "#f8fafc"
        });
    }
</script>

@endsection
