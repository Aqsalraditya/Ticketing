@extends('layouts.app')

@section('title', 'Dashboard - Secure System')

@section('content')
<div class="container py-4">
    <div class="row">
        {{-- BAGIAN KIRI: UTAMA (8 Kolom) --}}
        <div class="col-lg-8">
            {{-- Alert Selamat Datang & Role --}}
            <div class="card secure-border mb-4 shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 font-jakarta"><i class="bi bi-speedometer2 me-2"></i>Dashboard Utama</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-dark mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="alert-heading fw-bold mb-1">Selamat datang, {{ Auth::user()->name }}!</h5>
                                <p class="mb-0">Anda terautentikasi dengan sistem keamanan standar industri.</p>
                            </div>
                            <div class="text-end">
                                <span class="d-block small text-muted mb-1 text-uppercase fw-bold">Role Anda:</span>
                                @if(Auth::user()->isAdmin())
                                    <span class="badge bg-danger px-3 py-2 fs-6 shadow-sm">👑 Admin</span>
                                @elseif(Auth::user()->isStaff())
                                    <span class="badge bg-warning text-dark px-3 py-2 fs-6 shadow-sm">⚡ Staff</span>
                                @else
                                    <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">👤 User</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Data User --}}
                        <div class="col-md-6">
                            <div class="card h-100 border-light shadow-sm">
                                <div class="card-header bg-light fw-bold"><i class="bi bi-person-badge me-2"></i>Informasi Akun</div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <tr><td class="ps-3 py-2 text-muted">Nama:</td><td class="fw-medium text-end pe-3">{{ Auth::user()->name }}</td></tr>
                                        <tr><td class="ps-3 py-2 text-muted">Email:</td><td class="fw-medium text-end pe-3">{{ Auth::user()->email }}</td></tr>
                                        <tr>
                                            <td class="ps-3 py-2 text-muted">Password:</td>
                                            <td class="text-end pe-3">
                                                <code class="small text-truncate d-inline-block" style="max-width: 100px;">{{ Auth::user()->password }}</code>
                                                <span class="badge bg-success d-block small mt-1">✓ Bcrypt Hashed</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-3 py-2 text-muted">Session:</td>
                                            <td class="text-end pe-3">
                                                <code class="small">{{ Str::limit(session()->getId(), 10) }}...</code>
                                                <span class="badge bg-info text-dark d-block small mt-1">✓ Regenerated</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Checklist Keamanan --}}
                        <div class="col-md-6">
                            <div class="card h-100 border-light shadow-sm">
                                <div class="card-header bg-light fw-bold"><i class="bi bi-shield-check me-2"></i>Status Keamanan</div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush small">
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">Rate Limiting <span class="text-success fw-bold">Enabled ✓</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">Session Fixation Prot. <span class="text-success fw-bold">Active ✓</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">CSRF Middleware <span class="text-success fw-bold">Protected ✓</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">RBAC Otorisasi <span class="text-success fw-bold">Verified ✓</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 font-jakarta"><i class="bi bi-lightning-charge me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('tickets.index') }}" class="btn btn-outline-primary w-100 py-3 rounded-3 shadow-sm transition-hover">
                                <i class="bi bi-ticket-perforated fs-3 d-block mb-1"></i>
                                <span>Lihat Tiket</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('tickets.create') }}" class="btn btn-outline-success w-100 py-3 rounded-3 shadow-sm transition-hover">
                                <i class="bi bi-plus-circle fs-3 d-block mb-1"></i>
                                <span>Buat Tiket</span>
                            </a>
                        </div>
                        @can('access-admin')
                        <div class="col-md-4">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger w-100 py-3 rounded-3 shadow-sm transition-hover">
                                <i class="bi bi-person-gear fs-3 d-block mb-1"></i>
                                <span>Admin Panel</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: SIDEBAR (4 Kolom) --}}
        <div class="col-lg-4">
            {{-- Izin Akses (Permissions) --}}
            <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 font-jakarta"><i class="bi bi-key me-2"></i>Izin Akses Anda</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Hak akses dinamis berdasarkan role Anda:</p>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 border-light d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-check-circle-fill text-success me-2"></i>Kelola Tiket Sendiri</span>
                            <span class="badge bg-success">✓</span>
                        </div>
                        
                        <div class="list-group-item px-0 border-light d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi @can('view-all-tickets') bi-check-circle-fill text-success @else bi-x-circle-fill text-danger @endcan me-2"></i>
                                Lihat Semua Tiket
                            </span>
                            @can('view-all-tickets') <span class="badge bg-success">✓</span> @else <span class="badge bg-secondary">✗</span> @endcan
                        </div>

                        <div class="list-group-item px-0 border-light d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi @if(Auth::user()->hasAnyRole(['admin', 'staff'])) bi-check-circle-fill text-success @else bi-x-circle-fill text-danger @endif me-2"></i>
                                Update Status Tiket
                            </span>
                            @if(Auth::user()->hasAnyRole(['admin', 'staff'])) <span class="badge bg-success">✓</span> @else <span class="badge bg-secondary">✗</span> @endif
                        </div>

                        <div class="list-group-item px-0 border-light d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi @can('access-admin') bi-check-circle-fill text-success @else bi-x-circle-fill text-danger @endcan me-2"></i>
                                Akses Menu Admin
                            </span>
                            @can('access-admin') <span class="badge bg-success">✓</span> @else <span class="badge bg-secondary">✗</span> @endcan
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info RBAC --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0 font-jakarta"><i class="bi bi-info-circle me-2"></i>Info RBAC</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Sistem ini menggunakan <b>Role-Based Access Control</b>. Coba login dengan akun berbeda untuk melihat perubahan izin secara real-time.</p>
                    <div class="mt-3">
                        <a href="{{ route('auth-lab.index') }}" class="btn btn-sm btn-light border w-100">
                            <i class="bi bi-book me-1"></i> Dokumentasi Lab
                        </a>
                    </div>
                </div>
            </div>

            {{-- Logout Button --}}
            <div class="mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 py-2 shadow-sm fw-bold">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout dari Sesi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover:hover {
        transform: translateY(-3px);
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
    }
</style>
@endsection