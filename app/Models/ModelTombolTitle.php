<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTombolTitle extends Model
{
    use HasFactory;
    protected $table = 'sadarin_tomboltitle';
    protected $primaryKey = 'title_id';
    protected $fillable = [
        'title_nama',
    ];
}