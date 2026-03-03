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
        return view('homepage_detailpegawai', compact('user', 'jabatans', 'eselons', 'bidangs', 'golongans', 'pendidikans', 'berkas', 'jeniskerjapeg'));
    }
    public function strukturOrganisasi()
    {
        $dataPegawai = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->join('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')->where('sadarin_user.user_status', 1)->select('sadarin_user.*', 'sadarin_golongan.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_eselon.*')->get();
        return view('homepagestrukturorganisasi', compact('dataPegawai'));
    }
    public function lihatjajaran(Request $request)
    {
        $kategori = $request->kategori ?? null;

        if ($kategori === 'Fungsional') {
            // Ambil semua pegawai fungsional
            $pegawaiBidang = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->where('jabatan_kategori', 'Fungsional')->select('sadarin_user.*', 'sadarin_jabatan.*', 'sadarin_bidang.*', 'sadarin_golongan.*')->get();

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
            $pegawaiBidang = ModelUser::join('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')->join('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')->join('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')->where('user_bidang', $bidangId)->select('sadarin_user.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.*', 'sadarin_golongan.*')->get();

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

        return view('homepage_data_subbag_sekretariat', compact('datasekretariat', 'subbagNama'));
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
            return back()->with('error', 'Password salah');
        }

        // ✅ Password benar
        session([
            'password_verified' => true,
        ]);

        return redirect()->route('detail.pegawai');
    }
}