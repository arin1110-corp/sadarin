<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KodeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [KodeController::class, 'form'])->name('akses.form');
Route::post('/cek-kode', [KodeController::class, 'cek'])->name('akses.cek');
Route::get('/cek-bidang', [KodeController::class, 'cekbidang'])->name('cek.bidang');
Route::get('/data-sekretariat', [KodeController::class, 'datasekretariat'])->name('data.sekretariat');
Route::get('/data-ppep', [KodeController::class, 'datappep'])->name('data.ppep');
Route::get('/data-keuangan', [KodeController::class, 'datakeuangan'])->name('data.keuangan');
Route::get('/data-umpeg', [KodeController::class, 'dataumpeg'])->name('data.umpeg');
Route::get('/data-dppa2025', [KodeController::class, 'datadppa2025'])->name('data.dppa2025');
Route::get('/data-rak2025', [KodeController::class, 'datarak2025'])->name('data.rak2025');


// Halaman daftar bagian – hanya bisa diakses setelah sukses input kode akses
Route::middleware('akses.kontrol')->group(function () {
    Route::get('/daftar-bagian', [KodeController::class, 'daftarBagian'])->name('daftar.bagian');
    // Tambahkan rute lain yang memerlukan akses di sini
    Route::get('/homepage', [KodeController::class, 'form'])->name('homepage');
    Route::get('/pk-bidang', [KodeController::class, 'pkbidang'])->name('pk.bidang');
    Route::get('/data-upload', [KodeController::class, 'dataupload'])->name('data.upload');
});
