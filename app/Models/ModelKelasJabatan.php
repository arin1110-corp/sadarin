<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelKelasJabatan extends Model
{
    use HasFactory;
    protected $table = 'sadarin_kelasjabatan';
    protected $primaryKey = 'kelasjabatan_id';
    public $timestamps = false;
    protected $fillable = [
        'kelasjabatan_id',
        'kelasjabatan_nama',
        'kelasjabatan_status',
    ];
}
