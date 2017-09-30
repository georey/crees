<?php

namespace App\Models\catalogos;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Eloquent as Model;

class asesor extends Model
{
    // use SoftDeletes;

    public $table = "asesores";

    protected $primaryKey = 'id';

    public $fillable = [
        "dui", "nit", "codigo", "nombre", "apellido", "direccion", "telefono", "zona_id", "profesion_id", "estado_id", "sexo", "estado_civil_id", "fecha_nacimiento", "conyuge", "observaciones"
    ];

    protected $casts = [

    ];

    public static $rules = [

    ];

    public static function ListaAsesores() {
        $asesores = asesor::select('asesores.*')
                        ->addSelect(DB::raw("CONCAT(asesores.nombre, ' ', asesores.apellido) as nombre_completo"));
        return $asesores;
    }
}
