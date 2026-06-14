<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the account recovery view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an account recovery request — verify old credentials and set new username + password.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'last_username' => ['required', 'string'],
            'last_password' => ['required', 'string'],
            'new_username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
            'catatan' => ['required', 'string', 'max:1000'],
        ]);

        $user = User::where('username', $request->last_username)->first();

        if (! $user || ! Hash::check($request->last_password, $user->password)) {
            return back()->withInput($request->only('last_username'))
                ->withErrors(['last_username' => 'Username atau password lama tidak cocok.']);
        }

        $user->update([
            'username' => $request->new_username,
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('login')->with('status', 'Username dan password berhasil diubah. Silakan login dengan akun baru Anda.');
    }
}
