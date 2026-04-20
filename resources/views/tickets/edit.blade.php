@extends('layouts.app')

@section('title', 'Edit Tiket #' . $ticket->id)

@section('content')
    <div class="container-fluid px-md-5">
        <div class="row g-0 shadow-lg rounded-4 overflow-hidden border bg-white" style="min-height: 85vh;">

            {{-- PANEL KIRI: INFO --}}
            <div class="col-md-5 bg-light p-5 d-flex flex-column border-end position-relative">
                <div class="mb-auto">
                    <a href="{{ route('tickets.show', $ticket) }}"
                        class="btn btn-white shadow-sm rounded-pill px-4 mb-4 text-dark border bg-white">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <h1 class="fw-bold display-6 mb-2">Update <span class="text-primary"
                            style="color: var(--primary-soft) !important;">Tiket</span></h1>
                    <p class="text-muted small">ID: #{{ $ticket->id }} &bull; Dibuat {{ $ticket->created_at->diffForHumans() }}</p>

                    <div class="mt-5">
                        <div class="p-4 rounded-4 bg-white shadow-sm border mb-3">
                            <label class="small fw-bold text-muted d-block mb-2 uppercase letter-spacing-1">STATUS SAAT INI</label>
                            <span class="badge {{ $ticket->status_badge }} px-3 py-2 rounded-pill text-capitalize shadow-sm">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </span>
                        </div>

                        <div class="p-4 rounded-4 bg-white shadow-sm border mb-3">
                            <label class="small fw-bold text-muted d-block mb-1 uppercase letter-spacing-1">INFORMASI ROLE</label>
                            <p class="small mb-1 text-dark">Login sebagai: <strong>{{ Auth::user()->name }}</strong></p>
                            <span class="badge bg-secondary rounded-pill px-2">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                    </div>
                </div>

                {{-- ZONA BERBAHAYA (Hanya muncul jika punya izin delete) --}}
                @can('delete', $ticket)
                <div class="mt-4 pt-4 border-top">
                    <label class="small fw-bold text-danger d-block mb-2 uppercase letter-spacing-1">ZONA BERBAHAYA</label>
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST"
                        onsubmit="return confirm('Hapus permanen tiket ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none">
                            <i class="bi bi-trash3 me-1"></i> Hapus tiket ini secara permanen
                        </button>
                    </form>
                </div>
                @endcan
            </div>

            {{-- PANEL KANAN: FORM EDIT --}}
            <div class="col-md-7 p-5 bg-white d-flex flex-column justify-content-center scrollable-panel">
                
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm rounded-3 mb-4 border-0 border-start border-danger border-4">
                        <h6 class="fw-bold mb-2 small"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan:</h6>
                        <ul class="mb-0 x-small">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="w-100">
                    @csrf @method('PUT')
                    <div class="row g-4">
                        
                        <div class="col-12">
                            <label for="title" class="form-label fw-bold small text-muted uppercase">JUDUL TIKET</label>
                            <input type="text" name="title" id="title"
                                class="form-control form-control-lg border-0 bg-light px-4 py-3 @error('title') is-invalid @enderror"
                                value="{{ old('title', $ticket->title) }}" required>
                        </div>

                        {{-- Input Khusus Admin/Staff --}}
                        @if(auth()->user()->hasAnyRole(['admin', 'staff']))
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold small text-muted uppercase">UBAH STATUS</label>
                            <select name="status" id="status" class="form-select border-0 bg-light px-4 py-3 shadow-none">
                                <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <label for="priority" class="form-label fw-bold small text-muted uppercase">UBAH PRIORITAS</label>
                            <select name="priority" id="priority" class="form-select border-0 bg-light px-4 py-3 shadow-none">
                                <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-bold small text-muted uppercase">DESKRIPSI LENGKAP</label>
                            <textarea name="description" id="description" 
                                class="form-control border-0 bg-light px-4 py-3 @error('description') is-invalid @enderror" 
                                rows="5" required>{{ old('description', $ticket->description) }}</textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow-lg border-0 flex-grow-1 fw-bold">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                                </button>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-light border btn-lg px-4 py-3">Batal</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @media (min-width: 992px) {
            body { overflow: hidden; height: 100vh; }
            main.container-fluid { display: flex; align-items: center; justify-content: center; height: calc(100vh - 100px); }
            .scrollable-panel { max-height: 85vh; overflow-y: auto; }
        }
        .form-control:focus, .form-select:focus { background-color: #f1f3f5 !important; border: 1px solid var(--primary-soft) !important; box-shadow: none; }
        .letter-spacing-1 { letter-spacing: 1px; }
        .uppercase { text-transform: uppercase; }
        .x-small { font-size: 0.75rem; }
    </style>
@endsection