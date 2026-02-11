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
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillPaktaIntegritas1Desember()
    {
        $kumpulanJenisBaru = 'Pakta Integritas 1 Desember 2025';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill Pakta Integritas 1 Desember 2025 berhasil ditambahkan.');
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
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
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
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
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
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
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
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
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
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillUmbalTWIV()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Umpan Balik Triwulan IV';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillEvaluasiTWIV()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Evaluasi Kinerja Triwulan IV';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillUmbalTahunan2025()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Umpan Balik Tahunan 2025';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillEvaluasiTahunan2025()
    {
        // Jenis pengumpulan baru
        $kumpulanJenisBaru = 'Evaluasi Kinerja Tahunan 2025';

        // Ambil semua pegawai aktif
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
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
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $kumpulanJenisBaru, // pastikan unik per pegawai
                ],
                [
                    'kumpulan_status' => 0,
                    'kumpulan_file' => 'null',
                ],
            );
        }

        return redirect()
            ->back()
            ->with('success', "Semua pegawai berhasil dimasukkan ke pengumpulan berkas '$kumpulanJenisBaru' dengan status 0.");
    }
    public function prefillSKP2025()
    {
        $kumpulanJenisBaru = 'SKP 2025';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill SKP 2025 berhasil ditambahkan.');
    }
    public function prefillModelC2026()
    {
        $kumpulanJenisBaru = 'Model C 2026';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill SKP 2025 berhasil ditambahkan.');
    }
    public function prefillDataKTP()
    {
        $kumpulanJenisBaru = 'Data KTP';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill Data KTP berhasil ditambahkan.');
    }
    public function prefillDataNPWP()
    {
        $kumpulanJenisBaru = 'Data NPWP';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill Data NPWP berhasil ditambahkan.');
    }
    public function prefillDataBukuRekening()
    {
        $kumpulanJenisBaru = 'Data Buku Rekening';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill Data Buku Rekening berhasil ditambahkan.');
    }
    public function prefillDataBPJSKesehatan()
    {
        $kumpulanJenisBaru = 'Data BPJS Kesehatan';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill Data BPJS Kesehatan berhasil ditambahkan.');
    }
    public function prefillDataKartuKeluarga()
    {
        $kumpulanJenisBaru = 'Data Kartu Keluarga';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill Data Kartu Keluarga berhasil ditambahkan.');
    }
    public function prefillDataIjazah()
    {
        $kumpulanJenisBaru = 'Data Ijazah Terakhir';
        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
            ]);
        }

        return back()->with('success', 'Prefill Data Ijazah Terakhir berhasil ditambahkan.');
    }
    public function prefillLaporanPJLPJanuari2025()
    {
        $kumpulanJenisBaru = 'Laporan Bulanan PJLP Januari 2025';

        $pegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', '4')->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
                'kumpulan_sync' => 0,
            ]);
        }

        return back()->with('success', 'Prefill Laporan Bulanan PJLP Januari 2025 berhasil ditambahkan.');
    }
    public function prefillCoretax2026()
    {
        $kumpulanJenisBaru = 'Coretax 2026';

        $pegawai = ModelUser::where('user_status', 1)->get();

        foreach ($pegawai as $user) {
            // Tentukan identitas sesuai kondisi
            $identitas = $user->user_nip != '-' && $user->user_nip != null ? $user->user_nip : $user->user_nik; // gunakan NIK jika NIP '-'

            // Cek apakah sudah ada record
            $cek = ModelPengumpulanBerkas::where('kumpulan_user', $identitas)->where('kumpulan_jenis', $kumpulanJenisBaru)->first();

            if ($cek) {
                continue;
            }

            // Buat prefill
            ModelPengumpulanBerkas::create([
                'kumpulan_user' => $identitas,
                'kumpulan_jenis' => $kumpulanJenisBaru,
                'kumpulan_status' => 0,
                'kumpulan_file' => 'null',
                'kumpulan_sync' => 0,
            ]);
        }

        return back()->with('success', 'Prefill Coretax 2026 berhasil ditambahkan.');
    }
}