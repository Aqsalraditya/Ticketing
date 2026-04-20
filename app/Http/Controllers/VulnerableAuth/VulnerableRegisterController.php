<?php

namespace App\Http\Controllers\VulnerableAuth;

use App\Http\Controllers\Controller;
use App\Models\VulnerableUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VulnerableRegisterController extends Controller
{

    public function create(): View
    {
        return view('vulnerable-auth.register', [
            'isSecure' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required',           
            'email' => 'required|email',    
            'password' => 'required',       
        ]);

        if (VulnerableUser::where('email', $request->email)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email sudah terdaftar.']);
        }

        $user = VulnerableUser::create([
            'name' => $request->name,       
            'email' => $request->email,
            'password' => $request->password, 
        ]);

        // ❌ VULNERABLE: Auto login tanpa session regeneration
        $request->session()->put('vulnerable_user', $user);
        $request->session()->put('vulnerable_logged_in', true);

        return redirect()->route('vulnerable.dashboard')
            ->with('success', 'Registrasi berhasil! Password tersimpan: ' . $request->password);
    }

    /**
     * Show registered users with plaintext passwords
     * (untuk demo betapa bahayanya plaintext storage)
     */
    public function showUsers(): View
    {
        // ❌ VULNERABLE: Menampilkan semua user dengan password!
        $users = VulnerableUser::all();

        return view('vulnerable-auth.show-users', [
            'users' => $users,
            'isSecure' => false,
        ]);
    }
}
