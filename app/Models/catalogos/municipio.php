<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class municipio extends Model
{
    public $table = "municipios";
    public $timestamps = false;
    public $fillable = [
        "nombre", "departamento_id"
    ];
    protected $casts = [];
    public static $rules = [];

    public function departamento()
    {
        return $this->belongsTo('App\Models\catalogos\departamento', 'departamento_id');
    }
}
