<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class linea extends Model
{
    public $table = "lineas";
    public $timestamps = false;
    public $fillable = [
        "nombre", "tasa_anual", "indice_conversion", "tasa_mora", "multa", "periodo"
    ];
    protected $casts = [];
    public static $rules = [];

    public function prueba(){
    	return "algo";
    }
}