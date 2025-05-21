<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;

class LogActivityController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        return view('log', compact('logs'));
    }
}
