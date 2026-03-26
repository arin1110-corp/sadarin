<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KodeController;
use App\Http\Controllers\PreFillController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KepegawaianController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\AksesController;
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

// -------------------- Prefill Data --------------------
Route::get('/pengumpulan/prefill-evaluasi', [PreFillController::class, 'prefillEvaluasi']);
Route::get('/pengumpulan/prefill-evaluasi-tw1', [PreFillController::class, 'prefillEvaluasiTWI']);
Route::get('/pengumpulan/prefill-evaluasi-tw2', [PreFillController::class, 'prefillEvaluasiTWII']);
Route::get('/pengumpulan/prefill-evaluasi-tw3', [PreFillController::class, 'prefillEvaluasiTWIII']);
Route::get('/pengumpulan/prefill-evaluasi-tw4', [PreFillController::class, 'prefillEvaluasiTWIV']);
Route::get('/pengumpulan/prefill-evaluasi-tahunan', [PreFillController::class, 'prefillEvaluasiTahunan2025']);
Route::get('/pengumpulan/prefill-umpanbalik', [PreFillController::class, 'prefillUmbal']);
Route::get('/pengumpulan/prefill-umpanbalik-tw1', [PreFillController::class, 'prefillUmbalTWI']);
Route::get('/pengumpulan/prefill-umpanbalik-tw2', [PreFillController::class, 'prefillUmbalTWII']);
Route::get('/pengumpulan/prefill-umpanbalik-tw3', [PreFillController::class, 'prefillUmbalTWIII']);
Route::get('/pengumpulan/prefill-umpanbalik-tw4', [PreFillController::class, 'prefillUmbalTWIV']);
Route::get('/pakta-integritas-2025', [PreFillController::class, 'prefillPaktaIntegritas1Desember']);
Route::get('/model-c-2025', [PreFillController::class, 'prefillModelC2025']);
Route::get('/syntax-c-2025', [PreFillController::class, 'prefillSyntaxC2025']);
Route::get('/skp-2025', [PreFillController::class, 'prefillSKP2025']);
Route::get('/model-c-2026', [PreFillController::class, 'prefillModelC2026']);
Route::get('/data-ktp', [PreFillController::class, 'prefillDataKTP']);
Route::get('/data-npwp', [PreFillController::class, 'prefillDataNPWP']);
Route::get('/data-buku-rekening', [PreFillController::class, 'prefillDataBukuRekening']);
Route::get('/data-bpjs-kesehatan', [PreFillController::class, 'prefillDataBPJSKesehatan']);
Route::get('/data-kartu-keluarga', [PreFillController::class, 'prefillDataKartuKeluarga']);
Route::get('/data-ijazah', [PreFillController::class, 'prefillDataIjazah']);
Route::get('/data-laporan-pjlp-januari-2025', [PreFillController::class, 'prefillLaporanPJLPJanuari2025']);
Route::get('/data-coretax-2026', [PreFillController::class, 'prefillCoretax2026']);
Route::get('/data-laporan-ikd', [PreFillController::class, 'prefillLaporanIKD']);
Route::get('/data-perjanjian-kinerja-2026', [PreFillController::class, 'prefillPerjanjianKinerja2026']);

// -------------------- Homepage --------------------
Route::get('/', [HomepageController::class, 'form'])->name('akses.form');
Route::get('/halaman-utama', [HomepageController::class, 'form'])->name('halaman.utama');
Route::post('/cek-kode', [HomepageController::class, 'cek'])->name('akses.cek');
Route::get('/akses-kode', [HomepageController::class, 'akses_kode'])->name('akses.kode');
Route::get('/login', [HomepageController::class, 'login'])->name('login'); // Form login admin/kepegawaian
Route::post('/login-submit', [HomepageController::class, 'loginSubmit'])->name('login.submit');
Route::get('/logout', [HomepageController::class, 'logout'])->name('logout'); // Logout admin/kepegawaian
Route::get('/cek-kode', function () {
    return view('homepage_awal');
})->name('akses.depan');
Route::get('/homepage-menuawal', function () {
    return view('homepage_menuawal');
})->name('homepage.menuawal');
Route::get('/test-email', function () {
    Mail::raw('Test Email Brevo Laravel', function ($mail) {
        $mail->to('indraardika@gmail.com')->subject('Test SMTP Brevo');
    });

    return 'Email terkirim';
});

Route::get('/password/reset/{token}', [AksesController::class, 'formReset'])->name('password.reset.form');
Route::post('/password/reset/save', [AksesController::class, 'savePassword'])->name('password.reset.save');
Route::get('/preview-reset', function () {
    return view('auth.reset_password', [
        'token' => 'dummy-token',
    ]);
});

// Dashboard admin
Route::middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'admin'])->name('dashboard');

    Route::get('/admin-bidang', [AdminController::class, 'adminBidang'])->name('admin.bidang');
    Route::get('/bidang-data', [AdminController::class, 'bidangdata'])->name('bidang.data');
    Route::get('/bidang-tambah', [AdminController::class, 'bidangTambah'])->name('bidang.tambahdata');
    Route::post('/bidang-simpan', [AdminController::class, 'bidangSimpan'])->name('bidang.simpan');
    Route::get('/bidang-edit', [AdminController::class, 'bidangEdit'])->name('bidang.edit');
    Route::put('/bidang-update/{id}', [AdminController::class, 'bidangUpdate'])->name('bidang.update');
    Route::delete('/bidang-hapus/{id}', [AdminController::class, 'bidangHapus'])->name('bidang.destroy');

    Route::get('/admin-subbag', [AdminController::class, 'adminSubBag'])->name('admin.subbag');
    Route::post('/subbag-simpan', [AdminController::class, 'subbagSimpan'])->name('subbag.simpan');
    Route::get('/subbag-edit', [AdminController::class, 'subbagEdit'])->name('subbag.edit');
    Route::put('/subbag-update/{id}', [AdminController::class, 'subbagUpdate'])->name('subbag.update');
    Route::delete('/subbag-hapus/{id}', [AdminController::class, 'subbagHapus'])->name('subbag.destroy');

    Route::get('/admin-navigasi', [AdminController::class, 'adminNavigasi'])->name('admin.navigasi');
    Route::post('/navigasi-simpan', [AdminController::class, 'navigasiSimpan'])->name('navigasi.simpan');
    Route::get('/navigasi-edit', [AdminController::class, 'navigasiEdit'])->name('navigasi.edit');
    Route::put('/navigasi-update/{id}', [AdminController::class, 'navigasiUpdate'])->name('navigasi.update');
    Route::delete('/navigasi-hapus/{id}', [AdminController::class, 'navigasiHapus'])->name('navigasi.destroy');
    Route::get('/admin-subnavigasi', [AdminController::class, 'adminSubNavigasi'])->name('admin.subnavigasi');
    Route::post('/subnavigasi-simpan', [AdminController::class, 'subnavigasiSimpan'])->name('subnavigasi.simpan');
    Route::get('/subnavigasi-edit', [AdminController::class, 'subnavigasiEdit'])->name('subnavigasi.edit');
    Route::put('/subnavigasi-update/{id}', [AdminController::class, 'subnavigasiUpdate'])->name('subnavigasi.update');
    Route::delete('/subnavigasi-hapus/{id}', [AdminController::class, 'subnavigasiHapus'])->name('subnavigasi.destroy');
    Route::get('/admin-struktur', [AdminController::class, 'adminStruktur'])->name('admin.struktur');
    Route::post('/struktur-simpan', [AdminController::class, 'strukturSimpan'])->name('struktur.simpan');
    Route::get('/struktur-edit', [AdminController::class, 'strukturEdit'])->name('struktur.edit');
    Route::put('/struktur-update/{id}', [AdminController::class, 'strukturUpdate'])->name('struktur.update');
    Route::delete('/struktur-hapus/{id}', [AdminController::class, 'strukturHapus'])->name('struktur.destroy');
    Route::get('/admin-user', [AdminController::class, 'adminUser'])->name('admin.user');
    Route::post('/user-simpan', [AdminController::class, 'userSimpan'])->name('user.simpan');
    Route::get('/user-edit', [AdminController::class, 'userEdit'])->name('user.edit');
    Route::put('/user-update/{id}', [AdminController::class, 'userUpdate'])->name('user.update');
    Route::delete('/user-hapus/{id}', [AdminController::class, 'userHapus'])->name('user.destroy');

    // ... Tambahkan route admin lain di sini ...
});

// Dashboard kepegawaian
Route::middleware('kepegawaian.auth')->group(function () {
    Route::get('/kepegawaian-dashboard', [KepegawaianController::class, 'kepegawaianDashboard'])->name('kepegawaian.dashboard');
    Route::get('/data-ktp/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.data.ktp');
    Route::get('/data-npwp/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.data.npwp');
    Route::get('/data-bukurekening/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.data.rekening');
    Route::get('/data-bpjskesehatan/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.data.bpjs.kesehatan');
    Route::get('/data-kartukeluarga/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.data.kartu.keluarga');
    Route::get('/data-ijazah/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.data.ijazah');
    Route::get('/data-pakta-integritas/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.pakta.integritas');
    Route::get('/data-pakta-1desember/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.pakta.1desember');
    Route::get('/data-evkin-tw1/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.evkin.tw1');
    Route::get('/data-evkin-tw2/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.evkin.tw2');
    Route::get('/data-evkin-tw3/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.evkin.tw3');
    Route::get('/data-evkin-tw4/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.evkin.tw4');
    Route::get('/data-evkin-tahunan-2025/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.evkin.tahunan.2025');
    Route::get('/data-umpan-balik-tw1/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.umpan.tw1');
    Route::get('/data-umpan-balik-tw2/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.umpan.tw2');
    Route::get('/data-umpan-balik-tw3/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.umpan.tw3');
    Route::get('/data-umpan-balik-tw4/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.umpan.tw4');
    Route::get('/data-model-c-2025/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.model.c.2025');
    Route::get('/data-skp-2025/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.skp.2025');
    Route::get('/model-c-2026/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.model.c.2026');
    Route::get('/model-c-2025/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.model.c.2025');
    Route::get('/coretax-2026/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.coretax.2026');
    Route::get('/data-laporan-pjlp-januari-2025/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.laporan.pjlp.januari.2025');
    Route::get('/data-laporan-ikd/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.data.laporan.ikd');
    Route::get('/data-perjanjian-kinerja-2026/{id}', [KepegawaianController::class, 'dataPaktaIntegritas'])->name('kepegawaian.data.perjanjian.kinerja.2026');

    Route::get('/data-kepegawaian', [KepegawaianController::class, 'dataKepegawaian'])->name('kepegawaian.datakepegawaian');
    Route::get('/import-paktaintegritas', [KepegawaianController::class, 'syncPaktaIntegritas'])->name('kepegawaian.import.paktaintegritas');
    Route::get('/export/{id}', [KepegawaianController::class, 'exportPaktaIntegritas'])->name('kepegawaian.export');
    Route::get('/import-pegawai', [KepegawaianController::class, 'syncPegawai'])->name('kepegawaian.import.pegawai');
    Route::post('/export-pegawai', [KepegawaianController::class, 'exportDataPegawai'])->name('kepegawaian.export.data.excel.pegawai');
    Route::get('/pemuktahiran-data', [KepegawaianController::class, 'pemuktahiranData'])->name('kepegawaian.data.pegawai.pemuktahiran');
    Route::get('/data-naikpangkat', [KepegawaianController::class, 'dataKP'])->name('kepegawaian.data.naikpangkat');
    Route::get('/data-pensiun', [KepegawaianController::class, 'dataPensiun'])->name('kepegawaian.data.pensiun');
    Route::post('/update-status-pegawai', [KepegawaianController::class, 'updateStatusPegawai'])->name('kepegawaian.gantistatuspegawai');
    Route::get('/data-eselon', [KepegawaianController::class, 'dataEselon'])->name('kepegawaian.data.eselon');
    Route::post('/tambah-eselon', [KepegawaianController::class, 'tambahEselon'])->name('kepegawaian.tambah.eselon');
    Route::post('/edit-eselon', [KepegawaianController::class, 'ubahEselon'])->name('kepegawaian.edit.eselon');
    Route::post('/hapus-eselon', [KepegawaianController::class, 'hapusEselon'])->name('kepegawaian.hapus.eselon');
    Route::get('/data-jabatan', [KepegawaianController::class, 'dataJabatan'])->name('kepegawaian.data.jabatan');
    Route::post('/tambah-jabatan', [KepegawaianController::class, 'tambahJabatan'])->name('kepegawaian.tambah.jabatan');
    Route::post('/edit-jabatan', [KepegawaianController::class, 'ubahJabatan'])->name('kepegawaian.edit.jabatan');
    Route::post('/hapus-jabatan', [KepegawaianController::class, 'hapusJabatan'])->name('kepegawaian.hapus.jabatan');
    Route::get('/data-golongan', [KepegawaianController::class, 'dataGolongan'])->name('kepegawaian.data.golongan');
    Route::post('/tambah-golongan', [KepegawaianController::class, 'tambahGolongan'])->name('kepegawaian.tambah.golongan');
    Route::post('/edit-golongan', [KepegawaianController::class, 'ubahGolongan'])->name('kepegawaian.edit.golongan');
    Route::post('/hapus-golongan', [KepegawaianController::class, 'hapusGolongan'])->name('kepegawaian.hapus.golongan');
    Route::get('/data-pendidikan', [KepegawaianController::class, 'dataPendidikan'])->name('kepegawaian.data.pendidikan');
    Route::post('/tambah-pendidikan', [KepegawaianController::class, 'tambahPendidikan'])->name('kepegawaian.tambah.pendidikan');
    Route::post('/edit-pendidikan', [KepegawaianController::class, 'ubahPendidikan'])->name('kepegawaian.edit.pendidikan');
    Route::post('/hapus-pendidikan', [KepegawaianController::class, 'hapusPendidikan'])->name('kepegawaian.hapus.pendidikan');
    Route::get('/data-bidang', [KepegawaianController::class, 'dataBidang'])->name('kepegawaian.data.bidang');
    Route::get('/data-pegawai', [KepegawaianController::class, 'dataPegawai'])->name('kepegawaian.data.pegawai');
    Route::put('/verifikasi-user/{id}', [KepegawaianController::class, 'verifikasiPemuktahiran'])->name('kepegawaian.verifikasi.user');
    Route::get('/data-kenaikanberkala', [KepegawaianController::class, 'dataKGB'])->name('kepegawaian.data.berkala');
    Route::get('export-data-pegawai', [KepegawaianController::class, 'exportDataPegawai'])->name('kepegawaian.export.data.pegawai');
    Route::post('/ganti-jenis-kerja', [KepegawaianController::class, 'updateJenisKerja'])->name('kepegawaian.gantijeniskerjapegawai');
    Route::get('/kepegawaian/data/pegawai/{id}/{action}', [KepegawaianController::class, 'ModalDataPegawai'])->name('kepegawaian.data.pegawai.modal');
    Route::post('/kepegawaian/data/export/rekap', [KepegawaianController::class, 'exportDataRekap'])->name('kepegawaian.export.data.rekap');
    Route::post('/kepegawaian/sync/{id}', [KepegawaianController::class, 'Pegawaisync'])->name('kepegawaian.sync');
    Route::get('/kepegawaian/timkerja/', [KepegawaianController::class, 'timkerja'])->name('kepegawaian.data.timkerja');
    Route::post('/kepegawaian/timkerja/input', [KepegawaianController::class, 'inputTimkerja'])->name('kepegawaian.tambah.timkerja');
    Route::get('/get-kepala-bidang/{bidang}', [KepegawaianController::class, 'getKepalaBidang']);
    Route::post('/kepegawaian/timkerja/', [KepegawaianController::class, 'editTimkerja'])->name('kepegawaian.edit.timkerja');


    // ... Tambahkan route kepegawaian lain di sini ...
});

// Halaman daftar bagian – hanya bisa diakses setelah sukses input kode akses
Route::middleware('akses.kontrol')->group(function () {
    Route::get('/daftar-bagian', [AksesController::class, 'daftarBagian'])->name('daftar.bagian');
    // Tambahkan rute lain yang memerlukan akses di sini
    Route::get('/pk-bidang', [AksesController::class, 'pkbidang'])->name('pk.bidang');
    Route::get('/data-upload', [AksesController::class, 'dataupload'])->name('data.upload');
    Route::get('/umpan-balik', [AksesController::class, 'umpanbalik'])->name('umpan.balik');
    Route::get('/evaluasi-kinerja', [AksesController::class, 'evaluasikinerja'])->name('evaluasi.kinerja');
    // Rute untuk halaman cek bidang
    Route::get('/data-sekretariat', [AksesController::class, 'datasekretariat'])->name('data.sekretariat');
    Route::get('/data-kesenian', [AksesController::class, 'datakesenian'])->name('data.kesenian');
    Route::get('/data-cagar-budaya', [AksesController::class, 'datacagarbudaya'])->name('data.cagar-budaya');
    Route::get('/data-tradisi', [AksesController::class, 'datatradisi'])->name('data.tradisi');
    Route::get('/data-sejarah', [AksesController::class, 'datasejarah'])->name('data.sejarah');
    Route::get('/data-museum', [AksesController::class, 'datamuseum'])->name('data.museum');
    Route::get('/data-taman-budaya', [AksesController::class, 'datatamanbudaya'])->name('data.taman-budaya');
    Route::get('/data-monumen', [AksesController::class, 'datamonumen'])->name('data.monumen');
    Route::get('/struktur-organisasi', [AksesController::class, 'strukturOrganisasi'])->name('struktur.organisasi');
    Route::get('/struktur-organisasi/pdf', [AksesController::class, 'cetakStrukturPegawaiPdf'])->name('struktur.pdf');
    route::get('/lihat-jajaran', [AksesController::class, 'lihatjajaran'])->name('lihat.jajaran');

    // Rute untuk halaman cek Subbag

    Route::get('/data-ppep', [AksesController::class, 'datappep'])->name('data.ppep');
    Route::get('/data-keuangan', [AksesController::class, 'datakeuangan'])->name('data.keuangan');
    Route::get('/data-umpeg', [AksesController::class, 'dataumpeg'])->name('data.umpeg');
    Route::get('/data-senirupa', [AksesController::class, 'datasenirupa'])->name('data.senirupa');
    Route::get('/data-dppa2025', [AksesController::class, 'datadppa2025'])->name('data.dppa2025');
    Route::get('/data-rak2025', [AksesController::class, 'datarak2025'])->name('data.rak2025');
    Route::get('/bendahara-penerima', [AksesController::class, 'bendaharaPenerima'])->name('bendahara.penerima');
    Route::get('/bendahara-pengeluaran', [AksesController::class, 'bendaharaPengeluaran'])->name('bendahara.pengeluaran');
    Route::get('/data-pegawaipns', [AksesController::class, 'dataPegawaipns'])->name('data.pegawaiPNS');
    Route::get('/data-pegawai-pppk', [AksesController::class, 'dataPegawaiPPPK'])->name('data.pegawaiPPPK');
    Route::get('/data-pegawai-rekap', [AksesController::class, 'dataPegawaiRekap'])->name('data.rekappegawai');
    Route::get('/data-pegawai-per-bidang', [AksesController::class, 'dataPegawaiRekapPerBidang'])->name('data.perbidangpegawai');
    Route::get('/data-pegawai-rincian', [AksesController::class, 'dataPegawaiRincian'])->name('data.rincianpegawai');
    Route::get('arsip-disbud', [AksesController::class, 'arsipDisbud'])->name('arsip.disbud');
    Route::get('/timkerja-modal/{id}', [AksesController::class, 'timKerjaModal'])->name('timkerja.modal'); // web.php
    Route::put('/timkerja/{id}/update-uraian', [AksesController::class, 'updateUraian'])->name('timkerja.update_uraian');
    Route::post('/timkerja/{id}/tambah-anggota', [AksesController::class, 'tambahAnggota'])->name('timkerja.tambah_anggota');
    Route::get('/timkerja/{id}/pegawai-ajax', [AksesController::class, 'getPegawaiAjax'])->name('timkerja.pegawai_ajax');

    Route::post('/tambah-evaluasi-tw1', [AksesController::class, 'uploadBerkas'])->name('tambah.evaluasi.tw1');
    Route::post('/tambah-evaluasi-tw2', [AksesController::class, 'uploadBerkas'])->name('tambah.evaluasi.tw2');
    Route::post('/tambah-evaluasi-tw3', [AksesController::class, 'uploadBerkas'])->name('tambah.evaluasi.tw3');
    Route::post('/tambah-evaluasi-tw4', [AksesController::class, 'uploadBerkas'])->name('tambah.evaluasi.tw4');
    Route::post('/tambah-evaluasi-tahunan', [AksesController::class, 'uploadBerkas'])->name('tambah.evaluasi.tahunan');
    Route::post('/tambah-umpan-balik-tw1', [AksesController::class, 'uploadBerkas'])->name('tambah.umpanbalik.tw1');
    Route::post('/tambah-umpan-balik-tw2', [AksesController::class, 'uploadBerkas'])->name('tambah.umpanbalik.tw2');
    Route::post('/tambah-umpan-balik-tw3', [AksesController::class, 'uploadBerkas'])->name('tambah.umpanbalik.tw3');
    Route::post('/tambah-umpan-balik-tw4', [AksesController::class, 'uploadBerkas'])->name('tambah.umpanbalik.tw4');
    Route::post('/tambah-umpan-balik-tahunan', [AksesController::class, 'uploadBerkas'])->name('tambah.umpanbalik.tahunan');
    Route::post('/tambah-pakta-2025', [AksesController::class, 'uploadBerkas'])->name('tambah.pakta.2025');
    Route::post('/tambah-pakta-1desember', [AksesController::class, 'uploadBerkas'])->name('tambah.pakta.1desember');
    Route::post('/tambah-skp-2025', [AksesController::class, 'uploadBerkas'])->name('tambah.skp.2025');
    Route::post('/tambah-model-c-2026', [AksesController::class, 'uploadBerkas'])->name('tambah.modelc2026');
    Route::post('/tambah-model-c-2025', [AksesController::class, 'uploadBerkas'])->name('tambah.modelc2025');
    Route::post('/tambah-ktp', [AksesController::class, 'uploadBerkas'])->name('tambah.data.ktp');
    Route::post('/tambah-npwp', [AksesController::class, 'uploadBerkas'])->name('tambah.data.npwp');
    Route::post('/tambah-bukurekening', [AksesController::class, 'uploadBerkas'])->name('tambah.data.rekening');
    Route::post('/tambah-bpjskesehatan', [AksesController::class, 'uploadBerkas'])->name('tambah.data.bpjs.kesehatan');
    Route::post('/tambah-kartukeluarga', [AksesController::class, 'uploadBerkas'])->name('tambah.data.kartu.keluarga');
    Route::post('/tambah-dataijazah', [AksesController::class, 'uploadBerkas'])->name('tambah.data.ijazah');
    Route::post('/tambah-coretax-2026', [AksesController::class, 'uploadBerkas'])->name('tambah.coretax.2026');
    Route::post('/tambah-laporan-ikd', [AksesController::class, 'uploadBerkas'])->name('tambah.laporan.ikd');
    Route::post('/tambah-perjanjian-kinerja-2026', [AksesController::class, 'uploadBerkas'])->name('tambah.perjanjian.kinerja.2026');
    Route::post('/tambah-pjlp-januari', [AksesController::class, 'uploadBerkas'])->name('tambah.laporan.pjlp.januari');

    Route::post('/profil-check', [AksesController::class, 'profilCekPassword'])->name('akses.cek.profil');

    Route::post('/password/send-link', [AksesController::class, 'sendResetLink'])->name('password.send.link');
    Route::get('/password/reset', [AksesController::class, 'sendResetLink'])->name('password.reset');
});
Route::middleware(['akses.kontrol', 'sudah.nip'])->group(function () {
    Route::get('/detail-pegawai', [AksesController::class, 'detailpegawai'])->name('detail.pegawai');

    // Rute untuk halaman profil pengguna, hanya bisa diakses jika sudah memiliki NIP
    // Input Data

    Route::post('/pegawai-update', [AksesController::class, 'pegawaiUpdate'])->name('pegawai.update');
    Route::post('/pemuktahiran-update', [AksesController::class, 'updateDataPegawai'])->name('pemuktahiran.update');
    Route::post('/pemuktahiran-update-pasfoto', [AksesController::class, 'updatePasFoto'])->name('pemuktahiran.update.pasfoto');

    // Rute untuk halaman reset password, hanya bisa diakses jika sudah memiliki NIP
    // Tambahkan rute lain yang memerlukan pengguna sudah memiliki NIP di sini
});