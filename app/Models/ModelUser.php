<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class ModelUser extends Model
{
    use HasFactory;
    protected $table = 'sadarin_user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'user_nama',
        'user_golongan',
        'user_email',
        'user_notelp',
        'user_alamat',
        'user_jk',
        'user_foto',
        'user_bidang',
        'user_jabatan',
        'user_kelasjabatan',
        'user_status',
    ];
    public function bidang()
    {
        return $this->belongsTo(ModelBidang::class, 'user_bidang', 'bidang_id');
    }
    public function golongan()
    {
        return $this->belongsTo(ModelGolongan::class, 'user_golongan', 'golongan_id');
    }
    public function jabatan()
    {
        return $this->belongsTo(ModelJabatan::class, 'user_jabatan', 'jabatan_id');
    }
    public function eselon()
    {
        return $this->belongsTo(ModelEselon::class, 'user_eselon', 'eselon_id');
    }
    public function jenisKerja()
    {
        return $this->belongsTo(ModelJenisKerja::class, 'user_jeniskerja', 'jeniskerja_id');
    }


    // Accessor untuk tanggal pensiun
    public function getTanggalPensiunAttribute()
    {
        $tglLahir = Carbon::parse($this->user_tgllahir);
        $usia = 58; // default

        if ($this->jabatan) {
            $namaJabatan = strtolower($this->jabatan->jabatan_nama);
            $kategori    = strtolower($this->jabatan->jabatan_kategori);

            // --- Jabatan Struktural ---
            if ($kategori === 'struktural') {
                if (str_contains($namaJabatan, 'pimpinan tinggi madya')) {
                    $usia = 60; // usulan 63
                } elseif (str_contains($namaJabatan, 'pimpinan tinggi pratama')) {
                    $usia = 60; // usulan 62
                } elseif (
                    str_contains($namaJabatan, 'administrator') ||
                    str_contains($namaJabatan, 'pengawas') ||
                    str_contains($namaJabatan, 'eselon iii') ||
                    str_contains($namaJabatan, 'eselon iv')
                ) {
                    $usia = 58; // usulan 60
                } elseif (str_contains($namaJabatan, 'kepala dinas')) {
                    $usia = 60; // sesuai aturan Kepala Dinas
                }
            }

            // --- Jabatan Fungsional ---
            if ($kategori === 'fungsional') {
                if (str_contains($namaJabatan, 'ahli utama')) {
                    $usia = 65;
                } elseif (str_contains($namaJabatan, 'guru besar') || str_contains($namaJabatan, 'profesor')) {
                    $usia = 70;
                } elseif (str_contains($namaJabatan, 'ahli madya')) {
                    $usia = 60; // sesuai aturan
                } else {
                    $usia = 65; // default fungsional lain
                }
            }
        }

        return $tglLahir->copy()->addYears($usia);
    }
}
