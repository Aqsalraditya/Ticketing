<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class DemoBladeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('demo-blade.index');
    }



    public function directives()
    {
        // Data untuk demo
        $user = (object) [
            'name' => 'Demo User',
            'role' => 'admin', // Coba ganti: 'admin', 'moderator', 'guest'
            'isAdmin' => fn() => true,
            'isModerator' => fn() => false,
            'isGuest' => fn() => false,
        ];

        // Data tiket untuk demo loop
        $tickets = Ticket::take(5)->get();

        // Jika belum ada tiket, buat data dummy
        if ($tickets->isEmpty()) {
            $tickets = collect([
                (object) ['id' => 1, 'title' => 'Bug Login', 'status' => 'open', 'priority' => 'high'],
                (object) ['id' => 2, 'title' => 'Request Fitur', 'status' => 'in_progress', 'priority' => 'medium'],
                (object) ['id' => 3, 'title' => 'Typo di About', 'status' => 'closed', 'priority' => 'low'],
                (object) ['id' => 4, 'title' => 'Error Upload', 'status' => 'open', 'priority' => 'high'],
                (object) ['id' => 5, 'title' => 'Dark Mode', 'status' => 'open', 'priority' => 'low'],
            ]);
        }

        // Data untuk nested loop
        $categories = collect([
            (object) [
                'name' => 'Bug Reports',
                'items' => collect([
                    (object) ['name' => 'Login Bug'],
                    (object) ['name' => 'Upload Error'],
                ])
            ],
            (object) [
                'name' => 'Feature Requests',
                'items' => collect([
                    (object) ['name' => 'Dark Mode'],
                    (object) ['name' => 'Export PDF'],
                    (object) ['name' => 'Mobile App'],
                ])
            ],
        ]);

        return view('demo-blade.directives', compact('user', 'tickets', 'categories'));
    }


        public function components()
    {
        $ticket = Ticket::first() ?? (object) [
            'id' => 1,
            'title' => 'Sample Ticket',
            'description' => 'Ini adalah deskripsi tiket untuk demo component',
            'status' => 'open',
            'priority' => 'high',
            'created_at' => now(),
        ];

        return view('demo-blade.components', compact('ticket'));
    }

        public function includes()
    {
        $tickets = Ticket::take(5)->get();
        
        if ($tickets->isEmpty()) {
            $tickets = collect([
                (object) ['id' => 1, 'title' => 'Bug Login', 'status' => 'open', 'priority' => 'high'],
                (object) ['id' => 2, 'title' => 'Request Fitur', 'status' => 'in_progress', 'priority' => 'medium'],
            ]);
        }

        $emptyTickets = collect([]);

        return view('demo-blade.includes', compact('tickets', 'emptyTickets'));
    }


        public function stacks()
    {
        return view('demo-blade.stacks');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
