<?php

namespace App\Http\Controllers\hydrojoy;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if(Auth::check())
            return redirect()->route('admin.index');

        return view('hydrojoy.auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'national_code' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('admin.index', absolute: false));
        }

        return back()->withErrors([
            'national_code' => 'کاربری با این مشخصات وجود ندارد.',
        ])->onlyInput('national_code');
    }
}
