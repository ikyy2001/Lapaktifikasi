@extends('layout')

@section('title', 'Profile')

@section('content')

    @if($success = Session::get('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: "success",
                title: "{{ $success }}"
            });
        </script>

    @elseif($error = Session::get('error'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: "error",
                title: "{{ $error }}"
            });
        </script>

    @elseif($warning = Session::get('warning'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: "warning",
                title: "{{ $warning }}"
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            Swal.fire({
                icon: "error",
                title: "Gagal Mengubah Profil",
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif

    <style>
        .profile-container {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin-top: 10px;
        }
        
        .profile-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1a1a1a;
            margin-bottom: 24px;
            text-transform: uppercase;
            border-left: 4px solid #000000;
            padding-left: 14px;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            animation: profileFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @media (min-width: 992px) {
            .profile-grid {
                grid-template-columns: 320px 1fr;
            }
        }
        
        .mono-card {
            background: #ffffff;
            border: 1px solid #000000;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .mono-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #000000;
        }
        
        /* Left Panel: Profile Visuals */
        .profile-avatar-wrapper {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto 24px auto;
            border-radius: 50%;
            border: 2px solid #000000;
            padding: 6px;
            background: transparent;
            transition: all 0.3s ease;
        }
        
        .profile-avatar-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .profile-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            background: #f2f2f2;
        }
        
        .profile-user-name {
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
            color: #000000;
        }
        
        .profile-user-email {
            font-size: 0.85rem;
            color: #666666;
            text-align: center;
            margin-bottom: 24px;
            word-break: break-all;
        }
        
        .profile-badge {
            display: block;
            width: max-content;
            margin: 0 auto 30px auto;
            background: #000000;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            padding: 6px 16px;
            border-radius: 50px;
            text-transform: uppercase;
        }
        
        .profile-meta-list {
            border-top: 1px dashed #e5e5e5;
            padding-top: 24px;
        }
        
        .profile-meta-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.82rem;
            margin-bottom: 12px;
        }
        
        .profile-meta-label {
            color: #666666;
        }
        
        .profile-meta-value {
            font-weight: 600;
            color: #000000;
        }
        
        .profile-meta-barcode {
            margin-top: 28px;
            text-align: center;
            opacity: 0.8;
            color: #000000;
            font-size: 0.65rem;
            letter-spacing: 2px;
        }
        
        /* Right Panel: Edit Form */
        .form-title {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #000000;
            margin-bottom: 28px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 14px;
        }
        
        .mono-form-group {
            margin-bottom: 24px;
            position: relative;
        }
        
        .mono-form-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666666;
            margin-bottom: 8px;
        }
        
        .mono-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .mono-input-icon {
            position: absolute;
            left: 16px;
            color: #888888;
            font-size: 1.1rem;
            pointer-events: none;
            transition: color 0.3s ease;
        }
        
        .mono-input {
            width: 100%;
            background: #ffffff;
            border: 1px solid #cccccc;
            color: #000000 !important;
            padding: 12px 16px 12px 48px;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: inherit;
        }
        
        .mono-input[readonly] {
            background: #f9f9f9;
            border-color: #e5e5e5;
            color: #888888 !important;
            cursor: not-allowed;
        }
        
        .mono-input:focus {
            background: #ffffff;
            border-color: #000000;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }
        
        .mono-input:focus ~ .mono-input-icon {
            color: #000000;
        }
        
        /* Warning message */
        .mono-warning {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #fffdf5;
            border: 1px dashed #eed27a;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 10px;
            margin-bottom: 24px;
            font-size: 0.78rem;
            color: #7d6318;
            line-height: 1.4;
        }
        
        .mono-warning i {
            color: #bfa13f;
            font-size: 0.95rem;
            margin-top: 1px;
        }
        
        /* Buttons */
        .mono-btn-primary {
            background: #000000;
            color: #ffffff !important;
            border: 1px solid #000000;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 12px 28px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: inline-block;
        }
        
        .mono-btn-primary:hover {
            background: transparent;
            color: #000000 !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
        }
        
        .mono-btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-wrapper {
            text-align: right;
            margin-top: 16px;
        }
        
        @keyframes profileFadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="profile-container">
        <h4 class="profile-header-title">Profil Anda</h4>
        
        <div class="profile-grid">
            <!-- Left Card: Visual profile -->
            <div class="mono-card text-center">
                <div class="profile-avatar-wrapper">
                    <img class="profile-avatar-img" 
                         src="{{ $user->profile_picture ? asset('assets/img/avatar/' . $user->profile_picture) : asset('assets/img/avatar/' . session('profile_picture')) }}" 
                         alt="avatar">
                </div>
                
                <h3 class="profile-user-name">{{ (Auth::user()->name == '' ? 'User Baru' : $user->name) }}</h3>
                <p class="profile-user-email">{{ $user->email }}</p>
                
                <span class="profile-badge">{{ Auth::user()->role_id == 1 ? 'Administrator' : 'Customer' }}</span>
                
                <div class="profile-meta-list">
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">ID Akun</span>
                        <span class="profile-meta-value">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <span class="profile-meta-label">Bergabung</span>
                        <span class="profile-meta-value">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                    </div>
                </div>
                
                <div class="profile-meta-barcode">
                    <div>||| | |||| | || ||| ||</div>
                    <div style="font-size: 0.5rem; margin-top: 4px;">LAPAKTIFIKASI-MEMBER</div>
                </div>
            </div>
            
            <!-- Right Card: Form fields -->
            <div class="mono-card">
                <h4 class="form-title">Pengaturan Profil</h4>
                
                <form method="post" action="{{ url('update_profile') }}">
                    @csrf

                    @foreach($user->customer as $item)
                        <!-- Name Field -->
                        <div class="mono-form-group">
                            <label class="mono-form-label">Nama Lengkap</label>
                            <div class="mono-input-wrapper">
                                <input type="text" class="mono-input" name="name" autocomplete="off"
                                    placeholder="Nama Lengkap Anda" value="{{ (Auth::user()->name == '' ? '' : $user->name) }}">
                                <i class="bi bi-person-fill mono-input-icon"></i>
                            </div>
                            @if($user->name == '')
                                <div class="mono-warning">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>Isi nama lengkap Anda (Wajib).</span>
                                </div>
                            @endif
                        </div>

                        <!-- Email Field (Readonly) -->
                        <div class="mono-form-group">
                            <label class="mono-form-label">Alamat Email</label>
                            <div class="mono-input-wrapper">
                                <input type="email" class="mono-input" placeholder="Email" name="email" autocomplete="off"
                                    value="{{ $user->email }}" readonly>
                                <i class="bi bi-envelope-at-fill mono-input-icon"></i>
                            </div>
                        </div>

                        <!-- Phone Number Field -->
                        <div class="mono-form-group">
                            <label class="mono-form-label">Nomor Telepon</label>
                            <div class="mono-input-wrapper">
                                <input type="text" class="mono-input" placeholder="Nomor Telepon Anda" name="nomor_telepon"
                                    autocomplete="off"
                                    value="{{ ($item->nomor_telepon == '' ? '' : $item->nomor_telepon) }}">
                                <i class="bi bi-telephone-fill mono-input-icon"></i>
                            </div>
                            @if($item->nomor_telepon == '')
                                <div class="mono-warning">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>Isi nomor telepon agar admin bisa menghubungi Anda dengan mudah jika terjadi kendala pembayaran (Wajib).</span>
                                </div>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="btn-wrapper">
                            <button type="submit" name="simpan" class="mono-btn-primary">Simpan Perubahan</button>
                        </div>
                    @endforeach

                </form>
            </div>
        </div>
    </div>

@endsection