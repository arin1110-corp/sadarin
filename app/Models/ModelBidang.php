<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelBidang extends Model
{
    use HasFactory;
    //
    protected $table = 'sadarin_bidang';
    protected $primaryKey = 'bidang_id';
    public $timestamps = false;
    protected $fillable = [
        'bidang_id',
        'bidang_nama',
        'bidang_link',
        'bidang_instansi',
        'bidang_status',
    ];
}
