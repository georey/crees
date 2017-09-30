<?php
namespace App\Models\principal;

use Eloquent as Model;

class negocio extends Model
{
    public $table = "negocios";
    public $timestamps = false;
    public $fillable = [
        "nombre", "telefono", "direccion", "edad", "empleados", "dias_trabajo", "horario", "tipo_negocio_id", "cliente_id", 'municipio_id'
    ];
    protected $casts = [];
    public static $rules = [];

    public function municipio()
    {
        return $this->belongsTo('App\Models\catalogos\municipio', 'municipio_id');
    }

    public function tipo_negocio()
    {
    	return $this->belongsTo('App\Models\catalogos\tipo_negocio', 'tipo_negocio_id');
    }
}