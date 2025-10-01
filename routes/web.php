<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KodeController;
use Database\Seeders\EselonSeeder;

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

// -------------------- Pegawai (NIP) --------------------
Route::get('/', [KodeController::class, 'form'])->name('akses.form');
Route::get('/halaman-utama', [KodeController::class, 'form'])->name('halaman.utama');
Route::post('/cek-kode', [KodeController::class, 'cek'])->name('akses.cek');
Route::get('/akses-kode', [KodeController::class, 'akses_kode'])->name('akses.kode');

// -------------------- Admin / Kepegawaian --------------------
Route::get('/login', [KodeController::class, 'login'])->name('login'); // Form login admin/kepegawaian
Route::post('/login-submit', [KodeController::class, 'loginSubmit'])->name('login.submit');
Route::get('/logout', [KodeController::class, 'logout'])->name('logout'); // Logout admin/kepegawaian

// Dashboard admin
Route::middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [KodeController::class, 'admin'])->name('dashboard');

    Route::get('/admin-bidang', [KodeController::class, 'adminBidang'])->name('admin.bidang');
    Route::get('/bidang-data', [KodeController::class, 'bidangdata'])->name('bidang.data');
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
    Route::get('/admin-struktur', [KodeController::class, 'adminStruktur'])->name('admin.struktur');
    Route::post('/struktur-simpan', [KodeController::class, 'strukturSimpan'])->name('struktur.simpan');
    Route::get('/struktur-edit', [KodeController::class, 'strukturEdit'])->name('struktur.edit');
    Route::put('/struktur-update/{id}', [KodeController::class, 'strukturUpdate'])->name('struktur.update');
    Route::delete('/struktur-hapus/{id}', [KodeController::class, 'strukturHapus'])->name('struktur.destroy');
    Route::get('/admin-user', [KodeController::class, 'adminUser'])->name('admin.user');
    Route::post('/user-simpan', [KodeController::class, 'userSimpan'])->name('user.simpan');
    Route::get('/user-edit', [KodeController::class, 'userEdit'])->name('user.edit');
    Route::put('/user-update/{id}', [KodeController::class, 'userUpdate'])->name('user.update');
    Route::delete('/user-hapus/{id}', [KodeController::class, 'userHapus'])->name('user.destroy');

    // ... Tambahkan route admin lain di sini ...
});

// Dashboard kepegawaian
Route::middleware('kepegawaian.auth')->group(function () {
    Route::get('/kepegawaian-dashboard', [KodeController::class, 'kepegawaianDashboard'])->name('kepegawaian.dashboard');

    Route::get('/data-pakta-integritas/{id}', [KodeController::class, 'dataPaktaIntegritas'])->name('kepegawaian.pakta.integritas');
    Route::get('/data-kepegawaian', [KodeController::class, 'dataKepegawaian'])->name('kepegawaian.datakepegawaian');
    Route::get('/import-paktaintegritas', [KodeController::class, 'syncPaktaIntegritas'])->name('kepegawaian.import.paktaintegritas');
    Route::get('/export-paktaintegritas', [KodeController::class, 'exportPaktaIntegritas'])->name('kepegawaian.export.paktaintegritas');
    Route::get('/import-pegawai', [KodeController::class, 'syncPegawai'])->name('kepegawaian.import.pegawai');
    Route::get('/export-pegawai', [KodeController::class, 'exportPegawai'])->name('kepegawaian.export.pegawai');
    Route::get('/pemuktahiran-data', [KodeController::class, 'pemuktahiranData'])->name('kepegawaian.data.pegawai.pemuktahiran');
    Route::get('/data-naikpangkat', [KodeController::class, 'dataKP'])->name('kepegawaian.data.naikpangkat');
    Route::get('/data-pensiun', [KodeController::class, 'dataPensiun'])->name('kepegawaian.data.pensiun');
    Route::get('/data-eselon', [KodeController::class, 'dataEselon'])->name('kepegawaian.data.eselon');
    Route::post('/tambah-eselon', [KodeController::class, 'tambahEselon'])->name('kepegawaian.tambah.eselon');
    Route::post('/edit-eselon', [KodeController::class, 'ubahEselon'])->name('kepegawaian.edit.eselon');
    Route::post('/hapus-eselon', [KodeController::class, 'hapusEselon'])->name('kepegawaian.hapus.eselon');
    Route::get('/data-jabatan', [KodeController::class, 'dataJabatan'])->name('kepegawaian.data.jabatan');
    Route::post('/tambah-jabatan', [KodeController::class, 'tambahJabatan'])->name('kepegawaian.tambah.jabatan');
    Route::post('/edit-jabatan', [KodeController::class, 'ubahJabatan'])->name('kepegawaian.edit.jabatan');
    Route::post('/hapus-jabatan', [KodeController::class, 'hapusJabatan'])->name('kepegawaian.hapus.jabatan');
    Route::get('/data-golongan', [KodeController::class, 'dataGolongan'])->name('kepegawaian.data.golongan');
    Route::post('/tambah-golongan', [KodeController::class, 'tambahGolongan'])->name('kepegawaian.tambah.golongan');
    Route::post('/edit-golongan', [KodeController::class, 'ubahGolongan'])->name('kepegawaian.edit.golongan');
    Route::post('/hapus-golongan', [KodeController::class, 'hapusGolongan'])->name('kepegawaian.hapus.golongan');
    Route::get('/data-pendidikan', [KodeController::class, 'dataPendidikan'])->name('kepegawaian.data.pendidikan');
    Route::post('/tambah-pendidikan', [KodeController::class, 'tambahPendidikan'])->name('kepegawaian.tambah.pendidikan');
    Route::post('/edit-pendidikan', [KodeController::class, 'ubahPendidikan'])->name('kepegawaian.edit.pendidikan');
    Route::post('/hapus-pendidikan', [KodeController::class, 'hapusPendidikan'])->name('kepegawaian.hapus.pendidikan');
    Route::get('/data-bidang', [KodeController::class, 'dataBidang'])->name('kepegawaian.data.bidang');
    Route::get('/data-pegawai', [KodeController::class, 'dataPegawai'])->name('kepegawaian.data.pegawai');
    Route::put('/verifikasi-user/{id}', [KodeController::class, 'verifikasiPemuktahiran'])->name('kepegawaian.verifikasi.user');
    Route::get('/data-kenaikanberkala', [KodeController::class, 'dataKGB'])->name('kepegawaian.data.berkala');
    // ... Tambahkan route kepegawaian lain di sini ...
});


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
    Route::get('/data-kesenian', [KodeController::class, 'datakesenian'])->name('data.kesenian');
    Route::get('/data-cagar-budaya', [KodeController::class, 'datacagarbudaya'])->name('data.cagar-budaya');
    Route::get('/data-tradisi', [KodeController::class, 'datatradisi'])->name('data.tradisi');
    Route::get('/data-sejarah', [KodeController::class, 'datasejarah'])->name('data.sejarah');
    Route::get('/data-museum', [KodeController::class, 'datamuseum'])->name('data.museum');
    Route::get('/data-taman-budaya', [KodeController::class, 'datatamanbudaya'])->name('data.taman-budaya');
    Route::get('/data-monumen', [KodeController::class, 'datamonumen'])->name('data.monumen');
    Route::get('/struktur-organisasi', [KodeController::class, 'strukturOrganisasi'])->name('struktur.organisasi');
    route::get('/lihat-jajaran', [KodeController::class, 'lihatjajaran'])->name('lihat.jajaran');
    Route::post('/pegawai-update', [KodeController::class, 'pegawaiUpdate'])->name('pegawai.update');
    Route::post('/pemuktahiran-update', [KodeController::class, 'updateDataPegawai'])->name('pemuktahiran.update');
    Route::post('/pemuktahiran-update-pasfoto', [KodeController::class, 'updatePasFoto'])->name('pemuktahiran.update.pasfoto');

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
