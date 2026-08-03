@extends('layout')

@section('title', 'Badge Reputasi Toko')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 font-weight-bold text-dark">Badge Reputasi Toko</h1>
        <p class="text-muted small mb-0">Lihat pencapaian badge toko Anda dan progress menuju kriteria berikutnya.</p>
    </div>

    <div class="row">
        @foreach($badgeProgress as $item)
        @php
            $b = $item['badge'];
            $isOwned = $item['is_owned'];
            $percent = $item['percent'];
            $progressText = $item['progress_text'];
        @endphp
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100 {{ $isOwned ? 'border-primary' : '' }}" style="border-radius: 12px; overflow: hidden; {{ $isOwned ? 'background: #faf5ff; border: 1.5px solid #8b5cf6 !important;' : '' }}">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: {{ $isOwned ? '#8b5cf6' : '#e2e8f0' }}; color: {{ $isOwned ? '#ffffff' : '#64748b' }}; font-size: 1.2rem;">
                                <i class="bi {{ $isOwned ? 'bi-patch-check-fill' : 'bi-shield-lock-fill' }}"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold mb-0 text-dark" style="font-size: 1.05rem;">{{ $b->nama_badge }}</h5>
                                <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $b->kriteria_tipe)) }}</small>
                            </div>
                        </div>
                        <div>
                            @if($isOwned)
                                <span class="badge badge-success px-2 py-1"><i class="bi bi-check-circle-fill"></i> Dimiliki</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1"><i class="bi bi-lock-fill"></i> Belum Diberikan</span>
                            @endif
                        </div>
                    </div>

                    <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.5;">
                        {{ $b->deskripsi }}
                    </p>

                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center small mb-1">
                            <span class="font-weight-semibold text-muted">Progress Kriteria:</span>
                            <span class="font-weight-bold text-dark">{{ $percent }}%</span>
                        </div>

                        <div class="progress mb-2" style="height: 8px; border-radius: 4px; background: #e2e8f0;">
                            <div class="progress-bar {{ $isOwned ? 'bg-primary' : 'bg-info' }}" style="width: {{ $percent }}%; border-radius: 4px; background-color: {{ $isOwned ? '#8b5cf6' : '#06b6d4' }} !important;"></div>
                        </div>

                        <div class="small text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-info-circle mr-1"></i> {{ $progressText }}
                        </div>

                        @if($isOwned && $item['diperoleh_pada'])
                        <div class="small text-success mt-2 font-weight-bold" style="font-size: 0.78rem;">
                            <i class="bi bi-calendar-check mr-1"></i> Diperoleh pada: {{ \Carbon\Carbon::parse($item['diperoleh_pada'])->format('d M Y') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
