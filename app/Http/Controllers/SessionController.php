<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Sorry, those credentials do not match.',
            ]);
        }

        request()->session()->regenerate();

        $isEmployer = Auth::user()->employer ? true : false;
        session(['is_employer' => $isEmployer]);

        if ($isEmployer) {
            return redirect()->route('dashboard');
        }

        return redirect('/');
    }

    public function destroy()
    {
        Auth::logout();
        request()->session()->flush();
        return redirect('/');
    }
}
