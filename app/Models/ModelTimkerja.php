<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTimkerja extends Model
{
    use HasFactory;

    protected $table = 'sadarin_timkerja';
    protected $primaryKey = 'timkerja_id';
    public $fillable = ['timkerja_bidang', 'timkerja_nama', 'timkerja_ketuatim'];
}