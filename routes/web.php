<?php

use App\Http\Controllers\AccessMatrixController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'storeDetails'])->name('register.details');
    Route::get('/register/password', [RegisteredUserController::class, 'createPassword'])->name('register.password');
    Route::post('/register/password', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::delete('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.index')->middleware(['auth', 'role:admin']);
Route::get('/admin/laporan', [AdminReportController::class, 'laporan'])->name('admin.laporan')->middleware(['auth', 'role:admin,owner']);
Route::get('/admin/laporan/export', [AdminReportController::class, 'export'])->name('admin.laporan.export')->middleware(['auth', 'role:admin,owner']);
Route::get('/admin/analitik', [AdminReportController::class, 'analitik'])->name('admin.analitik')->middleware(['auth', 'role:admin,owner']);
Route::get('/admin/access', AccessMatrixController::class)->name('admin.access')->middleware(['auth', 'role:admin']);
Route::get('/owner', [AdminDashboardController::class, 'index'])->name('owner.index')->middleware(['auth', 'role:owner']);
Route::get('/gudang', [RoleDashboardController::class, 'gudang'])->name('gudang.index')->middleware(['auth', 'role:gudang']);
Route::resource('admin/users', UserController::class)
    ->names('admin.users')
    ->middleware(['auth', 'role:admin']);
Route::resource('admin/products', ProductController::class)
    ->names('admin.products')
    ->middleware(['auth', 'role:admin']);
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir/dashboard', [KasirController::class, 'dashboard'])->name('kasir.dashboard');
    Route::get('/kasir/pesanan', [KasirController::class, 'pesanan'])->name('kasir.pesanan');
    Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
    Route::post('/kasir/bayar', [KasirController::class, 'prepare'])->name('kasir.prepare');
    Route::get('/kasir/{transaction}/struk', [KasirController::class, 'struk'])->name('kasir.struk');
    Route::resource('kasir', KasirController::class)
        ->parameters(['kasir' => 'transaction']);
});
