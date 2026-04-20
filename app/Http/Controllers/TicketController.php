<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Ticket::class);

        $user = $request->user();
        $query = Ticket::with(['user', 'assignee']);

        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isStaff()) {
            $query->orderByRaw('CASE WHEN assigned_to = ? THEN 0 ELSE 1 END', [$user->id]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        $this->authorize('create', Ticket::class);

        return view('tickets.create');
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->authorize('create', Ticket::class);

        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $validatedData['status'] = 'open';

        $ticket = Ticket::create($validatedData);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil dibuat!');
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load(['user', 'assignee']);

        $staffList = [];
        if (Gate::allows('assign-tickets')) {
            $staffList = User::whereIn('role', ['staff', 'admin'])->get();
        }

        return view('tickets.show', compact('ticket', 'staffList'));
    }

    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);

        return view('tickets.edit', compact('ticket'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $ticket->update($request->validated());

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil diperbarui!');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus!');
    }

    public function updateStatus(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('changeStatus', $ticket);

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->update($validated);

        return back()->with('success', 'Status ticket berhasil diupdate!');
    }

    public function assign(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('assign', $ticket);

        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update($validated);

        $message = $validated['assigned_to']
            ? 'Ticket berhasil di-assign!'
            : 'Assignment ticket berhasil dihapus!';

        return back()->with('success', $message);
    }

    // Fungsi dari kode aslimu tetap dipertahankan
    public function vulnerableSearch(Request $request)
    {
        $query = $request->query('q');
        
        /** @var view-string $view */
        $view = 'vulnerable.search';
        
        return view($view, compact('query'));
    }
}