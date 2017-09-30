<?php

namespace App\Models\principal;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Eloquent as Model;

class cliente extends Model
{
    // use SoftDeletes;

    public $table = "clientes";

    protected $dates = ['created_at', 'updated_at', 'fecha_nacimiento'];

    protected $primaryKey = 'id';

    public $fillable = [
        "dui", "nit", "codigo", "nombre", "apellido", "direccion", "telefono", "zona_id", "profesion_id", "estado_id", "sexo", "estado_civil_id", "fecha_nacimiento", "conyuge", "observaciones", "conyuge_telefono"
    ];

    protected $casts = [

    ];

    public static $rules = [

    ];

    public function nombreCompleto()
    {
        return $this->nombre . " " . $this->apellido;
    }

    public function estado_civil()
    {
        return $this->belongsTo('App\Models\catalogos\estado_civil', 'estado_civil_id');
    }

    public function profesion()
    {
        return $this->belongsTo('App\Models\catalogos\profesion', 'profesion_id');
    }

    public function negocios()
    {
        return $this->hasMany('App\Models\principal\negocio');
    }

    public static function ListaClientes() {
        $clientes = cliente::select('clientes.*', 'zonas.nombre as zona', 'profesiones.nombre as profesion', 'estados.nombre as estado')
                        ->addSelect(DB::raw("CONCAT(clientes.nombre, ' ', clientes.apellido) as nombre_completo"))
                        ->join('profesiones', 'profesiones.id', '=', 'clientes.profesion_id')
                        ->join('zonas', 'zonas.id', '=', 'clientes.zona_id')
                        ->join('estados', 'estados.id', '=', 'clientes.estado_id')
                        ->join('estados_civiles', 'estados_civiles.id', '=', 'clientes.estado_civil_id');
        return $clientes;
    }
}
