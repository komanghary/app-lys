<?php

namespace App\Http\Controllers;

use App\Models\TPresensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Ambil data presensi untuk bulan dan tahun yang dipilih
        $presensi = TPresensi::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        // Kirim data ke view
        return view('presensi', compact('presensi', 'month', 'year'));
    }
}