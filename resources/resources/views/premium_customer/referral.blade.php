@extends('layout')

@section('title', 'Ajak Teman & Program Referral')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@600;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .referral-container {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        color: #f8fafc;
        margin-top: 10px;
        margin-bottom: 40px;
    }

    .glass-card-hero {
        background: radial-gradient(circle at 90% 10%, rgba(139, 92, 246, 0.18) 0%, transparent 60%),
                    radial-gradient(circle at 10% 90%, rgba(6, 182, 212, 0.15) 0%, transparent 60%),
                    linear-gradient(135deg, #0f172a 0%, #090d16 100%);
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 36px;
        position: relative;
        overflow: hidden;
    }

    .referral-code-box {
        background: rgba(15, 23, 42, 0.9);
        border: 2px dashed rgba(6, 182, 212, 0.6);
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.15);
        border-radius: 18px;
        padding: 24px;
        text-align: center;
    }

    .code-display {
        font-family: 'JetBrains Mono', monospace;
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: 3px;
        color: #22d3ee;
        text-shadow: 0 0 16px rgba(34, 211, 238, 0.5);
    }

    .share-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .share-input-group input {
        background: #090d16 !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #f8fafc !important;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.85rem !important;
        border-radius: 12px !important;
        height: 48px;
        padding: 0 16px;
    }

    .btn-copy-lg {
        background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.9rem;
        min-height: 48px;
        padding: 0 24px;
        border-radius: 12px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-copy-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    }

    /* Stat Cards Hierarchy */
    .stat-card-subtle {
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 24px;
        height: 100%;
        transition: transform 0.2s ease;
    }

    .stat-card-featured {
        background: linear-gradient(135deg, rgba(30, 27, 75, 0.9) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1.5px solid rgba(6, 182, 212, 0.5);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.15);
        border-radius: 18px;
        padding: 24px;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card-featured::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #8b5cf6, #06b6d4);
    }

    .stat-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #cbd5e1;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-number-lg {
        font-family: 'JetBrains Mono', monospace;
        font-size: 2.2rem;
        font-weight: 800;
        color: #f8fafc;
        margin-top: 6px;
    }

    .stat-number-featured {
        font-family: 'JetBrains Mono', monospace;
        font-size: 2.2rem;
        font-weight: 800;
        color: #38bdf8;
        text-shadow: 0 0 14px rgba(56, 189, 248, 0.4);
        margin-top: 6px;
    }

    /* High Contrast Table & Responsive Mobile Stack */
    .glass-card-table {
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        border-radius: 20px;
        padding: 28px;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .custom-table thead tr th {
        background: #1e293b !important;
        color: #f8fafc !important;
        font-weight: 700 !important;
        font-size: 0.82rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.6px !important;
        padding: 16px 20px !important;
        border: none !important;
    }

    .custom-table thead tr th:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .custom-table thead tr th:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .custom-table tbody tr {
        background: rgba(30, 41, 59, 0.4);
        transition: background 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: rgba(30, 41, 59, 0.7);
    }

    .custom-table tbody tr td {
        padding: 16px 20px !important;
        color: #cbd5e1 !important;
        font-size: 0.92rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        vertical-align: middle !important;
    }

    .custom-table tbody tr td:first-child {
        border-left: 1px solid rgba(255, 255, 255, 0.05) !important;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .custom-table tbody tr td:last-child {
        border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    /* Mobile Responsive Card Stack for Table */
    @media (max-width: 768px) {
        .glass-card-hero {
            padding: 24px;
        }

        .custom-table thead {
            display: none;
        }

        .custom-table, .custom-table tbody, .custom-table tr, .custom-table td {
            display: block;
            width: 100%;
        }

        .custom-table tbody tr {
            margin-bottom: 16px;
            border-radius: 16px !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 12px;
            background: rgba(30, 41, 59, 0.6);
        }

        .custom-table tbody tr td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px !important;
            border: none !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .custom-table tbody tr td:last-child {
            border-bottom: none !important;
        }

        .custom-table tbody tr td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #94a3b8;
            font-size: 0.78rem;
            text-transform: uppercase;
        }
    }
</style>

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
