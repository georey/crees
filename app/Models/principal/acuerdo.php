<?php

namespace App\Models\principal;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Eloquent as Model;

class acuerdo extends Model
{
    // use SoftDeletes;

    public $timestamps = false;

    public $table = "acuerdos";

    protected $primaryKey = 'id';

    public $fillable = [
        "prestamo_id", "user", "fecha", "justificacion", "monto_anterior", "monto_posterior"
    ];

    protected $casts = [];

    public static $rules = [];

    public function prestamo()
    {
        return $this->belongsTo('App\Models\principal\prestamo', 'prestamo_id');
    }

    public static function getAcuerdos() {
        $acuerdo = acuerdo::select('acuerdos.*', 'prestamos.codigo as codigo_prestamo')
                        ->addSelect(DB::raw("CONCAT(clientes.nombre, ' ', clientes.apellido) as nombre_completo"))
                        ->join('prestamos', 'prestamos.id', '=', 'acuerdos.prestamo_id')
                        ->join('clientes', 'clientes.id', '=', 'prestamos.cliente_id')
                        ;
        return $acuerdo;
    }
    
}
