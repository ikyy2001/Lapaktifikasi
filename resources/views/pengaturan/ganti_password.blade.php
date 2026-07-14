@extends('layout')

@section('title', 'Ganti Password')

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
        
        .mono-card {
            background: #ffffff;
            border: 1px solid #000000;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            animation: profileFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
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
        
        .mono-input:focus {
            background: #ffffff;
            border-color: #000000;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }
        
        .mono-input:focus ~ .mono-input-icon {
            color: #000000;
        }
        
        /* Checkbox styling */
        .mono-checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            cursor: pointer;
        }
        
        .mono-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .mono-checkbox:checked {
            background: #000000;
            border-color: #000000;
        }
        
        .mono-checkbox:checked::after {
            content: "\F26B"; /* Bootstrap Icons check code */
            font-family: "bootstrap-icons";
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 900;
        }
        
        .mono-checkbox:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }
        
        .mono-checkbox-label {
            font-size: 0.85rem;
            color: #444444;
            user-select: none;
            cursor: pointer;
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
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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
        <h4 class="profile-header-title">Ganti Password</h4>
        
        <div class="row">
            <div class="col-12">
                <div class="mono-card">
                    <h4 class="form-title">Ubah Kredensial Keamanan</h4>
                    
                    <form method="post" action="{{ url('proses_ganti_password') }}">
                        @csrf

                        <!-- Password Baru -->
                        <div class="mono-form-group">
                            <label class="mono-form-label" for="password_baru">Password Baru</label>
                            <div class="mono-input-wrapper">
                                <input type="password" class="mono-input" id="password_baru" name="password_baru"
                                    autocomplete="off" value="{{ old('password_baru') }}">
                                <i class="bi bi-shield-lock-fill mono-input-icon"></i>
                            </div>
                            @if ($errors->has('password_baru'))
                                <div class="mono-warning">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ ucfirst($errors->first('password_baru')) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="mono-form-group">
                            <label class="mono-form-label" for="konfirmasi_password_baru">Konfirmasi Password Baru</label>
                            <div class="mono-input-wrapper">
                                <input type="password" class="mono-input" id="konfirmasi_password_baru"
                                    name="konfirmasi_password_baru" autocomplete="off"
                                    value="{{ old('konfirmasi_password_baru') }}">
                                <i class="bi bi-shield-fill-check mono-input-icon"></i>
                            </div>
                            @if ($errors->has('konfirmasi_password_baru'))
                                <div class="mono-warning">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ ucfirst($errors->first('konfirmasi_password_baru')) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Lihat Password Checkbox -->
                        <div class="mono-checkbox-wrapper">
                            <input class="mono-checkbox" type="checkbox" id="lihat_password">
                            <label class="mono-checkbox-label" for="lihat_password">
                                Lihat Password
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="btn-wrapper">
                            <button type="submit" name="ganti" class="mono-btn-primary">Ganti Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection