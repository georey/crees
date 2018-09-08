<?php
namespace App\Models\principal;

use Eloquent as Model;
use DB;

class pago extends Model
{
    public $table = "pagos";
    protected $dates = ['created_at', 'updated_at', 'fecha'];
    public $fillable = [
        "capital", "interes", "mora", "multa", "prestamo_id", "saldo", "fecha", "cobrador_id", "pago_sucursal", "sucursal_id", "capital_pendiente", "interes_pendiente", "interes_mora_pendiente", "multa_pendiente"
    ];
    protected $casts = [];
    public static $rules = [];

    public function prestamo()
    {
        return $this->belongsTo('App\Models\principal\prestamo', 'prestamo_id');
    }

    public function getCuotaCompleta()
    {
    	return round($this->capital + $this->interes + $this->mora + $this->multa, 2);
    }

    public static function getReporteInteres($fecha_ini, $fecha_fin, $asesor_id = 0)
    {
        $rpt = pago::select(DB::raw("
            prestamos.codigo AS Codigo,
            clientes.nombre AS Nombre,
            clientes.apellido AS Apellido,
            SUM(pagos.capital) AS Capital,
            SUM(pagos.interes) AS Interes,
            SUM(pagos.mora) AS Mora, SUM(pagos.multa) AS Multa,
            (SELECT
                SUM(tg.monto)
            FROM gastos g
            INNER JOIN tipo_gastos tg ON tg.id = g.tipo_gasto_id
            INNER JOIN prestamos p ON p.id = g.prestamo_id
            WHERE
                g.prestamo_id = prestamos.id AND
                p.fecha BETWEEN '{$fecha_ini}' AND '{$fecha_fin}'
            ) AS Tramites"))
                    ->join('prestamos', 'prestamos.id', '=', 'pagos.prestamo_id')
                    ->join('clientes', 'clientes.id', '=', 'prestamos.cliente_id')
                    ->where('pagos.fecha','>=',$fecha_ini)
                    ->where('pagos.fecha','<=',$fecha_fin)
                    ->groupBy('prestamos.codigo')
                    ->groupBy('clientes.nombre')
                    ->groupBy('clientes.apellido')
                    ->orderBy('prestamos.codigo');
        $rpt = $asesor_id > 0 ? $rpt->where('prestamos.asesor_id',$asesor_id) : $rpt;                    
        return $rpt->get();
    }

    public static function getReporteInteresSumarizado($fecha_ini, $fecha_fin, $asesor_id = 0)
    {
        $rpt = pago::select(DB::raw("
            DATE(pagos.fecha) AS fecha,
            SUM(pagos.capital) AS Capital,
            SUM(pagos.interes) AS Interes,
            SUM(pagos.mora) AS Mora, SUM(pagos.multa) AS Multa,
            (SELECT
                SUM(tg.monto)
            FROM gastos g
            INNER JOIN tipo_gastos tg ON tg.id = g.tipo_gasto_id
            INNER JOIN prestamos p ON p.id = g.prestamo_id
            WHERE
                g.prestamo_id = prestamos.id AND
                p.fecha BETWEEN '{$fecha_ini}' AND '{$fecha_fin}'
            ) AS Tramites"))
                    ->join('prestamos', 'prestamos.id', '=', 'pagos.prestamo_id')
                    ->join('clientes', 'clientes.id', '=', 'prestamos.cliente_id')
                    ->where('pagos.fecha','>=',$fecha_ini)
                    ->where('pagos.fecha','<=',$fecha_fin)
                    ->groupBy(DB::raw('DATE(pagos.fecha)'));
        $rpt = $asesor_id > 0 ? $rpt->where('prestamos.asesor_id',$asesor_id) : $rpt;                    
        return $rpt->get();
    }

    public static function getIngresosByDate($params)
    {
        $fecha_ini = array_key_exists("fecha_ini", $params) ? $params["fecha_ini"] : date("Y-m-d");
        $fecha_fin = array_key_exists("fecha_fin", $params) ? $params["fecha_fin"] : date("Y-m-d");
        $rpt =pago::select('pagos.*')
                    ->join('prestamos', 'prestamos.id', '=', 'pagos.prestamo_id')
                    ->join('clientes', 'clientes.id', '=', 'prestamos.cliente_id')
                    ->whereRaw("date(pagos.fecha) >= '{$fecha_ini}'")
                    ->whereRaw("date(pagos.fecha) <= '{$fecha_fin}'")
                    ->whereRaw("(pagos.saldo is null OR pagos.saldo > 0)");
        return $rpt->get();
    }
}