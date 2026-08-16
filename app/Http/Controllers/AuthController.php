<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Sms\NikSmsService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function register(Request $request, NikSmsService $sms): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => [
                'required',
                'string',
                'max:11',
                'unique:users,mobile',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
        ]);

        $sms->sendSingle(
            $validated['mobile'],
            'به جمع تک آبی ها خوش آمدید.'
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('main.index');
    }

    public function login(): Response
    {
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'mobile' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'mobile.required' => __('auth.mobile_required'),
            'password.required' => __('auth.password_required'),
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('admin.index');
        }

        return back()
            ->withErrors([
                'mobile' => __('auth.invalid_credentials'),
            ])
            ->onlyInput('mobile');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
