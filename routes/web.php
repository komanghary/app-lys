<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard Pegawai
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/revisi', function () {
        return view('revisi');
    })->name('revisi');

    Route::get('/presensi', function () {
        return view('presensi');
    })->name('presensi');

    Route::get('/task/list', [TaskController::class, "list"])->name('task.list');

    Route::get('/task/complated', function () {
        return view('task.complated');
    })->name('task.complated');
});



Route::get('/dashboard-admin', function () {
    return view('dashboard-admin'); // View khusus untuk role 0
})->name('dashboard.admin')->middleware('auth');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';