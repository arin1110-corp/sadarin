<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelUser;
use App\Models\ModelBidang;
use App\Models\ModelSubBag;
use App\Models\ModelNavigasiSekretariat;
use App\Models\ModelSubNavigasiSekretariat;
use Google\Service\Bigquery\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class KodeController extends Controller
{
    private $kodeValid = ['A1@B2', 'X9#K7', 'R5$T3', 'Y7@U1', 'Z8%P4', 'K0#L9', 'N3&M6', 'G7*H8', 'W6!Q2', 'D4@Z5'];

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
            ->join('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->where('user_nip', $pegawai)
            ->select('sadarin_user.*', 'sadarin_golongan.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_eselon.*')
            ->first();
        if (!$pegawai) {
            return redirect()->route('akses.form')->withErrors(['kode_akses' => 'Kode akses salah.']);
        }
        return view('homepage_detailpegawai', compact('user'));
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
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala UPTD' THEN 2
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala UPTD' THEN 3
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Bidang' THEN 4
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala%' THEN 5
                        ELSE 5
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
    public function adminBidang()
    {
        $bidangs = ModelBidang::where('bidang_status', 1)->get();
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
}
