@extends('layout')

@section('title', 'Kelola Saldo Toko')

@section('content')

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success" });
</script>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Kelola Saldo Toko</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Toko</th>
                                <th>Username / Email Seller</th>
                                <th>Komisi Override</th>
                                <th>Saldo Saat Ini</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shops as $index => $shop)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $shop->nama_toko }}</strong></td>
                                <td>
                                    {{ $shop->user->name ?? '-' }}
                                    <br><small class="text-muted">{{ $shop->user->email ?? '-' }}</small>
                                </td>
                                <td>
                                    @if(!is_null($shop->komisi_override))
                                        <span class="badge badge-info">{{ $shop->komisi_override }}%</span>
                                    @else
                                        <span class="text-muted"><small>Global Default</small></span>
                                    @endif
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($shop->saldo, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <a href="{{ url('saldo_toko/detail/' . $shop->id_toko) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-history mr-1"></i> Detail / Mutasi
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data toko seller.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
