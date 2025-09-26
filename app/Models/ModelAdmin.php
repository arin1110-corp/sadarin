<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ModelAdmin extends Model
{
    use HasFactory;
    protected $table = 'sadarin_admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;
    protected $fillable = [
        'admin_nip',
        'admin_role',
        'admin_password',
        'admin_status'
    ];
}