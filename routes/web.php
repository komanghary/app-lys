<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagerTaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IdentitasController;
use App\Http\Controllers\UserTaskController;
use App\Http\Controllers\PresensiController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard Pegawai
    Route::get('/dashboard', [DashboardController::class, "index"])->name('dashboard');

    Route::get('/revisi', function () {
        return view('revisi');
    })->name('revisi');

    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi');

    // Route::get('/presensi', function () {
    //     return view('presensi');
    // })->name('presensi');
    // Route::get('/presensi', [PresensiController::class, 'showCalendar'])->name('presensi.calendar');
    // Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');

    Route::get('/identitas', [IdentitasController::class, 'index'])->name('identitas');

    Route::get('/task/list', [UserTaskController::class, "list"])->name('task.list');
    Route::get('/tasks/{id}/preview', [UserTaskController::class, 'preview'])->name('task.preview');
    Route::post('/task/upload/{id}', [UserTaskController::class, 'uploadFile'])->name('task.upload');


    // Manager Task
    Route::get('/task-manager', [ManagerTaskController::class, "index"])->name('task.manager.list')->middleware('role:2');
    Route::get('/task-manager/add', [ManagerTaskController::class, "add"])->name('task.manager.add')->middleware('role:2');
    Route::post('/task-manager/add', [ManagerTaskController::class, "store"])->name('task.manager.store')->middleware('role:2');
    Route::get('/task-manager/edit/{id}', [ManagerTaskController::class, "edit"])->name('task.manager.edit')->middleware('role:2');
    Route::post('/task-manager/edit/{id}', [ManagerTaskController::class, "update"])->name('task.manager.update')->middleware('role:2');
    Route::delete('/task-manager/delete/{id}', [ManagerTaskController::class, 'delete'])->name('task.manager.delete')->middleware('role:2');
    Route::post('/task/{id}/complete', [ManagerTaskController::class, 'markAsCompleted'])->name('task.complete')->middleware('role:2');
    Route::post('/task/{id}/revisi', [ManagerTaskController::class, 'revisi'])->name('task.revisi')->middleware('role:2');

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
