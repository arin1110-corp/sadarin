<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
