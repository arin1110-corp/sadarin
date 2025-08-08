<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelJenisKerja extends Model
{
    use HasFactory;
    protected $table = 'sadarin_jeniskerja';
    protected $primaryKey = 'jeniskerja_id';
    public $timestamps = false;
    protected $fillable = [
        'jeniskerja_nama',
        'jeniskerja_singkatan',
        'jeniskerja_status',
    ];
}
