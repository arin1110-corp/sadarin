<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSubNavigasiSekretariat extends Model
{
    use HasFactory;
    protected $table = 'sadarin_subnavigasisekre';
    protected $primaryKey = 'subnavigasisekre_id';
    public $timestamps = false;
    protected $fillable = [
        'subnavigasisekre_id',
        'subnavigasisekre_nama',
        'subnavigasisekre_navigasisekre',
        'subnavigasisekre_link',
        'subnavigasisekre_status'
    ];
    public function navigasisekretariat()
    {
        return $this->belongsTo(ModelNavigasiSekretariat::class, 'subnavigasisekre_navigasisekre', 'navigasisekre_id');
    }
}
