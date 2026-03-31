<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTombolBerkas extends Model
{
    use HasFactory;
    protected $table = 'sadarin_tombolberkas';
    protected $primaryKey = 'tombol_id';
    protected $fillable = [
        'tombol_nama',
        'tombol_prefix',
        'tombol_json',
        'tombol_expired',
    ];
}