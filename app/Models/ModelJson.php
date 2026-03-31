<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelJson extends Model
{
    use HasFactory;
    protected $table = 'sadarin_json';
    protected $primaryKey = 'json_id';
    protected $fillable = [
        'json_nama',
        'json_file',
    ];
}