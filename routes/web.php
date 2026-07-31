<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot.password.post');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () { return redirect()->route('dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notifications
    Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Search (Admin & Atasan)
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // Admin Access (Khusus Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/absensi/hari-ini', [AbsensiController::class, 'index'])->name('absensi.index');
    });

    // Atasan Only Access
    Route::middleware(['role:atasan'])->group(function () {
        Route::post('pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
        Route::resource('pegawai', PegawaiController::class)->only(['create', 'store']);
    });

    // Admin & Atasan Access (Kelola Karyawan & Gaji)
    Route::middleware(['role:admin,atasan'])->group(function () {
        Route::resource('pegawai', PegawaiController::class)->except(['create', 'store', 'show']);
    });

    // Atasan Access
    Route::middleware(['role:atasan'])->group(function () {
        // Run Payroll pages (Wizard)
        Route::get('/penggajian/create', [PenggajianController::class, 'create'])->name('penggajian.create'); // Step 1
        Route::post('/penggajian/preview', [PenggajianController::class, 'preview'])->name('penggajian.preview'); // Step 2
        Route::post('/penggajian/store', [PenggajianController::class, 'store'])->name('penggajian.store'); // Step 3
        Route::get('/penggajian/success', [PenggajianController::class, 'success'])->name('penggajian.success'); // Success
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');
    });

    // Pegawai Access
    Route::middleware(['role:pegawai'])->group(function () {
        Route::get('/absensi/kamera', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::get('/absensi/kalender', [AbsensiController::class, 'kalender'])->name('kalender.index');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        
        Route::get('/izincuti/create', [\App\Http\Controllers\IzinCutiController::class, 'create'])->name('izincuti.create');
        Route::post('/izincuti', [\App\Http\Controllers\IzinCutiController::class, 'store'])->name('izincuti.store');
    });

    // All authenticated users can view payroll history and payslip, and izincuti index
    Route::middleware(['role:admin,atasan,pegawai'])->group(function () {
        Route::get('/penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
        Route::get('/penggajian/{penggajian}', [PenggajianController::class, 'show'])->name('penggajian.show');
        
        Route::get('/izincuti', [\App\Http\Controllers\IzinCutiController::class, 'index'])->name('izincuti.index');
    });
    
    // Admin & Atasan Access for IzinCuti Approval
    Route::middleware(['role:admin,atasan'])->group(function () {
        Route::put('/izincuti/{izincuti}/status', [\App\Http\Controllers\IzinCutiController::class, 'updateStatus'])->name('izincuti.updateStatus');
    });
});