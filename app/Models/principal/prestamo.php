<?php
namespace App\Models\principal;

use Eloquent as Model;
use DB;
use Carbon\Carbon;

class prestamo extends Model
{
    public $table = "prestamos";
    protected $dates = ['created_at', 'updated_at', 'fecha'];
    public $fillable = [
        "monto", "linea_id", "cliente_id", "cuotas", "codigo", "estado_prestamo_id", "tasa_mora","multa", "observaciones", "garantia","descuento", "liquido","tasa","fecha","cuota","asesor_id", "cobrador_id"
    ];
    protected $casts = [];
    public static $rules = [];

    public function linea()
    {
    	return $this->belongsTo('App\Models\catalogos\linea', 'linea_id');
    }

    public function asesor()
    {
        return $this->belongsTo('App\Models\catalogos\asesor', 'asesor_id');
    }

    public function cobrador()
    {
        return $this->belongsTo('App\Models\catalogos\cobrador', 'cobrador_id');
    }

    public function estadoPrestamo()
    {
        return $this->belongsTo('App\Models\catalogos\estado_prestamo', 'estado_prestamo_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\principal\cliente', 'cliente_id');
    }

    public function pagos()
    {
        return $this->hasMany('App\Models\principal\pago');
    }

    public function fiadores()
    {
        return $this->belongsToMany('App\Models\principal\cliente', 'fiadores');
    }

    public function gastos()
    {
        return $this->belongsToMany('App\Models\catalogos\tipo_gasto', 'gastos');
    }

    public function montoCuotas()
    {
        // $tasa = $this->linea->tasa_anual / 100;
        // $conversion = $this->linea->indice_conversion;
        // $monto = $this->monto;
        // $cuotas = $this->cuotas;
        // $dividendo = ($tasa / $conversion) * $monto;
        // $divisor = 1 - pow((1 + ($tasa / $conversion)), (-1 * abs($cuotas)));
        // $monto_cuota = $dividendo / $divisor;
        return  round(($this->cuota * $this->getNumeroCuotas()) + $this->getInteresesPendientes(), 2);
    }

    public function getInteresesPendientes()
    {
        $pago = $this->pagos()->latest()->first();
        $interes = isset($pago->interes_pendiente) ? $pago->interes_pendiente : 0;
        //$mora = isset($pago->interes_mora_pendiente) ? $pago->interes_mora_pendiente : 0;
        //$multa = isset($pago->multa_pendiente) ? $pago->multa_pendiente : 0;
        return $interes; //+ $mora + $multa;
    }

    public function getCapitalPendienteAcumulado()
    {
        $pago = $this->pagos()->latest()->first();
        $capital_pendiente = isset($pago->capital_pendiente) ? $pago->capital_pendiente : 0;        
        return $capital_pendiente;
    }

    public function getCapitalPendiente()
    {
        $pago = $this->pagos()->latest()->first();
        if(!is_null($pago)) {
            return $pago->capital_pendiente<0?0:$pago->capital_pendiente;
        }
        return 0;
    }

    public function saldoAnterior()
    {
        $capitalPagos = $this->pagos->sum('capital');
        $interesPagos = $this->pagos->sum('interes');
        $saldo = round($this->monto - $capitalPagos, 2);
        return $saldo < 1 ? 0 : $saldo;
    }

    public function getMora()
    {
        $carbon = $this->getFechaActualSinHora();
        $proximaFecha = $this->getProximaFecha();
        if ($this->linea_id != 1) {
            $proximaFecha->addDays(2);
        }
        if ($carbon <= $proximaFecha) {
            return 0;
        } else {
            $tasa = ($this->tasa_mora /100) / 365;
            $cuota = $this->cuota;
            if ($this->linea_id != 1) {
                $proximaFecha->day = $proximaFecha->day - 1;
            }
            $dias = $proximaFecha->diffInDays($carbon);
            return ($cuota * $tasa * $dias);
        }
    }

    public function getMoraPendiente()
    {
        $pago = $this->pagos()->latest()->first();        
        $mora = isset($pago->interes_mora_pendiente) ? $pago->interes_mora_pendiente : 0;        
        return $mora;
    }

    public function getMultaPendiente()
    {
        $pago = $this->pagos()->latest()->first();        
        $multa = isset($pago->multa_pendiente) ? $pago->multa_pendiente : 0;        
        return $multa;
    }

    public function getClasificacion(){
        /*A1 Atrasos hasta de 7 días
        A2 Atrasos hasta 30 días
        B  Atrasos hasta 90 días
        C1 Atrasos hasta 120 días
        C2 Atrasos hasta 180 días
        D1 Atrasos hasta 270 días
        D2 Atrasos hasta 360 días
        E  Atrasos de más de 360 días*/
        $carbon = $this->getFechaActualSinHora();
        $dias = $carbon->diffInDays($this->getProximaFecha());
        $calificacion ="";
        switch (true) {
            case ($dias <= 7):
                $calificacion = "A1";
                break;            
            case ($dias > 7 && $dias <= 30):
                $calificacion = "A2";
                break;            
            case ($dias > 30 && $dias <= 90):
                $calificacion = "B";
                break;
            case ($dias > 90 && $dias <= 120):
                $calificacion = "C1";
                break;            
            case ($dias > 120 && $dias <= 180):
                $calificacion = "C2";
                break;            
            case ($dias > 180 && $dias <= 270):
                $calificacion = "D1";
                break;            
            case ($dias > 270 && $dias <= 360):
                $calificacion = "D2";
                break;            
            case ($dias > 360):
                $calificacion = "E";
                break;            
            default:
                $calificacion ="Indeterminada";
                break;
        }
        return $calificacion;
    }

    public function getMulta()
    {
        $carbon = $this->getFechaActualSinHora();
        $proximaFecha = $this->getProximaFecha();
        if ($this->linea_id != 1) {
            $proximaFecha->addDays(1);
        }
        $diffInDays = $proximaFecha->diffInDays($carbon);        
        if ($carbon <= $proximaFecha || $diffInDays == 0) {
            return 0;
        } else {
            if ($this->linea_id != 1) {
                return $this->multa * $this->getNumeroCuotas(true);
            }
            return ($this->multa * ($this->getNumeroCuotas() - 1));
        }
    }

    public function getInteres($carbon = null)
    {
        $tasa = ($this->tasa / 100) / 365;
        $montoActual = $this->monto - $this->pagos->sum('capital');
        $indice_conversion = $this->linea->indice_conversion;
        $dias = $this->getDias($carbon);
        return round(($montoActual * $tasa * $dias) + $this->getInteresesPendientes(), 2);
    }

    public function getDias($carbon = null)
    {
        if (!isset($carbon)) {
            $carbon = $this->getFechaActualSinHora();
        }
        $ultima_fecha = $this->getUltimaFecha();
        return $ultima_fecha->diffInDays($carbon);
    }

    public function getNumeroCuotas($dia_gracia = false)
    {
        $carbon = $this->getFechaActualSinHora();
        $ultima_fecha = $this->getUltimaFecha();        
        if ($carbon <= $ultima_fecha) {
            return 0;
        } else {            
            if($dia_gracia){
                $ultima_fecha->addDays(1);
            }
            $dias = $ultima_fecha->diffInDays($carbon);
            $periodo = round(365 / $this->linea->indice_conversion);
            return floor($dias / $periodo);
        }
    }

    public function getUltimaFecha()
    {
        if ($this->pagos->count('id') > 0) {
            $fecha = $this->pagos->max('fecha');
        } else {
            $fecha = $this->fecha;
        }
        $carbon = Carbon::parse($fecha);
        $carbon->hour = 0;
        $carbon->minute = 0;
        $carbon->second = 0;
        return $carbon;
    }

    public function getProximaFecha()
    {
        if ($this->pagos->count('id') > 0) {
            $fecha = $this->pagos->max('fecha');
        } else {
            $fecha = $this->fecha;
        }
        $carbon = Carbon::parse($fecha);
        $dias = round(365 / $this->linea->indice_conversion);
        $carbon->addDays($dias==1 ? 1 : $dias);
        $carbon->hour = 0;
        $carbon->minute = 0;
        $carbon->second = 0;
        return $carbon;
    }

    public function getFechaVencimiento()
    {
        // if ($this->pagos->count('id') > 0) {
        //     $fecha = $this->pagos->max('fecha');
        // } else {
        //     $fecha = $this->fecha;
        // }
        $fecha = $this->fecha;
        $carbon = Carbon::parse($fecha);
        $dias = round(365 / $this->linea->indice_conversion);
        $dias_vencimiento = $dias * $this->cuotas;
        $carbon->addDays($dias_vencimiento);
        $carbon->hour = 0;
        $carbon->minute = 0;
        $carbon->second = 0;
        return $carbon;
    }

    public function getFechaActualSinHora()
    {
        $carbon = new Carbon();
        $carbon->hour = 0;
        $carbon->minute = 0;
        $carbon->second = 0;
        return $carbon;
    }

    public function getEstadoInfored()
    {
        $estado = $this->estado_prestamo_id;
        if($estado == 1 && $this->getVencimiento() > $this->getFechaActualSinHora())
            $estado = 2; //Vencido
        if($estado == 1 && $this->getVencimiento() <= $this->getFechaActualSinHora())
            $estado = 1; //activo
        if($this->saldoAnterior() < 0.99) 
            $estado = 3; //cancelado
        return $estado;
    }

    public function getVencimiento()
    {
        $dias = round($this->cuotas * (365 / $this->linea->indice_conversion));
        return $this->fecha->addDays($dias);
    }

    public function getDescuento($prestamos = array())
    {
        $gastos = $this->gastos->toArray();
        $totalGasto = 0;
        foreach ($gastos as $gasto) {
            $totalGasto += $gasto['monto'];
        }

        foreach ($prestamos as $prestamo) {
            $pl_info = explode('-', $prestamo);
            $totalGasto += $pl_info[1];
        }

        $data['totalDescuento'] = $totalGasto;
        $data['totalLiquido'] = $this->monto - $totalGasto;
        return $data;
    }

    public function getTipoGarantia()
    {
        return $this->fiadores->count('prestamo_id') > 0 ? "FIDUCIARIA" : "PRENDARIA";
    }

    public static function getPrestamoCliente()
    {
        $prestamo = prestamo::select('prestamos.*')
                        ->addSelect(DB::raw("CONCAT(clientes.nombre, ' ', clientes.apellido) as nombre_completo"))
                        ->addSelect(DB::raw("estados_prestamo.estado as estado"))
                        ->join('clientes', 'prestamos.cliente_id', '=', 'clientes.id')
                        ->join('estados_prestamo', 'estados_prestamo.id', '=', 'prestamos.estado_prestamo_id')
                        ->where("estado_prestamo_id","!=", 4)
                        //->orderBy('clientes.apellido')
                        ->orderBy('prestamos.codigo');
                        //->get();
        return $prestamo;
    }

    public static function getPrestamoActivosCliente()
    {
        $prestamo = prestamo::select('prestamos.*')
                        ->addSelect(DB::raw("CONCAT(clientes.nombre, ' ', clientes.apellido) as nombre_completo"))
                        ->addSelect(DB::raw("estados_prestamo.estado as estado"))
                        ->join('clientes', 'prestamos.cliente_id', '=', 'clientes.id')
                        ->join('estados_prestamo', 'estados_prestamo.id', '=', 'prestamos.estado_prestamo_id')
                        ->where("estado_prestamo_id", 1)
                        ->orderBy('clientes.apellido')
                        ->orderBy('prestamos.codigo')
                        ->get();
        return $prestamo;
    }

    public static function getReportePrestamos($filtros = null)
    {
        $fecha_ini = isset($filtros['fecha_ini']) ? Carbon::createFromFormat('d-m-Y', $filtros['fecha_ini']) : Carbon::now()->startOfMonth()->format('d-m-Y');
        $fecha_fin = isset($filtros['fecha_fin']) ? Carbon::createFromFormat('d-m-Y', $filtros['fecha_fin']) : Carbon::now()->endOfMonth()->format('d-m-Y');
        $prestamo = prestamo::select('prestamos.*')
                        ->join('clientes', 'prestamos.cliente_id', '=', 'clientes.id')
                        ->whereBetween('prestamos.fecha', [$fecha_ini, $fecha_fin])
                        ;
        if (isset($filtros['estado_id']) && !empty($filtros['estado_id'])) {
            $prestamo->where('prestamos.estado_prestamo_id',$filtros['estado_id']);
        }
        return $prestamo->get();
    }

    public function prestamos_liquidados()
    {
        return $this->belongsToMany('App\Models\principal\prestamo', 'prestamos_liquidados', 'prestamo_id', 'prestamo_liquidado_id')->withPivot('monto');
    }

    public static function getReporteColocacion($fecha_ini, $fecha_fin, $asesor_id = 0)
    {
        $rpt = prestamo::select(DB::raw(
                                    'prestamos.codigo AS Codigo,
                                    clientes.nombre AS Nombre,
                                    clientes.apellido AS Apellido,
                                    prestamos.fecha As Fecha,
                                    lineas.nombre AS Linea,
                                    prestamos.monto AS Monto,
                                    prestamos.liquido As Liquido,
                                    SUM(prestamos_liquidados.monto) as total_liquidacion,
                                    SUM(tipo_gastos.monto) as total_gastos
                                    '))
                    ->join('clientes', 'clientes.id', '=', 'prestamos.cliente_id')
                    ->join('lineas', 'lineas.id', '=', 'prestamos.linea_id')
                    ->leftJoin('gastos', 'gastos.prestamo_id', '=', 'prestamos.id')
                    ->leftJoin('tipo_gastos', 'tipo_gastos.id', '=', 'gastos.tipo_gasto_id')
                    ->leftJoin('prestamos_liquidados', 'prestamos_liquidados.prestamo_id', '=', 'prestamos.id')
                    ->where('prestamos.fecha','>=',$fecha_ini)
                    ->where('prestamos.fecha','<=',$fecha_fin)
                    ->whereNotIn('estado_prestamo_id', [4])
                    ->groupBy('prestamos.codigo')
                    ->groupBy('clientes.nombre')
                    ->groupBy('clientes.apellido')
                    ->groupBy('prestamos.fecha')
                    ->groupBy('lineas.nombre')
                    ->groupBy('prestamos.monto')
                    ->groupBy('prestamos.liquido')
                    // ->groupBy('prestamos_liquidados.monto')
                    // ->groupBy('prestamos.codigo')
                    // ->groupBy('clientes.nombre')
                    // ->groupBy('clientes.apellido')
                    ->orderBy('prestamos.fecha')
                    ->orderBy('prestamos.codigo');
        $rpt = $asesor_id > 0 ? $rpt->where('prestamos.asesor_id',$asesor_id) : $rpt;                    
        return $rpt->distinct()->get();
    }

    public static function getReporteColocacionSumarizado($fecha_ini, $fecha_fin, $asesor_id = 0)
    {
        $rpt = prestamo::select(DB::raw(
                                    '
                                    prestamos.fecha As Fecha,
                                    lineas.nombre AS Linea,
                                    prestamos.monto AS Monto,
                                    prestamos.liquido As Liquido,
                                    SUM(prestamos_liquidados.monto) as total_liquidacion,
                                    SUM(tipo_gastos.monto) as total_gastos
                                    '))
                    ->join('clientes', 'clientes.id', '=', 'prestamos.cliente_id')
                    ->join('lineas', 'lineas.id', '=', 'prestamos.linea_id')
                    ->leftJoin('gastos', 'gastos.prestamo_id', '=', 'prestamos.id')
                    ->leftJoin('tipo_gastos', 'tipo_gastos.id', '=', 'gastos.tipo_gasto_id')
                    ->leftJoin('prestamos_liquidados', 'prestamos_liquidados.prestamo_id', '=', 'prestamos.id')
                    ->where('prestamos.fecha','>=',$fecha_ini)
                    ->where('prestamos.fecha','<=',$fecha_fin)
                    ->whereNotIn('estado_prestamo_id', [4])
                    ->groupBy('prestamos.fecha')
                    ->groupBy('lineas.nombre')
                    ->groupBy('prestamos.monto')
                    ->groupBy('prestamos.liquido')
                    // ->groupBy('prestamos_liquidados.monto')
                    // ->groupBy('prestamos.codigo')
                    // ->groupBy('clientes.nombre')
                    // ->groupBy('clientes.apellido')
                    ->orderBy('prestamos.fecha')
                    ->orderBy('lineas.nombre');
        $rpt = $asesor_id > 0 ? $rpt->where('prestamos.asesor_id',$asesor_id) : $rpt;                    
        return $rpt->distinct()->get();
    }

    public static function getColecta($fecha = null)
    {
        $rpt = prestamo::select(DB::raw(
                                    'prestamos.codigo AS codigo,
                                    clientes.nombre AS nombre,
                                    clientes.apellido AS apellido                                    
                                    '))
                    ->join('clientes', 'clientes.id', '=', 'prestamos.cliente_id')
                    ->where('prestamos.estado_prestamo_id', 1)
                    ->orderBy('prestamos.codigo')
                    ->get();
        return $rpt;
    }

    public static function getPrestamosMes($fecha_ini, $fecha_fin){
        $rpt = prestamo::select('prestamos.*')
                        ->addSelect(DB::raw("CONCAT(clientes.nombre, ' ', clientes.apellido) as nombre_completo"))
                        ->addSelect(DB::raw("(((365/lineas.indice_conversion) * prestamos.cuotas)/30) AS meses"))
                        ->addSelect(DB::raw("max(pagos.id) as ultimo_pago"))
                                    /*"
                                    clientes.nombre AS nombre,
                                    clientes.apellido AS apellido,
                                    prestamos.fecha As fecha,
                                    lineas.nombre AS linea,
                                    lineas.id_infored AS id_infored,
                                    prestamos.monto AS monto,
                                    prestamos.liquido As liquido,
                                    max(pagos.fecha) as fecha_ultimo_pago,
                                    max(pagos.id) as ultimo_pago,
                                    (((365/lineas.indice_conversion) * prestamos.cuotas)/30) AS meses,
                                    prestamos.monto - sum(pagos.capital) AS saldo_capital,
                                    prestamos.cuota,
                                    prestamos.cuotas,
                                    clientes.fecha_nacimiento,
                                    clientes.dui,
                                    clientes.nit,
                                    CASE WHEN clientes.sexo = 1 THEN 'Masculino' ELSE 'Femenino' END AS sexo,
                                    "))*/
                        ->join('clientes', 'clientes.id', '=', 'prestamos.cliente_id')
                        ->join('lineas', 'lineas.id', '=', 'prestamos.linea_id')
                        ->join('pagos', 'pagos.prestamo_id', '=', 'prestamos.id')
                        ->groupBy('prestamos.id')
                        ->whereBetween("pagos.fecha",[$fecha_ini, $fecha_fin])
                        ->orWhere("estado_prestamo_id", 1)
                        ->get();
        return $rpt;
    }
}