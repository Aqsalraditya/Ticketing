@extends('layouts.app')

@section('title', 'Detail Tiket #' . $ticket->id)

@push('styles')
    <style>
        .letter-spacing-1 { letter-spacing: 1px; }
        .uppercase { text-transform: uppercase; }
        .bg-light-soft { background-color: #f8fbfb !important; }
        .comment-card { transition: all 0.2s ease; border-radius: 16px !important; }
        .comment-card:hover { background-color: #f8f9fa; transform: translateX(5px); }
        .sticky-sidebar { position: sticky; top: 1rem; }
        .x-small { font-size: 0.75rem; }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-md-5 py-4">
        <div class="row g-0 shadow-lg rounded-4 overflow-hidden border bg-white" style="min-height: 85vh;">

            {{-- PANEL KIRI: METADATA & INFO --}}
            <div class="col-md-4 bg-light p-4 p-lg-5 border-end">
                <div class="sticky-sidebar">
                    <div class="mb-4">
                        <a href="{{ route('tickets.index') }}" class="btn btn-white shadow-sm rounded-pill px-4 mb-4 text-dark border bg-white">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <div class="mb-3">
                            <span class="text-muted small fw-bold letter-spacing-1 d-block mb-1">TIKET #{{ $ticket->id }}</span>
                            <h1 class="fw-bold h3 mb-3 text-dark">{{ $ticket->title }}</h1>
                        </div>
                    </div>

                    <div class="mb-3 p-4 rounded-4 bg-white shadow-sm border">
                        <label class="small fw-bold text-muted d-block mb-2 uppercase letter-spacing-1">STATUS & PRIORITAS</label>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge {{ $ticket->status_badge }} px-3 py-2 rounded-pill text-capitalize shadow-sm">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </span>
                            <span class="badge {{ $ticket->priority_badge }} px-3 py-2 rounded-pill text-capitalize shadow-sm">
                                {{ $ticket->priority }} Priority
                            </span>
                        </div>
                    </div>

                    {{-- Info Penugasan (Assignee) --}}
                    <div class="mb-3 p-4 rounded-4 bg-white shadow-sm border">
                        <label class="small fw-bold text-muted d-block mb-3 uppercase letter-spacing-1">PETUGAS TERKAIT</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="bi bi-person-badge-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold small text-dark">{{ $ticket->assignee->name ?? 'Belum Ditugaskan' }}</h6>
                                <small class="text-muted x-small">Assigned Staff</small>
                            </div>
                        </div>
                    </div>

                    {{-- Pengirim --}}
                    <div class="mb-4 p-4 rounded-4 bg-white shadow-sm border">
                        <label class="small fw-bold text-muted d-block mb-3 uppercase letter-spacing-1">PELAPOR</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="bi bi-person-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold small text-dark">{{ $ticket->user->name ?? 'Unknown User' }}</h6>
                                <small class="text-muted x-small">Dibuat {{ $ticket->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Security Badge Info --}}
                    <div class="p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-20 small">
                        <label class="fw-bold text-success d-block mb-1 x-small uppercase letter-spacing-1">
                            <i class="bi bi-shield-lock-fill me-1"></i> Data Protection
                        </label>
                        <span class="x-small text-dark opacity-75">Tiket ini dilindungi oleh sistem otorisasi RBAC dan enkripsi SSL.</span>
                    </div>
                </div>
            </div>

            {{-- PANEL KANAN: KONTEN --}}
            <div class="col-md-8 p-4 p-lg-5 bg-white d-flex flex-column">

                {{-- DESKRIPSI UTAMA --}}
                <div class="mb-5">
                    <label class="form-label fw-bold small text-muted uppercase letter-spacing-1 mb-3">Rincian Permasalahan</label>
                    <div class="p-4 rounded-4 bg-light bg-opacity-50 border-0 text-dark" style="line-height: 1.8; font-size: 1.05rem;">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>
                </div>

                {{-- AKSI DINAMIS BERDASARKAN IZIN --}}
                <div class="mb-5 pb-4 border-bottom">
                    <div class="row g-4">
                        {{-- Quick Controls (Staff/Admin) --}}
                        <div class="col-lg-7">
                            <label class="form-label fw-bold small text-muted uppercase mb-3 d-block">Kontrol Status</label>
                            <div class="d-flex flex-wrap gap-2">
                                @can('update', $ticket)
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-primary px-3 rounded-3 shadow-sm border-0">
                                        <i class="bi bi-pencil-square me-2"></i>Edit
                                    </a>
                                @endcan

                                @can('changeStatus', $ticket)
                                    @if ($ticket->status !== 'in_progress')
                                        <form action="{{ route('tickets.update-status', $ticket) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="btn btn-outline-info px-3 rounded-3 fw-bold">Proses</button>
                                        </form>
                                    @endif
                                    @if ($ticket->status !== 'closed')
                                        <form action="{{ route('tickets.update-status', $ticket) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="closed">
                                            <button type="submit" class="btn btn-outline-success px-3 rounded-3 fw-bold">Tutup</button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </div>

                        {{-- Admin Assignment --}}
                        @can('assign-tickets')
                        <div class="col-lg-5">
                            <label class="form-label fw-bold small text-muted uppercase mb-3 d-block">Tugaskan Petugas</label>
                            <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="input-group">
                                @csrf @method('PATCH')
                                <select name="assigned_to" class="form-select form-select-sm border-light bg-light">
                                    <option value="">-- Lepas --</option>
                                    @foreach($staffList ?? [] as $staff)
                                        <option value="{{ $staff->id }}" {{ $ticket->assigned_to == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-dark btn-sm px-3">Set</button>
                            </form>
                        </div>
                        @endcan
                    </div>
                </div>

                {{-- KOMENTAR SECTION --}}
                <div class="mt-2">
                    <h5 class="fw-bold mb-4 text-dark d-flex align-items-center">
                        <i class="bi bi-chat-left-text me-2 text-primary"></i> Diskusi Internal
                        <span class="badge bg-light text-primary border ms-2 rounded-pill small">{{ $ticket->comments->count() }}</span>
                    </h5>

                    {{-- Form Komentar --}}
                    <div class="mb-5 p-4 rounded-4 bg-light border-0">
                        <form action="{{ route('comments.store', $ticket) }}" method="POST">
                            @csrf
                            <textarea name="content" id="content" rows="3"
                                class="form-control rounded-4 border-0 shadow-sm mb-3 px-4 py-3 @error('content') is-invalid @enderror"
                                placeholder="Tambahkan tanggapan atau solusi..." required minlength="3" maxlength="2000">{{ old('content') }}</textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="x-small text-muted" id="charCounter">2000 karakter tersisa</span>
                                <button type="submit" class="btn btn-dark px-4 rounded-pill shadow-sm">
                                    Kirim Balasan <i class="bi bi-send-fill ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- List Komentar --}}
                    <div class="d-flex flex-column gap-3 mb-5">
                        @forelse($ticket->comments as $comment)
                            <div class="comment-card p-4 border bg-white shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
                                            style="width: 40px; height: 40px; background-color: var(--primary-soft) !important;">
                                            <span class="fw-bold">{{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold small">{{ $comment->user->name ?? 'Unknown User' }}
                                                @if ($comment->user_id === $ticket->user_id)
                                                    <span class="badge bg-info bg-opacity-10 text-info ms-1 x-small border border-info border-opacity-25">Author</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted x-small">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    @if (auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Hapus komentar?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    @endif
                                </div>
                                <div class="ps-5 text-dark small" style="line-height: 1.6;">
                                    {!! nl2br(e($comment->content)) !!}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 bg-light rounded-4 border-dashed border">
                                <i class="bi bi-chat-square-dots display-4 text-muted opacity-25"></i>
                                <p class="mt-3 mb-0 text-muted small fw-bold">Belum ada diskusi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('content');
            const counter = document.getElementById('charCounter');
            const maxLength = 2000;

            if (textarea && counter) {
                textarea.addEventListener('input', function() {
                    const remaining = maxLength - this.value.length;
                    counter.textContent = `${remaining} karakter tersisa`;
                    counter.className = remaining < 100 ? 'x-small text-danger fw-bold' : 'x-small text-muted';
                });
            }
        });
    </script>
@endpush