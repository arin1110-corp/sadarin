<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelPendidikan extends Model
{
    use HasFactory;
    protected $table = 'sadarin_pendidikan';
    protected $primaryKey = 'pendidikan_id';
    public $timestamps = false;
    protected $fillable = [
        'pendidikan_jenjang',
        'pendidikan_jurusan',
        'pendidikan_status',
    ];
}
