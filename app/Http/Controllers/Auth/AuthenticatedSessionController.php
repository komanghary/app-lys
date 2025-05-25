<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\LogActivity;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah. atau belum di verifikasi Admin',
            ])->onlyInput('email');
        }

        // Login dulu
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Baru arahkan sesuai role
        switch ($user->role) {
            case 0:
                LogActivity::add('Login Berhasil', 'User login dengan email ' . $user->email);
                return redirect()->route('dashboard.admin');
            case 1:
                LogActivity::add('Login Berhasil', 'User login dengan email ' . $user->email);
                return redirect()->route('dashboard');
            default:
                LogActivity::add('Login Berhasil', 'User login dengan email ' . $user->email);
                return redirect()->route('dashboard.manager');
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
