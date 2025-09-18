<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelPengumpulanBerkas extends Model
{
    use HasFactory;
    protected $table = 'sadarin_pengumpulanberkas';
    protected $primaryKey = 'kumpulan_id';
    protected $fillable = [
        'kumpulan_user',
        'kumpulan_file',
        'kumpulan_jenis',
        'kumpulan_status',
    ];
    public $timestamps = false;
}
