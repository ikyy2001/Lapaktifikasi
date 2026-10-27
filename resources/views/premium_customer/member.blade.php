@extends('layout')

@section('title', 'Level & Keuntungan Saya')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/customer-member.css') }}">
@endpush

@section('content')

@if($error = Session::get('error'))
<script>
    Swal.fire({ title: "Gagal", text: "{{ $error }}", icon: "error", background: "#0f172a", color: "#f8fafc" });
</script>
@endif

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success", background: "#0f172a", color: "#f8fafc" });
</script>
@endif

@php
    $tierCurrent = $progressInfo['tier_saat_ini'];
    $tierNext = $progressInfo['tier_berikutnya'];
    $sisaNominal = $progressInfo['sisa_nominal'];
    $progressPercent = $progressInfo['persentase_progress'];
    $totalBelanja = (float) $customer->total_belanja_akumulasi;
    $targetThreshold = $tierNext ? (float) $tierNext->minimal_belanja : $totalBelanja;
@endphp

<div class="container member-container">

    <!-- HERO CARD: TIER STATUS & ENERGY GAUGE -->
    <div class="glass-card-hero mb-4">
        <div class="d-flex justify-content-between align-items-start hero-top-flex gap-3">
            <div style="max-width: 100%;">
                <span class="tier-badge-pill">
                    <i class="bi bi-award-fill" style="color: #22d3ee;"></i>
                    Level {{ $tierCurrent?->nama_tier ?? 'Bronze' }}
                </span>
                <h1 class="tier-title-name">{{ $customer->nama_customer ?? 'Pelanggan Setia' }}</h1>
                <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                    @if($customer->kode_referral)
                    <span class="referral-chip">
                        <i class="bi bi-ticket-perforated"></i> Referral: {{ $customer->kode_referral }}
                    </span>
                    @endif
                    <span class="badge px-3 py-2" style="background: rgba(139, 92, 246, 0.2); color: #c084fc; border: 1px solid rgba(139, 92, 246, 0.4); font-size: 0.78rem;">
                        <i class="bi bi-percent mr-1"></i> Cashback Tier: {{ (float) ($tierCurrent?->benefit_cashback_persen ?? 0) }}%
                    </span>
                </div>
            </div>

            <div class="hero-amount-block">
                <small class="text-muted text-uppercase font-weight-bold d-block mb-1" style="letter-spacing: 0.5px;">Total Akumulasi Belanja</small>
                <div class="h2 font-weight-bold text-white mono-amount mb-0">
                    Rp {{ number_format($totalBelanja, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- LAPAKTIFIKASI ENERGY GAUGE (PROGRESS BAR) -->
        <div class="energy-gauge-wrapper">
            <div class="energy-gauge-labels">
                <span>Progress Energy Level</span>
                <span class="mono-amount">
                    Rp {{ number_format($totalBelanja, 0, ',', '.') }} /
                    @if($tierNext)
                        Rp {{ number_format($targetThreshold, 0, ',', '.') }}
                    @else
                        Max Tier
                    @endif
                </span>
            </div>
            <div class="energy-gauge-track">
                <div class="energy-gauge-fill" style="width: {{ $progressPercent }}%;"></div>
            </div>
            <div class="progress-hint">
                @if($tierNext)
                    Belanja <strong class="mono-amount" style="color: #22d3ee;">Rp {{ number_format($sisaNominal, 0, ',', '.') }}</strong> lagi untuk melompat ke <strong>Level {{ $tierNext->nama_tier }}</strong>.
                @else
                    🎉 Selamat! Anda telah mencapai level tertinggi (<strong>{{ $tierCurrent?->nama_tier ?? 'Sultan' }}</strong>).
                @endif
            </div>
        </div>
    </div>

    <!-- TAB SWITCHER (BAHASA INDONESIA KONSISTEN) -->
    <div class="tab-nav-container">
        <button class="tab-btn active" onclick="switchTab('tab-benefits', this)">
            <i class="bi bi-star-fill mr-1"></i> Keuntungan Tier
        </button>
        <button class="tab-btn" onclick="switchTab('tab-vouchers', this)">
            <i class="bi bi-ticket-detailed-fill mr-1"></i> Voucher Saya
        </button>
        <button class="tab-btn" onclick="switchTab('tab-packs', this)">
            <i class="bi bi-gift-fill mr-1"></i> Paket Voucher Spesial
        </button>
    </div>

    <!-- TAB 1: KEUNTUNGAN TIER -->
    <div id="tab-benefits" class="tab-pane active">
        <div class="row">
            @foreach($allTiers as $t)
                @php
                    $isUnlocked = $tierCurrent && ($t->urutan <= $tierCurrent->urutan);
                    $isCurrent = $tierCurrent && ($t->id_tier === $tierCurrent->id_tier);
                    $benefits = is_array($t->benefit_deskripsi) ? $t->benefit_deskripsi : json_decode($t->benefit_deskripsi ?? '[]', true);
                @endphp
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="tier-card-item {{ $isUnlocked ? 'tier-card-unlocked' : 'tier-card-locked' }} {{ $isCurrent ? 'tier-card-current' : '' }}">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge p-2" style="background-color: {{ $t->warna_tema ?? '#8b5cf6' }}; color: #000; font-weight: 800; font-size: 0.8rem;">
                                    TIER {{ $t->urutan }}
                                </span>
                                <h4 class="mb-0 text-white font-weight-bold" style="font-size: 1.25rem;">{{ $t->nama_tier }}</h4>
                            </div>
                            <div>
                                @if($isCurrent)
                                    <span class="badge px-3 py-2" style="background: rgba(34, 211, 238, 0.2); color: #22d3ee; border: 1px solid rgba(34, 211, 238, 0.4); font-size: 0.78rem;">
                                        <i class="bi bi-person-check-fill mr-1"></i> LEVEL SAAT INI
                                    </span>
                                @elseif($isUnlocked)
                                    <span class="badge px-3 py-2" style="background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); font-size: 0.78rem;">
                                        <i class="bi bi-check-circle-fill mr-1"></i> SUDAH TERBUKA
                                    </span>
                                @else
                                    <span class="badge px-3 py-2" style="background: rgba(239, 68, 68, 0.18); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4); font-size: 0.78rem;">
                                        <i class="bi bi-lock-fill mr-1"></i> BELUM TERCAPAI
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- STRUCTURAL CONTRAST: Text is 100% Legible (Off-white / Slate) even when Locked -->
                        <div class="mb-3 p-3 rounded" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08);">
                            <div class="small text-secondary mb-1">
                                Minimal Akumulasi Belanja: <strong class="text-white mono-amount">Rp {{ number_format((float)$t->minimal_belanja, 0, ',', '.') }}</strong>
                            </div>
                            <div class="small text-secondary">
                                Cashback Transaksi: <strong style="color: #22d3ee;" class="mono-amount">{{ (float)$t->benefit_cashback_persen }}%</strong>
                            </div>
                        </div>

                        <div class="benefits-list flex-grow-1">
                            @if(!empty($benefits))
                                @foreach($benefits as $b)
                                    <div class="benefit-item">
                                        <div class="benefit-icon-box {{ $isUnlocked ? 'benefit-icon-unlocked' : 'benefit-icon-locked' }}">
                                            @if($isUnlocked)
                                                <i class="bi bi-check-lg"></i>
                                            @else
                                                <i class="bi bi-lock-fill"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-white font-weight-medium" style="font-size: 0.9rem; line-height: 1.5;">
                                                {{ $b }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-secondary small">Akses fitur standar Lapaktifikasi.</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 2: VOUCHER SAYA -->
    <div id="tab-vouchers" class="tab-pane">
        <div class="row">
            @forelse($vouchers as $v)
                @php
                    $alreadyClaimed = in_array($v->id_voucher, $claimedVoucherIds);
                @endphp
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="voucher-card">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                            @if($v->scope === 'global')
                                <span class="badge px-2 py-1" style="background: rgba(6, 182, 212, 0.2); color: #22d3ee; border: 1px solid rgba(6, 182, 212, 0.3); font-size: 0.75rem;">Global Voucher</span>
                            @else
                                <span class="badge px-2 py-1" style="background: rgba(236, 72, 153, 0.2); color: #f472b6; border: 1px solid rgba(236, 72, 153, 0.3); font-size: 0.75rem;">{{ $v->toko?->nama_toko ?? 'Toko Spesifik' }}</span>
                            @endif
                            <small class="text-secondary mono-amount">
                                Min. Rp {{ number_format((float)$v->minimal_transaksi, 0, ',', '.') }}
                            </small>
                        </div>

                        <div class="voucher-code">{{ $v->kode }}</div>

                        <div class="mt-3">
                            <div class="h5 font-weight-bold text-white mb-1">
                                Diskon {{ $v->tipe_diskon === 'persen' ? (float)$v->nilai_diskon . '%' : 'Rp ' . number_format((float)$v->nilai_diskon, 0, ',', '.') }}
                            </div>
                            @if($v->maksimal_potongan)
                                <div class="text-secondary small">Maksimal Potongan: Rp {{ number_format((float)$v->maksimal_potongan, 0, ',', '.') }}</div>
                            @endif
                            <div class="text-muted small mt-1">
                                <i class="bi bi-clock-history"></i> Valid s/d: {{ $v->berlaku_sampai ? $v->berlaku_sampai->format('d M Y') : 'Tanpa Batas' }}
                            </div>
                        </div>

                        <div class="mt-3 text-right">
                            @if($alreadyClaimed)
                                <button class="btn-claimed" disabled><i class="bi bi-check2-all"></i> Sudah Diklaim</button>
                            @else
                                <form action="{{ route('premium.voucher.klaim', $v->id_voucher) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-klaim">
                                        <i class="bi bi-ticket-perforated-fill mr-1"></i> Klaim Voucher
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-ticket-detailed text-muted d-block mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">Belum Ada Voucher Tersedia</h5>
                    <p class="text-muted small">Cek kembali secara berkala untuk mendapatkan voucher diskon menarik!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- TAB 3: PAKET VOUCHER SPESIAL -->
    <div id="tab-packs" class="tab-pane">
        <div class="glass-card-hero text-center py-5">
            <div class="mb-3">
                <i class="bi bi-gift-fill" style="font-size: 3.5rem; color: #8b5cf6;"></i>
            </div>
            <h3 class="font-weight-bold text-white mb-2">Paket Voucher Spesial Member</h3>
            <p class="text-secondary mx-auto" style="max-width: 540px; line-height: 1.6;">
                Tingkatkan terus level membership Anda untuk membuka **Paket Voucher Spesial** bulanan dengan potongan hingga 50% di toko favorit!
            </p>
            <div class="d-inline-flex gap-3 flex-wrap justify-content-center mt-3">
                <span class="badge p-3" style="background: rgba(139, 92, 246, 0.2); border: 1px solid rgba(139, 92, 246, 0.4); color: #c084fc; font-size: 0.85rem;">
                    <i class="bi bi-gem mr-1"></i> Silver Pack (1x Voucher/bln)
                </span>
                <span class="badge p-3" style="background: rgba(234, 179, 8, 0.2); border: 1px solid rgba(234, 179, 8, 0.4); color: #fde047; font-size: 0.85rem;">
                    <i class="bi bi-trophy-fill mr-1"></i> Gold Pack (3x Voucher/bln)
                </span>
                <span class="badge p-3" style="background: rgba(6, 182, 212, 0.2); border: 1px solid rgba(6, 182, 212, 0.4); color: #67e8f9; font-size: 0.85rem;">
                    <i class="bi bi-crown-fill mr-1"></i> Sultan VIP Pack (Unlimited Cashback)
                </span>
            </div>
        </div>
    </div>

</div>

<script>
    function switchTab(tabId, btnElement) {
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            pane.classList.remove('active');
        });
        
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });

        document.getElementById(tabId).classList.add('active');
        btnElement.classList.add('active');
    }
</script>

@endsection
