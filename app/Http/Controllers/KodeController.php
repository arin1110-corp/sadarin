<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelUser;
use App\Models\ModelBidang;
use App\Models\ModelSubBag;
use App\Models\ModelNavigasiSekretariat;
use App\Models\ModelSubNavigasiSekretariat;
use App\Models\ModelPakta;
use Google\Service\Bigquery\Model;
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
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Hash;

class KodeController extends Controller
{

    protected $drive;
    private $kodeValid = ['A1@B2', 'X9#K7', 'R5$T3', 'Y7@U1', 'Z8%P4', 'K0#L9', 'N3&M6', 'G7*H8', 'W6!Q2', 'D4@Z5'];


    public function __construct(GoogleDriveService $drive)
    {
        $this->drive = $drive;
    }
    public function login()
    {
        return view('homepage_login');
    }
    // Proses login
    public function loginSubmit(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari admin berdasarkan NIP
        $admin = ModelAdmin::where('admin_nip', $request->nip)->first();

        if (!$admin) {
            return back()->withErrors(['login' => 'NIP tidak ditemukan'])->withInput();
        }

        // Cek password
        if (!Hash::check($request->password, $admin->admin_password)) {
            return back()->withErrors(['login' => 'Password salah'])->withInput();
        }

        // Cek status aktif
        if ($admin->admin_status != 1) {
            return back()->withErrors(['login' => 'Akun tidak aktif'])->withInput();
        }



        // Redirect berdasarkan role
        if ($admin->admin_role === 'Admin') {
            // Simpan session
            session([
                'admin_id' => $admin->admin_id,
                'admin_role' => $admin->admin_role,
                'admin_nip' => $admin->admin_nip
            ]);
            return redirect()->route('dashboard'); // route dashboard admin
        } elseif ($admin->admin_role === 'Kepegawaian') {
            session([
                'kepegawaian_id' => $admin->admin_id,
                'kepegawaian_role' => $admin->admin_role,
                'kepegawaian_nip' => $admin->admin_nip
            ]);
            return redirect()->route('kepegawaian.dashboard'); // route dashboard kepegawaian
        }

        return back()->withErrors(['login' => 'Role tidak dikenali']);
    }

    // Logout
    public function logout()
    {
        session()->forget(['admin_id', 'admin_role', 'admin_nip']);
        return redirect()->route('akses.form');
    }
    public function form()
    {
        return view('homepage_awal');
    }

    public function cek(Request $request)
    {
        $request->validate([
            'kode_akses' => ['required', 'string', 'min:5'],
        ]);

        if (in_array($request->kode_akses, $this->kodeValid)) {
            session([
                'kode_akses_valid' => true,
                'akses_full' => true,
                'user_info' => null // akses penuh, tidak butuh data user
            ]);

            $bidang = ModelBidang::where('bidang_status', 1)->get();
            return view('homepage_cekbidang', compact('bidang'));
        }

        // Kalau kode akses adalah NIP
        $user = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->where('user_nip', $request->kode_akses)
            ->select('sadarin_user.user_nip', 'sadarin_user.user_nama', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama')
            ->first();

        if ($user) {
            session([
                'kode_akses_valid' => true,
                'akses_full' => false,
                'user_info' => $user
            ]);

            $bidang = ModelBidang::where('bidang_status', 1)->get();
            return view('homepage_cekbidang', compact('bidang', 'user'));
        }

        return back()->withErrors(['kode_akses' => 'Kode akses salah.'])->withInput();
    }
    public function detailpegawai()
    {
        $pegawai = session('user_info')->user_nip;
        $user = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->join('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')
            ->join('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->where('user_nip', $pegawai)
            ->select('sadarin_user.*', 'sadarin_golongan.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_eselon.*', 'sadarin_pendidikan.*')
            ->first();
        if (!$pegawai) {
            return redirect()->route('akses.form')->withErrors(['kode_akses' => 'Kode akses salah.']);
        }
        $jabatans = ModelJabatan::all();
        $eselons = ModelEselon::all();
        $bidangs = ModelBidang::all();
        $golongans = ModelGolongan::all();
        $pendidikans = ModelPendidikan::all();
        return view('homepage_detailpegawai', compact('user', 'jabatans', 'eselons', 'bidangs', 'golongans', 'pendidikans'));
    }
    public function strukturOrganisasi()
    {
        $dataPegawai = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->join('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->where('sadarin_user.user_status', 1)
            ->select('sadarin_user.*', 'sadarin_golongan.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_eselon.*')
            ->get();
        return view('homepagestrukturorganisasi', compact('dataPegawai'));
    }
    public function lihatjajaran(Request $request)
    {
        $kategori = $request->kategori ?? null;

        if ($kategori === 'Fungsional') {
            // Ambil semua pegawai fungsional
            $pegawaiBidang = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
                ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
                ->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
                ->where('jabatan_kategori', 'Fungsional')
                ->select('sadarin_user.*', 'sadarin_jabatan.*', 'sadarin_bidang.*', 'sadarin_golongan.*')
                ->get();

            $kepalaAtas = null;
            $kepalaSejajar = null;
            $staff = $pegawaiBidang; // Semua fungsional dianggap staff
            $bidangNama = 'Fungsional';
        } else {
            $bidangId = $request->id ?? null;
            if (!$bidangId) {
                abort(404);
            }

            // Ambil semua pegawai di bidang
            $pegawaiBidang = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
                ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
                ->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
                ->where('user_bidang', $bidangId)
                ->select('sadarin_user.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.*', 'sadarin_golongan.*')
                ->get();

            $kepala = $pegawaiBidang->filter(function ($p) {
                return str_contains($p->jabatan_nama, 'Kepala Bidang')
                    || str_contains($p->jabatan_nama, 'Sekretaris')
                    || str_contains($p->jabatan_nama, 'Kepala UPTD')
                    || str_contains($p->jabatan_nama, 'Kepala')
                    && !str_contains($p->jabatan_nama, 'Kepala Dinas');
            });

            $prioritasJabatan = [
                'Kepala Bidang' => 1,
                'Sekretaris' => 2,
                'Kepala UPTD' => 3,
                'Kepala' => 4,
            ];

            $kepala = $kepala->sortBy(function ($p) use ($prioritasJabatan) {
                foreach ($prioritasJabatan as $key => $value) {
                    if (str_contains($p->jabatan_nama, $key)) return $value;
                }
                return 999;
            });

            $kepalaAtas = $kepala->first();
            $kepalaSejajar = $kepala->skip(1);
            $staff = $pegawaiBidang->filter(function ($p) {
                return !str_contains($p->jabatan_nama, 'Kepala') && !str_contains($p->jabatan_nama, 'Sekretaris');
            });

            $bidangNama = $kepalaAtas->bidang_nama ?? '-';
        }

        return view('homepage_lihatjajaran', compact('kepalaAtas', 'kepalaSejajar', 'staff', 'bidangNama'));
    }
    public function datasekretariat()
    {
        $bidang = 1;
        // Mengambil data bidang dengan status aktif
        $bidangnama = ModelBidang::where('bidang_id', $bidang)->value('bidang_nama');
        $subbag = ModelSubBag::with('bidang')->where('subbag_status', 1)->where('subbag_bidang', $bidang)->get();
        return view('homepage_sekretariat', compact('subbag', 'bidangnama'));
    }
    public function datakesenian()
    {
        $bidang = 2;
        // Mengambil data bidang dengan status aktif
        $bidangnama = ModelBidang::where('bidang_id', $bidang)->value('bidang_nama');
        $subbag = ModelSubBag::with('bidang')->where('subbag_status', 1)->where('subbag_bidang', $bidang)->get();
        return view('homepage_sekretariat', compact('subbag', 'bidangnama'));
    }
    public function datacagarbudaya()
    {
        $bidang = 3;
        // Mengambil data bidang dengan status aktif
        $bidangnama = ModelBidang::where('bidang_id', $bidang)->value('bidang_nama');
        $subbag = ModelSubBag::with('bidang')->where('subbag_status', 1)->where('subbag_bidang', $bidang)->get();
        return view('homepage_sekretariat', compact('subbag', 'bidangnama'));
    }
    public function datatradisi()
    {
        $bidang = 4;
        // Mengambil data bidang dengan status aktif
        $bidangnama = ModelBidang::where('bidang_id', $bidang)->value('bidang_nama');
        $subbag = ModelSubBag::with('bidang')->where('subbag_status', 1)->where('subbag_bidang', $bidang)->get();
        return view('homepage_sekretariat', compact('subbag', 'bidangnama'));
    }
    public function datasejarah()
    {
        $bidang = 5;
        // Mengambil data bidang dengan status aktif
        $bidangnama = ModelBidang::where('bidang_id', $bidang)->value('bidang_nama');
        $subbag = ModelSubBag::with('bidang')->where('subbag_status', 1)->where('subbag_bidang', $bidang)->get();
        return view('homepage_sekretariat', compact('subbag', 'bidangnama'));
    }
    public function datamuseum()
    {
        $bidang = 6;
        // Mengambil data bidang dengan status aktif
        $bidangnama = ModelBidang::where('bidang_id', $bidang)->value('bidang_nama');
        $subbag = ModelSubBag::with('bidang')->where('subbag_status', 1)->where('subbag_bidang', $bidang)->get();
        return view('homepage_sekretariat', compact('subbag', 'bidangnama'));
    }
    public function datatamanbudaya()
    {
        $bidang = 7;
        // Mengambil data bidang dengan status aktif
        $bidangnama = ModelBidang::where('bidang_id', $bidang)->value('bidang_nama');
        $subbag = ModelSubBag::with('bidang')->where('subbag_status', 1)->where('subbag_bidang', $bidang)->get();
        return view('homepage_sekretariat', compact('subbag', 'bidangnama'));
    }
    public function datamonumen()
    {
        $bidang = 8;
        // Mengambil data bidang dengan status aktif
        $bidangnama = ModelBidang::where('bidang_id', $bidang)->value('bidang_nama');
        $subbag = ModelSubBag::with('bidang')->where('subbag_status', 1)->where('subbag_bidang', $bidang)->get();
        return view('homepage_sekretariat', compact('subbag', 'bidangnama'));
    }

    public function dataumpeg()
    {
        $subbagId = 1;
        $subbagNama = ModelSubbag::where('subbag_id', $subbagId)->value('subbag_nama');

        // Cek apakah user punya akses penuh atau tidak
        $aksesFull = session('akses_full', false);

        $datasekretariatQuery = ModelNavigasiSekretariat::with(['subnavigasisekretariat'])
            ->where('navigasisekre_subbag', $subbagId);

        // Kalau akses bukan penuh (login pakai NIP) → filter level = 1
        if (!$aksesFull) {
            $datasekretariatQuery->where('navigasisekre_level', 1);
        }

        $datasekretariat = $datasekretariatQuery->get();

        return view('homepage_data_subbag_sekretariat', compact('datasekretariat', 'subbagNama'));
    }
    public function datappep()
    {
        $subbagNama = ModelSubbag::where('subbag_id', 3)->value('subbag_nama');
        $datasekretariat = ModelNavigasiSekretariat::with('subnavigasisekretariat')
            ->where('navigasisekre_subbag', 3)
            ->get();
        return view('homepage_data_subbag_sekretariat', compact('datasekretariat', 'subbagNama'));
    }
    public function datakeuangan()
    {
        $subbagNama = ModelSubbag::where('subbag_id', 2)->value('subbag_nama');
        $datasekretariat = ModelNavigasiSekretariat::with('subnavigasisekretariat')
            ->where('navigasisekre_subbag', 2)
            ->get();
        return view('homepage_data_subbag_sekretariat', compact('datasekretariat', 'subbagNama'));
    }
    public function datasenirupa()
    {
        $subbagId = 4;
        $subbagNama = ModelSubbag::where('subbag_id', $subbagId)->value('subbag_nama');

        // Cek apakah user punya akses penuh atau tidak
        $aksesFull = session('akses_full', false);

        $datasekretariatQuery = ModelNavigasiSekretariat::with(['subnavigasisekretariat'])
            ->where('navigasisekre_subbag', $subbagId);

        // Kalau akses bukan penuh (login pakai NIP) → filter level = 1
        if (!$aksesFull) {
            $datasekretariatQuery->where('navigasisekre_level', 1);
        }

        $datasekretariat = $datasekretariatQuery->get();

        return view('homepage_data_subbag_sekretariat', compact('datasekretariat', 'subbagNama'));
    }

    public function akses_kode()
    {
        return view('homepage_awal');
    }
    public function dataPegawaiPNS()
    {
        $users = ModelUser::with('bidang')
            ->where('user_status', 1)->where('user_jeniskerja', 1)
            ->get();

        $rekapBidang = $users->groupBy('user_bidang')->map(function ($group) {
            return [
                'nama' => optional($group->first()->bidang)->bidang_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('golongan')
            ->where('user_status', 1)->where('user_jeniskerja', 1)
            ->get();

        $rekapGolongan = $users->groupBy('user_golongan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->golongan)->golongan_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('jabatan')
            ->where('user_status', 1)->where('user_jeniskerja', 1)
            ->get();

        $rekapJabatan = $users->groupBy('user_jabatan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->jabatan)->jabatan_nama,
                'jumlah' => $group->count(),
            ];
        });

        $order = [
            'Kepala Dinas' => 1,
            'Sekretaris' => 2,
            'Kepala Bidang' => 3,
            'Kepala UPT' => 4
        ];

        $rekapJabatan = $rekapJabatan->sortBy(function ($item) use ($order) {
            $nama = $item['nama'];

            // Jika ada di daftar order khusus
            if (isset($order[$nama])) {
                return $order[$nama];
            }

            // Jika diawali "Kepala" tapi tidak ada di daftar, kasih prioritas setelah 3
            if (stripos($nama, 'Kepala') === 0) {
                return 4;
            }

            // Selain itu, urutan terakhir
            return 5;
        });

        $jumlahLaki = ModelUser::where('user_jk', 'L')->where('user_jeniskerja', 1)->where('user_status', 1)->count();
        $jumlahPerempuan = ModelUser::where('user_jk', 'P')->where('user_jeniskerja', 1)->where('user_status', 1)->count();

        $dataPegawai = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->where('user_status', 1)
            ->where('user_jeniskerja', 1)
            ->orderByRaw(" CASE 
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Dinas' THEN 1
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Bidang' THEN 2
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala UPT' THEN 3
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala%' THEN 4
                        ELSE 5 END ")
            ->orderBy('sadarin_user.user_nama', 'asc')
            ->select('sadarin_user.*', 'sadarin_jabatan.jabatan_nama')
            ->get();
        $totalPegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->count();
        return view('homepage_data_pegawai_pns', compact(
            'dataPegawai',
            'totalPegawai',
            'rekapBidang',
            'rekapGolongan',
            'rekapJabatan',
            'jumlahLaki',
            'jumlahPerempuan'
        ));
    }
    public function dataPegawaiPPPK()
    {
        $users = ModelUser::with('bidang')
            ->where('user_status', 1)->where('user_jeniskerja', 2)
            ->get();

        $rekapBidang = $users->groupBy('user_bidang')->map(function ($group) {
            return [
                'nama' => optional($group->first()->bidang)->bidang_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('golongan')
            ->where('user_status', 1)->where('user_jeniskerja', 2)
            ->get();

        $rekapGolongan = $users->groupBy('user_golongan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->golongan)->golongan_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('jabatan')
            ->where('user_status', 1)->where('user_jeniskerja', 2)
            ->get();

        $rekapJabatan = $users->groupBy('user_jabatan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->jabatan)->jabatan_nama,
                'jumlah' => $group->count(),
            ];
        });

        $order = [
            'Kepala Dinas' => 1,
            'Kepala Bidang' => 2,
            'Kepala UPT' => 3
        ];

        $rekapJabatan = $rekapJabatan->sortBy(function ($item) use ($order) {
            $nama = $item['nama'];

            // Jika ada di daftar order khusus
            if (isset($order[$nama])) {
                return $order[$nama];
            }

            // Jika diawali "Kepala" tapi tidak ada di daftar, kasih prioritas setelah 3
            if (stripos($nama, 'Kepala') === 0) {
                return 4;
            }

            // Selain itu, urutan terakhir
            return 5;
        });

        $jumlahLaki = ModelUser::where('user_jk', 'L')->where('user_jeniskerja', 2)->where('user_status', 1)->count();
        $jumlahPerempuan = ModelUser::where('user_jk', 'P')->where('user_jeniskerja', 2)->where('user_status', 1)->count();
        $dataPegawai = ModelUser::where('user_status', 1)
            ->where('user_jeniskerja', 2)
            ->get();
        $totalPegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->count();
        return view('homepage_data_pegawai_pns', compact(
            'dataPegawai',
            'totalPegawai',
            'rekapBidang',
            'rekapGolongan',
            'rekapJabatan',
            'jumlahLaki',
            'jumlahPerempuan'
        ));
    }
    public function dataPegawaiRincian()
    {
        // Mengambil data pegawai semua
        $dataPegawai = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->where('sadarin_user.user_status', 1)
            ->orderByRaw("
                    CASE
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Dinas' THEN 1
                        WHEN sadarin_jabatan.jabatan_nama = 'Sekretaris' THEN 2
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala UPTD' THEN 3
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala UPTD' THEN 4
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Bidang' THEN 5
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala%' THEN 6
                        ELSE 7
                    END
                ")
            ->orderByRaw("
        CASE
            WHEN sadarin_user.user_jeniskerja = 1 THEN 1
            WHEN sadarin_user.user_jeniskerja = 2 THEN 2
            ELSE 3
        END
    ")
            ->orderBy('sadarin_user.user_nama', 'asc')
            ->select('sadarin_user.*', 'sadarin_golongan.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama')
            ->get();

        return view('homepage_rincian_pegawai', compact(
            'dataPegawai'
        ));
    }
    public function umpanbalik()
    {
        return view('homepage_umpeg_umpanbalik');
    }
    public function evaluasikinerja()
    {
        return view('homepage_umpeg_evkin');
    }
    public function bendaharaPengeluaran()
    {
        return view('homepage_keu_bendpengeluaran');
    }
    public function cekAkses(Request $request)
    {
        $target = $request->target;
        if (session('kode_akses_valid')) {
            return redirect()->route('daftar.bagian');
        }
        return redirect()->route('akses.kode')->withErrors(['kode_akses' => 'Kode Salah.']);
    }
    public function datadppa2025()
    {
        return view('homepage_dppa2025');
    }
    public function datarak2025()
    {
        return view('homepage_rak2025');
    }

    public function daftarBagian()
    {
        return view('homepage_data');
    }
    public function pkbidang()
    {
        return view('homepage_pk_bidang');
    }
    public function cekbidang()
    {
        return view('homepage_cekbidang');
    }


    public function dataupload()
    {
        return view('homepage_upload_data');
    }

    // Admin Dashboard

    public function admin()
    {
        $users = ModelUser::with('bidang')
            ->where('user_status', 1)
            ->get();

        $rekapBidang = $users->groupBy('user_bidang')->map(function ($group) {
            return [
                'nama' => optional($group->first()->bidang)->bidang_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('golongan')
            ->where('user_status', 1)
            ->get();

        $rekapGolongan = $users->groupBy('user_golongan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->golongan)->golongan_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('jabatan')
            ->where('user_status', 1)
            ->get();

        $rekapJabatan = $users->groupBy('user_jabatan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->jabatan)->jabatan_nama,
                'jumlah' => $group->count(),
            ];
        });
        $jumlahLaki = ModelUser::where('user_jk', 'L')->where('user_status', 1)->count();
        $jumlahPerempuan = ModelUser::where('user_jk', 'P')->where('user_status', 1)->count();

        $dataPegawai = ModelUser::where('user_status', 1)->get();
        $datapnspegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->count();
        $totalPegawai = ModelUser::where('user_status', 1)->count();

        $dataPns = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->get();
        $dataPppk = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->get();
        return view('admin.dashboard', compact(
            'dataPegawai',
            'totalPegawai',
            'rekapBidang',
            'rekapGolongan',
            'rekapJabatan',
            'jumlahLaki',
            'jumlahPerempuan',
            'datapnspegawai',
            'datapppkpegawai',
            'dataPns',
            'dataPppk'
        ));
    }



    // Bidang Management
    public function adminBidang()
    {
        $bidangs = ModelBidang::get();
        return view('admin.bidangindex', compact('bidangs'));
    }
    public function bidangSimpan()
    {
        $request = request();
        $request->validate([
            'bidang_nama' => 'required|string|max:255',
            'bidang_link' => 'required|string|max:255',
            'bidang_status' => 'required|integer',
        ]);

        ModelBidang::create([
            'bidang_nama' => $request->bidang_nama,
            'bidang_status' => $request->bidang_status,
            'bidang_link' => $request->bidang_link,
            'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
        ]);

        return redirect()->route('admin.bidang')->with('success', 'Bidang berhasil ditambahkan.');
    }
    public function bidangUpdate(Request $request, $id)
    {
        $request->validate([
            'bidang_nama' => 'required|string|max:255',
            'bidang_link' => 'required|string|max:255',
            'bidang_status' => 'required|integer',
        ]);

        $bidang = ModelBidang::findOrFail($id);
        $bidang->update([
            'bidang_nama' => $request->bidang_nama,
            'bidang_link' => $request->bidang_link,
            'bidang_status' => $request->bidang_status,
        ]);

        return redirect()->route('admin.bidang')->with('success', 'Bidang berhasil diperbarui.');
    }

    public function bidangHapus($id)
    {
        $bidang = ModelBidang::findOrFail($id);
        $bidang->delete();
        return redirect()->route('admin.bidang')->with('success', 'Bidang berhasil dihapus.');
    }
    //// Akhir Bidang Management


    //// Sub Bagian Management
    public function adminSubBag()
    {
        $bidangs = ModelBidang::where('bidang_status', 1)->get();
        $subbags = ModelSubBag::with('bidang')->where('subbag_status', 1)->get();
        return view('admin.subbagindex', compact('subbags', 'bidangs'));
    }
    public function subbagSimpan()
    {
        $request = request();
        $request->validate([
            'subbag_nama' => 'required|string|max:255',
            'subbag_bidang' => 'required|string',
            'subbag_status' => 'required|integer',
            'subbag_link' => 'required',
        ]);
        // Debug isi subbag_link
        // dd($request->subbag_link);

        ModelSubBag::create([
            'subbag_nama' => $request->subbag_nama,
            'subbag_bidang' => $request->subbag_bidang,
            'subbag_status' => $request->subbag_status,
            'subbag_link' => $request->subbag_link,
        ]);

        return redirect()->route('admin.subbag')->with('success', 'Sub Bagian berhasil ditambahkan.');
    }
    public function subbagUpdate(Request $request, $id)
    {
        $request->validate([
            'subbag_nama' => 'required|string|max:255',
            'subbag_bidang' => 'required|string',
            'subbag_status' => 'required|integer',
            'subbag_link' => 'required',
        ]);

        $subbag = ModelSubBag::findOrFail($id);
        $subbag->update([
            'subbag_nama' => $request->subbag_nama,
            'subbag_bidang' => $request->subbag_bidang,
            'subbag_status' => $request->subbag_status,
            'subbag_link' => $request->subbag_link,
        ]);

        return redirect()->route('admin.subbag')->with('success', 'Sub Bagian berhasil diperbarui.');
    }
    public function subbagHapus($id)
    {
        $subbag = ModelSubBag::findOrFail($id);
        $subbag->delete();
        return redirect()->route('admin.subbag')->with('success', 'Sub Bagian berhasil dihapus.');
    }
    //// Akhir Sub Bagian Management



    /// Navigasi Management
    public function adminNavigasi()
    {
        $navigasisekretariat = ModelNavigasiSekretariat::with('subnavigasisekretariat')
            ->with('subbag')->where('navigasisekre_status', 1)->get();
        $subbags = ModelSubBag::where('subbag_status', 1)->get();
        return view('admin.navigasiindex', compact('navigasisekretariat', 'subbags'));
    }
    public function navigasiSimpan()
    {
        $request = request();
        $request->validate([
            'navigasisekre_nama' => 'required|string|max:255',
            'navigasisekre_deskripsi' => 'nullable|string|max:255',
            'navigasisekre_subbag' => 'required|string',
            'navigasisekre_status' => 'required|integer',
            'navigasisekre_level' => 'required|integer',

        ]);

        ModelNavigasiSekretariat::create([
            'navigasisekre_nama' => $request->navigasisekre_nama,
            'navigasisekre_subbag' => $request->navigasisekre_subbag,
            'navigasisekre_deskripsi' => $request->navigasisekre_deskripsi,
            'navigasisekre_status' => $request->navigasisekre_status,
            'navigasisekre_level' => $request->navigasisekre_level
        ]);

        return redirect()->route('admin.navigasi')->with('success', 'Navigasi berhasil ditambahkan.');
    }
    public function navigasiUpdate(Request $request, $id)
    {
        $request->validate([
            'navigasisekre_nama' => 'required|string|max:255',
            'navigasisekre_deskripsi' => 'nullable|string|max:255',
            'navigasisekre_subbag' => 'required|string',
            'navigasisekre_status' => 'required|integer',
            'navigasisekre_level' => 'required|integer',
        ]);

        $navigasi = ModelNavigasiSekretariat::findOrFail($id);
        $navigasi->update([
            'navigasisekre_nama' => $request->navigasisekre_nama,
            'navigasisekre_deskripsi' => $request->navigasisekre_deskripsi,
            'navigasisekre_subbag' => $request->navigasisekre_subbag,
            'navigasisekre_status' => $request->navigasisekre_status,
            'navigasisekre_level' => $request->navigasisekre_level
        ]);

        return redirect()->route('admin.navigasi')->with('success', 'Navigasi berhasil diperbarui.');
    }
    public function navigasiHapus($id)
    {
        $navigasi = ModelNavigasiSekretariat::findOrFail($id);
        $navigasi->delete();
        return redirect()->route('admin.navigasi')->with('success', 'Navigasi berhasil dihapus.');
    }
    //// Akhir Navigasi Management



    /// Sub Navigasi Management
    public function adminSubNavigasi()
    {
        $navs = ModelNavigasiSekretariat::where('navigasisekre_status', 1)->get();
        $subnavigasisekretariat = ModelSubNavigasiSekretariat::with('navigasisekretariat')->where('subnavigasisekre_status', 1)->get();
        return view('admin.subnavigasiindex', compact('subnavigasisekretariat', 'navs'));
    }
    public function subnavigasiSimpan()
    {
        $request = request();
        $request->validate([
            'subnavigasisekre_nama' => 'required',
            'subnavigasisekre_navigasisekre' => 'required|string',
            'subnavigasisekre_status' => 'required|integer',
            'subnavigasisekre_link' => 'required|string',
        ]);

        ModelSubNavigasiSekretariat::create([
            'subnavigasisekre_nama' => $request->subnavigasisekre_nama,
            'subnavigasisekre_navigasisekre' => $request->subnavigasisekre_navigasisekre,
            'subnavigasisekre_status' => $request->subnavigasisekre_status,
            'subnavigasisekre_link' => $request->subnavigasisekre_link,
        ]);

        return redirect()->route('admin.subnavigasi')->with('success', 'Sub Navigasi berhasil ditambahkan.');
    }
    public function subnavigasiUpdate(Request $request, $id)
    {
        $request->validate([
            'subnavigasisekre_nama' => 'required',
            'subnavigasisekre_navigasisekre' => 'required|string',
            'subnavigasisekre_status' => 'required|integer',
            'subnavigasisekre_link' => 'required|string',
        ]);

        $subnavigasi = ModelSubNavigasiSekretariat::findOrFail($id);
        $subnavigasi->update([
            'subnavigasisekre_nama' => $request->subnavigasisekre_nama,
            'subnavigasisekre_navigasisekre' => $request->subnavigasisekre_navigasisekre,
            'subnavigasisekre_status' => $request->subnavigasisekre_status,
            'subnavigasisekre_link' => $request->subnavigasisekre_link,
        ]);

        return redirect()->route('admin.subnavigasi')->with('success', 'Sub Navigasi berhasil diperbarui.');
    }
    public function subnavigasiHapus($id)
    {
        $subnavigasi = ModelSubNavigasiSekretariat::findOrFail($id);
        $subnavigasi->delete();
        return redirect()->route('admin.subnavigasi')->with('success', 'Sub Navigasi berhasil dihapus.');
    }
    //// Akhir Sub Navigasi Management

    /// User Management
    public function adminUser()
    {
        $users = ModelUser::with('bidang', 'jabatan', 'golongan', 'eselon')->get();
        $bidangs = ModelBidang::where('bidang_status', 1)->get();
        $jabatans = DB::table('sadarin_jabatan')->get();
        $golongans = DB::table('sadarin_golongan')->get();
        $eselons = DB::table('sadarin_eselon')->get();
        return view('admin.userindex', compact('users', 'bidangs', 'jabatans', 'golongans', 'eselons'));
    }

    /// Akhir User Management


    /// Dashboard Kepegawaian
    /// Data Kepegawaian
    public function kepegawaianDashboard()
    {
        $dataPegawai = ModelUser::where('user_status', 1)->get();
        $datapnspegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->count();
        $totalPegawai = ModelUser::where('user_status', 1)->count();

        $pemuktahiran = ModelUser::where('user_tmt', '!=', '1990-01-01')->count();
        $pendidikan = ModelUser::where('user_pendidikan', '=', 0)->count();
        $pemuktahiranFoto = ModelUser::where('user_foto', '-')->count();
        $pemuktahiranJabatan = ModelUser::where('user_jabatan', 65)->count();

        return view('kepegawaian.dashboard', compact(
            'dataPegawai',
            'totalPegawai',
            'datapnspegawai',
            'datapppkpegawai',
            'pendidikan',
            'pemuktahiran',
            'pemuktahiranFoto',
            'pemuktahiranJabatan'
        ));
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
            ->select(
                'sadarin_user.*',
                'sadarin_bidang.*',
                'sadarin_jabatan.*',
                'sadarin_golongan.*',
                'sadarin_eselon.*',
                'sadarin_pendidikan.*'
            )
            ->where('sadarin_user.user_status', 1)
            ->orderByRaw("
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
                ")
            ->get();

        $datapnspegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->count();
        $totalPegawai = ModelUser::where('user_status', 1)->count();

        return view('kepegawaian.datapegawai', compact(
            'dataPegawai',
            'totalPegawai',
            'datapnspegawai',
            'datapppkpegawai'
        ));
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
            ->select(
                'sadarin_ubahuser.*',
                'sadarin_bidang.*',
                'sadarin_jabatan.*',
                'sadarin_golongan.*',
                'sadarin_eselon.*',
                'sadarin_pendidikan.*'
            )
            ->where('sadarin_ubahuser.ubahuser_status', 0)
            ->orderByRaw("
                    sadarin_ubahuser.created_at DESC
                ")
            ->get();

        $datapnspegawai = ModelUbahUser::where('ubahuser_status', 0)->where('ubahuser_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUbahUser::where('ubahuser_status', 0)->where('ubahuser_jeniskerja', 2)->count();
        $totalPegawaiPemuktahiran = ModelUbahUser::where('ubahuser_status', 0)->count();
        $totalPegawai = ModelUser::where('user_status', 1)->count();

        return view('kepegawaian.pemuktahiran', compact(
            'dataPegawai',
            'totalPegawai',
            'totalPegawaiPemuktahiran',
            'datapnspegawai',
            'datapppkpegawai',
        ));
    }
    public function pegawaiUpdate(Request $request)
    {

        $validator = Validator::make(
            array_merge($request->all(), $request->allFiles()), // 👈 tambahin file
            [
                'user_nama'        => 'required|string|max:100',
                'user_nik'         => 'required|string|max:100',
                'user_nip'         => 'required|string|max:100',
                'user_email'       => 'nullable|email|max:100',
                'user_notelp'      => 'nullable|numeric',
                'user_npwp'        => 'nullable|numeric',
                'user_bpjs'        => 'nullable|numeric',
                'user_norek'       => 'nullable|numeric',
                'user_jmltanggungan' => 'nullable|numeric',
                'user_tgllahir'    => 'required|date',
                'user_tmt'         => 'required|date',
                'user_spmt'        => 'required|date',
                'user_tempatlahir'  => 'required|string|max:100',

                'user_foto' => 'nullable|file|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'user_foto.image' => 'File yang diupload harus berupa gambar.',
                'user_foto.mimes' => 'Format foto hanya boleh JPG atau PNG.',
                'user_foto.max'   => 'Ukuran foto maksimal 2MB.',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
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
        $request->validate([
            'user_foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'user_foto.image' => 'File yang diupload harus berupa gambar.',
            'user_foto.mimes' => 'Format foto hanya boleh JPG atau PNG.',
            'user_foto.max'   => 'Ukuran foto maksimal 2MB.',
        ]);

        $user = ModelUser::findOrFail($request->user_id);

        if ($request->hasFile('user_foto')) {
            $file = $request->file('user_foto');
            $nip = $request->user_nip ?? $user->user_nip;
            $filename = "{$nip}_Pasfoto." . $file->getClientOriginalExtension();

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
        $endThisMonth   = $now->copy()->endOfMonth();

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
        $pensiunBulanIni   = $users->whereBetween('tanggal_pensiun', [$startThisMonth, $endThisMonth])->count();
        $pensiunBulanDepan = $users->whereBetween('tanggal_pensiun', [
            $now->copy()->addMonth()->startOfMonth(),
            $now->copy()->addMonth()->endOfMonth()
        ])->count();
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
        $daftarPensiun = $users->filter(function ($u) use ($startThisMonth, $now) {
            return $u->tanggal_pensiun >= $startThisMonth && $u->tanggal_pensiun <= $now->copy()->addYear()->endOfYear();
        })->sortBy('tanggal_pensiun')
            // normalisasi supaya view mudah akses fields (tetap bisa pakai model kalau mau)
            ->map(function ($u) {
                return (object) [
                    'id' => $u->user_id ?? $u->id ?? null,
                    'user_id' => $u->user_id ?? $u->id ?? null,
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
        $pnsTahunIni = $users->filter(function ($u) use ($now) {
            return ((string)$u->user_jeniskerja === '1' || $u->user_jeniskerja == 1)
                && $u->tanggal_pensiun->year == $now->year;
        })->count();

        $pppkTahunIni = $users->filter(function ($u) use ($now) {
            return ((string)$u->user_jeniskerja === '2' || $u->user_jeniskerja == 2)
                && $u->tanggal_pensiun->year == $now->year;
        })->count();

        // opsional: statistik total agar sesuai bagian atas dashboard
        $totalPegawai = ModelUser::count();
        $datapnspegawai = ModelUser::where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_jeniskerja', 2)->count();

        return view('kepegawaian.pensiun', compact(
            'totalPegawai',
            'datapnspegawai',
            'datapppkpegawai',
            'pensiunBulanIni',
            'pensiunBulanDepan',
            'pensiun3Bulan',
            'pensiun6Bulan',
            'pensiun1Tahun',
            'pensiun2025',
            'pensiun2026',
            'pnsTahunIni',
            'pppkTahunIni',
            'daftarPensiun',
            'listPensiun2025',
            'listPensiun2026'
        ));
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
        $endThisMonth   = $now->copy()->endOfMonth();

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
        $kgbBulanIni   = $users->whereBetween('tanggal_kgb', [$startThisMonth, $endThisMonth])->count();
        $kgbBulanDepan = $users->whereBetween('tanggal_kgb', [
            $now->copy()->addMonth()->startOfMonth(),
            $now->copy()->addMonth()->endOfMonth()
        ])->count();
        $kgb3Bulan = $users->whereBetween('tanggal_kgb', [$now, $now->copy()->addMonths(3)->endOfMonth()])->count();
        $kgb6Bulan = $users->whereBetween('tanggal_kgb', [$now, $now->copy()->addMonths(6)->endOfMonth()])->count();
        $kgb1Tahun = $users->whereBetween('tanggal_kgb', [$now, $now->copy()->addYear()->endOfMonth()])->count();

        // List detail KGB tahun 2025 & 2026
        $listKgb2025 = $users->filter(fn($u) => $u->tanggal_kgb->year == 2025)->sortBy('tanggal_kgb')->values();
        $listKgb2026 = $users->filter(fn($u) => $u->tanggal_kgb->year == 2026)->sortBy('tanggal_kgb')->values();

        // daftar pegawai KGB dalam 1 tahun ke depan
        $daftarKgb = $users->filter(function ($u) use ($startThisMonth, $now) {
            return $u->tanggal_kgb >= $startThisMonth && $u->tanggal_kgb <= $now->copy()->addYear()->endOfYear();
        })->map(function ($u) {
            return (object) [
                'id' => $u->user_id ?? $u->id ?? null,
                'user_id' => $u->user_id ?? $u->id ?? null,
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
        })->sortBy(function ($u) {
            return $u->tanggal_kgb->timestamp; // 🔥 urut berdasarkan timestamp
        })->values();

        // opsional: total pegawai dll
        $totalPegawai = ModelUser::count();
        $datapnspegawai = ModelUser::where('user_jeniskerja', 1)->count();
        $datapppkpegawai = ModelUser::where('user_jeniskerja', 2)->count();

        return view('kepegawaian.kenaikanberkala', compact(
            'totalPegawai',
            'datapnspegawai',
            'datapppkpegawai',
            'kgbBulanIni',
            'kgbBulanDepan',
            'kgb3Bulan',
            'kgb6Bulan',
            'kgb1Tahun',
            'listKgb2025',
            'listKgb2026',
            'daftarKgb'
        ));
    }

    /**
     * Hitung tanggal KGB berikutnya (umumnya setiap 2 tahun dari TMT/KGB terakhir)
     */
    protected function hitungTanggalKgb($u)
    {
        $tmt = Carbon::parse($u->user_tmt);
        return $tmt->copy()->addYears(2); // aturan umum: 2 tahun sekali
    }


    public function verifikasiPemuktahiran($id)
    {
        $ubah = ModelUbahUser::findOrFail($id);

        // cari user di tabel sadarin_user sesuai id user
        $user = ModelUser::findOrFail($ubah->ubahuser_iduser);

        // mapping field satu per satu
        $user->user_nama          = $ubah->ubahuser_nama;
        $user->user_nip           = $ubah->ubahuser_nip;
        $user->user_nik           = $ubah->ubahuser_nik;
        $user->user_gelardepan    = $ubah->ubahuser_gelardepan;
        $user->user_gelarbelakang = $ubah->ubahuser_gelarbelakang;
        $user->user_jk            = $ubah->ubahuser_jk;
        $user->user_tgllahir      = $ubah->ubahuser_tgllahir;
        $user->user_pendidikan    = $ubah->ubahuser_pendidikan;
        $user->user_jabatan       = $ubah->ubahuser_jabatan;
        $user->user_golongan      = $ubah->ubahuser_golongan;
        $user->user_eselon        = $ubah->ubahuser_eselon;
        $user->user_tempatlahir   = $ubah->ubahuser_tempatlahir;
        $user->user_kelasjabatan  = $ubah->ubahuser_kelasjabatan;
        $user->user_bidang        = $ubah->ubahuser_bidang;
        $user->user_tmt           = $ubah->ubahuser_tmt;
        $user->user_spmt          = $ubah->ubahuser_spmt;
        $user->user_jeniskerja    = $ubah->ubahuser_jeniskerja;
        $user->user_alamat        = $ubah->ubahuser_alamat;
        $user->user_notelp        = $ubah->ubahuser_notelp;
        $user->user_email         = $ubah->ubahuser_email;
        $user->user_bpjs          = $ubah->ubahuser_bpjs;
        $user->user_norek         = $ubah->ubahuser_norek;
        $user->user_npwp          = $ubah->ubahuser_npwp;
        $user->user_jmltanggungan = $ubah->ubahuser_jmltanggungan;
        $user->user_foto          = $ubah->ubahuser_foto; // karena simpan di path sama

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
            ->leftJoin('sadarin_pengumpulanberkas', 'sadarin_user.user_nip', '=', 'sadarin_pengumpulanberkas.kumpulan_user')
            ->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->leftJoin('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->leftJoin('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')
            ->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->select(
                'sadarin_user.*',
                'sadarin_pengumpulanberkas.kumpulan_id',
                'sadarin_pengumpulanberkas.kumpulan_status',
                'sadarin_pengumpulanberkas.kumpulan_file',
                'sadarin_bidang.bidang_nama',
                'sadarin_jabatan.jabatan_nama',
                'sadarin_golongan.*',
                'sadarin_eselon.*',
                'sadarin_pendidikan.*'
            )
            ->where('sadarin_user.user_status', 1)
            ->where('sadarin_pengumpulanberkas.kumpulan_jenis', $id)
            ->orderByRaw("
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
                ")
            ->get();

        // Filter PNS dan PPPK
        $dataPns = $dataPegawai->where('user_jeniskerja', '1');
        $dataPppk = $dataPegawai->where('user_jeniskerja', '2');

        // Statistik jumlah yang sudah kumpul
        $jumlahPnsKumpul = $dataPns->where('kumpulan_status', 1)->count();
        $jumlahPppkKumpul = $dataPppk->where('kumpulan_status', 1)->count();

        $dataPns = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->get();
        $dataPppk = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->get();
        return view('kepegawaian.paktaintegritas', compact(
            'dataPegawai',
            'dataPns',
            'dataPppk',
            'jumlahPnsKumpul',
            'jumlahPppkKumpul',
            'dataPns',
            'dataPppk'
        ));
    }
    /// akhir Pakta Integritas
    /// lihat pakta
    public function syncPaktaIntegritas()
    {
        // ambil semua pegawai aktif
        $users = ModelUser::whereIn('user_jeniskerja', ['1', '2'])->get();

        foreach ($users as $user) {
            // tentukan folder berdasarkan jenis kerja
            if ($user->user_jeniskerja == '1') {
                $folderId = env('GOOGLE_DRIVE_FOLDER_PNS');
            } elseif ($user->user_jeniskerja == '2') {
                $folderId = env('GOOGLE_DRIVE_FOLDER_PPPK');
            } else {
                continue;
            }

            // cek file di Google Drive
            $fileData = $this->drive->findFileByNip($user->user_nip, $folderId);

            // simpan ke tabel pengumpulan berkas
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => 'Pakta Integritas',
                ],
                [
                    'kumpulan_file'   => $fileData['file_url'],
                    'kumpulan_status' => $fileData['status'],
                ]
            );
        }

        return response()->json(['message' => 'Sinkronisasi selesai.']);
    }
    public function lihatPakta($id)
    {
        $berkas = DB::table('sadarin_pengumpulanberkas')
            ->where('kumpulan_id', $id)
            ->first();

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
    public function exportPaktaIntegritas()
    {
        $dataPegawai = DB::table('sadarin_user')
            ->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_pengumpulanberkas', 'sadarin_user.user_nip', '=', 'sadarin_pengumpulanberkas.kumpulan_user')
            ->select('sadarin_user.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_pengumpulanberkas.kumpulan_status', 'sadarin_pengumpulanberkas.kumpulan_file')
            ->where('sadarin_user.user_status', 1)
            ->orderByRaw("
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
                ")
            ->get();

        $dataPns = $dataPegawai->where('user_jeniskerja', '1');
        $dataPppk = $dataPegawai->where('user_jeniskerja', '2');

        return Excel::download(new PegawaiExport($dataPns, $dataPppk), 'PaktaIntegritasDisbud.xlsx');
    }
}
