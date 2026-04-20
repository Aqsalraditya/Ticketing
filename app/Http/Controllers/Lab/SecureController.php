<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SecureController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Admin lihat semua, user biasa lihat milik sendiri
        if ($user->role === 'admin' || $user->role === 'staff') {
            $tickets = Ticket::with('user')->latest()->get();
        } else {
            $tickets = $user->tickets()->with('user')->latest()->get();
        }

        return view('bac-lab.secure.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        // ✅ SECURE: Cek hak akses (Policy) SEBELUM menampilkan data
        // Jika bukan pemiliknya/admin, Laravel 11 akan otomatis melempar 403 Forbidden!
        Gate::authorize('view', $ticket);

        $ticket->load('user');
        return view('bac-lab.secure.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        // ✅ SECURE
        Gate::authorize('update', $ticket);

        return view('bac-lab.secure.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        // ✅ SECURE
        Gate::authorize('update', $ticket);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        if (in_array(auth()->user()->role, ['admin', 'staff'])) {
            $statusValidation = $request->validate([
                'status' => 'sometimes|in:open,in_progress,resolved,closed',
            ]);
            $validated = array_merge($validated, $statusValidation);
        }

        $ticket->update($validated);

        return redirect()
            ->route('bac-lab.secure.tickets.show', $ticket)
            ->with('success', 'Ticket berhasil diupdate!');
    }

    public function destroy(Ticket $ticket)
    {
        // ✅ SECURE: Hanya role tertentu yang bisa hapus
        Gate::authorize('delete', $ticket);

        $ticket->delete();

        return redirect()
            ->route('bac-lab.secure.tickets.index')
            ->with('success', 'Ticket berhasil dihapus!');
    }
}