<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelEselon extends Model
{
    use HasFactory;
    protected $table = 'sadarin_eselon';
    protected $primaryKey = 'eselon_id';
    public $timestamps = false;
    protected $fillable = [
        'eselon_id',
        'eselon_nama',
        'eselon_status',
    ];
}
