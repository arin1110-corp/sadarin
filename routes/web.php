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
Route::get('/akses-kode', [KodeController::class, 'akses_kode'])->name('akses.kode');
// Rute untuk halaman utama
// Rute Admin
Route::get('/dashboard', [KodeController::class, 'admin'])->name('dashboard');


Route::get('/admin-bidang', [KodeController::class, 'adminBidang'])->name('admin.bidang');
Route::get('bidang-data', [KodeController::class, 'bidangdata'])->name('bidang.data');
Route::get('/bidang-tambah', [KodeController::class, 'bidangTambah'])->name('bidang.tambahdata');
Route::post('/bidang-simpan', [KodeController::class, 'bidangSimpan'])->name('bidang.simpan');
Route::get('/bidang-edit', [KodeController::class, 'bidangEdit'])->name('bidang.edit');
Route::post('/bidang-update/{id}', [KodeController::class, 'bidangUpdate'])->name('bidang.update');
Route::get('/bidang-hapus', [KodeController::class, 'bidangHapus'])->name('bidang.destroy');


Route::get('/admin-subbag', [KodeController::class, 'adminSubBag'])->name('admin.subbag');
Route::post('/subbag-simpan', [KodeController::class, 'subbagSimpan'])->name('subbag.simpan');

Route::get('/admin-navigasi', [KodeController::class, 'adminNavigasi'])->name('admin.navigasi');
Route::get('/admin-subnavigasi', [KodeController::class, 'adminSubNavigasi'])->name('admin.subnavigasi');



// Halaman daftar bagian – hanya bisa diakses setelah sukses input kode akses
Route::middleware('akses.kontrol')->group(function () {
    Route::get('/daftar-bagian', [KodeController::class, 'daftarBagian'])->name('daftar.bagian');
    // Tambahkan rute lain yang memerlukan akses di sini
    Route::get('/pk-bidang', [KodeController::class, 'pkbidang'])->name('pk.bidang');
    Route::get('/data-upload', [KodeController::class, 'dataupload'])->name('data.upload');
    Route::get('/umpan-balik', [KodeController::class, 'umpanbalik'])->name('umpan.balik');
    Route::get('/evaluasi-kinerja', [KodeController::class, 'evaluasikinerja'])->name('evaluasi.kinerja');
    // Rute untuk halaman cek bidang
    Route::get('/data-sekretariat', [KodeController::class, 'datasekretariat'])->name('data.sekretariat');
    Route::get('data-kesenian', [KodeController::class, 'datakesenian'])->name('data.kesenian');
    Route::get('/data-cagar-budaya', [KodeController::class, 'datacagarbudaya'])->name('data.cagar-budaya');
    Route::get('/data-tradisi', [KodeController::class, 'datatradisi'])->name('data.tradisi');
    Route::get('/data-sejarah', [KodeController::class, 'datasejarah'])->name('data.sejarah');
    Route::get('/data-museum', [KodeController::class, 'datamuseum'])->name('data.museum');
    Route::get('/data-taman-budaya', [KodeController::class, 'datatamanbudaya'])->name('data.taman-budaya');
    Route::get('/data-monumen', [KodeController::class, 'datamonumen'])->name('data.monumen');
    // Rute untuk halaman cek Subbag

    Route::get('/data-ppep', [KodeController::class, 'datappep'])->name('data.ppep');
    Route::get('/data-keuangan', [KodeController::class, 'datakeuangan'])->name('data.keuangan');
    Route::get('/data-umpeg', [KodeController::class, 'dataumpeg'])->name('data.umpeg');


    Route::get('/data-dppa2025', [KodeController::class, 'datadppa2025'])->name('data.dppa2025');
    Route::get('/data-rak2025', [KodeController::class, 'datarak2025'])->name('data.rak2025');
    Route::get('/bendahara-penerima', [KodeController::class, 'bendaharaPenerima'])->name('bendahara.penerima');
    Route::get('/bendahara-pengeluaran', [KodeController::class, 'bendaharaPengeluaran'])->name('bendahara.pengeluaran');
    Route::get('/data-pegawaipns', [KodeController::class, 'datapegawaipns'])->name('data.pegawaiPNS');
});
