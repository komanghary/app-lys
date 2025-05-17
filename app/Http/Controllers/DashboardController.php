<?php

namespace App\Http\Controllers;

use App\Models\TTask;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // dd(Auth::user()->role);
        if (Auth::user()->role == '0') {
            return view('dashboard-admin', compact('progres', 'revisi', 'review', 'selesai'));
        } elseif (Auth::user()->role == '2') {
            $progres = TTask::where('status', 0)
                ->where('revisi', '=!', 0)
                ->count();

            $revisi = TTask::where("revisi", "<>", 0)
                ->where("status", "<>", 2)->count();

            $review = TTask::where('status', 1)
                ->whereNotIn('id', function ($query) {
                    $query->select('revisi')
                        ->from('t_tasks')
                        ->whereNotNull('revisi');
                })
                ->count();

            $selesai = TTask::where([
                'status' => 2,
                'revisi' => 0
            ])->count();
            return view('dashboard-manager', compact('progres', 'revisi', 'review', 'selesai'));
        } else {
            $progres = TTask::where([
                'status' => 0,
                'user_id' => Auth::user()->id
            ])->count();

            $revisi = TTask::where([
                'user_id' => Auth::user()->id
            ])
                ->where("revisi", "<>", 0)
                ->where("status", "<>", 2)->count();

            $review = TTask::where([
                ['status', '=', 1],
                ['user_id', '=', Auth::user()->id],
            ])
                ->whereColumn('id', '<>', 'revisi')  // Membandingkan kolom 'id' dengan 'revisi'
                ->count();

            $selesai = TTask::where([
                'status' => 2,
                'revisi' => 0,
                'user_id' => Auth::user()->id
            ])->count();
            return view('dashboard', compact('progres', 'revisi', 'review', 'selesai'));
        }
    }
}
