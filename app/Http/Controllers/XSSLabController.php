<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Comment;

class XSSLabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('xss-lab.index');

    }

        public function reflectedVulnerable(Request $request)
    {
        $searchQuery = $request->input('q', '');
        
        return view('xss-lab.vulnerable.reflected', [
            'searchQuery' => $searchQuery,
        ]);
    }


        public function reflectedSecure(Request $request)
    {
        $searchQuery = $request->input('q', '');
        
        return view('xss-lab.secure.reflected', [
            'searchQuery' => $searchQuery,
        ]);
    }



        public function storedVulnerable()
    {
        $comments = Comment::orderBy('created_at', 'desc')->get();
        $ticket = Ticket::first();
        
        return view('xss-lab.vulnerable.stored', [
            'comments' => $comments,
            'ticket' => $ticket,
        ]);
    }


        public function storedVulnerableStore(Request $request)
    {

        Comment::create([
            'ticket_id' => $request->ticket_id ?? 1,
            'author_name' => $request->author_name,
            // 'content' => $request->content
            'content' => $request->input('content'),
        ]);

        return redirect()->route('xss-lab.stored.vulnerable')
            ->with('success', 'Komentar berhasil ditambahkan!');
    }


        public function storedSecure()
    {
        $comments = Comment::orderBy('created_at', 'desc')->get();
        $ticket = Ticket::first();
        
        return view('xss-lab.secure.stored', [
            'comments' => $comments,
            'ticket' => $ticket,
        ]);
    }


        public function storedSecureStore(Request $request)
    {

        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'author_name' => 'required|string|max:100',
            'content' => 'required|string|max:1000',
        ]);

        Comment::create($validated);

        return redirect()->route('xss-lab.stored.secure')
               ->with('success', 'Komentar berhasil ditambahkan!');
    }


        public function domVulnerable()
    {
        return view('xss-lab.vulnerable.dom-based');
    }


        public function domSecure()
    {
        return view('xss-lab.secure.dom-based');
    }



        public function resetComments()
    {
        Comment::truncate();
        
        return redirect()->route('xss-lab.index')
            ->with('success', 'Semua komentar berhasil dihapus!');
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
