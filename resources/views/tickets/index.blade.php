@extends('layouts.app')

@section('title', 'Daftar Tiket Support')

@section('content')
<div class="container-fluid px-4 py-4">
    
    {{-- HEADER & TOMBOL TAMBAH --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold h4 mb-1">
                <i class="bi bi-ticket-detailed me-2 text-primary"></i>
                {{ auth()->user()->isUser() ? 'Tiket Saya' : 'Semua Tiket Support' }}
            </h2>
            <p class="text-muted small mb-0">
                @if(auth()->user()->isAdmin())
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 shadow-sm">👑 Admin</span> Kelola dan pantau seluruh tiket sistem.
                @elseif(auth()->user()->isStaff())
                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 shadow-sm">⚡ Staff</span> Kelola tiket yang ditugaskan kepada Anda.
                @else
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 shadow-sm">👤 User</span> Pantau status permintaan bantuan Anda.
                @endif
            </p>
        </div>
        
        @can('create', App\Models\Ticket::class)
        <a href="{{ route('tickets.create') }}" class="btn btn-primary px-4 py-2 shadow-sm rounded-pill fw-medium">
            <i class="bi bi-plus-lg me-2"></i>Buat Tiket Baru
        </a>
        @endcan
    </div>

    {{-- FILTER SECTION (Admin & Staff Only) --}}
    @can('view-all-tickets')
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body bg-light bg-opacity-50 py-3">
            <form action="{{ route('tickets.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <span class="small fw-bold text-muted text-uppercase me-2"><i class="bi bi-funnel"></i> Filter:</span>
                </div>
                <div class="col-md-3">
                    <select name="status" id="status" class="form-select form-select-sm border-0 shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary px-3">Terapkan</button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-white border px-3">Reset</a>
                </div>
            </form>
        </div>
    </div>
    @endcan

    {{-- KOTAK STATUS (CARDS) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-4">
                        <i class="bi bi-envelope-open text-warning fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold mb-1">OPEN</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ \App\Models\Ticket::where('status', 'open')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-4">
                        <i class="bi bi-gear-wide-connected text-info fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">In Progress</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ \App\Models\Ticket::where('status', 'in_progress')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-4">
                        <i class="bi bi-check2-circle text-success fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold mb-1">CLOSED</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ \App\Models\Ticket::where('status', 'closed')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0 text-muted small fw-bold">JUDUL TIKET</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-center">STATUS</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-center">PRIORITAS</th>
                            <th class="py-3 border-0 text-muted small fw-bold">DIBUAT OLEH</th>
                            <th class="px-4 py-3 border-0 text-muted small fw-bold text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center mb-1">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-decoration-none fw-bold text-dark me-2">
                                        {{ $ticket->title }}
                                    </a>
                                    @if($ticket->assigned_to === auth()->id())
                                        <span class="badge bg-info text-dark x-small py-1"><i class="bi bi-pin-angle-fill me-1"></i>Assigned to you</span>
                                    @endif
                                </div>
                                <span class="text-muted x-small d-block mb-1">{{ Str::limit($ticket->description, 70) }}</span>
                                <span class="x-small text-muted italic"><i class="bi bi-clock me-1"></i>{{ $ticket->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $ticket->status_badge }} rounded-pill px-3 py-2 fw-medium text-capitalize">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $ticket->priority_badge }} bg-opacity-10 text-uppercase x-small py-1 px-2 border" style="font-size: 0.65rem;">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="small fw-bold text-dark">{{ $ticket->user->name ?? 'Unknown' }}</span>
                                    @if($ticket->assignee)
                                        <span class="x-small text-primary"><i class="bi bi-person-badge"></i> To: {{ $ticket->assignee->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 text-end">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden border">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-white btn-sm px-3 border-end" title="Lihat Detail">
                                        <i class="bi bi-eye text-info"></i>
                                    </a>
                                    @can('update', $ticket)
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-white btn-sm px-3 border-end" title="Edit">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </a>
                                    @endcan
                                    @can('delete', $ticket)
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm px-3" title="Hapus">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3" alt="Kosong">
                                <p class="text-muted mb-0">Tidak ada tiket yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGINASI --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $tickets->links() }}
    </div>

    {{-- RBAC INFO FOOTER --}}
    <div class="card mt-5 border-0 shadow-sm rounded-4 bg-light">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i>Ringkasan Hak Akses: {{ ucfirst(auth()->user()->role) }}</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <i class="bi @can('create', App\Models\Ticket::class) bi-check-circle-fill text-success @else bi-x-circle-fill text-danger @endcan me-2"></i>
                        <span class="small">Buat Tiket Baru</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <i class="bi @can('view-all-tickets') bi-check-circle-fill text-success @else bi-x-circle-fill text-danger @endcan me-2"></i>
                        <span class="small">Lihat Semua Tiket</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <i class="bi @can('assign-tickets') bi-check-circle-fill text-success @else bi-x-circle-fill text-danger @endcan me-2"></i>
                        <span class="small">Assign Staff</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <i class="bi @can('access-admin') bi-check-circle-fill text-success @else bi-x-circle-fill text-danger @endcan me-2"></i>
                        <span class="small">Akses Admin Panel</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .table thead th { letter-spacing: 0.5px; }
    .table tbody tr { transition: all 0.2s ease-in-out; }
    .table tbody tr:hover { background-color: #f8f9fa !important; transform: translateY(-1px); }
    .x-small { font-size: 0.75rem; }
    .italic { font-style: italic; }
    .btn-white { background: white; }
    .btn-white:hover { background: #f8f9fa; }
</style>
@endsection