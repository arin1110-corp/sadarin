<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelNavigasiSekretariat extends Model
{
    use HasFactory;
    protected $table = 'sadarin_navigasi_sekretariat';
    protected $primaryKey = 'navigasisekre_id';
    public $timestamps = false;
    protected $fillable = [
        'navigasisekre_id',
        'navigasisekre_nama',
        'navigasisekre_deskripsi',
        'navigasisekre_urutan',
        'navigasisekre_subbag',
        'navigasisekre_status'
    ];
    public function subbag()
    {
        return $this->belongsTo(ModelSubBag::class, 'navigasisekre_subbag', 'subbag_id');
    }
    public function bidang()
    {
        return $this->belongsTo(ModelBidang::class, 'subbbag_bidang', 'bidang_id');
    }
    public function subnavigasisekretariat()
    {
        return $this->hasMany(ModelSubNavigasiSekretariat::class, 'subnavigasisekre_navigasisekre', 'navigasisekre_id');
    }
}
