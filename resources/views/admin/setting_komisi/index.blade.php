@extends('layout')

@section('title', 'Setting Komisi Platform')

@section('content')

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success" });
</script>
@endif

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h4>Pengaturan Komisi Default Platform</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Nilai komisi default ini akan dipotong secara otomatis dari setiap transaksi sukses seller, kecuali jika seller tersebut memiliki nilai <strong>komisi override</strong> khusus yang diatur di menu Kelola Seller.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <strong>Gagal memperbarui:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ url('setting_komisi/update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="komisi_default">Komisi Default Platform (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" max="100" class="form-control" id="komisi_default" name="komisi_default" value="{{ old('komisi_default', $setting->komisi_default) }}" placeholder="Contoh: 10.00" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Gunakan titik (.) untuk nilai desimal. Contoh: 10.50 atau 12.00.
                        </small>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
