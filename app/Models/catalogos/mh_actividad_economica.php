<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class mh_actividad_economica extends Model
{
    public $table = "mh_actividad_economica";
    public $timestamps = false;
    public $fillable = [
        "codigo", "descripcion"
    ];
    protected $casts = [];
    public static $rules = [];
}
