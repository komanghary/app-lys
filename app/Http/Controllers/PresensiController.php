<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPresensi;
use App\Models\TIdentitas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 1) {
            $presensis = TPresensi::with('user')
                ->where('identitas_id', $user->id)
                ->get();
        } else {
            $presensis = TPresensi::with('user')->get();
        }

        $sudahPresensi = TPresensi::where('identitas_id', $user->id)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();

        // Hitung jumlah identitas aktif dan yang sudah presensi
        $belumPresensiCount = null;

        if ($user->role != 1) {
            $totalIdentitas = TIdentitas::whereNull('deleted_at')->count();

            $sudahPresensiCount = TPresensi::whereDate('tanggal', now()->toDateString())
                ->distinct('identitas_id')
                ->count('identitas_id');

            $belumPresensiCount = $totalIdentitas - $sudahPresensiCount;
        }

        return view('presensi', [
            'presensis' => $presensis,
            'userRole' => $user->role,
            'sudahPresensi' => $sudahPresensi,
            'belumPresensiCount' => $belumPresensiCount
        ]);
    }
    public function store(Request $request)
    {
        $user = Auth::user();

        // Tentukan ID pegawai dan tanggal
        $identitasId = $user->role == 2 ? $request->input('identitas_id') : $user->id;
        $tanggal = $user->role == 2 ? $request->input('tanggal') : now()->toDateString();

        // Validasi input untuk manager
        if ($user->role == 2) {
            $request->validate([
                'identitas_id' => 'required|exists:users,id',
                'tanggal' => 'required|date',
                'status' => 'required|in:1,2,3',
                'keterangan' => 'nullable|string|max:255',
            ]);
        }

        // Cek apakah presensi sudah ada untuk user dan tanggal
        $existing = TPresensi::where('identitas_id', $identitasId)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Presensi sudah ada untuk tanggal tersebut.');
        }

        // Simpan presensi
        TPresensi::create([
            'identitas_id' => $identitasId,
            'tanggal' => $tanggal,
            'jam_masuk' => now()->toTimeString(),
            'status' => $user->role == 2 ? $request->input('status') : 1,
            'keterangan' => $user->role == 2 ? $request->input('keterangan') : null,
        ]);

        return redirect()->back()->with('success', 'Presensi berhasil ditambahkan.');
    }
    public function update(Request $request, $id)
    {
        $presensi = TPresensi::findOrFail($id);
        $presensi->status = $request->input('status');
        $presensi->save();

        return response()->json(['message' => 'Status updated.']);
    }


}
