<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PengingatController;
use App\Http\Controllers\KonfirmasiObatController;
use App\Http\Controllers\PengingatObatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Pasien
    Route::resource('pasien', PasienController::class);
    
    // Jadwal
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/{jadwal}', [JadwalController::class, 'show'])->name('jadwal.show');
    Route::patch('/jadwal/{jadwal}/status', [JadwalController::class, 'updateStatus'])->name('jadwal.status');
    Route::patch('/jadwal/{jadwal}/reschedule', [JadwalController::class, 'reschedule'])->name('jadwal.reschedule');
    
    // Jadwal Pasien
    Route::get('/pasien/{pasien}/jadwal/edit', [JadwalController::class, 'editJadwalPasien'])->name('pasien.jadwal.edit');
    Route::patch('/pasien/{pasien}/jadwal', [JadwalController::class, 'updateJadwalPasien'])->name('pasien.jadwal.update');
    
    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{riwayat}', [RiwayatController::class, 'show'])->name('riwayat.show');
    Route::get('/pasien/{pasien}/riwayat', [RiwayatController::class, 'riwayatPasien'])->name('pasien.riwayat');
    
    // Pengingat Obat Harian
    Route::get('/pengingat-obat', [PengingatObatController::class, 'index'])->name('pengingat-obat.index');
    Route::get('/pengingat-obat/bulk-create', [PengingatObatController::class, 'bulkCreate'])->name('pengingat-obat.bulk-create');
    Route::post('/pengingat-obat/bulk-store', [PengingatObatController::class, 'bulkStore'])->name('pengingat-obat.bulk-store');
    Route::get('/pasien/{pasien}/pengingat-obat/create', [PengingatObatController::class, 'create'])->name('pengingat-obat.create');
    Route::post('/pasien/{pasien}/pengingat-obat', [PengingatObatController::class, 'store'])->name('pengingat-obat.store');
    Route::get('/pengingat-obat/{jadwal}', [PengingatObatController::class, 'show'])->name('pengingat-obat.show');
    Route::put('/pengingat-obat/{jadwal}/status', [PengingatObatController::class, 'updateStatus'])->name('pengingat-obat.update-status');
    Route::put('/pengingat-obat/{jadwal}/reschedule', [PengingatObatController::class, 'reschedule'])->name('pengingat-obat.reschedule');
    Route::delete('/pengingat-obat/{jadwal}', [PengingatObatController::class, 'destroy'])->name('pengingat-obat.destroy');
    
    // Statistik Konfirmasi Obat
    Route::get('/konfirmasi-obat/statistik', [KonfirmasiObatController::class, 'statistik'])->name('konfirmasi-obat.statistik');
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk Pengingat WhatsApp
Route::middleware(['auth'])->group(function () {
    Route::get('/pengingat', [PengingatController::class, 'index'])->name('pengingat.index');
    Route::post('/pengingat/{id}/kirim', [PengingatController::class, 'kirimPengingat'])->name('pengingat.kirim');
    Route::post('/pengingat/kirim-semua', [PengingatController::class, 'kirimSemuaPengingat'])->name('pengingat.kirim-semua');
});

// Rute untuk Konfirmasi Obat dari pasien (tidak perlu auth)
Route::get('/konfirmasi-obat/{token}', [KonfirmasiObatController::class, 'show'])->name('konfirmasi-obat.show');
Route::post('/konfirmasi-obat/{token}', [KonfirmasiObatController::class, 'konfirmasi'])->name('konfirmasi-obat.konfirmasi');

require __DIR__.'/auth.php';
