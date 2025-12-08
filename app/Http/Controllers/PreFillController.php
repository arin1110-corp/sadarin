<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ModelUser;
use App\Models\ModelBidang;
use App\Models\ModelSubBag;
use App\Models\ModelNavigasiSekretariat;
use App\Models\ModelSubNavigasiSekretariat;
use App\Models\ModelPakta;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use App\Exports\PegawaiExport;
use App\Models\ModelEselon;
use App\Models\ModelJabatan;
use App\Models\ModelGolongan;
use App\Models\ModelPendidikan;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\GoogleDriveService;
use App\Models\ModelPengumpulanBerkas;
use App\Models\ModelUbahUser;
use Carbon\Carbon;
use App\Models\ModelAdmin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PreFillController extends Controller
{
    //
    public function prefillEvaluasi()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Evaluasi Kinerja Triwulan III';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file'   => 'null',
                ]
            );
        }

        return redirect()->back()->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillPaktaIntegritas1Desember()
    {
        $kumpulanJenisBaru = 'Pakta Integritas 1 Desember 2025';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {

            // Cek apakah sudah ada record untuk jenis ini
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $user->user_nip)
                ->where('kumpulan_jenis', $kumpulanJenisBaru)
                ->first();

            // Kalau SUDAH ADA → jangan sentuh
            if ($cek) {
                continue;
            }

            // Kalau BELUM ADA → buat prefill baru
            ModelPengumpulanBerkas::create([
                'kumpulan_user'   => $user->user_nip,
                'kumpulan_jenis'  => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file'   => 'null', // WAJIB string "null" karena query lama pakai itu
            ]);
        }

        return back()->with('success', "Prefill Pakta Integritas 1 Desember 2025 berhasil ditambahkan tanpa mengubah file lama.");
    }

    public function prefillUmbal()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Umpan Balik Triwulan III';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file'   => 'null',
                ]
            );
        }

        return redirect()->back()->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillEvaluasiTWII()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Evaluasi Kinerja Triwulan II';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file'   => 'null',
                ]
            );
        }

        return redirect()->back()->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillUmbalTWII()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Umpan Balik Triwulan II';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file'   => 'null',
                ]
            );
        }

        return redirect()->back()->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillEvaluasiTWI()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Evaluasi Kinerja Triwulan I';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file'   => 'null',
                ]
            );
        }

        return redirect()->back()->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillUmbalTWI()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Umpan Balik Triwulan I';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file'   => 'null',
                ]
            );
        }

        return redirect()->back()->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillModelC2025()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Model C 2025';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file'   => 'null',
                ]
            );
        }

        return redirect()->back()->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
}