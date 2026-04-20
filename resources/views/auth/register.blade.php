@extends('layouts.auth')

@section('title', 'Register - Secure Ticketing')

@section('content')
    <div class="container-fluid px-md-5 py-4">
        <div class="row g-0 shadow-lg rounded-4 overflow-hidden border bg-white" style="min-height: 85vh;">

            {{-- PANEL KIRI: BRANDING & VISUAL --}}
            <div class="col-md-5 d-none d-md-flex flex-column justify-content-center align-items-center p-5 text-white text-center"
                style="background: linear-gradient(135deg, #88d3ce 0%, #6ebfb9 100%);">
                <div class="mb-4">
                    <i class="bi bi-person-plus-fill" style="font-size: 5rem;"></i>
                </div>
                <h1 class="fw-bold mb-3">Bergabung Bersama Kami</h1>
                <p class="lead opacity-75">Buat akun Anda sekarang untuk mengakses sistem bantuan terpadu SMK Wikrama Bogor.</p>

                <div class="mt-4 p-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 w-100 max-w-sm">
                    <small class="d-block mb-1 opacity-75 text-uppercase letter-spacing-1">Data Terlindungi Aman</small>
                    <span class="small">Informasi Anda dienkripsi dan disimpan dengan standar keamanan tinggi.</span>
                </div>
            </div>

            {{-- PANEL KANAN: FORM REGISTER & DEMO LAB --}}
            <div class="col-md-7 p-4 p-lg-5 bg-white d-flex flex-column justify-content-center">
                <div class="w-100 px-lg-4">
                    
                    {{-- Judul & Badge Secure --}}
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 class="fw-bold h3 mb-2 text-dark">Buat Akun Baru</h2>
                            <p class="text-muted small mb-0">Silakan lengkapi data diri Anda di bawah ini.</p>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                            <i class="bi bi-shield-check me-1"></i> SECURE REGISTER
                        </span>
                    </div>

                    {{-- Security Features Info --}}
                    <div class="alert alert-success small mb-4 border-0 shadow-sm rounded-3 bg-success bg-opacity-10 text-dark">
                        <strong><i class="bi bi-shield-fill-check text-success"></i> Fitur Keamanan Aktif:</strong>
                        <ul class="mb-0 mt-2 ps-3 text-muted">
                            <li>Aturan password kuat (Huruf, Angka, dll)</li>
                            <li>Konfirmasi password untuk mencegah *typo*</li>
                            <li>Hashing otomatis menggunakan bcrypt</li>
                            <li>Validasi input anti-XSS</li>
                        </ul>
                    </div>

                    {{-- FORM REGISTER UTAMA --}}
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold small text-muted uppercase letter-spacing-1">NAMA LENGKAP</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light px-3 text-muted">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="name" id="name"
                                    class="form-control form-control-lg border-0 bg-light px-3 py-3 rounded-end-3 @error('name') is-invalid @enderror"
                                    placeholder="Nama Lengkap Anda" value="{{ old('name') }}" required autofocus>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small text-muted uppercase letter-spacing-1">ALAMAT EMAIL</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light px-3 text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg border-0 bg-light px-3 py-3 rounded-end-3 @error('email') is-invalid @enderror"
                                    placeholder="nama@email.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold small text-muted uppercase letter-spacing-1">PASSWORD</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light px-3 text-muted">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-lg border-0 bg-light px-3 py-3 @error('password') is-invalid @enderror"
                                    placeholder="••••••••" required>
                                <button class="btn btn-light border-0 bg-light text-muted px-3 rounded-end-3" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div class="form-text mt-2 small text-muted">
                                <i class="bi bi-info-circle me-1"></i> Min 8 karakter, harus mengandung huruf besar/kecil & angka.
                            </div>
                            @error('password')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold small text-muted uppercase letter-spacing-1">KONFIRMASI PASSWORD</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light px-3 text-muted">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control form-control-lg border-0 bg-light px-3 py-3"
                                    placeholder="Ulangi Password Anda" required>
                                <button class="btn btn-light border-0 bg-light text-muted px-3 rounded-end-3" type="button" id="toggleConfirmPassword">
                                    <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg py-3 shadow-sm border-0 fw-bold" style="background-color: var(--primary-soft);">
                                Daftar Sekarang <i class="bi bi-person-plus ms-2"></i>
                            </button>
                        </div>

                        <p class="text-center text-muted small mb-0">
                            Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--primary-soft);">Login di sini</a>
                        </p>
                    </form>

                    <hr class="my-5 text-muted opacity-25">

                    {{-- AREA DEMO LAB (Compare & Code Preview) --}}
                    <div class="bg-light p-4 rounded-4 border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-code me-2"></i>Lab Keamanan</h6>
                            <a href="{{ route('vulnerable.register') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-medium">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Mode Vulnerable
                            </a>
                        </div>
                        
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="card-header bg-dark text-white border-0 py-2 d-flex justify-content-between align-items-center">
                                <small class="font-monospace"><i class="bi bi-file-earmark-code me-1"></i>Secure Code Preview</small>
                            </div>
                            <div class="card-body p-0">
                                <pre class="bg-dark text-light p-3 mb-0 small" style="overflow-x: auto;"><code>// RegisterController.php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => [
            'required', 'confirmed',
            <span class="text-success">// Strong password rules</span>
            Rules\Password::defaults()
                ->min(8)
                ->letters()
                ->numbers()
                ->mixedCase()
        ],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        <span class="text-success">// Auto-hashed via model casting!</span>
        'password' => $request->password,
    ]);
}</code></pre>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f4f7f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 0.25rem rgba(110, 191, 185, 0.25);
            border: 1px solid var(--primary-soft) !important;
        }

        .input-group-text { border-radius: 12px 0 0 12px; }
        .form-control { border-radius: 0; }
        .rounded-end-3 { border-radius: 0 12px 12px 0 !important; }
        .letter-spacing-1 { letter-spacing: 1px; }
        .uppercase { text-transform: uppercase; }
        
        pre { font-size: 0.8rem; line-height: 1.5; }
    </style>

    <script>
        // Toggle untuk Password Utama
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });

        // Toggle untuk Konfirmasi Password
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#password_confirmation');
        const eyeIconConfirm = document.querySelector('#eyeIconConfirm');

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            eyeIconConfirm.classList.toggle('bi-eye');
            eyeIconConfirm.classList.toggle('bi-eye-slash');
        });
    </script>
@endsection