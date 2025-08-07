<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelJabatan extends Model
{
    use HasFactory;
    protected $table = 'sadarin_jabatan';
    protected $primaryKey = 'jabatan_id';
    public $timestamps = false;
    protected $fillable = [
        'jabatan_id',
        'jabatan_nama',
        'jabatan_status',
    ];
}
