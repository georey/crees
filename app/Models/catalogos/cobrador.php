<?php

namespace App\Models\catalogos;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Eloquent as Model;

class cobrador extends Model
{
    // use SoftDeletes;

    public $table = "cobradores";

    protected $primaryKey = 'id';

    public $fillable = [
        "nombre", "apellido", "telefono"
    ];

    protected $casts = [

    ];

    public static $rules = [

    ];

    public static function ListaCobradores() {
        $cobradores = cobrador::select('cobradores.*')
                        ->addSelect(DB::raw("CONCAT(cobradores.nombre, ' ', cobradores.apellido) as nombre_completo"));
        return $cobradores;
    }
}
