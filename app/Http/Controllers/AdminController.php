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

class AdminController extends Controller
{
    //
    // Admin Dashboard

    public function admin()
    {
        $users = ModelUser::with('bidang')->where('user_status', 1)->get();

        $rekapBidang = $users->groupBy('user_bidang')->map(function ($group) {
            return [
                'nama' => optional($group->first()->bidang)->bidang_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('golongan')->where('user_status', 1)->get();

        $rekapGolongan = $users->groupBy('user_golongan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->golongan)->golongan_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('jabatan')->where('user_status', 1)->get();

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
        return view('admin.dashboard', compact('dataPegawai', 'totalPegawai', 'rekapBidang', 'rekapGolongan', 'rekapJabatan', 'jumlahLaki', 'jumlahPerempuan', 'datapnspegawai', 'datapppkpegawai', 'dataPns', 'dataPppk'));
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
        $navigasisekretariat = ModelNavigasiSekretariat::with('subnavigasisekretariat')->with('subbag')->where('navigasisekre_status', 1)->get();
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
            'navigasisekre_level' => $request->navigasisekre_level,
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
            'navigasisekre_level' => $request->navigasisekre_level,
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
        $navs = ModelNavigasiSekretariat::get();
        $subnavigasisekretariat = ModelSubNavigasiSekretariat::with('navigasisekretariat')->get();
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
}