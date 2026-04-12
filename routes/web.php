<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterPeriksaController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienDashboardController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PasienRiwayatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/obat', [ObatController::class, 'index'])->name('admin.obat.index');
    Route::post('/obat', [ObatController::class, 'store'])->name('admin.obat.store');
    Route::put('/obat/{obat}', [ObatController::class, 'update'])->name('admin.obat.update');

    Route::get('/export/dokter', [ExportController::class, 'adminDokter'])->name('admin.export.dokter');
    Route::get('/export/pasien', [ExportController::class, 'adminPasien'])->name('admin.export.pasien');
    Route::get('/export/obat', [ExportController::class, 'adminObat'])->name('admin.export.obat');
    Route::get('/pembayaran', [PembayaranController::class, 'adminIndex'])->name('admin.pembayaran.index');
    Route::post('/pembayaran/{periksa}/verifikasi', [PembayaranController::class, 'verify'])->name('admin.pembayaran.verify');
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::get('/periksa', [DokterPeriksaController::class, 'index'])->name('dokter.periksa.index');
    Route::post('/periksa', [DokterPeriksaController::class, 'store'])->name('dokter.periksa.store');

    Route::get('/export/jadwal', [ExportController::class, 'dokterJadwal'])->name('dokter.export.jadwal');
    Route::get('/export/riwayat-pasien', [ExportController::class, 'dokterRiwayat'])->name('dokter.export.riwayat-pasien');
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', [PasienDashboardController::class, 'index'])->name('pasien.dashboard');
    Route::get('/dashboard/snapshot', [PasienDashboardController::class, 'snapshot'])->name('pasien.dashboard.snapshot');
    Route::post('/daftar-poli', [PasienDashboardController::class, 'store'])->name('pasien.daftar-poli');
    Route::get('/riwayat', [PasienRiwayatController::class, 'index'])->name('pasien.riwayat.index');
    Route::get('/riwayat/{daftarPoli}', [PasienRiwayatController::class, 'show'])->name('pasien.riwayat.show');
    Route::get('/pembayaran', [PembayaranController::class, 'pasienIndex'])->name('pasien.pembayaran.index');
    Route::post('/pembayaran/{periksa}/upload', [PembayaranController::class, 'upload'])->name('pasien.pembayaran.upload');
});
