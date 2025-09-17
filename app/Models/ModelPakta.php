<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelPakta extends Model
{
    use HasFactory;
    protected $table = 'sadarin_paktaintegritas';
    protected $primaryKey = 'pakta_id';
    public $timestamps = false;
    protected $fillable = [
        'pakta_user',
        'pakta_isi',
        'pakta_status',
    ];
}
