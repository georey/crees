<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class mh_municipio extends Model
{
    public $table = "mh_municipios";
    public $timestamps = false;
    public $fillable = [
        "nombre","codigo","departamento_codigo"
    ];
    protected $casts = [];
    public static $rules = [];

        public function departamento()
    {
        return mh_departamento::where('codigo', $this->departamento_codigo)->first();
    }
}