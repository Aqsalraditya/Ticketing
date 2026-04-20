@extends('layouts.auth')

@section('title', 'Login - Authorization Lab')

@section('content')
    <div class="container-fluid px-md-5 py-4">
        <div class="row g-0 shadow-lg rounded-4 overflow-hidden border bg-white" style="min-height: 85vh;">

            {{-- PANEL KIRI: BRANDING & VISUAL --}}
            <div class="col-md-5 d-none d-md-flex flex-column justify-content-center align-items-center p-5 text-white text-center"
                style="background-color: #0066ff;">
                <div class="mb-4">
                    <i class="bi bi-shield-lock-fill" style="font-size: 5rem;"></i>
                </div>
                <h1 class="fw-bold mb-3">Selamat Datang Kembali</h1>
                <p class="lead opacity-75">Sistem bantuan terpadu untuk solusi cepat dan aman bagi seluruh warga SMK Wikrama Bogor.</p>

                <div class="mt-4 p-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 w-100 max-w-sm">
                    <small class="d-block mb-1 opacity-75 text-uppercase letter-spacing-1">Keamanan & Otorisasi</small>
                    <span class="small">Akses fitur sistem sesuai dengan peran (role) akun Anda.</span>
                </div>
            </div>

            {{-- PANEL KANAN: FORM LOGIN & LAB INFO --}}
            <div class="col-md-7 p-4 p-lg-5 bg-white d-flex flex-column justify-content-center">
                <div class="w-100 px-lg-4">
                    
                    {{-- Judul & Badge Lab --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
                        <div>
                            <h2 class="fw-bold h3 mb-2 text-dark">Login Ke Akun</h2>
                            <p class="text-muted small mb-0">Masukkan kredensial Anda untuk mengetes sistem otorisasi.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill">
                                <i class="bi bi-person-badge me-1"></i> AUTHORIZATION LAB
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                <i class="bi bi-shield-check me-1"></i> SECURE
                            </span>
                        </div>
                    </div>

                    {{-- Security Features Alert --}}
                    <div class="alert alert-success small mb-4 border-0 shadow-sm rounded-3 bg-success bg-opacity-10 text-dark">
                        <strong><i class="bi bi-shield-fill-check text-success"></i> Fitur Keamanan Aktif:</strong>
                        <ul class="mb-0 mt-2 ps-3 text-muted">
                            <li>Rate limiting (Mencegah Brute Force)</li>
                            <li>Session Regeneration (Mencegah Fixation)</li>
                            <li>Bcrypt Password Hashing & CSRF Protection</li>
                            <li><b>Role-Based Access Control (Admin, Staff, User)</b></li>
                        </ul>
                    </div>

                    {{-- FORM LOGIN --}}
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small text-muted uppercase letter-spacing-1">ALAMAT EMAIL</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light px-3 text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg border-0 bg-light px-3 py-3 @error('email') is-invalid @enderror"
                                    placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label fw-bold small text-muted uppercase letter-spacing-1">PASSWORD</label>
                                <a href="#" class="text-decoration-none small" style="color: #0066ff;">Lupa Password?</a>
                            </div>
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
                            @error('password')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn text-white btn-lg py-3 shadow-sm border-0 fw-bold" style="background-color: #0066ff;">
                                Masuk Sekarang <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <hr class="my-5 text-muted opacity-25">

                    {{-- LAB AREA: Test Accounts & Code Preview --}}
                    <div class="bg-light p-4 rounded-4 border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-person-badge me-2"></i>Akun Uji Coba Otorisasi</h6>
                            <a href="{{ route('vulnerable.login') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-medium">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Mode Vulnerable
                            </a>
                        </div>
                        
                        {{-- Tabel Akun --}}
                        <div class="table-responsive mb-4">
                            <table class="table table-sm table-bordered bg-white small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Role</th>
                                        <th>Akses Halaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>admin@wikrama.sch.id</code></td>
                                        <td><code>password</code></td>
                                        <td><span class="badge bg-danger">admin</span></td>
                                        <td><small>Full access & Admin Panel</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>staff@wikrama.sch.id</code></td>
                                        <td><code>password</code></td>
                                        <td><span class="badge bg-primary">staff</span></td>
                                        <td><small>Kelola tiket yang ditugaskan</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>budi@student.sch.id</code></td>
                                        <td><code>password</code></td>
                                        <td><span class="badge bg-secondary">user</span></td>
                                        <td><small>Hanya tiket sendiri</small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Code Preview Otorisasi --}}
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="card-header bg-dark text-white border-0 py-2">
                                <small class="font-monospace"><i class="bi bi-code-slash me-1"></i>TicketPolicy.php (Update Logic)</small>
                            </div>
                            <div class="card-body p-0">
                                <pre class="bg-dark text-light p-3 mb-0 small" style="overflow-x: auto;"><code>public function update(User $user, Ticket $ticket): bool
{
    <span class="text-success">// Staff hanya bisa edit jika ditugaskan</span>
    if ($user->isStaff()) {
        return $ticket->assigned_to === $user->id;
    }

    <span class="text-success">// User hanya bisa edit milik sendiri & belum closed</span>
    return $ticket->belongsToUser($user) && $ticket->isEditable();
}</code></pre>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; align-items: center; }
        .form-control:focus { background-color: #fff !important; box-shadow: 0 0 0 0.25rem rgba(0, 102, 255, 0.25); border: 1px solid #0066ff !important; }
        .input-group-text { border-radius: 12px 0 0 12px; }
        .form-control { border-radius: 0; }
        .rounded-end-3 { border-radius: 0 12px 12px 0 !important; }
        .letter-spacing-1 { letter-spacing: 1px; }
        .uppercase { text-transform: uppercase; }
        .custom-checkbox:checked { background-color: #0066ff; border-color: #0066ff; }
        pre { font-size: 0.75rem; line-height: 1.4; }
        table code { color: #d63384; }
    </style>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>
@endsection