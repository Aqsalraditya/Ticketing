<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'isSecure' => true,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:rfc,dns', 
                'max:255',
                'unique:' . User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
                    ->min(8)         
                    ->letters()       
                    ->numbers()       
                    ->mixedCase()     
            ],
        ], [
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('tickets.index')
            ->with('success', 'Registrasi berhasil! Selamat datang di Secure Ticketing.');
    }
}

