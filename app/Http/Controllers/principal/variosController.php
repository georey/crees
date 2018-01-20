<?php

namespace App\Http\Controllers\principal;

use App\Http\Requests;
use App\Models\principal\pago;
use App\Models\principal\prestamo;
use App\Models\principal\cliente;
use App\Models\catalogos\linea;
use App\Models\catalogos\cobrador;
use App\Models\catalogos\asesor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;
use Carbon\Carbon;
use Excel;
use PDF;

class variosController extends Controller
{	
	public function corteCaja()
    {
    	$data=[];
        return view('principal.caja.corte_caja')->with($data);
    }

    public function getColectasSaldos()
    {
    	$data['asesores'] = asesor::all();
        $data["reporte"] = ["asesor_id"=>0];
    	$data['prestamos']=prestamo::where("estado_prestamo_id",1)->get();
        return view('principal.cobros.colectas_saldos')->with($data);
    }

     public function postColectasSaldos(Request $request)
    {
        $data['asesores'] = asesor::all();
        $data["reporte"] = ['asesor_id' =>$request->asesor_id];
        $tipo = $request->tipo_reporte;
        $filtro = $request->btn_submit;
        $prestamos = prestamo::where("estado_prestamo_id",1);
        if($request->asesor_id > 0)
            $prestamos->where("asesor_id",$request->asesor_id);        
        $data['prestamos']=$prestamos->get();

        switch ($filtro) {
            case 'filtrar':
                return view('principal.cobros.colectas_saldos')->with($data);
                break;
            case 'xls':
                Excel::create('Reporte de colectas y saldos'.date("Ymd"), function ($excel) use($data){
                    $excel->sheet('Colectas', function ($sheet) use($data){
                        $sheet->setOrientation('landscape');
                        $sheet->loadView('reportes.colectas_tabla', $data);
                    });
                })->export('xls');
                break;
            case 'pdf':
                $pdf = PDF::loadView('reportes.colectas_tabla', $data);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->download('Reporte de colectas y saldos'.date("Ymd").'.pdf');
                break;
        }
    }
}