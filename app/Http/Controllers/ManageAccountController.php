<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class ManageAccountController extends Controller
{
    public function index()
    {
        // Ambil user aktif (role 1 dan 2)
        $users = User::whereIn('role', [1, 2])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // jumlah per halaman bisa disesuaikan


        // Ambil user yang sudah di-soft-delete
        $deletedUsers = User::onlyTrashed()
            ->whereIn('role', [1, 2])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        $pendingUsers = User::withoutGlobalScope('verified')
            ->where('is_verified', false)
            ->whereIn('role', [1, 2])
            ->with('identitas') // eager loading identitas
            ->orderBy('created_at', 'desc')
            ->paginate(10);
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

        return redirect()->back()->with('success', 'Role berhasil diperbarui.');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->back()->with('success', 'Akun berhasil direstore.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // soft delete

        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }

    public function verify($id)
    {
        $user = User::withoutGlobalScope('verified')->findOrFail($id);
        $user->is_verified = true;
        $user->save();

        return redirect()->back()->with('success', 'Akun berhasil diverifikasi.');
    }

    public function cancel($id)
    {
        // Tanpa global scope supaya bisa akses user yang belum diverifikasi
        $user = User::withoutGlobalScope('verified')->withTrashed()->findOrFail($id);

        // Hapus permanen
        $user->forceDelete();

        return redirect()->back()->with('success', 'Akun berhasil dibatalkan dan dihapus permanen.');
    }


}
