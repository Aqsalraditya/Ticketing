@extends('layouts.app')

@section('title', 'Buat Tiket Baru')

@section('content')
<div class="container-fluid px-md-5">
    <div class="row g-0 shadow-lg rounded-4 overflow-hidden border bg-white" style="min-height: 80vh; max-height: 85vh;">
        
        {{-- PANEL KIRI: INFO & PETUNJUK --}}
        <div class="col-md-5 bg-light p-5 d-flex flex-column justify-content-center border-end">
            <div class="mb-4">
                <a href="{{ route('tickets.index') }}" class="btn btn-white shadow-sm rounded-pill px-4 mb-4 text-dark border">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                <h1 class="fw-bold display-6 mb-3">Sampaikan <span class="text-primary" style="color: var(--primary-soft) !important;">Kendala</span> Anda</h1>
                <p class="text-muted lead">Tim support kami siap membantu menyelesaikan masalah Anda secepat mungkin.</p>
            </div>

            <div class="mt-4">
                <h6 class="fw-bold mb-3 text-uppercase small text-muted letter-spacing-1">Keamanan & Validasi:</h6>
                <div class="d-flex mb-3">
                    <div class="me-3"><i class="bi bi-shield-check text-success"></i></div>
                    <p class="small mb-0 text-muted"><b>CSRF Protection:</b> Form ini dilindungi dari serangan Cross-Site Request Forgery.</p>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3"><i class="bi bi-lock text-success"></i></div>
                    <p class="small mb-0 text-muted"><b>Server Validation:</b> Input divalidasi ketat di sisi server (StoreTicketRequest).</p>
                </div>
                <div class="d-flex">
                    <div class="me-3"><i class="bi bi-regex text-success"></i></div>
                    <p class="small mb-0 text-muted"><b>XSS Sanitization:</b> Tag HTML berbahaya akan otomatis dibersihkan oleh sistem.</p>
                </div>
            </div>
        </div>

        {{-- PANEL KANAN: FORM --}}
        <div class="col-md-7 p-5 bg-white scrollable-panel d-flex flex-column">
            <div class="my-auto w-100">
                
                {{-- Global Error Display --}}
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm rounded-3 mb-4 border-0 border-start border-danger border-4">
                        <h6 class="fw-bold mb-2 small"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan pada input:</h6>
                        <ul class="mb-0 x-small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tickets.store') }}" method="POST" class="w-100">
                    @csrf
                    <div class="row g-4">
                        
                        {{-- Judul Tiket --}}
                        <div class="col-12">
                            <label for="title" class="form-label fw-bold small text-muted text-uppercase letter-spacing-1">Judul Tiket <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" 
                                class="form-control form-control-lg border-0 bg-light px-4 py-3 @error('title') is-invalid @enderror" 
                                value="{{ old('title') }}" placeholder="Apa kendala Anda?" required>
                            @error('title') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                        </div>

                        {{-- Prioritas --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase letter-spacing-1">Tingkat Prioritas <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="priority" id="low" value="low" required {{ old('priority') == 'low' ? 'checked' : '' }}>
                                <label class="btn btn-outline-light border text-muted flex-grow-1 py-3 rounded-3" for="low">Low</label>

                                <input type="radio" class="btn-check" name="priority" id="medium" value="medium" {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }}>
                                <label class="btn btn-outline-light border text-muted flex-grow-1 py-3 rounded-3" for="medium">Medium</label>

                                <input type="radio" class="btn-check" name="priority" id="high" value="high" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                <label class="btn btn-outline-light border text-muted flex-grow-1 py-3 rounded-3" for="high">High</label>
                            </div>
                            @error('priority') <div class="text-danger x-small mt-2">{{ $message }}</div> @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="col-12">
                            <label for="category" class="form-label fw-bold small text-muted text-uppercase letter-spacing-1">Kategori (Opsional)</label>
                            <input type="text" name="category" id="category" 
                                class="form-control border-0 bg-light px-4 py-3 @error('category') is-invalid @enderror" 
                                value="{{ old('category') }}" placeholder="Contoh: Teknis, Akun, Pembayaran">
                            @error('category') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold small text-muted text-uppercase letter-spacing-1">Deskripsi Masalah <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" 
                                class="form-control border-0 bg-light px-4 py-3 @error('description') is-invalid @enderror" 
                                rows="4" placeholder="Ceritakan detail masalahnya di sini..." required>{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow-lg border-0 w-100 w-md-auto fw-bold">
                                Kirim Tiket Sekarang <i class="bi bi-send ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 992px) {
        body { overflow: hidden; height: 100vh; }
        main.container-fluid { display: flex; align-items: center; justify-content: center; height: calc(100vh - 100px); }
        .scrollable-panel { max-height: 85vh; overflow-y: auto; }
    }
    .form-control:focus { background-color: #fff !important; box-shadow: 0 0 0 0.25rem rgba(110, 191, 185, 0.25); border: 1px solid var(--primary-soft) !important; }
    .btn-check:checked + .btn-outline-light { background-color: var(--primary-soft) !important; color: white !important; border-color: var(--primary-soft) !important; }
    .x-small { font-size: 0.75rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
</style>
@endsection