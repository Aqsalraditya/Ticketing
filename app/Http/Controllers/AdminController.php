<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use AuthorizesRequests;

    public function dashboard()
    {
        $this->authorize('access-admin');

        $stats = [
            'total_users' => User::count(),
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::whereIn('status', ['open', 'in_progress'])->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
            'unassigned_tickets' => Ticket::whereNull('assigned_to')->count(),
        ];

        $recentTickets = Ticket::with(['user', 'assignee'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTickets'));
    }

    public function users()
    {
        $this->authorize('manage-users');

        $users = User::withCount('tickets')
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function allTickets(Request $request)
    {
        $this->authorize('access-admin');

        $query = Ticket::with(['user', 'assignee']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->boolean('unassigned')) {
            $query->whereNull('assigned_to');
        }

        $tickets = $query->latest()->paginate(10);

        $staffList = User::where('role', 'staff')->get();

        return view('admin.tickets', compact('tickets', 'staffList'));
    }

    public function assignTicket(Request $request, Ticket $ticket)
    {
        $this->authorize('assign-tickets');

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $staff = User::findOrFail($request->assigned_to);
        if (! $staff->isStaff() && ! $staff->isAdmin()) {
            return back()->with('error', 'Hanya staff atau admin yang bisa di-assign.');
        }

        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress',
        ]);

        return back()->with('success', 'Ticket berhasil di-assign ke '.$staff->name);
    }

    public function reports()
    {
        $ticketStats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
        ];

        $priorityStats = [
            'high' => Ticket::where('priority', 'high')->count(),
            'medium' => Ticket::where('priority', 'medium')->count(),
            'low' => Ticket::where('priority', 'low')->count(),
        ];

        $userStats = null;
        if (auth()->user()->isAdmin()) {
            $userStats = [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'staff' => User::where('role', 'staff')->count(),
                'users' => User::where('role', 'user')->count(),
            ];
        }

        $staffPerformance = User::whereIn('role', ['admin', 'staff'])
            ->withCount(['assignedTickets as resolved_count' => function ($query) {
                $query->whereIn('status', ['resolved', 'closed']);
            }])
            ->withCount('assignedTickets as total_assigned')
            ->get();

        return view('admin.reports', compact(
            'ticketStats',
            'priorityStats',
            'userStats',
            'staffPerformance'
        ));
    }
}