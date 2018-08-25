<?php
namespace App\Models\sistema;

use Eloquent as Model;

class rol extends Model
{
    public $table = "roles";
    public $timestamps = false;
    public $fillable = [
        "nombre"
    ];
    protected $casts = [];
    public static $rules = [];

    public function permisos()
    {
        return $this->belongsToMany('App\Models\sistema\permiso', 'permisosxrol', 'rol_id', 'permiso_id');
    }    
}
