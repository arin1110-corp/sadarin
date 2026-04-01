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
use App\Models\ModelTimkerjaDetail;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class AksesController extends Controller
{
    //
    public function detailpegawai()
    {
        $pegawai = session('user_info')->user_nip;
        $pegawai1 = session('user_info')->user_nik;
        $jeniskerjapeg = session('user_info')->user_jeniskerja;
        // Jika NIP tidak valid, anggap NIP = null agar tidak dipakai
        if ($pegawai == '-' || $pegawai == '' || $pegawai == null) {
            $pegawai = null;
        }
        $user = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->join('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')
            ->join('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->where(function ($q) use ($pegawai, $pegawai1) {
                if ($pegawai) {
                    $q->where('user_nip', $pegawai);
                }
                $q->orWhere('user_nik', $pegawai1);
            })
            ->select('sadarin_user.*', 'sadarin_golongan.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_eselon.*', 'sadarin_pendidikan.*')
            ->first();
        if (!$pegawai && !$pegawai1) {
            return redirect()
                ->route('akses.form')
                ->withErrors(['kode_akses' => 'Kode akses salah.']);
        }
        $berkas = DB::table('sadarin_pengumpulanberkas')
            ->where(function ($q) use ($pegawai, $pegawai1) {
                if ($pegawai) {
                    $q->where('kumpulan_user', $pegawai);
                }
                $q->orWhere('kumpulan_user', $pegawai1);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $jabatans = ModelJabatan::all();
        $eselons = ModelEselon::all();
        $bidangs = ModelBidang::all();
        $golongans = ModelGolongan::all();
        $pendidikans = ModelPendidikan::all();
        $dataTombol = DB::table('sadarin_tombolberkas')
            ->leftJoin('sadarin_mappingtombol', function ($join) use ($jeniskerjapeg) {
                $join->on('sadarin_tombolberkas.tombol_id', '=', 'sadarin_mappingtombol.mapping_tombol')
                    ->where('sadarin_mappingtombol.mapping_jeniskerja', $jeniskerjapeg);
            })
            ->leftJoin('sadarin_json', 'sadarin_tombolberkas.tombol_json', '=', 'sadarin_json.json_id')
            ->leftJoin('sadarin_tomboltitle', 'sadarin_tombolberkas.tombol_title', '=', 'sadarin_tomboltitle.title_id')
            ->select(
                'sadarin_tombolberkas.*',
                'sadarin_mappingtombol.mapping_id',
                'sadarin_mappingtombol.mapping_folderid',
            'sadarin_mappingtombol.mapping_jeniskerja',
            'sadarin_json.json_nama',
            'sadarin_tomboltitle.title_nama'
        )
            ->whereNotNull('sadarin_mappingtombol.mapping_id')
            ->get();

        // 🔥 versi 1: flat (untuk atas)
        $tombolsFlat = $dataTombol;

        // 🔥 versi 2: grouped (untuk bawah)
        $tombolsGroup = $dataTombol->groupBy('title_nama');

        return view('homepage_detailpegawai', compact('user', 'jabatans', 'eselons', 'bidangs', 'golongans', 'pendidikans', 'berkas', 'jeniskerjapeg', 'tombolsFlat', 'tombolsGroup'));
    }
    public function strukturOrganisasi()
    {
        $dataPegawai = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->join('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')->where('sadarin_user.user_status', 1)->select('sadarin_user.*', 'sadarin_golongan.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_eselon.*')->where('sadarin_user.user_status', 1)->get();
        return view('homepagestrukturorganisasi', compact('dataPegawai'));
    }
    public function lihatjajaran(Request $request)
    {
        $kategori = $request->kategori ?? null;

        if ($kategori === 'Fungsional') {
            // Ambil semua pegawai fungsional
            $pegawaiBidang = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->where('jabatan_kategori', 'Fungsional')->select('sadarin_user.*', 'sadarin_jabatan.*', 'sadarin_bidang.*', 'sadarin_golongan.*')->where('sadarin_user.user_status', 1)->get();

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
            $pegawaiBidang = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->where('user_bidang', $bidangId)->select('sadarin_user.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.*', 'sadarin_golongan.*')->where('sadarin_user.user_status', 1)->get();

            $kepala = $pegawaiBidang->filter(function ($p) {
                return str_contains($p->jabatan_nama, 'Kepala Bidang') || str_contains($p->jabatan_nama, 'Sekretaris') || str_contains($p->jabatan_nama, 'Kepala UPTD') || (str_contains($p->jabatan_nama, 'Kepala') && !str_contains($p->jabatan_nama, 'Kepala Dinas'));
            });

            $prioritasJabatan = [
                'Kepala Bidang' => 1,
                'Sekretaris' => 2,
                'Kepala UPTD' => 3,
                'Kepala' => 4,
            ];

            $kepala = $kepala->sortBy(function ($p) use ($prioritasJabatan) {
                foreach ($prioritasJabatan as $key => $value) {
                    if (str_contains($p->jabatan_nama, $key)) {
                        return $value;
                    }
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

        $datasekretariatQuery = ModelNavigasiSekretariat::with(['subnavigasisekretariat'])->where('navigasisekre_subbag', $subbagId);

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

        $datasekretariat = ModelNavigasiSekretariat::with([
            'subnavigasisekretariat' => function ($query) {
                $query->where('subnavigasisekre_status', 1);
            },
        ])
            ->where('navigasisekre_subbag', 3)
            ->get();

        return view('homepage_menunavigasi', compact('datasekretariat', 'subbagNama'));
    }
    public function datakeuangan()
    {
        $subbagNama = ModelSubbag::where('subbag_id', 2)->value('subbag_nama');
        $datasekretariat = ModelNavigasiSekretariat::with('subnavigasisekretariat')->where('navigasisekre_subbag', 2)->get();
        return view('homepage_data_subbag_sekretariat', compact('datasekretariat', 'subbagNama'));
    }
    public function datasenirupa()
    {
        $subbagId = 4;
        $subbagNama = ModelSubbag::where('subbag_id', $subbagId)->value('subbag_nama');

        // Cek apakah user punya akses penuh atau tidak
        $aksesFull = session('akses_full', false);

        $datasekretariatQuery = ModelNavigasiSekretariat::with(['subnavigasisekretariat'])->where('navigasisekre_subbag', $subbagId);

        // Kalau akses bukan penuh (login pakai NIP) → filter level = 1
        if (!$aksesFull) {
            $datasekretariatQuery->where('navigasisekre_level', 1);
        }

        $datasekretariat = $datasekretariatQuery->get();

        return view('homepage_data_subbag_sekretariat', compact('datasekretariat', 'subbagNama'));
    }
    public function dataPegawaiPNS()
    {
        $users = ModelUser::with('bidang')->where('user_status', 1)->where('user_jeniskerja', 1)->get();

        $rekapBidang = $users->groupBy('user_bidang')->map(function ($group) {
            return [
                'nama' => optional($group->first()->bidang)->bidang_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('golongan')->where('user_status', 1)->where('user_jeniskerja', 1)->get();

        $rekapGolongan = $users->groupBy('user_golongan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->golongan)->golongan_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('jabatan')->where('user_status', 1)->where('user_jeniskerja', 1)->get();

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
            'Kepala UPT' => 4,
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
            ->orderByRaw(
                " CASE
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Dinas' THEN 1
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Bidang' THEN 2
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala UPT' THEN 3
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala%' THEN 4
                        ELSE 5 END ",
            )
            ->orderBy('sadarin_user.user_nama', 'asc')
            ->select('sadarin_user.*', 'sadarin_jabatan.jabatan_nama')
            ->get();
        $totalPegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 1)->count();
        return view('homepage_data_pegawai_pns', compact('dataPegawai', 'totalPegawai', 'rekapBidang', 'rekapGolongan', 'rekapJabatan', 'jumlahLaki', 'jumlahPerempuan'));
    }
    public function dataPegawaiRekap()
    {
        $base = ModelUser::query()->join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->leftJoin('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->where('sadarin_user.user_status', 1);

        /* ================= TOTAL ================= */
        $totalPegawai = (clone $base)->count();
        $jumlahLaki = (clone $base)->where('sadarin_user.user_jk', 'L')->count();
        $jumlahPerempuan = (clone $base)->where('sadarin_user.user_jk', 'P')->count();

        /* ================= DETAIL ================= */
        $rekapBidangDetail = (clone $base)
            ->selectRaw(
                '
        sadarin_bidang.bidang_nama as bidang,
        sadarin_jabatan.jabatan_nama as jabatan,
        sadarin_jabatan.jabatan_kategori as jabatan_kategori,
        CONCAT(
            sadarin_golongan.golongan_nama,
            " (",
            sadarin_golongan.golongan_pangkat,
            ")"
        ) as golongan,
        sadarin_pendidikan.pendidikan_jenjang as jenjang,
        sadarin_pendidikan.pendidikan_jurusan as jurusan,
        sadarin_user.user_jk as jk,
        COUNT(*) as jumlah
    ',
            )
            ->groupBy('sadarin_bidang.bidang_nama', 'sadarin_jabatan.jabatan_nama', 'sadarin_jabatan.jabatan_kategori', 'sadarin_golongan.golongan_nama', 'sadarin_golongan.golongan_pangkat', 'sadarin_pendidikan.pendidikan_jenjang', 'sadarin_pendidikan.pendidikan_jurusan', 'sadarin_user.user_jk')
            ->orderBy('sadarin_bidang.bidang_nama')
            ->orderBy('sadarin_jabatan.jabatan_kategori')
            ->orderBy('sadarin_jabatan.jabatan_nama')
            ->get();

        /* ================= REKAP ARRAY ================= */
        $bidangRekap = [];

        foreach ($rekapBidangDetail as $row) {
            // 🔑 LOGIKA GABUNG DINAS
            if ($row->bidang === 'Sekretariat' || str_starts_with($row->bidang, 'Bidang')) {
                $key = 'DINAS KEBUDAYAAN PROVINSI BALI';
            } else {
                // UPTD tetap berdiri sendiri
                $key = $row->bidang;
            }

            $jk = $row->jk;

            /* ================= PENDIDIKAN TERAKHIR ================= */
            $bidangRekap[$key]['pendidikan_jenjang'][$row->jenjang][$jk] = ($bidangRekap[$key]['pendidikan_jenjang'][$row->jenjang][$jk] ?? 0) + $row->jumlah;

            /* ================= KATEGORI JABATAN ================= */
            $bidangRekap[$key]['jabatan_kategori'][$row->jabatan_kategori][$jk] = ($bidangRekap[$key]['jabatan_kategori'][$row->jabatan_kategori][$jk] ?? 0) + $row->jumlah;

            /* ================= JABATAN ================= */
            $bidangRekap[$key]['jabatan'][$row->jabatan][$jk] = ($bidangRekap[$key]['jabatan'][$row->jabatan][$jk] ?? 0) + $row->jumlah;

            /* ================= GOLONGAN ================= */
            $bidangRekap[$key]['golongan'][$row->golongan][$jk] = ($bidangRekap[$key]['golongan'][$row->golongan][$jk] ?? 0) + $row->jumlah;

            /* ================= DETAIL PENDIDIKAN ================= */
            $namaPendidikan = trim($row->jenjang . ' - ' . $row->jurusan);

            $bidangRekap[$key]['pendidikan_detail'][$namaPendidikan][$jk] = ($bidangRekap[$key]['pendidikan_detail'][$namaPendidikan][$jk] ?? 0) + $row->jumlah;
        }
        // ================= HITUNG TOTAL DINAS DAN UPTD =================
        $totalPerUnit = [];

        foreach ($bidangRekap as $unit => $data) {
            $jumlahL = 0;
            $jumlahP = 0;

            // Hitung total semua kategori dari unit (bisa dari pendidikan, jabatan, atau golongan, pilih salah satu)
            foreach ($data['pendidikan_jenjang'] ?? [] as $jenjang => $jk) {
                $jumlahL += $jk['L'] ?? 0;
                $jumlahP += $jk['P'] ?? 0;
            }

            $totalPerUnit[$unit] = [
                'L' => $jumlahL,
                'P' => $jumlahP,
                'total' => $jumlahL + $jumlahP,
            ];
        }

        // Total DINAS saja
        $totalDinas = $totalPerUnit['DINAS KEBUDAYAAN PROVINSI BALI']['total'] ?? 0;

        // Total UPTD (loop dan pilih unit selain DINAS)
        $totalUPTD = [];
        foreach ($totalPerUnit as $unit => $tot) {
            if ($unit !== 'DINAS KEBUDAYAAN PROVINSI BALI') {
                $totalUPTD[$unit] = $tot['total'];
            }
        }

        return view('homepage_jumlah_pegawai', compact('totalPegawai', 'jumlahLaki', 'jumlahPerempuan', 'bidangRekap', 'totalDinas', 'totalUPTD'));
    }
    public function dataPegawaiRekapPerBidang()
    {
        $base = ModelUser::query()->join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->leftJoin('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->where('sadarin_user.user_status', 1);

        /* ================= TOTAL ================= */
        $totalPegawai = (clone $base)->count();
        $jumlahLaki = (clone $base)->where('sadarin_user.user_jk', 'L')->count();
        $jumlahPerempuan = (clone $base)->where('sadarin_user.user_jk', 'P')->count();

        /* ================= DETAIL ================= */
        $rekapBidangDetail = (clone $base)
            ->selectRaw(
                '
        sadarin_bidang.bidang_nama as bidang,
        sadarin_jabatan.jabatan_nama as jabatan,
        sadarin_jabatan.jabatan_kategori as jabatan_kategori,
        CONCAT(
            sadarin_golongan.golongan_nama,
            " (",
            sadarin_golongan.golongan_pangkat,
            ")"
        ) as golongan,
        sadarin_pendidikan.pendidikan_jenjang as jenjang,
        sadarin_pendidikan.pendidikan_jurusan as jurusan,
        sadarin_user.user_jk as jk,
        COUNT(*) as jumlah
    ',
            )
            ->groupBy('sadarin_bidang.bidang_nama', 'sadarin_jabatan.jabatan_nama', 'sadarin_jabatan.jabatan_kategori', 'sadarin_golongan.golongan_nama', 'sadarin_golongan.golongan_pangkat', 'sadarin_pendidikan.pendidikan_jenjang', 'sadarin_pendidikan.pendidikan_jurusan', 'sadarin_user.user_jk')
            ->orderBy('sadarin_bidang.bidang_nama')
            ->orderBy('sadarin_jabatan.jabatan_kategori')
            ->orderBy('sadarin_jabatan.jabatan_nama')
            ->get();

        /* ================= REKAP ARRAY ================= */
        $bidangRekap = [];

        foreach ($rekapBidangDetail as $row) {
            // 🔑 LOGIKA GABUNG DINAS
            $key = $row->bidang;

            $jk = $row->jk;

            /* ================= PENDIDIKAN TERAKHIR ================= */
            $bidangRekap[$key]['pendidikan_jenjang'][$row->jenjang][$jk] = ($bidangRekap[$key]['pendidikan_jenjang'][$row->jenjang][$jk] ?? 0) + $row->jumlah;

            /* ================= KATEGORI JABATAN ================= */
            $bidangRekap[$key]['jabatan_kategori'][$row->jabatan_kategori][$jk] = ($bidangRekap[$key]['jabatan_kategori'][$row->jabatan_kategori][$jk] ?? 0) + $row->jumlah;

            /* ================= JABATAN ================= */
            $bidangRekap[$key]['jabatan'][$row->jabatan][$jk] = ($bidangRekap[$key]['jabatan'][$row->jabatan][$jk] ?? 0) + $row->jumlah;

            /* ================= GOLONGAN ================= */
            $bidangRekap[$key]['golongan'][$row->golongan][$jk] = ($bidangRekap[$key]['golongan'][$row->golongan][$jk] ?? 0) + $row->jumlah;

            /* ================= DETAIL PENDIDIKAN ================= */
            $namaPendidikan = trim($row->jenjang . ' - ' . $row->jurusan);

            $bidangRekap[$key]['pendidikan_detail'][$namaPendidikan][$jk] = ($bidangRekap[$key]['pendidikan_detail'][$namaPendidikan][$jk] ?? 0) + $row->jumlah;
        }
        // ================= HITUNG TOTAL DINAS DAN UPTD =================
        $totalPerUnit = [];

        foreach ($bidangRekap as $unit => $data) {
            $jumlahL = 0;
            $jumlahP = 0;

            // Hitung total semua kategori dari unit (bisa dari pendidikan, jabatan, atau golongan, pilih salah satu)
            foreach ($data['pendidikan_jenjang'] ?? [] as $jenjang => $jk) {
                $jumlahL += $jk['L'] ?? 0;
                $jumlahP += $jk['P'] ?? 0;
            }

            $totalPerUnit[$unit] = [
                'L' => $jumlahL,
                'P' => $jumlahP,
                'total' => $jumlahL + $jumlahP,
            ];
        }

        // Total DINAS saja
        $totalDinas = $totalPerUnit['DINAS KEBUDAYAAN PROVINSI BALI']['total'] ?? 0;

        // Total UPTD (loop dan pilih unit selain DINAS)
        $totalUPTD = [];
        foreach ($totalPerUnit as $unit => $tot) {
            if ($unit !== 'DINAS KEBUDAYAAN PROVINSI BALI') {
                $totalUPTD[$unit] = $tot['total'];
            }
        }

        return view('homepage_jumlah_pegawai_per_bidang', compact('totalPegawai', 'jumlahLaki', 'jumlahPerempuan', 'bidangRekap', 'totalDinas', 'totalUPTD'));
    }
    public function dataPegawaiPPPK()
    {
        $users = ModelUser::with('bidang')->where('user_status', 1)->where('user_jeniskerja', 2)->get();

        $rekapBidang = $users->groupBy('user_bidang')->map(function ($group) {
            return [
                'nama' => optional($group->first()->bidang)->bidang_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('golongan')->where('user_status', 1)->where('user_jeniskerja', 2)->get();

        $rekapGolongan = $users->groupBy('user_golongan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->golongan)->golongan_nama,
                'jumlah' => $group->count(),
            ];
        });
        $users = ModelUser::with('jabatan')->where('user_status', 1)->where('user_jeniskerja', 2)->get();

        $rekapJabatan = $users->groupBy('user_jabatan')->map(function ($group) {
            return [
                'nama' => optional($group->first()->jabatan)->jabatan_nama,
                'jumlah' => $group->count(),
            ];
        });

        $order = [
            'Kepala Dinas' => 1,
            'Kepala Bidang' => 2,
            'Kepala UPT' => 3,
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
        $dataPegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->get();
        $totalPegawai = ModelUser::where('user_status', 1)->where('user_jeniskerja', 2)->count();
        return view('homepage_data_pegawai_pns', compact('dataPegawai', 'totalPegawai', 'rekapBidang', 'rekapGolongan', 'rekapJabatan', 'jumlahLaki', 'jumlahPerempuan'));
    }
    public function dataPegawaiRincian()
    {
        // Mengambil data pegawai semua
        $dataPegawai = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->where('sadarin_user.user_status', 1)
            ->orderByRaw(
                "
                    CASE
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Dinas' THEN 1
                        WHEN sadarin_jabatan.jabatan_nama = 'Sekretaris' THEN 2
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala UPTD' THEN 3
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala UPTD' THEN 4
                        WHEN sadarin_jabatan.jabatan_nama = 'Kepala Bidang' THEN 5
                        WHEN sadarin_jabatan.jabatan_nama LIKE 'Kepala%' THEN 6
                        ELSE 7
                    END
                ",
            )
            ->orderByRaw(
                "
        CASE
            WHEN sadarin_user.user_jeniskerja = 1 THEN 1
            WHEN sadarin_user.user_jeniskerja = 2 THEN 2
            ELSE 3
        END
    ",
            )
            ->orderBy('sadarin_user.user_nama', 'asc')
            ->select('sadarin_user.*', 'sadarin_golongan.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama')
            ->get();

        return view('homepage_rincian_pegawai', compact('dataPegawai'));
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
        return redirect()
            ->route('akses.kode')
            ->withErrors(['kode_akses' => 'Kode Salah.']);
    }
    public function profilCekPassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $userSession = session('user_info');

        if (!$userSession) {
            return redirect()->route('akses.depan')->with('error', 'Session habis, silakan login ulang');
        }

        $user = ModelUser::where('user_nip', $userSession->user_nip)->orWhere('user_nik', $userSession->user_nik)->first();

        if (!$user) {
            return redirect()->route('akses.depan')->with('error', 'User tidak ditemukan');
        }

        if (!$user->user_password) {
            return back()->with('error', 'Password belum diset');
        }

        if (!Hash::check($request->password, $user->user_password)) {
            return redirect()->route('homepage.menuawal')->with('error', 'Password salah, silakan coba lagi');
        }

        // ✅ Password benar
        session([
            'password_verified' => true,
        ]);

        return redirect()->route('detail.pegawai');
    }
    public function uploadBerkasBaru(Request $request, $tombol_id)
    {
        $user = session('user_info');

        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,jpg,png,gif,bmp,webp',
        ]);

        $tombolMapping = DB::table('sadarin_mappingtombol')->join('sadarin_tombolberkas', 'mapping_tombol', '=', 'tombol_id')->where('mapping_tombol', $tombol_id)->where('mapping_jeniskerja', $user->user_jeniskerja)->select('sadarin_mappingtombol.*', 'sadarin_tombolberkas.tombol_nama')->first();

        if (!$tombolMapping) {
            return back()->with('error', 'Mapping tombol belum tersedia.');
        }

        $finalId = $user->user_nip ?: $user->user_nik;
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        $filename = $finalId . '_' . str_replace(' ', '_', $tombolMapping->tombol_nama) . '.' . $ext;

        $folderServer = $tombolMapping->mapping_folder; // path server
        if (!file_exists(public_path($folderServer))) {
            mkdir(public_path($folderServer), 0755, true);
        }

        // Hapus file lama
        $baseName = $finalId . '_' . str_replace(' ', '_', $tombolMapping->tombol_nama);
        foreach (glob(public_path($folderServer . '/' . $baseName . '.*')) as $old) {
            if (file_exists($old)) {
                unlink($old);
            }
        }

        // Upload file
        $file->move(public_path($folderServer), $filename);

        $url = asset($folderServer . '/' . $filename);

        ModelPengumpulanBerkas::updateOrCreate(
            [
                'kumpulan_user' => $finalId,
                'kumpulan_jenis' => $tombolMapping->tombol_nama,
            ],
            [
                'kumpulan_file' => $url,
                'kumpulan_status' => 1,
                'kumpulan_sync' => 0,
                'kumpulan_keterangan' => 'Upload melalui sistem',
            ],
        );

        return back()
            ->with('success', 'File ' . $tombolMapping->tombol_nama . ' berhasil diupload.')
            ->with('open_modal', $tombol_id); // modal tetap buka sesuai tombol
    }
    public function uploadBerkas(Request $request)
    {
        $user = session('user_info');
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,jpg,png,gif,bmp,webp', // PDF + gambar
            'kumpulan_jenis' => 'required|string',
            'jenisfile' => 'required|string',
        ]);

        $nip = $user->user_nip;
        $nik = $user->user_nik;
        $jeniskerja = $user->user_jeniskerja;
        $jenis = $request->kumpulan_jenis;
        $jenisfile = $request->jenisfile;

        // Gunakan NIP atau NIK
        $finalId = $nip == '-' || $nip == null || $nip == '' ? $nik : $nip;

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension(); // ambil ekstensi asli
        $filename = $finalId . '_' . str_replace(' ', '_', $jenis) . '.' . $ext; // tetap namanya sama tapi sesuai ekstensi

        // Mapping folder seperti sebelumnya
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
        // Hapus file lama (jika ada, ekstensi apa pun)
        $baseName = $finalId . '_' . str_replace(' ', '_', $jenis);
        $oldFiles = glob(public_path($folder . '/' . $baseName . '.*'));

        if ($oldFiles) {
            foreach ($oldFiles as $old) {
                if (file_exists($old)) {
                    unlink($old);
                }
            }
        }
        // Buat folder jika belum ada
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        // Upload file
        $file->move(public_path($folder), $filename);

        $url = asset($folder . '/' . $filename);

        $keterangan = 'Mengupload berkas ' . $jenis . ' melalui sistem.';
        if ($request->filled('tanggal_melapor')) {
            $keterangan = 'Tanggal Melapor ' . \Carbon\Carbon::parse($request->tanggal_melapor)->translatedFormat('d F Y');
        }

        // Simpan / update di DB
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

        return redirect()
            ->route('homepage.menuawal')
            ->with('success', 'File ' . $jenis . ' berhasil diupload.')
            ->with('open_modal', $jenisfile); // kirim info modal mana yang harus dibuka
    }
    public function arsipDisbud()
    {
        $bidang = ModelBidang::where('bidang_status', 1)->get();
        return view('homepage_cekbidang', compact('bidang'));
    }
    // ==============================
    // 1. Kirim link reset password
    // ==============================
    public function sendResetLink(Request $request)
    {
        $user = session('user_info');

        if (!$user) {
            return redirect()->route('homepage.menuawal')->with('error', 'User tidak ditemukan.');
        }

        // ambil data terbaru dari database
        $dbUser = ModelUser::where('user_nip', $user->user_nip)->first();

        if (!$dbUser) {
            return redirect()->route('homepage.menuawal')->with('error', 'Data user tidak ditemukan.');
        }

        // cek email kosong
        if (empty($dbUser->user_email)) {
            return redirect()->route('homepage.menuawal')->with('error', 'Email belum diatur. Silakan hubungi admin.');
        }

        // cek format email
        if (!filter_var($dbUser->user_email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('homepage.menuawal')->with('error', 'Format email tidak valid. Silakan hubungi admin.');
        }

        // cek cooldown reset 4 jam
        if ($dbUser->user_reset_expired && now()->lt($dbUser->user_reset_expired)) {
            $sisa = now()->diffInMinutes($dbUser->user_reset_expired);

            return redirect()
                ->route('homepage.menuawal')
                ->with('error', 'Reset password hanya dapat dilakukan setiap 4 jam. Silakan coba lagi dalam ' . $sisa . ' menit.');
        }

        // generate token
        $token = Str::random(64);

        $link = url('/password/reset/' . $token);

        try {
            Mail::send(
                'auth.email_reset_password',
                [
                    'link' => $link,
                    'user' => $dbUser,
                ],
                function ($mail) use ($dbUser) {
                    $mail->to($dbUser->user_email);
                    $mail->subject('Reset Password SADARIN');
                },
            );

            // simpan token jika email berhasil
            $dbUser->update([
                'user_reset_token' => $token,
                'user_reset_expired' => now()->addHours(4),
            ]);
        } catch (\Exception $e) {
            $msg = $e->getMessage();

            // limit kirim email
            if (str_contains($msg, 'Daily user sending limit exceeded')) {
                return redirect()->route('homepage.menuawal')->with('error', 'Batas pengiriman email hari ini sudah tercapai. Silakan coba kembali besok.');
            }

            // email tidak ditemukan
            if (str_contains($msg, '550') || str_contains($msg, 'mailbox unavailable') || str_contains($msg, 'recipient address rejected')) {
                return redirect()->route('homepage.menuawal')->with('error', 'Email tidak aktif atau tidak ditemukan. Silakan hubungi admin.');
            }

            return redirect()->route('homepage.menuawal')->with('error', 'Gagal mengirim email. Silakan coba kembali.');
        }

        return redirect()->route('homepage.menuawal')->with('success', 'Link reset password telah dikirim ke email.');
    }

    // ==============================
    // 2. Form reset password
    // ==============================
    public function formReset($token)
    {
        $user = ModelUser::where('user_reset_token', $token)->first();

        if (!$user) {
            abort(404);
        }

        if ($user->user_reset_expired < now()) {
            return redirect('/')->with('error', 'Link reset sudah kadaluarsa');
        }

        return view('auth.reset_password', [
            'token' => $token,
        ]);
    }

    // ==============================
    // 3. Simpan password baru
    // ==============================
    public function savePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = ModelUser::where('user_reset_token', $request->token)->first();

        if (!$user) {
            abort(404);
        }

        if ($user->user_reset_expired < now()) {
            return redirect('/')->with('error', 'Link reset sudah kadaluarsa');
        }

        $user->user_password = bcrypt($request->password);
        $user->user_reset_token = null;
        $user->user_reset_expired = null;
        $user->save();

        return redirect('/')->with('success', 'Password berhasil diperbarui');
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
        $user->user_lokasikerja = $request->user_lokasikerja;

        $user->save();

        return redirect()->back()->with('success', 'Data pegawai berhasil diupdate.');
    }
    public function timKerjaModal($id)
    {
        $timkerja_data = DB::table('sadarin_timkerja')
            // Join bidang
            ->leftJoin('sadarin_bidang', 'sadarin_timkerja.timkerja_bidang', '=', 'sadarin_bidang.bidang_id')

            // Ketua tim (dari timkerja_ketua)
            ->leftJoin('sadarin_user', 'sadarin_timkerja.timkerja_ketuatim', '=', 'sadarin_user.user_id')

            // Kepala Bidang
            ->leftJoin('sadarin_user as kepala_bidang', function ($join) {
                $join
                    ->on('kepala_bidang.user_bidang', '=', 'sadarin_bidang.bidang_id')
                    ->whereIn('kepala_bidang.user_jabatan', [19, 29, 54])
                    ->where('kepala_bidang.user_status', 1);
            })
            ->where('sadarin_timkerja.timkerja_id', $id)

            ->select('sadarin_timkerja.timkerja_id', 'sadarin_timkerja.timkerja_uraian', 'sadarin_timkerja.timkerja_nama', 'sadarin_bidang.bidang_nama', 'kepala_bidang.user_nama as kepala_bidang', 'sadarin_user.user_nama as ketua_tim', 'sadarin_user.user_foto as foto_ketua')
            ->first();
        $anggota = DB::table('sadarin_timkerja_detail')->leftJoin('sadarin_user', 'sadarin_timkerja_detail.timkerja_detail_anggota', '=', 'sadarin_user.user_id')->leftJoin('sadarin_timkerja', 'sadarin_timkerja_detail.timkerja_detail_timkerja', '=', 'sadarin_timkerja.timkerja_id')->leftJoin('sadarin_bidang', 'sadarin_timkerja.timkerja_bidang', '=', 'sadarin_bidang.bidang_id')->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->leftJoin('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')->select('sadarin_user.user_id', 'sadarin_user.user_lokasikerja', 'sadarin_timkerja_detail.*', 'sadarin_user.user_nama', 'sadarin_user.user_foto', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_golongan.golongan_pangkat', 'sadarin_eselon.eselon_nama', 'sadarin_eselon.eselon_nama')->where('timkerja_id', $id)->get();

        $timkerja = ModelTimKerja::where('timkerja_id', $id)->first();

        $anggotaGrouped = $anggota->groupBy('user_lokasikerja');

        return view('homepage_timkerja', compact('timkerja', 'timkerja_data', 'anggota', 'anggotaGrouped'));
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
    public function getUsersAjax(Request $request, $id)
    {
        // 🔥 mapping tim
        $anggotaData = DB::table('sadarin_timkerja_detail')->join('sadarin_timkerja', 'sadarin_timkerja_detail.timkerja_detail_timkerja', '=', 'sadarin_timkerja.timkerja_id')->select('timkerja_detail_anggota', 'timkerja_detail_timkerja', 'sadarin_timkerja.timkerja_nama')->get();
        $ketuaData = DB::table('sadarin_timkerja')->select('timkerja_id', 'timkerja_ketuatim', 'timkerja_nama')->get()->groupBy('timkerja_ketuatim'); // user_id jadi key

        $anggotaMapping = $anggotaData->groupBy('timkerja_detail_anggota');

        // 🔥 QUERY USERS (INI YANG KAMU KURANGIN)
        $query = DB::table('sadarin_user')->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->select('sadarin_user.user_id', 'sadarin_user.user_nama', 'sadarin_user.user_foto', 'sadarin_bidang.bidang_nama', 'sadarin_jabatan.jabatan_nama')->where('sadarin_user.user_status', 1);

        // 🔍 SEARCH
        if ($request->search['value']) {
            $search = $request->search['value'];
            $query->where('sadarin_user.user_nama', 'like', "%$search%");
        }

        $total = $query->count();

        // 🔥 PAGINATION
        $users = $query->offset($request->start)->limit($request->length)->get();

        // 🔥 MAP DATA
        $data = $users->map(function ($u) use ($anggotaMapping, $ketuaData, $id) {
            if (isset($ketuaData[$u->user_id])) {
                $timList = $ketuaData[$u->user_id];
                $namaTim = $timList->pluck('timkerja_nama')->implode(', ');

                $isKetuaDiTimIni = $timList->contains('timkerja_id', $id);

                $u->status = 'ketua';
                $u->tim_nama = 'Ketua Tim: ' . $namaTim;
            } elseif (isset($anggotaMapping[$u->user_id])) {
                $timList = $anggotaMapping[$u->user_id];
                $namaTim = $timList->pluck('timkerja_nama')->implode(', ');

                $isTimIni = $timList->contains('timkerja_detail_timkerja', $id);

                if ($isTimIni) {
                    $u->status = 'tim_ini';
                } else {
                    $u->status = 'tim_lain';
                }

                $u->tim_nama = $namaTim;
            } else {
                $u->status = 'belum ';
                $u->tim_nama = null;
            }

            return $u;
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }
    public function hapusAnggota($id, $userId)
    {
        DB::table('sadarin_timkerja_detail')->where('timkerja_detail_timkerja', $id)->where('timkerja_detail_anggota', $userId)->delete();

        return response()->json([
            'success' => true,
        ]);
    }
    public function updateUraian(Request $request, $id)
    {
        $request->validate([
            'uraian' => 'required',
            'uraian.*' => 'required',
        ]);

        $timkerja = ModelTimKerja::findOrFail($id);

        // Simpan dengan pembatas unik |||
        $timkerja->timkerja_uraian = implode('|||', array_map('trim', $request->uraian));

        $timkerja->save();

        return redirect()->back()->with('success', 'Uraian berhasil diperbarui!');
    }
    public function tambahAnggota(Request $request, $timkerja_id)
    {
        $request->validate([
            'pegawai_id' => 'required',
        ]);

        foreach ($request->pegawai_id as $pegawaiId) {
            // Cek dulu agar tidak duplikat

            ModelTimKerjaDetail::create([
                'timkerja_detail_timkerja' => $timkerja_id,
                'timkerja_detail_anggota' => $pegawaiId,
            ]);
        }

        return response()->json(['success' => true]);
    }
    public function cetakStrukturPegawaiPdf()
    {
        $path = public_path('file/Struktur-Organisasi-Dinas-Kebudayaan-Provinsi-Bali.pdf');

        return response()->file($path);
    }
}