@extends('layout')

@section('title', 'Setting Website')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                <h4 class="mb-0 text-dark"><i class="bi bi-gear-fill text-primary mr-2"></i> Pengaturan Website</h4>
            </div>
            
            <div class="card-body pt-0">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-left-success" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-left-danger" role="alert">
                    <strong>Gagal!</strong> Periksa kembali inputan Anda.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <form action="{{ route('admin.setting_website.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Logo & Favicon Section -->
                        <div class="col-md-4 mb-4">
                            <div class="p-3 border rounded bg-light">
                                <h6 class="font-weight-bold mb-3 border-bottom pb-2">Logo & Favicon</h6>
                                
                                <div class="form-group text-center">
                                    <label class="d-block font-weight-bold text-left">Logo Website</label>
                                    <div class="mb-2 p-3 bg-white border rounded d-inline-block" style="min-width: 150px; min-height: 80px;">
                                        @if($settings->logo_path)
                                            <img src="{{ asset($settings->logo_path) }}" alt="Logo" style="max-width: 100%; max-height: 60px;">
                                        @else
                                            <span class="text-muted d-block mt-3"><i class="bi bi-image"></i> Belum ada logo</span>
                                        @endif
                                    </div>
                                    <input type="file" name="logo" class="form-control-file mt-2" accept="image/*">
                                    <small class="text-muted text-left d-block mt-1">Disarankan: Format PNG transparan.</small>
                                </div>
                                
                                <hr>
                                
                                <div class="form-group text-center">
                                    <label class="d-block font-weight-bold text-left">Favicon (Ikon Tab)</label>
                                    <div class="mb-2 p-3 bg-white border rounded d-inline-block" style="min-width: 80px; min-height: 80px;">
                                        @if($settings->favicon_path)
                                            <img src="{{ asset($settings->favicon_path) }}" alt="Favicon" style="max-width: 32px; max-height: 32px;">
                                        @else
                                            <span class="text-muted d-block mt-2"><i class="bi bi-image"></i></span>
                                        @endif
                                    </div>
                                    <input type="file" name="favicon" class="form-control-file mt-2" accept="image/png, image/jpeg, image/x-icon, image/svg+xml">
                                    <small class="text-muted text-left d-block mt-1">Disarankan: Persegi, resolusi 32x32 atau 64x64.</small>
                                </div>
                            </div>
                        </div>

                        <!-- General Information Section -->
                        <div class="col-md-8 mb-4">
                            <div class="p-4 border rounded">
                                <h6 class="font-weight-bold mb-3 border-bottom pb-2">Informasi Umum</h6>
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Website / Judul <span class="text-danger">*</span></label>
                                    <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">Deskripsi Singkat</label>
                                    <textarea name="site_description" class="form-control" rows="3">{{ old('site_description', $settings->site_description) }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Email Kontak</label>
                                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings->contact_email) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Nomor Telepon / WhatsApp</label>
                                            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings->contact_phone) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">Alamat Perusahaan</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address', $settings->address) }}</textarea>
                                </div>
                                
                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-primary px-4 font-weight-bold"><i class="bi bi-save mr-1"></i> Simpan Pengaturan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
