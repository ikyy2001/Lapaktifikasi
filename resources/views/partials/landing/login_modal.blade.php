@php
    $siteName = isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi';
@endphp

<!-- LOGIN MODAL -->
<div class="modal-overlay" id="login-modal" onclick="closeModalOverlay(event)">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal()" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        <div class="modal-header-content">
            <div class="modal-logo">L</div>
            <div class="modal-title">Masuk ke {{ $siteName }}</div>
            <div class="modal-subtitle">Masuk untuk menikmati akses akun &amp; produk digital</div>
        </div>
        <div class="login-tabs">
            <button class="login-tab active" data-role="customer" onclick="switchModalTab(this, 'customer')">Pelanggan</button>
            <button class="login-tab" data-role="admin" onclick="switchModalTab(this, 'admin')">Admin / Seller</button>
        </div>
        <form action="{{ url('/proses_login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" id="modal-username-label">Email Pelanggan</label>
                <div class="form-input-wrap">
                    <i class="bi bi-person form-icon"></i>
                    <input class="form-input" type="text" id="modal-email" name="email"
                        placeholder="Masukkan email Anda" required autocomplete="username">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <div class="form-input-wrap">
                    <i class="bi bi-lock form-icon"></i>
                    <input class="form-input" type="password" id="modal-password" name="password"
                        placeholder="Masukkan kata sandi" required autocomplete="current-password">
                </div>
            </div>
            <label class="form-check-row">
                <input type="checkbox" id="modal-show-password" onclick="togglePassword()"> Tampilkan kata sandi
            </label>
            <button class="btn-modal-submit" type="submit">
                <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
            </button>
        </form>
        <div class="modal-register-hint">
            Belum punya akun? <a href="{{ url('/pendaftaran') }}">Daftar di sini &rarr;</a>
        </div>
    </div>
</div>
