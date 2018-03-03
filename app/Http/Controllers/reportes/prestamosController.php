<?php namespace App\Http\Controllers\reportes;

use App\Http\Controllers\Controller;
use App\Models\catalogos\asesor;
use App\Models\catalogos\cobrador;
use App\Models\catalogos\estado_prestamo;
use App\Models\principal\prestamo;
use App\Models\principal\pago;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Excel;

class prestamosController extends Controller {

	function __construct()
    {
        // $this->middleware('menu');
    }

    public function getIndex(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->filtrar($Request);
        }
    	$data['estados'] = estado_prestamo::all();
    	$data['cobradores'] = cobrador::all();
    	$data['asesores'] = asesor::all();
        $fecha = new Carbon();
        $data['reporte'] = [
                "fecha_ini" => $fecha->now()->format('d-m-Y'), 
                "fecha_fin" => $fecha->now()->format('d-m-Y'),
                "estado_id" => 0,
                "reporte_id" => 0,
                "asesor_id" => 0
                ];
        $data['tabla'] = 'reportes.prestamos_tabla';
        $data['prestamos'] = prestamo::getReportePrestamos();
        return view('reportes.prestamos')->with($data);
    }

    public function postIndex(Request $request)
    {
        $info['estados'] = estado_prestamo::all();
        $info['cobradores'] = cobrador::all();
        $info['asesores'] = asesor::all();
        $info['fecha'] = new Carbon();
        $tipo = $request->tipo_reporte;
        $filtro = $request->btn_submit;
        $fecha_ini = Carbon::createFromFormat('d-m-Y H:i:s', $request->fecha_ini . " 00:00:00");
        $fecha_fin = Carbon::createFromFormat('d-m-Y H:i:s', $request->fecha_fin . " 23:59:59");
        $data['prestamos'] = null;
        $info['reporte'] = [
                "fecha_ini" => $request->fecha_ini, 
                "fecha_fin" => $request->fecha_fin,
                "estado_id" => 0,
                "reporte_id" =>$tipo,
                "asesor_id" => $request->asesor_id
                ];
        switch ($tipo) {
            case 1:
                $data = pago::getReporteInteres($fecha_ini, $fecha_fin, $request->asesor_id);
                $info['prestamos'] = $data;
                $info['tabla'] = 'reportes.prestamos_tabla_interes';
                if ($filtro == 'filtrar') {
                    return view('reportes.prestamos')->with($info);
                } else {
                    Excel::create('Reporte de interes cobrados', function($excel) use($data){
                        $excel->setTitle('Reporte de interes cobrados');
                        $excel->sheet('Intereses', function($sheet) use($data){
                            $sheet->setOrientation('landscape');
                            $sheet->fromArray($data->toArray());
                        });
                    })->export('xls');
                }
                break;
            case 2:
                $data = prestamo::getReporteColocacion($fecha_ini, $fecha_fin, $request->asesor_id);
                $info['prestamos'] = $data;
                $info['tabla'] = 'reportes.prestamos_tabla_colocacion_prestamo';
                if ($filtro == 'filtrar') {
                    return view('reportes.prestamos')->with($info);
                } else {
                    Excel::create('Reporte de prestamos colocados', function($excel) use($data){
                        $excel->setTitle('Reporte de prestamos colocados');
                        $excel->sheet('Prestamos', function($sheet) use($data){
                            $sheet->setOrientation('landscape');
                            $sheet->fromArray($data->toArray());
                        });
                    })->export('xls');
                }
                break;
            default:
                $data['prestamos'] = null;
                break;
        }
    }
}