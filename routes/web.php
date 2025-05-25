<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\ManagerTaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IdentitasController;
use App\Http\Controllers\UserTaskController;
use App\Http\Controllers\TaskCalendarController;
use App\Http\Controllers\ManageAccountController;
use App\Http\Middleware\CheckRoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RekapController;


Route::get('/', function () {
    if (auth()->check()) {
        switch (auth()->user()->role) {
            case 0:
                return redirect()->route('dashboard.admin');
            case 1:
                return redirect()->route('dashboard');
            default:
                return redirect()->route('dashboard.manager');
        }
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard Pegawai
    Route::middleware([CheckRoleMiddleware::class . ":1"])->group(function () {
        Route::get('/dashboard', [DashboardController::class, "index"])->name('dashboard'); // role 1
    });

    Route::get('/identitas', [IdentitasController::class, 'index'])->name('identitas');

    Route::get('/task/list', [UserTaskController::class, "list"])->name('task.list');
    Route::get('/tasks/{id}/preview', [UserTaskController::class, 'preview'])->name('task.preview');
    Route::post('/task/upload/{id}', [UserTaskController::class, 'uploadFile'])->name('task.upload');
    Route::get('/task-calendar', [TaskCalendarController::class, 'index'])->name('task.calendar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware([CheckRoleMiddleware::class . ":2"])->group(function () {
        Route::get('/dashboard-manager', [DashboardController::class, "index"])->name('dashboard.manager'); // role 2
    });

    // Manager Task
    Route::middleware([CheckRoleMiddleware::class . ":2"])->group(function () {
        Route::get('/revisi', function () {
            return view('revisi');
        })->name('revisi');
        Route::get('/task-manager', [ManagerTaskController::class, "index"])->name('task.manager.list');
        Route::get('/task-manager/add', [ManagerTaskController::class, "add"])->name('task.manager.add');
        Route::post('/task-manager/add', [ManagerTaskController::class, "store"])->name('task.manager.store');
        Route::get('/task-manager/edit/{id}', [ManagerTaskController::class, "edit"])->name('task.manager.edit');
        Route::post('/task-manager/edit/{id}', [ManagerTaskController::class, "update"])->name('task.manager.update');
        Route::delete('/task-manager/delete/{id}', [ManagerTaskController::class, 'delete'])->name('task.manager.delete');
        Route::post('/task/{id}/complete', [ManagerTaskController::class, 'markAsCompleted'])->name('task.complete');
        Route::post('/task/{id}/revisi', [ManagerTaskController::class, 'revisi'])->name('task.revisi');
        Route::get('/rekap-laporan', [RekapController::class, 'generateReport'])->name('rekap.download');
        Route::get('/rekap-form', [RekapController::class, 'form'])->name('rekap.form');
    });


    // User Admin
    Route::middleware(CheckRoleMiddleware::class . ":0")->group(function () {
        Route::get('/dashboard-admin', function () {
            return view('dashboard-admin'); // View khusus untuk role 0
        })->name('dashboard.admin');

        Route::get('manage-account', [ManageAccountController::class, 'index'])->name('manage.account');
        Route::put('manage-account/{id}/update', [ManageAccountController::class, 'updateRole'])->name('manage.account.update');
        Route::delete('manage-account/{id}', [ManageAccountController::class, 'destroy'])->name('manage.account.destroy');
        Route::post('manage-account/{id}/restore', [ManageAccountController::class, 'restore'])->name('manage.account.restore');
        Route::post('manage-account/{id}/verify', [ManageAccountController::class, 'verify'])->name('manage.account.verify');
        Route::delete('/manage-account/{id}/cancel', [ManageAccountController::class, 'cancel'])->name('manage.account.cancel');
        Route::get('/log', [LogActivityController::class, 'index'])->name('log.index');
    });
});


require __DIR__ . '/auth.php';
