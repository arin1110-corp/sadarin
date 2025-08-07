<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelUser;
use Google\Service\Bigquery\Model;

class KodeController extends Controller
{
    private $kodeValid = ['A1@B2', 'X9#K7', 'R5$T3', 'Y7@U1', 'Z8%P4', 'K0#L9', 'N3&M6', 'G7*H8', 'W6!Q2', 'D4@Z5'];

    public function form()
    {
        return view('homepage_awal');
    }
    public function akses_kode()
    {
        return view('homepage_awal');
    }
    public function dataPegawaiPNS()
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
        $totalPegawai = ModelUser::where('user_status', 1)->count();
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

    public function cek(Request $request)
    {
        $request->validate([
            'kode_akses' => ['required', 'string', 'min:5'],
        ]);

        if (in_array($request->kode_akses, $this->kodeValid)) {
            session(['kode_akses_valid' => true]);
            return redirect()->route('cek.bidang');
        }

        return back()->withErrors(['kode_akses' => 'Kode akses salah.'])->withInput();
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
    public function datappep()
    {
        return view('homepage_data_ppep');
    }
    public function datakeuangan()
    {
        return view('homepage_data_keuangan');
    }
    public function dataumpeg()
    {
        return view('homepage_data_umpeg');
    }
    public function dataupload()
    {
        return view('homepage_upload_data');
    }
    public function datasekretariat()
    {
        return view('homepage_sekretariat');
    }
}
