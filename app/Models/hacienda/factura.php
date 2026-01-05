<?php
namespace App\Models\hacienda;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;

class factura extends Model
{
    public $table = "mh_factura";
    protected $dates = ['created_at', 'updated_at'];
    public $fillable = [
        "cliente_id", "estado", "numero_control", "codigo_generacion", "json","sello_recepcion","respuesta_mh","respuesta_anulacion","tipo_dte"
    ];
    protected $casts = [];
    public static $rules = [];

    public function cliente()
    {
        return $this->belongsTo('App\Models\principal\cliente', 'cliente_id');
    }


    /**
     * Obtiene la secuencia correlativa para el tipo de DTE y año actual.
     * @param string $tipoDte ("01", "03", "14", etc)
     * @return int
     */
    public static function secuencia($tipoDte)
    {
        $anio = date('Y');
        $count = DB::table('mh_factura')
            ->where('tipo_dte', $tipoDte)
            ->whereYear('created_at', '=', $anio)
            ->count();
        return $count + 1;
    }

    
    public static function getFacturas()
    {
        $prestamo = factura::select('mh_factura.*')
                        ->addSelect(DB::raw("CONCAT(COALESCE(clientes.nombre, ''), ' ', COALESCE(clientes.apellido, '')) as nombre_completo"))
                        ->addSelect(DB::raw("
                            CASE mh_factura.estado
                                WHEN " . estadoFactura::CREADA . " THEN 'Creada'
                                WHEN " . estadoFactura::ENVIADA . " THEN 'Enviada'
                                WHEN " . estadoFactura::RECHAZADA . " THEN 'Rechazada'
                                WHEN " . estadoFactura::CERTIFICADA . " THEN 'Certificada'
                                WHEN " . estadoFactura::PENDIENTE . " THEN 'Pendiente'
                                WHEN " . estadoFactura::CLIENTE . " THEN 'Cliente'
                                WHEN " . estadoFactura::ANULADA . " THEN 'Anulada'
                                ELSE 'Desconocido'
                            END as estado_nombre
                        "))
                        ->addSelect(DB::raw("DATE_FORMAT(mh_factura.created_at, '%d/%m/%Y') as fecha_factura"))
                        ->leftJoin('clientes', 'mh_factura.cliente_id', '=', 'clientes.id')
                        
                        //->orderBy('clientes.apellido')
                        ;
                        //->get();
        return $prestamo;
    }
}