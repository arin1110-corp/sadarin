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
use App\Exports\PegawaiPerBidangExport;
use PDF;
use Illuminate\Support\Facades\Artisan;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class KepegawaianController extends Controller
{
    //
    /// Dashboard Kepegawaian
    /// Data Kepegawaian
    public function kepegawaianDashboard()
    {
        $dataPegawai = ModelUser::get();
        $dataPegawaiaktif = ModelUser::where('user_status', 1)->count();
        $dataPegawainonaktif = ModelUser::where('user_status', 0)->count();
        $datapnspegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->count();
        $datapppkparuhwaktu = ModelUser::where('user_status', 1)->where('user_jeniskerja', 3)->count();
        $datanonasn = ModelUser::where('user_status', 1)->where('user_jeniskerja', 4)->count();
        $totalPegawai = ModelUser::count();

        $pemuktahiran = ModelUser::where('user_tmt', '!=', '1990-01-01')->count();
        $pendidikan = ModelUser::where('user_pendidikan', '=', 0)->count();
        $pemuktahiranFoto = ModelUser::where('user_foto', '-')->count();
        $pemuktahiranJabatan = ModelUser::where('user_jabatan', 65)->count();

        return view('kepegawaian.dashboard', compact('dataPegawai', 'totalPegawai', 'datapnspegawai', 'datapppkpegawai', 'pendidikan', 'pemuktahiran', 'pemuktahiranFoto', 'pemuktahiranJabatan', 'datapppkparuhwaktu', 'datanonasn', 'dataPegawaiaktif', 'dataPegawainonaktif'));
    }
    public function dataPegawai()
    {
        // Ambil semua pegawai dengan join tanpa alias
        $dataPegawai = DB::table('sadarin_user')
            ->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->leftJoin('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->leftJoin('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')
            ->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->select('sadarin_user.*', 'sadarin_bidang.*', 'sadarin_jabatan.*', 'sadarin_golongan.*', 'sadarin_eselon.*', 'sadarin_pendidikan.*')
            ->orderByRaw(
                "
                    CASE
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala Dinas' THEN 0
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Sekretaris' THEN 1
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala Bidang' THEN 2
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala UPTD' THEN 3
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala%' THEN 4
                        ELSE 5
                    END,
                    sadarin_user.user_jeniskerja ASC,
                    sadarin_user.user_nama ASC
                ",
            )
            ->get();
        $listBidang = ModelBidang::get();

        $listpegawaiPNS = ModelUser::where('user_jeniskerja', 1)->get();
        $listpegawaiPPPK = ModelUser::where('user_jeniskerja', 2)->get();
        $listpegawaiPPPKParuhWaktu = ModelUser::where('user_jeniskerja', 3)->get();
        $listpegawaiNonASN = ModelUser::where('user_jeniskerja', 4)->get();

        $dataPegawaiTotal = ModelUser::get();
        $dataPegawaiaktif = ModelUser::where('user_status', 1)->count();
        $dataPegawainonaktif = ModelUser::where('user_status', 0)->count();
        $datapnspegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->count();
        $datapppkparuhwaktu = ModelUser::where('user_status', 1)->where('user_jeniskerja', 3)->count();
        $datanonasn = ModelUser::where('user_status', 1)->where('user_jeniskerja', 4)->count();
        $totalPegawai = ModelUser::count();

        return view('kepegawaian.datapegawai', compact('dataPegawai', 'listBidang', 'listpegawaiNonASN', 'listpegawaiPNS', 'listpegawaiPPPK', 'listpegawaiPPPKParuhWaktu', 'totalPegawai', 'datapnspegawai', 'datapppkpegawai', 'datapppkparuhwaktu', 'datanonasn', 'dataPegawaiaktif', 'dataPegawainonaktif'));
    }
    public function updateStatusPegawai(Request $request)
    {
        $userId = $request->input('user_id');
        $newStatus = $request->input('user_status');
        $ketStatus = $request->input('user_ket');
        $user = ModelUser::find($userId);
        if ($user) {
            $user->user_status = $newStatus;
            $user->user_ket = $ketStatus;
            $user->save();
            return redirect()->route('kepegawaian.data.pegawai')->with('success', 'Status pegawai berhasil diperbarui.');
        } else {
            return redirect()->route('kepegawaian.data.pegawai')->with('error', 'Pegawai tidak ditemukan.');
        }
    }
    public function updateJenisKerja(Request $request)
    {
        $userId = $request->input('user_id');
        $newJenisKerja = $request->input('user_jeniskerja');
        $user = ModelUser::find($userId);
        if ($user) {
            $user->user_jeniskerja = $newJenisKerja;
            $user->save();
            return redirect()->route('kepegawaian.data.pegawai')->with('success', 'Jenis kerja pegawai berhasil diperbarui.');
        } else {
            return redirect()->route('kepegawaian.data.pegawai')->with('error', 'Pegawai tidak ditemukan.');
        }
    }
    public function pemuktahiranData()
    {
        // Ambil semua pegawai dengan join tanpa alias
        $dataPegawai = DB::table('sadarin_ubahuser')
            ->leftJoin('sadarin_bidang', 'sadarin_ubahuser.ubahuser_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_golongan', 'sadarin_ubahuser.ubahuser_golongan', '=', 'sadarin_golongan.golongan_id')
            ->leftJoin('sadarin_eselon', 'sadarin_ubahuser.ubahuser_eselon', '=', 'sadarin_eselon.eselon_id')
            ->leftJoin('sadarin_pendidikan', 'sadarin_ubahuser.ubahuser_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')
            ->leftJoin('sadarin_jabatan', 'sadarin_ubahuser.ubahuser_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->select('sadarin_ubahuser.*', 'sadarin_bidang.*', 'sadarin_jabatan.*', 'sadarin_golongan.*', 'sadarin_eselon.*', 'sadarin_pendidikan.*')
            ->where('sadarin_ubahuser.ubahuser_status', 0)
            ->orderByRaw(
                "
                    sadarin_ubahuser.created_at DESC
                ",
            )
            ->get();

        $datapnspegawai = ModelUbahUser::where('ubahuser_status', 0)->where('ubahuser_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUbahUser::where('ubahuser_status', 0)->where('ubahuser_jeniskerja', 2)->count();
        $totalPegawaiPemuktahiran = ModelUbahUser::where('ubahuser_status', 0)->count();
        $totalPegawai = ModelUser::where('user_status', 1)->count();

        return view('kepegawaian.pemuktahiran', compact('dataPegawai', 'totalPegawai', 'totalPegawaiPemuktahiran', 'datapnspegawai', 'datapppkpegawai'));
    }
    public function pegawaiUpdate(Request $request)
    {
        $validator = Validator::make(
            array_merge($request->all(), $request->allFiles()), // 👈 tambahin file
            [
                'user_nama' => 'required|string|max:100',
                'user_nik' => 'required|string|max:100',
                'user_nip' => 'required|string|max:100',
                'user_email' => 'nullable|email|max:100',
                'user_notelp' => 'nullable|numeric',
                'user_npwp' => 'nullable|numeric',
                'user_bpjs' => 'nullable|numeric',
                'user_norek' => 'nullable|numeric',
                'user_jmltanggungan' => 'nullable|numeric',
                'user_tgllahir' => 'required|date',
                'user_tmt' => 'required|date',
                'user_spmt' => 'required|date',
                'user_tempatlahir' => 'required|string|max:100',

                'user_foto' => 'nullable|file|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'user_foto.image' => 'File yang diupload harus berupa gambar.',
                'user_foto.mimes' => 'Format foto hanya boleh JPG atau PNG.',
                'user_foto.max' => 'Ukuran foto maksimal 2MB.',
            ],
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil data user asli dari tabel sadarin_user
        $userAsli = ModelUser::findOrFail($request->user_id);
        // Ambil user
        $ubah = new ModelUbahUser();
        $ubah->ubahuser_iduser = $request->user_id;
        $ubah->ubahuser_nip = $request->user_nip;
        $ubah->ubahuser_nama = $request->user_nama;
        $ubah->ubahuser_nik = $request->user_nik;
        $ubah->ubahuser_tgllahir = $request->user_tgllahir;
        $ubah->ubahuser_jabatan = $request->jabatan_id;
        $ubah->ubahuser_npwp = $request->user_npwp;
        $ubah->ubahuser_bpjs = $request->user_bpjs;
        $ubah->ubahuser_pendidikan = $request->user_pendidikan;
        $ubah->ubahuser_norek = $request->user_norek;
        $ubah->ubahuser_tmt = $request->user_tmt;
        $ubah->ubahuser_tempatlahir = $request->user_tempatlahir;
        $ubah->ubahuser_spmt = $request->user_spmt;
        $ubah->ubahuser_gelardepan = $request->user_gelardepan;
        $ubah->ubahuser_gelarbelakang = $request->user_gelarbelakang;
        $ubah->ubahuser_kelasjabatan = $request->user_kelasjabatan;
        $ubah->ubahuser_eselon = $request->user_eselon;
        $ubah->ubahuser_golongan = $request->user_golongan;
        $ubah->ubahuser_email = $request->user_email;
        $ubah->ubahuser_notelp = $request->user_notelp;
        $ubah->ubahuser_alamat = $request->user_alamat;
        $ubah->ubahuser_jk = $request->user_jk;
        $ubah->ubahuser_bidang = $request->bidang_id;
        $ubah->ubahuser_jmltanggungan = $request->user_jmltanggungan;
        $ubah->ubahuser_status = 0;
        $ubah->ubahuser_jeniskerja = $request->user_jeniskerja;
        // Cek upload foto
        if ($request->hasFile('user_foto')) {
            $file = $request->file('user_foto');
            $nip = $request->user_nip;
            $filename = "{$nip}_Pasfoto." . $file->getClientOriginalExtension();

            // simpan langsung ke public/assets/foto_pegawai
            $destinationPath = public_path('assets/foto_pegawai');
            $file->move($destinationPath, $filename);

            // simpan path relatif untuk dipakai di img src
            $ubah->ubahuser_foto = "assets/foto_pegawai/{$filename}";
        } else {
            // pakai foto lama dari tabel user
            $ubah->ubahuser_foto = $userAsli->user_foto;
        }

        $ubah->save();

        return redirect()->back()->with('success', 'Data perubahan berhasil disimpan. Menunggu verifikasi admin.');
    }
    public function updateDataPegawai(Request $request)
    {
        // Cari user berdasarkan ID
        $user = ModelUser::find($request->user_id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Validasi semua field kecuali foto
        $request->validate([
            'user_nama' => 'required|string|max:100',
            'user_nik' => 'required|string|max:100',
            'user_nip' => 'required|string|max:100',
            'user_email' => 'nullable|email|max:100',
            'user_notelp' => 'nullable|string|max:15',
            'user_npwp' => 'nullable|string|max:150',
            'user_bpjs' => 'nullable|string|max:150',
            'user_norek' => 'nullable|string|max:150',
            'user_jmltanggungan' => 'nullable|string|max:10',
            'user_tgllahir' => 'required|date',
            'user_tmt' => 'required|date',
            'user_spmt' => 'required|date',
            // jangan validasi user_foto di sini
        ]);

        // Update semua field dari form
        $user->user_gelardepan = $request->user_gelardepan;
        $user->user_gelarbelakang = $request->user_gelarbelakang;
        $user->user_nama = $request->user_nama;
        $user->user_jk = $request->user_jk;
        $user->user_nip = $request->user_nip;
        $user->user_nik = $request->user_nik;
        $user->user_jabatan = $request->jabatan_id;
        $user->user_bidang = $request->bidang_id;
        $user->user_email = $request->user_email;
        $user->user_notelp = $request->user_notelp;
        $user->user_norek = $request->user_norek;
        $user->user_jmltanggungan = $request->user_jmltanggungan;
        $user->user_npwp = $request->user_npwp;
        $user->user_bpjs = $request->user_bpjs;
        $user->user_alamat = $request->user_alamat;
        $user->user_golongan = $request->user_golongan;
        $user->user_kelasjabatan = $request->user_kelasjabatan;
        $user->user_eselon = $request->user_eselon;
        $user->user_tmt = $request->user_tmt;
        $user->user_spmt = $request->user_spmt;
        $user->user_tempatlahir = $request->user_tempatlahir;
        $user->user_tgllahir = $request->user_tgllahir;
        $user->user_pendidikan = $request->user_pendidikan;
        $user->user_jeniskerja = $request->user_jeniskerja;

        $user->save();

        return redirect()->back()->with('success', 'Data pegawai berhasil diupdate.');
    }
    public function updatePasfoto(Request $request)
    {
        $request->validate(
            [
                'user_foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'user_foto.image' => 'File yang diupload harus berupa gambar.',
                'user_foto.mimes' => 'Format foto hanya boleh JPG atau PNG.',
                'user_foto.max' => 'Ukuran foto maksimal 2MB.',
            ],
        );

        $user = ModelUser::findOrFail($request->user_id);

        if ($request->hasFile('user_foto')) {
            $file = $request->file('user_foto');

            // Ambil NIP & NIK (request → fallback ke data lama)
            $nip = $request->user_nip ?? $user->user_nip;
            $nik = $request->user_nik ?? $user->user_nik;

            // Jika NIP adalah '-' maka gunakan NIK
            $finalId = $nip == '-' || $nip == null || $nip == '' ? $nik : $nip;

            $filename = "{$finalId}_Pasfoto." . $file->getClientOriginalExtension();

            $destinationPath = public_path('assets/foto_pegawai');
            $file->move($destinationPath, $filename);

            $user->user_foto = "assets/foto_pegawai/{$filename}";
        }

        $user->save();

        return redirect()->back()->with('success', 'Data pegawai berhasil diupdate.');
    }

    public function dataPendidikan()
    {
        $pendidikans = DB::table('sadarin_pendidikan')->get();
        return view('kepegawaian.pendidikan', compact('pendidikans'));
    }

    public function tambahPendidikan(Request $request)
    {
        $request->validate([
            'pendidikan_jenjang' => 'required|string|max:100',
            'pendidikan_jurusan' => 'required|string|max:255',
            'pendidikan_status' => 'required|integer',
        ]);

        DB::table('sadarin_pendidikan')->insert([
            'pendidikan_jenjang' => $request->pendidikan_jenjang,
            'pendidikan_jurusan' => $request->pendidikan_jurusan,
            'pendidikan_status' => $request->pendidikan_status,
        ]);

        return redirect()->back()->with('success', 'Data pendidikan berhasil ditambahkan.');
    }
    public function hapusPendidikan(Request $request)
    {
        $id = $request->pendidikan_id;
        DB::table('sadarin_pendidikan')->where('pendidikan_id', $id)->delete();
        return redirect()->back()->with('success', 'Data pendidikan berhasil dihapus.');
    }
    public function ubahPendidikan(Request $request)
    {
        $request->validate([
            'pendidikan_jenjang' => 'required|string|max:100',
            'pendidikan_jurusan' => 'required|string|max:255',
            'pendidikan_status' => 'required|integer',
        ]);

        DB::table('sadarin_pendidikan')
            ->where('pendidikan_id', $request->pendidikan_id)
            ->update([
                'pendidikan_jenjang' => $request->pendidikan_jenjang,
                'pendidikan_jurusan' => $request->pendidikan_jurusan,
            ]);

        return redirect()->back()->with('success', 'Data pendidikan berhasil diubah.');
    }
    public function dataGolongan()
    {
        $golongans = DB::table('sadarin_golongan')->get();
        return view('kepegawaian.golongan', compact('golongans'));
    }
    public function tambahGolongan(Request $request)
    {
        $request->validate([
            'golongan_nama' => 'required|string|max:100',
            'golongan_pangkat' => 'nullable|string|max:255',
            'golongan_status' => 'required|integer',
        ]);

        DB::table('sadarin_golongan')->insert([
            'golongan_nama' => $request->golongan_nama,
            'golongan_pangkat' => $request->golongan_pangkat,
            'golongan_status' => $request->golongan_status,
        ]);

        return redirect()->back()->with('success', 'Data golongan berhasil ditambahkan.');
    }
    public function hapusGolongan(Request $request)
    {
        $id = $request->golongan_id;
        DB::table('sadarin_golongan')->where('golongan_id', $id)->delete();
        return redirect()->back()->with('success', 'Data golongan berhasil dihapus.');
    }
    public function ubahGolongan(Request $request)
    {
        $request->validate([
            'golongan_nama' => 'required|string|max:100',
            'golongan_kategori' => 'nullable|string|max:255',
            'golongan_status' => 'required|integer',
        ]);

        DB::table('sadarin_golongan')
            ->where('golongan_id', $request->golongan_id)
            ->update([
                'golongan_nama' => $request->golongan_nama,
                'golongan_kategori' => $request->golongan_pangkat,
                'golongan_status' => $request->golongan_status,
            ]);

        return redirect()->back()->with('success', 'Data golongan berhasil diubah.');
    }
    public function dataJabatan()
    {
        $jabatans = DB::table('sadarin_jabatan')->get();
        return view('kepegawaian.jabatan', compact('jabatans'));
    }
    public function tambahJabatan(Request $request)
    {
        $request->validate([
            'jabatan_nama' => 'required|string|max:255',
            'jabatan_kategori' => 'required|string|max:255',
            'jabatan_status' => 'required|integer',
        ]);

        DB::table('sadarin_jabatan')->insert([
            'jabatan_nama' => $request->jabatan_nama,
            'jabatan_kategori' => $request->jabatan_kategori,
            'jabatan_status' => $request->jabatan_status,
        ]);

        return redirect()->back()->with('success', 'Data jabatan berhasil ditambahkan.');
    }
    public function hapusJabatan(Request $request)
    {
        $id = $request->jabatan_id;
        DB::table('sadarin_jabatan')->where('jabatan_id', $id)->delete();
        return redirect()->back()->with('success', 'Data jabatan berhasil dihapus.');
    }
    public function ubahJabatan(Request $request)
    {
        $request->validate([
            'jabatan_nama' => 'required|string|max:255',
            'jabatan_kategori' => 'required|string|max:255',
            'jabatan_status' => 'required|integer',
        ]);

        DB::table('sadarin_jabatan')
            ->where('jabatan_id', $request->jabatan_id)
            ->update([
                'jabatan_nama' => $request->jabatan_nama,
                'jabatan_kategori' => $request->jabatan_kategori,
                'jabatan_status' => $request->jabatan_status,
            ]);

        return redirect()->back()->with('success', 'Data jabatan berhasil diubah.');
    }
    public function dataEselon()
    {
        $eselons = DB::table('sadarin_eselon')->get();
        return view('kepegawaian.eselon', compact('eselons'));
    }
    public function tambahEselon(Request $request)
    {
        $request->validate([
            'eselon_nama' => 'required|string|max:100',
            'eselon_status' => 'required|integer',
        ]);

        DB::table('sadarin_eselon')->insert([
            'eselon_nama' => $request->eselon_nama,
            'eselon_status' => $request->eselon_status,
        ]);

        return redirect()->back()->with('success', 'Data eselon berhasil ditambahkan.');
    }
    public function hapusEselon(Request $request)
    {
        $id = $request->eselon_id;
        DB::table('sadarin_eselon')->where('eselon_id', $id)->delete();
        return redirect()->back()->with('success', 'Data eselon berhasil dihapus.');
    }
    public function ubahEselon(Request $request)
    {
        $request->validate([
            'eselon_nama' => 'required|string|max:100',
            'eselon_status' => 'required|integer',
        ]);

        DB::table('sadarin_eselon')
            ->where('eselon_id', $request->eselon_id)
            ->update([
                'eselon_nama' => $request->eselon_nama,
                'eselon_status' => $request->eselon_status,
            ]);

        return redirect()->back()->with('success', 'Data eselon berhasil diubah.');
    }
    public function dataPensiun()
    {
        $now = Carbon::now();
        $startThisMonth = $now->copy()->startOfMonth();
        $endThisMonth = $now->copy()->endOfMonth();

        // ambil data user yang punya tanggal lahir (optimalkan with() sesuai relasi di projectmu)
        $users = ModelUser::with(['jabatan', 'eselon', 'bidang', 'pendidikan', 'golongan'])
            ->whereNotNull('user_tgllahir')
            ->get();

        // hitung tanggal_pensiun untuk tiap user (pakai accessor jika sudah ada di model)
        $users = $users->map(function ($u) {
            if (isset($u->tanggal_pensiun) && $u->tanggal_pensiun instanceof Carbon) {
                $tp = $u->tanggal_pensiun;
            } else {
                $tp = $this->hitungTanggalPensiun($u);
            }
            $u->tanggal_pensiun = $tp instanceof Carbon ? $tp : Carbon::parse($tp);
            return $u;
        });

        // Statistik hitungan
        $pensiunBulanIni = $users->whereBetween('tanggal_pensiun', [$startThisMonth, $endThisMonth])->count();
        $pensiunBulanDepan = $users->whereBetween('tanggal_pensiun', [$now->copy()->addMonth()->startOfMonth(), $now->copy()->addMonth()->endOfMonth()])->count();
        $pensiun3Bulan = $users->whereBetween('tanggal_pensiun', [$now, $now->copy()->addMonths(3)->endOfMonth()])->count();
        $pensiun6Bulan = $users->whereBetween('tanggal_pensiun', [$now, $now->copy()->addMonths(6)->endOfMonth()])->count();
        $pensiun1Tahun = $users->whereBetween('tanggal_pensiun', [$now, $now->copy()->addYear()->endOfMonth()])->count();

        // Statistik tahun 2025 dan 2026
        $pensiun2025 = $users->filter(fn($u) => $u->tanggal_pensiun->year == 2025)->count();
        $pensiun2026 = $users->filter(fn($u) => $u->tanggal_pensiun->year == 2026)->count();

        // List detail pegawai yang pensiun tahun 2025 dan 2026 (collection of models)
        $listPensiun2025 = $users->filter(fn($u) => $u->tanggal_pensiun->year == 2025)->sortBy('tanggal_pensiun');
        $listPensiun2026 = $users->filter(fn($u) => $u->tanggal_pensiun->year == 2026)->sortBy('tanggal_pensiun');

        // daftar pegawai yang pensiun 1 tahun ke depan (dari awal bulan sekarang sampai 1 tahun ke depan)
        $daftarPensiun = $users
            ->filter(function ($u) use ($startThisMonth, $now) {
                return $u->tanggal_pensiun >= $startThisMonth && $u->tanggal_pensiun <= $now->copy()->addYear()->endOfYear();
            })
            ->sortBy('tanggal_pensiun')
            // normalisasi supaya view mudah akses fields (tetap bisa pakai model kalau mau)
            ->map(function ($u) {
                return (object) [
                    'id' => $u->user_id ?? ($u->id ?? null),
                    'user_id' => $u->user_id ?? ($u->id ?? null),
                    'user_nama' => $u->user_nama,
                    'user_nip' => $u->user_nip,
                    'user_nik' => $u->user_nik ?? null,
                    'user_jabatan' => $u->jabatan->jabatan_nama ?? ($u->user_jabatan ?? '-'),
                    'user_eselon' => $u->eselon->eselon_nama ?? ($u->user_eselon ?? '-'),
                    'user_jeniskerja' => $u->user_jeniskerja,
                    'user_status' => $u->user_status ?? null,
                    'user_jk' => $u->user_jk ?? null,
                    'bidang_nama' => $u->bidang->bidang_nama ?? ($u->user_bidang ?? '-'),
                    'pendidikan_jenjang' => $u->pendidikan->pendidikan_jenjang ?? '-',
                    'pendidikan_jurusan' => $u->pendidikan->pendidikan_jurusan ?? '-',

                    'user_kelasjabatan' => $u->user_kelasjabatan ?? null,
                    'golongan_nama' => $u->golongan->golongan_nama ?? ($u->user_golongan ?? '-'),
                    'golongan_pangkat' => $u->golongan->golongan_pangkat ?? null,
                    'user_tmt' => $u->user_tmt ?? null,
                    'user_spmt' => $u->user_spmt ?? null,
                    'user_foto' => $u->user_foto ?? null,
                    'tanggal_pensiun' => $u->tanggal_pensiun,
                    'user_tgllahir' => $u->user_tgllahir ?? null,
                    'user_alamat' => $u->user_alamat ?? null,
                    'user_notelp' => $u->user_notelp ?? null,
                    'user_email' => $u->user_email ?? null,
                    'user_bpjs' => $u->user_bpjs ?? null,
                    'user_norek' => $u->user_norek ?? null,
                    'user_npwp' => $u->user_npwp ?? null,
                    'user_jmltanggungan' => $u->user_jmltanggungan ?? null,
                    'user_gelardepan' => $u->user_gelardepan ?? null,
                    'user_gelarbelakang' => $u->user_gelarbelakang ?? null,
                    'user_tempatlahir' => $u->user_tempatlahir ?? null,
                ];
            });

        // statistik besar PNS & PPPK yang pensiun TAHUN INI
        $pnsTahunIni = $users
            ->filter(function ($u) use ($now) {
                return ((string) $u->user_jeniskerja === '1' || $u->user_jeniskerja == 1) && $u->tanggal_pensiun->year == $now->year;
            })
            ->count();

        $pppkTahunIni = $users
            ->filter(function ($u) use ($now) {
                return ((string) $u->user_jeniskerja === '2' || $u->user_jeniskerja == 2) && $u->tanggal_pensiun->year == $now->year;
            })
            ->count();

        // opsional: statistik total agar sesuai bagian atas dashboard
        $totalPegawai = ModelUser::count();
        $datapnspegawai = ModelUser::where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_jeniskerja', 2)->count();

        return view('kepegawaian.pensiun', compact('totalPegawai', 'datapnspegawai', 'datapppkpegawai', 'pensiunBulanIni', 'pensiunBulanDepan', 'pensiun3Bulan', 'pensiun6Bulan', 'pensiun1Tahun', 'pensiun2025', 'pensiun2026', 'pnsTahunIni', 'pppkTahunIni', 'daftarPensiun', 'listPensiun2025', 'listPensiun2026'));
    }

    /**
     * Hitung tanggal pensiun berdasarkan nama/jabatan/kategori.
     * (logika fleksibel: cari kata kunci seperti "ahli utama", "ahli madya", "eselon iii", "kepala dinas", dll)
     */
    protected function hitungTanggalPensiun($u)
    {
        $tglLahir = Carbon::parse($u->user_tgllahir);
        $usia = 58; // default

        $nama = strtolower($u->jabatan->jabatan_nama ?? ($u->user_jabatan ?? ''));
        $kategori = strtolower($u->jabatan->jabatan_kategori ?? ($u->jabatan_kategori ?? ''));

        // Struktural
        if ($kategori === 'struktural' || str_contains($nama, 'eselon') || str_contains($nama, 'kepala dinas')) {
            if (str_contains($nama, 'pimpinan tinggi madya')) {
                $usia = 60;
            } elseif (str_contains($nama, 'pimpinan tinggi pratama')) {
                $usia = 60;
            } elseif (str_contains($nama, 'kepala dinas')) {
                $usia = 60;
            } elseif (str_contains($nama, 'eselon iii') || str_contains($nama, 'eselon iv') || str_contains($nama, 'administrator') || str_contains($nama, 'pengawas')) {
                $usia = 58;
            }
        }

        // Fungsional
        if ($kategori === 'fungsional' || str_contains($nama, 'ahli') || str_contains($nama, 'guru besar') || str_contains($nama, 'profesor')) {
            if (str_contains($nama, 'ahli utama')) {
                $usia = 65;
            } elseif (str_contains($nama, 'ahli madya')) {
                $usia = 60;
            } elseif (str_contains($nama, 'guru besar') || str_contains($nama, 'profesor')) {
                $usia = 70;
            } else {
                // default fungsional
                $usia = 65;
            }
        }

        return $tglLahir->copy()->addYears($usia);
    }
    public function dataKgb()
    {
        $now = Carbon::now();
        $startThisMonth = $now->copy()->startOfMonth();
        $endThisMonth = $now->copy()->endOfMonth();

        // ambil data user yang punya TMT (dasar perhitungan KGB)
        $users = ModelUser::with(['jabatan', 'eselon', 'bidang', 'pendidikan', 'golongan'])
            ->whereNotNull('user_tmt')
            ->get();

        // hitung tanggal_kgb untuk tiap user
        $users = $users->map(function ($u) {
            if (isset($u->tanggal_kgb) && $u->tanggal_kgb instanceof Carbon) {
                $tkgb = $u->tanggal_kgb;
            } else {
                $tkgb = $this->hitungTanggalKgb($u);
            }
            $u->tanggal_kgb = $tkgb instanceof Carbon ? $tkgb : Carbon::parse($tkgb);
            return $u;
        });

        // Statistik hitungan
        $kgbBulanIni = $users->whereBetween('tanggal_kgb', [$startThisMonth, $endThisMonth])->count();
        $kgbBulanDepan = $users->whereBetween('tanggal_kgb', [$now->copy()->addMonth()->startOfMonth(), $now->copy()->addMonth()->endOfMonth()])->count();
        $kgb3Bulan = $users->whereBetween('tanggal_kgb', [$now, $now->copy()->addMonths(3)->endOfMonth()])->count();
        $kgb6Bulan = $users->whereBetween('tanggal_kgb', [$now, $now->copy()->addMonths(6)->endOfMonth()])->count();
        $kgb1Tahun = $users->whereBetween('tanggal_kgb', [$now, $now->copy()->addYear()->endOfMonth()])->count();

        // List detail KGB tahun 2025 & 2026
        $listKgb2025 = $users->filter(fn($u) => $u->tanggal_kgb->year == 2025)->sortBy('tanggal_kgb')->values();
        $listKgb2026 = $users->filter(fn($u) => $u->tanggal_kgb->year == 2026)->sortBy('tanggal_kgb')->values();

        // daftar pegawai KGB dalam 1 tahun ke depan
        $daftarKgb = $users
            ->filter(function ($u) use ($startThisMonth, $now) {
                return $u->tanggal_kgb >= $startThisMonth && $u->tanggal_kgb <= $now->copy()->addYear()->endOfYear();
            })
            ->map(function ($u) {
                return (object) [
                    'id' => $u->user_id ?? ($u->id ?? null),
                    'user_id' => $u->user_id ?? ($u->id ?? null),
                    'user_nama' => $u->user_nama,
                    'user_nip' => $u->user_nip,
                    'user_nik' => $u->user_nik ?? null,
                    'user_jabatan' => $u->jabatan->jabatan_nama ?? ($u->user_jabatan ?? '-'),
                    'user_eselon' => $u->eselon->eselon_nama ?? ($u->user_eselon ?? '-'),
                    'user_jeniskerja' => $u->user_jeniskerja,
                    'user_status' => $u->user_status ?? null,
                    'user_jk' => $u->user_jk ?? null,
                    'bidang_nama' => $u->bidang->bidang_nama ?? ($u->user_bidang ?? '-'),
                    'pendidikan_jenjang' => $u->pendidikan->pendidikan_jenjang ?? '-',
                    'pendidikan_jurusan' => $u->pendidikan->pendidikan_jurusan ?? '-',
                    'user_kelasjabatan' => $u->user_kelasjabatan ?? null,
                    'golongan_nama' => $u->golongan->golongan_nama ?? ($u->user_golongan ?? '-'),
                    'golongan_pangkat' => $u->golongan->golongan_pangkat ?? null,
                    'user_tmt' => $u->user_tmt ?? null,
                    'user_spmt' => $u->user_spmt ?? null,
                    'user_foto' => $u->user_foto ?? null,
                    'tanggal_kgb' => Carbon::parse($u->tanggal_kgb), // 🔥 PAKSA JADI CARBON
                    'user_tgllahir' => $u->user_tgllahir ?? null,
                    'user_alamat' => $u->user_alamat ?? null,
                    'user_notelp' => $u->user_notelp ?? null,
                    'user_email' => $u->user_email ?? null,
                    'user_bpjs' => $u->user_bpjs ?? null,
                    'user_norek' => $u->user_norek ?? null,
                    'user_npwp' => $u->user_npwp ?? null,
                    'user_jmltanggungan' => $u->user_jmltanggungan ?? null,
                    'user_gelardepan' => $u->user_gelardepan ?? null,
                    'user_gelarbelakang' => $u->user_gelarbelakang ?? null,
                    'user_tempatlahir' => $u->user_tempatlahir ?? null,
                ];
            })
            ->sortBy(function ($u) {
                return $u->tanggal_kgb->timestamp; // 🔥 urut berdasarkan timestamp
            })
            ->values();

        // opsional: total pegawai dll
        $totalPegawai = ModelUser::count();
        $datapnspegawai = ModelUser::where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_jeniskerja', 2)->count();

        return view('kepegawaian.kenaikanberkala', compact('totalPegawai', 'datapnspegawai', 'datapppkpegawai', 'kgbBulanIni', 'kgbBulanDepan', 'kgb3Bulan', 'kgb6Bulan', 'kgb1Tahun', 'listKgb2025', 'listKgb2026', 'daftarKgb'));
    }

    /**
     * Hitung tanggal KGB berikutnya (umumnya setiap 2 tahun dari TMT/KGB terakhir)
     */
    protected function hitungTanggalKgb($u)
    {
        $tmt = Carbon::parse($u->user_tmt);
        return $tmt->copy()->addYears(2); // aturan umum: 2 tahun sekali
    }
    public function dataKp()
    {
        $now = Carbon::now();
        $startThisMonth = $now->copy()->startOfMonth();
        $endThisMonth = $now->copy()->endOfMonth();

        // ambil data user yang punya TMT (dasar perhitungan KP)
        $users = ModelUser::with(['jabatan', 'eselon', 'bidang', 'pendidikan', 'golongan'])
            ->whereNotNull('user_tmt')
            ->get();

        // hitung tanggal KP untuk tiap user
        $users = $users->map(function ($u) {
            if (isset($u->tanggal_kp) && $u->tanggal_kp instanceof Carbon) {
                $tkp = $u->tanggal_kp;
            } else {
                $tkp = $this->hitungTanggalKp($u);
            }
            $u->tanggal_kp = $tkp instanceof Carbon ? $tkp : Carbon::parse($tkp);
            return $u;
        });

        // Statistik hitungan
        $kpBulanIni = $users->whereBetween('tanggal_kp', [$startThisMonth, $endThisMonth])->count();
        $kpBulanDepan = $users->whereBetween('tanggal_kp', [$now->copy()->addMonth()->startOfMonth(), $now->copy()->addMonth()->endOfMonth()])->count();
        $kp3Bulan = $users->whereBetween('tanggal_kp', [$now, $now->copy()->addMonths(3)->endOfMonth()])->count();
        $kp6Bulan = $users->whereBetween('tanggal_kp', [$now, $now->copy()->addMonths(6)->endOfMonth()])->count();
        $kp1Tahun = $users->whereBetween('tanggal_kp', [$now, $now->copy()->addYear()->endOfMonth()])->count();

        // List detail KP tahun 2025 & 2026
        $listKp2025 = $users->filter(fn($u) => $u->tanggal_kp->year == 2025)->sortBy('tanggal_kp')->values();
        $listKp2026 = $users->filter(fn($u) => $u->tanggal_kp->year == 2026)->sortBy('tanggal_kp')->values();

        // daftar pegawai KP dalam 1 tahun ke depan
        $daftarKp = $users
            ->filter(function ($u) use ($startThisMonth, $now) {
                return $u->tanggal_kp >= $startThisMonth && $u->tanggal_kp <= $now->copy()->addYear()->endOfYear();
            })
            ->map(function ($u) {
                return (object) [
                    'id' => $u->user_id ?? ($u->id ?? null),
                    'user_id' => $u->user_id ?? ($u->id ?? null),
                    'user_nama' => $u->user_nama,
                    'user_nip' => $u->user_nip,
                    'user_nik' => $u->user_nik ?? null,
                    'user_jabatan' => $u->jabatan->jabatan_nama ?? ($u->user_jabatan ?? '-'),
                    'user_eselon' => $u->eselon->eselon_nama ?? ($u->user_eselon ?? '-'),
                    'user_jeniskerja' => $u->user_jeniskerja,
                    'user_status' => $u->user_status ?? null,
                    'user_jk' => $u->user_jk ?? null,
                    'bidang_nama' => $u->bidang->bidang_nama ?? ($u->user_bidang ?? '-'),
                    'pendidikan_jenjang' => $u->pendidikan->pendidikan_jenjang ?? '-',
                    'pendidikan_jurusan' => $u->pendidikan->pendidikan_jurusan ?? '-',
                    'user_kelasjabatan' => $u->user_kelasjabatan ?? null,
                    'golongan_nama' => $u->golongan->golongan_nama ?? ($u->user_golongan ?? '-'),
                    'golongan_pangkat' => $u->golongan->golongan_pangkat ?? null,
                    'jabatan_kategori' => $u->jabatan->jabatan_kategori ?? null,
                    'user_tmt' => $u->user_tmt ?? null,
                    'user_spmt' => $u->user_spmt ?? null,
                    'user_foto' => $u->user_foto ?? null,
                    'tanggal_kp' => Carbon::parse($u->tanggal_kp), // 🔥 PAKSA JADI CARBON
                    'user_tgllahir' => $u->user_tgllahir ?? null,
                    'user_alamat' => $u->user_alamat ?? null,
                    'user_notelp' => $u->user_notelp ?? null,
                    'user_email' => $u->user_email ?? null,
                    'user_bpjs' => $u->user_bpjs ?? null,
                    'user_norek' => $u->user_norek ?? null,
                    'user_npwp' => $u->user_npwp ?? null,
                    'user_jmltanggungan' => $u->user_jmltanggungan ?? null,
                    'user_gelardepan' => $u->user_gelardepan ?? null,
                    'user_gelarbelakang' => $u->user_gelarbelakang ?? null,
                    'user_tempatlahir' => $u->user_tempatlahir ?? null,
                ];
            })
            ->sortBy(function ($u) {
                return $u->tanggal_kp->timestamp; // urut berdasarkan tanggal KP
            })
            ->values();

        // opsional: total pegawai dll
        $totalPegawai = ModelUser::count();
        $datapnspegawai = ModelUser::where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_jeniskerja', 2)->count();

        return view('kepegawaian.kenaikanpangkat', compact('totalPegawai', 'datapnspegawai', 'datapppkpegawai', 'kpBulanIni', 'kpBulanDepan', 'kp3Bulan', 'kp6Bulan', 'kp1Tahun', 'listKp2025', 'listKp2026', 'daftarKp'));
    }

    /**
     * Hitung tanggal KP berikutnya (umumnya setiap 4 tahun dari TMT/KP terakhir)
     */
    protected function hitungTanggalKp($u)
    {
        $tmt = Carbon::parse($u->user_tmt);
        return $tmt->copy()->addYears(4); // aturan umum: 4 tahun sekali
    }

    public function verifikasiPemuktahiran($id)
    {
        $ubah = ModelUbahUser::findOrFail($id);

        // cari user di tabel sadarin_user sesuai id user
        $user = ModelUser::findOrFail($ubah->ubahuser_iduser);

        // mapping field satu per satu
        $user->user_nama = $ubah->ubahuser_nama;
        $user->user_nip = $ubah->ubahuser_nip;
        $user->user_nik = $ubah->ubahuser_nik;
        $user->user_gelardepan = $ubah->ubahuser_gelardepan;
        $user->user_gelarbelakang = $ubah->ubahuser_gelarbelakang;
        $user->user_jk = $ubah->ubahuser_jk;
        $user->user_tgllahir = $ubah->ubahuser_tgllahir;
        $user->user_pendidikan = $ubah->ubahuser_pendidikan;
        $user->user_jabatan = $ubah->ubahuser_jabatan;
        $user->user_golongan = $ubah->ubahuser_golongan;
        $user->user_eselon = $ubah->ubahuser_eselon;
        $user->user_tempatlahir = $ubah->ubahuser_tempatlahir;
        $user->user_kelasjabatan = $ubah->ubahuser_kelasjabatan;
        $user->user_bidang = $ubah->ubahuser_bidang;
        $user->user_tmt = $ubah->ubahuser_tmt;
        $user->user_spmt = $ubah->ubahuser_spmt;
        $user->user_jeniskerja = $ubah->ubahuser_jeniskerja;
        $user->user_alamat = $ubah->ubahuser_alamat;
        $user->user_notelp = $ubah->ubahuser_notelp;
        $user->user_email = $ubah->ubahuser_email;
        $user->user_bpjs = $ubah->ubahuser_bpjs;
        $user->user_norek = $ubah->ubahuser_norek;
        $user->user_npwp = $ubah->ubahuser_npwp;
        $user->user_jmltanggungan = $ubah->ubahuser_jmltanggungan;
        $user->user_foto = $ubah->ubahuser_foto; // karena simpan di path sama

        $user->save();

        // update status ubahuser
        $ubah->ubahuser_status = 1;
        $ubah->save();

        return redirect()->back()->with('success', 'Data pegawai berhasil diverifikasi dan disimpan.');
    }
    /// Akhir Data Kepegawaian

    /// awal Pakta Integritas
    public function dataPaktaIntegritas($id)
    {
        // Ambil semua pegawai dengan join tanpa alias
        $dataPegawai = DB::table('sadarin_user')
            ->leftJoin('sadarin_pengumpulanberkas', function ($join) {
                $join->on(
                    'sadarin_pengumpulanberkas.kumpulan_user',
                    '=',
                    DB::raw("
                CASE
                    WHEN sadarin_user.user_nip <> '-' THEN sadarin_user.user_nip
                    ELSE sadarin_user.user_nik
                END
            "),
                );
            })
            ->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->leftJoin('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->leftJoin('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')
            ->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->select('sadarin_user.*', 'sadarin_pengumpulanberkas.kumpulan_id', 'sadarin_pengumpulanberkas.kumpulan_status', 'sadarin_pengumpulanberkas.kumpulan_sync', 'sadarin_pengumpulanberkas.kumpulan_file', 'sadarin_pengumpulanberkas.kumpulan_jenis', 'sadarin_bidang.bidang_nama', 'sadarin_jabatan.jabatan_nama', 'sadarin_golongan.*', 'sadarin_eselon.*', 'sadarin_pendidikan.*')
            ->where('sadarin_user.user_status', 1)
            ->where('sadarin_pengumpulanberkas.kumpulan_jenis', $id)
            ->orderByRaw(
                "
                    CASE
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala Dinas' THEN 0
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Sekretaris' THEN 1
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala Bidang' THEN 2
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala UPTD' THEN 3
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala%' THEN 4
                        ELSE 5
                    END,
                    sadarin_user.user_jeniskerja ASC,
                    sadarin_user.user_nama ASC
                ",
            )
            ->get();

        $dataBelumSinkron = $dataPegawai->where('kumpulan_status', 1)->where('kumpulan_sync', 0)->count();

        // Filter PNS dan PPPK
        $dataPns = $dataPegawai->where('user_jeniskerja', '1');
        $dataPppk = $dataPegawai->where('user_jeniskerja', '2');
        $dataParuhWaktu = $dataPegawai->where('user_jeniskerja', '3');
        $dataNonASN = $dataPegawai->where('user_jeniskerja', '4');

        // Statistik jumlah yang sudah kumpul
        $jumlahPnsKumpul = $dataPns->where('kumpulan_status', 1)->count();
        $jumlahPppkKumpul = $dataPppk->where('kumpulan_status', 1)->count();
        $jumlahParuhWaktuKumpul = $dataParuhWaktu->where('kumpulan_status', 1)->count();
        $jumlahPJLPKumpul = $dataNonASN->where('kumpulan_status', 1)->count();

        // Statistik belum kumpul
        $jumlahPnsBelumKumpul = $dataPns->where('kumpulan_status', 0)->count();
        $jumlahPppkBelumKumpul = $dataPppk->where('kumpulan_status', 0)->count();
        $jumlahParuhWaktuBelumKumpul = $dataParuhWaktu->where('kumpulan_status', 0)->count();
        $jumlahPJLPBelumKumpul = $dataNonASN->where('kumpulan_status', 0)->count();

        $jenis = $dataPegawai[0]->kumpulan_jenis;
        return view('kepegawaian.paktaintegritas', compact('dataBelumSinkron', 'jumlahPnsBelumKumpul', 'jumlahPppkBelumKumpul', 'jumlahParuhWaktuBelumKumpul', 'jumlahPJLPBelumKumpul', 'dataPegawai', 'dataPns', 'dataPppk', 'jumlahPnsKumpul', 'jumlahPppkKumpul', 'jumlahParuhWaktuKumpul', 'jumlahPJLPKumpul', 'jenis', 'dataPns', 'dataPppk', 'dataParuhWaktu', 'dataNonASN'));
    }
    /// akhir Pakta Integritas
    /// lihat pakta
    // public function syncPaktaIntegritas()
    // {
    //     // ambil semua pegawai aktif
    //     $users = ModelUser::whereIn('user_jeniskerja', ['1', '2'])->get();

    //     foreach ($users as $user) {
    //         // tentukan folder berdasarkan jenis kerja
    //         if ($user->user_jeniskerja == '1') {
    //             $folderId = env('GOOGLE_DRIVE_FOLDER_PNS');
    //         } elseif ($user->user_jeniskerja == '2') {
    //             $folderId = env('GOOGLE_DRIVE_FOLDER_PPPK');
    //         } else {
    //             continue;
    //         }

    //         // cek file di Google Drive
    //         $fileData = $this->drive->findFileByNip($user->user_nip, $folderId);

    //         // simpan ke tabel pengumpulan berkas
    //         ModelPengumpulanBerkas::updateOrCreate(
    //             [
    //                 'kumpulan_user'  => $user->user_nip,
    //                 'kumpulan_jenis' => 'Pakta Integritas',
    //             ],
    //             [
    //                 'kumpulan_file'   => $fileData['file_url'],
    //                 'kumpulan_status' => $fileData['status'],
    //             ]
    //         );
    //     }

    //     return response()->json(['message' => 'Sinkronisasi selesai.']);
    // }
    // public function prefillSyntaxC2025()
    // {
    //     // ambil semua pegawai aktif
    //     $users = ModelUser::whereIn('user_jeniskerja', ['1', '2'])->get();

    //     foreach ($users as $user) {
    //         // tentukan folder berdasarkan jenis kerja
    //         if ($user->user_jeniskerja == '1') {
    //             $folderId = env('GOOGLE_DRIVE_FOLDER_MODELC_PNS');
    //         } elseif ($user->user_jeniskerja == '2') {
    //             $folderId = env('GOOGLE_DRIVE_FOLDER_MODELC_PPPK');
    //         } else {
    //             continue;
    //         }

    //         // cek file di Google Drive
    //         $fileData = $this->drive->findFileByNip($user->user_nip, $folderId);

    //         // simpan ke tabel pengumpulan berkas
    //         ModelPengumpulanBerkas::updateOrCreate(
    //             [
    //                 'kumpulan_user'  => $user->user_nip,
    //                 'kumpulan_jenis' => 'Model C 2025',
    //             ],
    //             [
    //                 'kumpulan_file'   => $fileData['file_url'],
    //                 'kumpulan_status' => $fileData['status'],
    //             ]
    //         );
    //     }

    //     return response()->json(['message' => 'Sinkronisasi selesai.']);
    // }
    public function lihatPakta($id)
    {
        $berkas = DB::table('sadarin_pengumpulanberkas')->where('kumpulan_id', $id)->first();

        if (!$berkas || $berkas->kumpulan_status != 1) {
            return redirect()->back()->with('error', 'Berkas tidak ditemukan atau belum terkumpul.');
        }

        // Pastikan file ada
        $filePath = storage_path('app/' . $berkas->kumpulan_file);
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // View file via URL publik (harus di symlink storage: php artisan storage:link)
        $fileUrl = asset('storage/' . $berkas->kumpulan_file);

        return view('kepegawaian.lihat-berkas', compact('berkas', 'fileUrl'));
    }
    /// akhir lihat pakta
    /// export data pakta
    public function exportPaktaIntegritas(Request $request, $id)
    {
        $dataPegawai = DB::table('sadarin_user')
            ->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->leftJoin('sadarin_pengumpulanberkas', function ($join) {
                $join->on('sadarin_pengumpulanberkas.kumpulan_user', '=', 'sadarin_user.user_nip')->orOn('sadarin_pengumpulanberkas.kumpulan_user', '=', 'sadarin_user.user_nik');
            })
            ->select('sadarin_user.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_golongan.golongan_nama', 'sadarin_golongan.golongan_pangkat', 'sadarin_bidang.bidang_nama', 'sadarin_pengumpulanberkas.kumpulan_status', 'sadarin_pengumpulanberkas.kumpulan_keterangan', 'sadarin_pengumpulanberkas.kumpulan_file')
            ->where('sadarin_user.user_status', 1)
            ->where('sadarin_pengumpulanberkas.kumpulan_jenis', $id)
            ->orderByRaw(
                "
        CASE
            WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala Dinas' THEN 0
            WHEN sadarin_jabatan.jabatan_nama LIKE 'Sekretaris' THEN 1
            WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala Bidang' THEN 2
            WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala UPTD' THEN 3
            WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala%' THEN 4
            ELSE 5
        END,
        sadarin_user.user_jeniskerja ASC,
        sadarin_user.user_nama ASC
    ",
            )
            ->get();

        $dataPns = $dataPegawai->where('user_jeniskerja', '1');
        $dataPppk = $dataPegawai->where('user_jeniskerja', '2');
        $dataParuhWaktu = $dataPegawai->where('user_jeniskerja', '3');
        $dataPJLP = $dataPegawai->where('user_jeniskerja', '4');

        return Excel::download(new PegawaiExport($dataPns, $dataPppk, $dataPJLP, $dataParuhWaktu), $id . 'Disbud.xlsx');
    }
    public function Pegawaisync($id)
    {
        try {

            Artisan::call('sync:berkas', [
                'jenis' => $id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Sinkron berhasil'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'error'  => $e->getMessage()
            ], 500);
        }
    }
    public function uploadBerkas(Request $request)
    {
        $request->validate([
            'user_nip' => 'required|string',
            'file' => 'required|mimes:pdf',
            'kumpulan_jenis' => 'required|string',
            'jenisfile' => 'required|string', // 'evkin' atau 'umpanbalik'
            'user_jeniskerja' => 'required|string', // '1' = PNS, '2' = PPPK
        ]);

        $nip = $request->user_nip;
        $nik = $request->user_nik;
        $jenis = $request->kumpulan_jenis;
        $jenisfile = $request->jenisfile;
        $jeniskerja = $request->user_jeniskerja;

        // Jika NIP adalah '-' maka gunakan NIK
        $finalId = $nip == '-' || $nip == null || $nip == '' ? $nik : $nip;
        // Tentukan filename
        $filename = $finalId . '_' . str_replace(' ', '_', $jenis) . '.pdf';

        // Mapping folder di public/assets/
        $folderMap = [
            'evaluasikinerjatriwulan1' => [
                '1' => 'assets/evkintw1/pns',
                '2' => 'assets/evkintw1/pppk',
                '3' => 'assets/evkintw1/paruhwaktu',
                '4' => 'assets/evkintw1/nonasn',
            ],
            'umpanbaliktriwulan1' => [
                '1' => 'assets/umpanbaliktw1/pns',
                '2' => 'assets/umpanbaliktw1/pppk',
                '3' => 'assets/umpanbaliktw1/paruhwaktu',
                '4' => 'assets/umpanbaliktw1/nonasn',
            ],
            'evaluasikinerjatriwulan2' => [
                '1' => 'assets/evkintw2/pns',
                '2' => 'assets/evkintw2/pppk',
                '3' => 'assets/evkintw2/paruhwaktu',
                '4' => 'assets/evkintw2/nonasn',
            ],
            'umpanbaliktriwulan2' => [
                '1' => 'assets/umpanbaliktw2/pns',
                '2' => 'assets/umpanbaliktw2/pppk',
                '3' => 'assets/umpanbaliktw2/paruhwaktu',
                '4' => 'assets/umpanbaliktw2/nonasn',
            ],
            'evaluasikinerjatriwulan3' => [
                '1' => 'assets/evkintw3/pns',
                '2' => 'assets/evkintw3/pppk',
                '3' => 'assets/evkintw3/paruhwaktu',
                '4' => 'assets/evkintw3/nonasn',
            ],
            'umpanbaliktriwulan3' => [
                '1' => 'assets/umpanbaliktw3/pns',
                '2' => 'assets/umpanbaliktw3/pppk',
                '3' => 'assets/umpanbaliktw3/paruhwaktu',
                '4' => 'assets/umpanbaliktw3/nonasn',
            ],
            'evaluasikinerjatriwulan4' => [
                '1' => 'assets/evkin/pns',
                '2' => 'assets/evkin/pppk',
                '3' => 'assets/evkin/paruhwaktu',
                '4' => 'assets/evkin/nonasn',
            ],
            'umpanbaliktriwulan4' => [
                '1' => 'assets/umpanbalik/pns',
                '2' => 'assets/umpanbalik/pppk',
                '3' => 'assets/umpanbalik/paruhwaktu',
                '4' => 'assets/umpanbalik/nonasn',
            ],
            'evaluasikinerjatahunan2025' => [
                '1' => 'assets/evkin2025/pns',
                '2' => 'assets/evkin2025/pppk',
                '3' => 'assets/evkin2025/paruhwaktu',
                '4' => 'assets/evkin2025/nonasn',
            ],
            'pakta1desember' => [
                '1' => 'assets/pakta1desember/pns',
                '2' => 'assets/pakta1desember/pppk',
                '3' => 'assets/pakta1desember/paruhwaktu',
                '4' => 'assets/pakta1desember/nonasn',
            ],
            'skp2025' => [
                '1' => 'assets/skp2025/pns',
                '2' => 'assets/skp2025/pppk',
                '3' => 'assets/skp2025/paruhwaktu',
                '4' => 'assets/skp2025/nonasn',
            ],
            'modelc2026' => [
                '1' => 'assets/modelc2026/pns',
                '2' => 'assets/modelc2026/pppk',
                '3' => 'assets/modelc2026/paruhwaktu',
                '4' => 'assets/modelc2026/nonasn',
            ],
            'modelc2025' => [
                '1' => 'assets/modelc2025/pns',
                '2' => 'assets/modelc2025/pppk',
                '3' => 'assets/modelc2025/paruhwaktu',
                '4' => 'assets/modelc2025/nonasn',
            ],
            'paktaintegritas' => [
                '1' => 'assets/pakta2025/pns',
                '2' => 'assets/pakta2025/pppk',
                '3' => 'assets/pakta2025/paruhwaktu',
                '4' => 'assets/pakta2025/nonasn',
            ],
            'dataktp' => [
                '1' => 'assets/dataktp/pns',
                '2' => 'assets/dataktp/pppk',
                '3' => 'assets/dataktp/paruhwaktu',
                '4' => 'assets/dataktp/nonasn',
            ],
            'datanpwp' => [
                '1' => 'assets/datanpwp/pns',
                '2' => 'assets/datanpwp/pppk',
                '3' => 'assets/datanpwp/paruhwaktu',
                '4' => 'assets/datanpwp/nonasn',
            ],
            'databukurekening' => [
                '1' => 'assets/databukurekening/pns',
                '2' => 'assets/databukurekening/pppk',
                '3' => 'assets/databukurekening/paruhwaktu',
                '4' => 'assets/databukurekening/nonasn',
            ],
            'databpjskesehatan' => [
                '1' => 'assets/databpjskesehatan/pns',
                '2' => 'assets/databpjskesehatan/pppk',
                '3' => 'assets/databpjskesehatan/paruhwaktu',
                '4' => 'assets/databpjskesehatan/nonasn',
            ],
            'dataijazah' => [
                '1' => 'assets/dataijazah/pns',
                '2' => 'assets/dataijazah/pppk',
                '3' => 'assets/dataijazah/paruhwaktu',
                '4' => 'assets/dataijazah/nonasn',
            ],
            'datakartukeluarga' => [
                '1' => 'assets/datakartukeluarga/pns',
                '2' => 'assets/datakartukeluarga/pppk',
                '3' => 'assets/datakartukeluarga/paruhwaktu',
                '4' => 'assets/datakartukeluarga/nonasn',
            ],
            'laporanpjlpjanuari2025' => [
                '4' => 'assets/laporanpjlpjanuari2025/pjlp',
            ],
            'coretax2026' => [
                '1' => 'assets/coretax2026/pns',
                '2' => 'assets/coretax2026/pppk',
                '3' => 'assets/coretax2026/paruhwaktu',
                '4' => 'assets/coretax2026/pjlp',
            ],
            'laporanikd' => [
                '1' => 'assets/laporanikd/pns',
                '2' => 'assets/laporanikd/pppk',
                '3' => 'assets/laporanikd/paruhwaktu',
                '4' => 'assets/laporanikd/nonasn',
            ],
            'perjanjiankinerja2026' => [
                '1' => 'assets/perjanjiankinerja2026/pns',
                '2' => 'assets/perjanjiankinerja2026/pppk',
                '3' => 'assets/perjanjiankinerja2026/paruhwaktu',
                '4' => 'assets/perjanjiankinerja2026/nonasn',
            ],
        ];

        if (!isset($folderMap[$jenisfile][$jeniskerja])) {
            return back()->with('error', 'Folder untuk jenis file ini belum disiapkan.');
        }

        $folder = $folderMap[$jenisfile][$jeniskerja];

        // Buat folder jika belum ada
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        // Upload file ke public/assets/
        $file = $request->file('file');
        $file->move(public_path($folder), $filename);

        // Path file yang bisa diakses via asset()
        $url = asset($folder . '/' . $filename);

        $keterangan = 'Mengupload berkas ' . $jenis . ' melalui sistem.';

        if ($request->filled('tanggal_melapor')) {
            $keterangan = 'Tanggal Melapor ' . \Carbon\Carbon::parse($request->tanggal_melapor)->translatedFormat('d F Y');
        }
        // Simpan ke DB
        ModelPengumpulanBerkas::updateOrCreate(
            [
                'kumpulan_user' => $finalId,
                'kumpulan_jenis' => $jenis,
            ],
            [
                'kumpulan_file' => $url,
                'kumpulan_status' => 1,
                'kumpulan_sync' => 0,
                'kumpulan_keterangan' => $keterangan,
            ],
        );

        return back()
            ->with('success', 'File ' . $jenis . ' berhasil diupload.')
            ->with('file_url', $url);
    }
    public function uploadBerkasDrive(Request $request)
    {
        $request->validate([
            'user_nip' => 'required|string',
            'file' => 'required|mimes:pdf|max:10240',
            'kumpulan_jenis' => 'required|string',
            'jenisfile' => 'required|string',
            'user_jeniskerja' => 'required|string',
        ]);

        $nip = $request->user_nip;
        $nik = $request->user_nik;
        $jenis = $request->kumpulan_jenis;
        $jenisfile = $request->jenisfile;
        $jeniskerja = $request->user_jeniskerja;

        $finalId = $nip == '-' || empty($nip) ? $nik : $nip;
        $filename = $finalId . '_' . str_replace(' ', '_', $jenis) . '.pdf';

        $folderMapDrive = [
            'laporanpjlpjanuari2025' => [
                '1' => env('GOOGLE_DRIVE_FOLDER_BULANAN_PJLP_JANUARI_2025'),
            ],
        ];

        if (!isset($folderMapDrive[$jenisfile][$jeniskerja])) {
            return back()->with('error', 'Folder Drive belum disetting.');
        }

        $folderId = $folderMapDrive[$jenisfile][$jeniskerja];

        if (empty($folderId)) {
            return back()->with('error', 'Folder ID kosong di ENV.');
        }

        try {
            // ✅ 1. INIT CLIENT
            $client = new Client();
            $client->setAuthConfig(storage_path(env('GOOGLE_APPLICATION_PJLP_CREDENTIALS')));
            $client->addScope(Drive::DRIVE);
            $client->setAccessType('offline');

            // ✅ 2. INIT DRIVE SERVICE
            $driveService = new Drive($client);

            // ✅ 3. FILE METADATA
            $fileMetadata = new DriveFile([
                'name' => $filename,
                'parents' => [$folderId],
            ]);

            // ✅ 4. UPLOAD
            $uploadedFile = $driveService->files->create($fileMetadata, [
                'data' => file_get_contents($request->file('file')->getRealPath()),
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id',
                'supportsAllDrives' => true,
            ]);

            dd($uploadedFile->getId());
        } catch (\Google\Service\Exception $e) {
            dd($e->getErrors());
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function exportDataPegawai()
    {
        return Excel::download(new PegawaiPerBidangExport(), 'DATA_PEGAWAI_PER_BIDANG.xlsx');
    }
    public function cetakStrukturPegawaiPdf()
    {
        $path = public_path('file/Struktur-Organisasi-Dinas-Kebudayaan-Provinsi-Bali.pdf');

        return response()->file($path);
    }
}