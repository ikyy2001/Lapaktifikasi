@extends('layout')

@section('title', 'Setting Komisi & Maintenance Platform')

@section('content')

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success" });
</script>
@endif

<div class="row">
    <!-- Pengaturan Komisi & Limit -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h4><i class="bi bi-sliders mr-2"></i> Pengaturan Komisi Default Platform</h4>
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

                    <div class="form-group">
                        <label for="digital_file_limit_mb">Batas Ukuran Upload File Digital (MB)</label>
                        <div class="input-group">
                            <input type="number" step="1" min="1" class="form-control" id="digital_file_limit_mb" name="digital_file_limit_mb" value="{{ old('digital_file_limit_mb', $setting->digital_file_limit_mb ?? 250) }}" placeholder="Contoh: 250" required>
                            <div class="input-group-append">
                                <span class="input-group-text">MB</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Batas maksimal ukuran file ZIP/gambar yang bisa diupload oleh seller (default: 250MB).
                        </small>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pengaturan Mode Maintenance -->
    <div class="col-md-6">
        <div class="card @if($setting->is_maintenance) card-warning @else card-success @endif">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-tools mr-2"></i> Mode Maintenance System</h4>
                @if($setting->is_maintenance)
                    <span class="badge badge-warning px-3 py-2" style="font-size: 0.85rem;"><i class="bi bi-exclamation-triangle-fill mr-1"></i> AKTIF</span>
                @else
                    <span class="badge badge-success px-3 py-2" style="font-size: 0.85rem;"><i class="bi bi-check-circle-fill mr-1"></i> NONAKTIF (NORMAL)</span>
                @endif
            </div>
            <div class="card-body">
                <div class="p-3 mb-3 border rounded @if($setting->is_maintenance) bg-light-warning border-warning @else bg-light border-success @endif">
                    <h6 class="font-weight-bold mb-2">
                        @if($setting->is_maintenance)
                            <span class="text-warning"><i class="bi bi-shield-lock-fill"></i> Mode Maintenance Sedang Aktif!</span>
                        @else
                            <span class="text-success"><i class="bi bi-shield-check"></i> Sistem Berjalan Normal</span>
                        @endif
                    </h6>
                    <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.5;">
                        @if($setting->is_maintenance)
                            Pengguna dengan role <strong>Seller</strong> dan <strong>Customer</strong> saat ini <strong>TIDAK BISA</strong> mengakses dashboard atau fitur internal aplikasi. Halaman Landing Page dan Login tetap dapat dibuka. Admin (Anda) tetap bisa mengakses seluruh fitur.
                        @else
                            Seluruh pengguna (Admin, Seller, dan Customer) dapat mengakses dashboard dan fitur aplikasi secara normal tanpa pembatasan.
                        @endif
                    </p>
                </div>

                <form action="{{ url('setting_komisi/toggle_maintenance') }}" method="POST" id="form-toggle-maintenance">
                    @csrf
                    <input type="hidden" name="is_maintenance" value="{{ $setting->is_maintenance ? 0 : 1 }}">
                    
                    @if($setting->is_maintenance)
                        <button type="button" onclick="confirmMaintenanceToggle(false)" class="btn btn-success btn-block font-weight-bold py-2">
                            <i class="bi bi-power mr-1"></i> Nonaktifkan Mode Maintenance
                        </button>
                    @else
                        <button type="button" onclick="confirmMaintenanceToggle(true)" class="btn btn-warning btn-block font-weight-bold py-2">
                            <i class="bi bi-power mr-1"></i> Aktifkan Mode Maintenance
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmMaintenanceToggle(enable) {
    const title = enable ? "Aktifkan Mode Maintenance?" : "Nonaktifkan Mode Maintenance?";
    const text = enable 
        ? "Saat diaktifkan, Seller dan Customer akan diblokir dari dashboard dan diarahkan ke halaman Under Maintenance."
        : "Saat dinonaktifkan, Seller dan Customer dapat kembali mengakses dashboard dan transaksi secara normal.";
    const icon = enable ? "warning" : "question";

    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: enable ? "#ffc107" : "#28a745",
        cancelButtonColor: "#6c757d",
        confirmButtonText: enable ? "Ya, Aktifkan!" : "Ya, Nonaktifkan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-toggle-maintenance').submit();
        }
    });
}
</script>

@endsection
