<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTimkerjaDetail extends Model
{
    use HasFactory;

    protected $table = 'sadarin_timkerja_detail';
    protected $primaryKey = 'timkerja_detail_id';
    protected $fillable = ['timkerja_detail_id', 'timkerja_detail_timkerja', 'timkerja_detail_anggota'];
    public $timestamps = false;
}