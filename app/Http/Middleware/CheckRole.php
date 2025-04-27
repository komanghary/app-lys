<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Ambil role pengguna yang sedang login
        $userRole = Auth::user()->role;

        // Jika role pengguna tidak ada dalam daftar roles yang diperbolehkan
        if (!in_array($userRole, $roles)) {
            return redirect('/'); // Redirect ke halaman utama atau halaman lain yang sesuai
        }

        // Jika role sesuai, lanjutkan ke route berikutnya
        return $next($request);
    }
}
