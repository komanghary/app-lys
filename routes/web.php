<?php

use App\Http\Controllers\ManagerTaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserTaskController;
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

    Route::get('/task/list', [UserTaskController::class, "list"])->name('task.list');

    Route::get('/task/complated', function () {
        return view('task.complated');
    })->name('task.complated');

    Route::get('/tasks/{id}/preview', [UserTaskController::class, 'preview'])->name('task.preview');
    Route::post('/task/upload/{id}', [UserTaskController::class, 'uploadFile'])->name('task.upload');

    // Manager
    Route::get('/task-manager', [ManagerTaskController::class, "index"])->name('task.manager.list');
    Route::get('/task-manager/add', [ManagerTaskController::class, "add"])->name('task.manager.add');
    Route::post('/task-manager/add', [ManagerTaskController::class, "store"])->name('task.manager.store');
    Route::get('/task-manager/edit/{id}', [ManagerTaskController::class, "edit"])->name('task.manager.edit');
    Route::post('/task-manager/edit/{id}', [ManagerTaskController::class, "update"])->name('task.manager.update');
    Route::get('/task-manager/delete/{id}', [ManagerTaskController::class, "delete"])->name('task.manager.delete');
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