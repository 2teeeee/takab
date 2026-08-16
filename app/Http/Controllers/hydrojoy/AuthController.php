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
            'national_code' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'national_code.required' => __('auth.national_code_required'),
            'password.required'      => __('auth.password_required'),
        ]);

        if (Auth::attempt(
            [
                'national_code' => $credentials['national_code'],
                'password'      => $credentials['password'],
            ],
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            return redirect()->route('main.index');
        }

        return back()
            ->withErrors([
                'national_code' => __('auth.invalid_credentials'),
            ])
            ->onlyInput('national_code');
    }
}
