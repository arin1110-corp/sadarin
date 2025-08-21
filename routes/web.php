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
Route::get('/halaman-utama', [KodeController::class, 'form'])->name('halaman.utama');
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
Route::put('/bidang-update/{id}', [KodeController::class, 'bidangUpdate'])->name('bidang.update');
Route::delete('/bidang-hapus/{id}', [KodeController::class, 'bidangHapus'])->name('bidang.destroy');


Route::get('/admin-subbag', [KodeController::class, 'adminSubBag'])->name('admin.subbag');
Route::post('/subbag-simpan', [KodeController::class, 'subbagSimpan'])->name('subbag.simpan');
Route::get('/subbag-edit', [KodeController::class, 'subbagEdit'])->name('subbag.edit');
Route::put('/subbag-update/{id}', [KodeController::class, 'subbagUpdate'])->name('subbag.update');
Route::delete('/subbag-hapus/{id}', [KodeController::class, 'subbagHapus'])->name('subbag.destroy');

Route::get('/admin-navigasi', [KodeController::class, 'adminNavigasi'])->name('admin.navigasi');
Route::post('/navigasi-simpan', [KodeController::class, 'navigasiSimpan'])->name('navigasi.simpan');
Route::get('/navigasi-edit', [KodeController::class, 'navigasiEdit'])->name('navigasi.edit');
Route::put('/navigasi-update/{id}', [KodeController::class, 'navigasiUpdate'])->name('navigasi.update');
Route::delete('/navigasi-hapus/{id}', [KodeController::class, 'navigasiHapus'])->name('navigasi.destroy');

Route::get('/admin-subnavigasi', [KodeController::class, 'adminSubNavigasi'])->name('admin.subnavigasi');
Route::post('/subnavigasi-simpan', [KodeController::class, 'subnavigasiSimpan'])->name('subnavigasi.simpan');
Route::get('/subnavigasi-edit', [KodeController::class, 'subnavigasiEdit'])->name('subnavigasi.edit');
Route::put('/subnavigasi-update/{id}', [KodeController::class, 'subnavigasiUpdate'])->name('subnavigasi.update');
Route::delete('/subnavigasi-hapus/{id}', [KodeController::class, 'subnavigasiHapus'])->name('subnavigasi.destroy');



// Halaman daftar bagian – hanya bisa diakses setelah sukses input kode akses
Route::middleware('akses.kontrol')->group(function () {
    Route::get('/daftar-bagian', [KodeController::class, 'daftarBagian'])->name('daftar.bagian');
    // Tambahkan rute lain yang memerlukan akses di sini
    Route::get('/pk-bidang', [KodeController::class, 'pkbidang'])->name('pk.bidang');
    Route::get('/detail-pegawai', [KodeController::class, 'detailpegawai'])->name('detail.pegawai');
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


    Route::get('/data-senirupa', [KodeController::class, 'datasenirupa'])->name('data.senirupa');


    Route::get('/data-dppa2025', [KodeController::class, 'datadppa2025'])->name('data.dppa2025');
    Route::get('/data-rak2025', [KodeController::class, 'datarak2025'])->name('data.rak2025');
    Route::get('/bendahara-penerima', [KodeController::class, 'bendaharaPenerima'])->name('bendahara.penerima');
    Route::get('/bendahara-pengeluaran', [KodeController::class, 'bendaharaPengeluaran'])->name('bendahara.pengeluaran');
    Route::get('/data-pegawaipns', [KodeController::class, 'dataPegawaipns'])->name('data.pegawaiPNS');
    Route::get('/data-pegawai-pppk', [KodeController::class, 'dataPegawaiPPPK'])->name('data.pegawaiPPPK');
    Route::get('/data-pegawai-rincian', [KodeController::class, 'dataPegawaiRincian'])->name('data.rincianpegawai');
});
