<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class VulnerableController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets()->with('user')->latest()->get();
        return view('bac-lab.vulnerable.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with('user')->findOrFail($id);
        return view('bac-lab.vulnerable.tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('bac-lab.vulnerable.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'sometimes|in:open,in_progress,resolved,closed',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update($validated);

        return redirect()
            ->route('bac-lab.vulnerable.tickets.show', $ticket)
            ->with('success', 'Ticket berhasil diupdate!');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()
            ->route('bac-lab.vulnerable.tickets.index')
            ->with('success', 'Ticket berhasil dihapus!');
    }
}