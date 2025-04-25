<?php

namespace App\Http\Controllers;
use App\Models\TIdentitas;
use App\Models\User;
use Illuminate\Http\Request;


class IdentitasController extends Controller
{
    public function index()
    {
        $identitas = TIdentitas::with('user') // Memuat relasi user
            ->whereHas('user', function ($query) {
                $query->where('role', 1); // Hanya user dengan role 1
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Menampilkan 10 data per halaman

        return view('identitas', compact('identitas'));
    }

}
