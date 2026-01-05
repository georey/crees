<?php
namespace App\Models\hacienda;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;

class factura extends Model
{
    public $table = "mh_factura";
    protected $dates = ['created_at', 'updated_at'];
    public $fillable = [
        "cliente_id", "estado", "numero_control", "codigo_generacion", "json","sello_recepcion","respuesta_mh","respuesta_anulacion"
    ];
    protected $casts = [];
    public static $rules = [];

    public function cliente()
    {
        return $this->belongsTo('App\Models\principal\cliente', 'cliente_id');
    }

    public static function secuencia(){
        $nextId = DB::select("SHOW TABLE STATUS LIKE 'mh_factura'");
        $siguienteId = $nextId[0]->Auto_increment;
        return $siguienteId;
    }

    
    public static function getFacturas()
    {
        $prestamo = factura::select('mh_factura.*')
                        ->addSelect(DB::raw("CONCAT(clientes.nombre, ' ', clientes.apellido) as nombre_completo"))
                        ->addSelect(DB::raw("
                            CASE mh_factura.estado
                                WHEN " . estadoFactura::CREADA . " THEN 'Creada'
                                WHEN " . estadoFactura::ENVIADA . " THEN 'Enviada'
                                WHEN " . estadoFactura::RECHAZADA . " THEN 'Rechazada'
                                WHEN " . estadoFactura::CERTIFICADA . " THEN 'Certificada'
                                WHEN " . estadoFactura::PENDIENTE . " THEN 'Pendiente'
                                WHEN " . estadoFactura::CLIENTE . " THEN 'Cliente'
                                ELSE 'Desconocido'
                            END as estado_nombre
                        "))
                        ->addSelect(DB::raw("DATE_FORMAT(mh_factura.created_at, '%d/%m/%Y') as fecha_factura"))
                        ->join('clientes', 'mh_factura.cliente_id', '=', 'clientes.id')
                        
                        //->orderBy('clientes.apellido')
                        ;
                        //->get();
        return $prestamo;
    }
}