<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;

class ManageAccountController extends Controller
{
    public function index()
    {
        // Ambil user aktif (role 1 dan 2)
        $users = User::whereIn('role', [1, 2])
            ->orderBy('created_at', 'desc')
            ->get(); // jumlah per halaman bisa disesuaikan


        // Ambil user yang sudah di-soft-delete
        $deletedUsers = User::onlyTrashed()
            ->whereIn('role', [1, 2])
            ->orderBy('deleted_at', 'desc')
            ->get();

        $pendingUsers = User::withoutGlobalScope('verified')
            ->where('is_verified', false)
            ->whereIn('role', [1, 2])
            ->with('identitas') // eager loading identitas
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($pendingUsers);

        return view('manage-account', compact('users', 'deletedUsers', 'pendingUsers'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:1,2',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        LogActivity::add('Edit Role', 'Mengubah role user ID #' . $id . ' menjadi ' . $request->role);

        return redirect()->back()->with('success', 'Role berhasil diperbarui.');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        LogActivity::add('Restore Akun', 'Merestore user ID #' . $id);
        return redirect()->back()->with('success', 'Akun berhasil direstore.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // soft delete
        LogActivity::add('Hapus Akun', 'Menghapus user ID #' . $id);

        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }

    public function verify($id)
    {
        $user = User::withoutGlobalScope('verified')->findOrFail($id);
        $user->is_verified = true;
        $user->save();
        LogActivity::add('Verifikasi Akun', 'Memverifikasi user ID #' . $id);

        return redirect()->back()->with('success', 'Akun berhasil diverifikasi.');
    }

    public function cancel($id)
    {
        // Tanpa global scope supaya bisa akses user yang belum diverifikasi
        $user = User::withoutGlobalScope('verified')->withTrashed()->findOrFail($id);

        // Hapus permanen
        $user->forceDelete();
        LogActivity::add('Cancel Verifikasi', 'Membatalkan verifikasi user ID #' . $id);
        return redirect()->back()->with('success', 'Akun berhasil dibatalkan dan dihapus permanen.');
    }


}
