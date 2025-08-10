<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSubBag extends Model
{
    use HasFactory;
    protected $table = 'sadarin_subbag';
    protected $primaryKey = 'subbag_id';
    public $timestamps = false;
    protected $fillable = [
        'subbag_id',
        'subbag_nama',
        'subbag_status',
        'subbag_link',
        'subbag_bidang'
    ];

    public function bidang()
    {
        return $this->belongsTo(ModelBidang::class, 'subbag_bidang', 'bidang_id');
    }
}
