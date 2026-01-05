<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class mh_departamento extends Model
{
    public $table = "mh_departamentos";
    public $timestamps = false;
    public $fillable = [        
        "nombre","codigo"        
    ];
    protected $casts = [];
    public static $rules = [];

    public function municipios()
    {
        return $this->hasMany(mh_municipio::class, 'departamento_codigo', 'codigo');
    }
}