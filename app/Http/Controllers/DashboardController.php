<?php

namespace App\Http\Controllers;

use App\Models\TTask;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
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
            'status' => 1,
            'user_id' => Auth::user()->id
        ])->count();

        $selesai = TTask::where([
            'status' => 2,
            'revisi' => 0,
            'user_id' => Auth::user()->id
        ])->count();

        return view('dashboard', compact('progres', 'revisi', 'review', 'selesai'));
    }
}