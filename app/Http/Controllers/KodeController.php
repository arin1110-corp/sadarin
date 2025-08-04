<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function umpanbalik()
    {
        return view('homepage_umpeg_umpanbalik');
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
