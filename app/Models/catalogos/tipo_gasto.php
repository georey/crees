<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class tipo_gasto extends Model
{
    public $table = "tipo_gastos";
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $fillable = [
        "tipo", "monto", "linea_id", "monto_min", "monto_max"
    ];
    protected $casts = [];
    public static $rules = [];

    public static function ListaGastos() {
        $tipo_gastos = tipo_gasto::select('tipo_gastos.*', 'lineas.nombre as linea')
        				->join('lineas', 'lineas.id', '=', 'tipo_gastos.linea_id');
        return $tipo_gastos;
    }
}