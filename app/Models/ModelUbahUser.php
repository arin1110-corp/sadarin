<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelUbahUser extends Model
{
    use HasFactory;

    protected $table = 'sadarin_ubahuser';
    protected $primaryKey = 'ubahuser_id';
    public $timestamps = true;
    protected $fillable = [
        'ubahuser_iduser',
        'ubahuser_nip',
        'ubahuser_nama',
        'ubahuser_nik',
        'ubahuser_tgllahir',
        'ubahuser_jabatan',
        'ubahuser_npwp',
        'ubahuser_pendidikan',
        'ubahuser_norek',
        'ubahuser_tmt',
        'ubahuser_spmt',
        'ubahuser_bpjs',
        'ubahuser_gelardepan',
        'ubahuser_gelarbelakang',
        'ubahuser_kelasjabatan',
        'ubahuser_eselon',
        'ubahuser_golongan',
        'ubahuser_email',
        'ubahuser_notelp',
        'ubahuser_alamat',
        'ubahuser_jk',
        'ubahuser_foto',
        'ubahuser_bidang',
        'ubahuser_jmltanggungan',
        'ubahuser_status',
        'ubahuser_jeniskerja'
    ];
}
