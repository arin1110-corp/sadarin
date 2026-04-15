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
use App\Models\ModelTimkerja;
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

class HomepageController extends Controller
{
    //
    protected $drive;
    private $kodeValid = ['X9#K7', 'R5$T3', 'Y7@U1', 'Z8%P4', 'K0#L9', 'N3&M6', 'G7*H8', 'W6!Q2', 'D4@Z5'];

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
            return back()
                ->withErrors(['login' => 'NIP tidak ditemukan'])
                ->withInput();
        }

        // Cek password
        if (!Hash::check($request->password, $admin->admin_password)) {
            return back()
                ->withErrors(['login' => 'Password salah'])
                ->withInput();
        }

        // Cek status aktif
        if ($admin->admin_status != 1) {
            return back()
                ->withErrors(['login' => 'Akun tidak aktif'])
                ->withInput();
        }

        // Redirect berdasarkan role
        if ($admin->admin_role === 'Admin') {
            // Simpan session
            session([
                'admin_id' => $admin->admin_id,
                'admin_role' => $admin->admin_role,
                'admin_nip' => $admin->admin_nip,
            ]);
            return redirect()->route('dashboard'); // route dashboard admin
        } elseif ($admin->admin_role === 'Kepegawaian') {
            session([
                'kepegawaian_id' => $admin->admin_id,
                'kepegawaian_role' => $admin->admin_role,
                'kepegawaian_nip' => $admin->admin_nip,
            ]);
            return redirect()->route('kepegawaian.dashboard'); // route dashboard kepegawaian
        }

        return back()->withErrors(['login' => 'Role tidak dikenali']);
    }

    // Logout
    public function logout(Request $request)
    {
        // Hapus semua data session
        $request->session()->flush();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()
            ->route('akses.form')
            ->withErrors(['kode_akses' => 'Anda logout.']);
    }
    public function form()
    {
        return view('homepage_awal');
    }
    public function maintenance()
    {
        return view('homepage_maintenance');
    }

    public function cek(Request $request)
    {
        $request->validate([
            'kode_akses' => ['required', 'string', 'min:5'],
        ]);
        // Reset session lama
        session()->forget(['kode_akses_valid', 'akses_full', 'user_info']);

        if (in_array($request->kode_akses, $this->kodeValid)) {
            session([
                'kode_akses_valid' => true,
                'akses_full' => true,
                'user_info' => null, // akses penuh, tidak butuh data user
            ]);

            $bidang = ModelBidang::where('bidang_status', 1)->get();
            return view('homepage_menuawal', compact('bidang'));
        }

        // Kalau kode akses adalah NIP
        $user = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->where(function ($q) use ($request) {
                $q->where('user_nip', '!=', '-') // tambahkan ini
                    ->where('user_nip', $request->kode_akses)
                    ->orWhere('user_nik', $request->kode_akses);
            })
            ->select('sadarin_user.user_nip', 'user_id', 'sadarin_user.user_nama', 'sadarin_user.user_email', 'sadarin_user.user_foto', 'sadarin_user.user_password', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_user.user_jeniskerja', 'sadarin_user.user_nik')
            ->first();
        if ($user) {
            session([
                'kode_akses_valid' => true,
                'akses_full' => false,
                'user_info' => $user,
                'user_id' => $user->user_id,
            ]);

            $bidang = ModelBidang::where('bidang_status', 1)->get();
            $tim = ModelTimKerja::where('timkerja_ketuatim', $user->user_id)->first();

            return view('homepage_menuawal', compact('bidang', 'user', 'tim'));
        }

        return back()
            ->withErrors(['kode_akses' => 'Kode akses salah.'])
            ->withInput();
    }

    public function akses_kode()
    {
        return view('homepage_awal');
    }
}