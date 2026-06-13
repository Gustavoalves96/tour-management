<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class FirstAccessPasswordController extends Controller
{
    public function edit(): View
    {
        return view('auth.first-access-password');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => $validated['password'], // criptografada pelo cast 'hashed'
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')
            ->with('status', 'Senha alterada com sucesso!');
    }
}
