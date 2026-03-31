<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTombolMapping extends Model
{
    use HasFactory;
    protected $table = 'sadarin_mappingtombol';
    protected $primaryKey = 'mapping_id';
    protected $fillable = [
        'mapping_tombol',
        'mapping_jeniskerja',
        'mapping_folderid',
        'mapping_folder',
    ];
}