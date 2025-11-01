<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HutangRetailController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\StokBanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Hutang Retail Routes - Utamakan yang paling sering diakses
    Route::prefix('hutangretail')->group(function () {
        Route::get('/dashboard', [HutangRetailController::class, 'dashboard'])->name('hutangretail.dashboard');
        Route::get('/', [HutangRetailController::class, 'index'])->name('hutangretail.index');
        Route::get('/create', [HutangRetailController::class, 'create'])->name('hutangretail.create');
        Route::post('/', [HutangRetailController::class, 'store'])->name('hutangretail.store');
        Route::get('/{id}', [HutangRetailController::class, 'show'])->name('hutangretail.show');
        Route::get('/{id}/edit', [HutangRetailController::class, 'edit'])->name('hutangretail.edit');
        Route::put('/{id}', [HutangRetailController::class, 'update'])->name('hutangretail.update');
        Route::delete('/{id}', [HutangRetailController::class, 'destroy'])->name('hutangretail.destroy');
        Route::post('/{id}/bayar', [HutangRetailController::class, 'bayarHutang'])->name('hutangretail.bayar');
        
        // API Routes untuk Hutang Retail
        Route::get('/api/data', [HutangRetailController::class, 'apiHutangRetail'])->name('hutangretail.api');
        Route::get('/api/sisa-per-retail', [HutangRetailController::class, 'sisaHutangPerRetail'])->name('hutangretail.api.sisa');
        Route::get('/export/laporan', [HutangRetailController::class, 'exportLaporan'])->name('hutangretail.export');
    });
    
    // Stok Ban Routes
    Route::prefix('stokban')->group(function () {
        Route::get('/dashboard', [StokBanController::class, 'dashboard'])->name('stokban.dashboard');
        Route::get('/', [StokBanController::class, 'index'])->name('stokban.index');
        Route::get('/create', [StokBanController::class, 'create'])->name('stokban.create');
        Route::post('/', [StokBanController::class, 'store'])->name('stokban.store');
        Route::get('/{id}', [StokBanController::class, 'show'])->name('stokban.show');
        Route::get('/{id}/edit', [StokBanController::class, 'edit'])->name('stokban.edit');
        Route::put('/{id}', [StokBanController::class, 'update'])->name('stokban.update');
        Route::delete('/{id}', [StokBanController::class, 'destroy'])->name('stokban.destroy');
    });
    
    // Penjualan Routes
    Route::prefix('penjualan')->group(function () {
        Route::get('/dashboard', [PenjualanController::class, 'dashboard'])->name('penjualan.dashboard');
        Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/create', [PenjualanController::class, 'create'])->name('penjualan.create');
        Route::post('/', [PenjualanController::class, 'store'])->name('penjualan.store');
        Route::get('/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::get('/{id}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
        Route::put('/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
        Route::delete('/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    });
    
    // Laporan Routes
    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/create', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/{id}', [LaporanController::class, 'show'])->name('laporan.show');
        Route::get('/{id}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/{id}', [LaporanController::class, 'update'])->name('laporan.update');
        Route::delete('/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
        Route::get('/import', [LaporanController::class, 'importForm'])->name('laporan.import.form');
        Route::post('/import', [LaporanController::class, 'import'])->name('laporan.import');
    });
    
    // User Management (Super Admin Only)
    Route::middleware(['role:superadmin'])->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/', [UserController::class, 'store'])->name('users.store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });
    });
});

// Fallback Route
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});