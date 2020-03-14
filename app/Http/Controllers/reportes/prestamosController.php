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
use PDF;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Style_NumberFormat;

class prestamosController extends Controller {

	function __construct()
    {
        $this->middleware('menu');
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

    private function getStyles(){
        $cabecera_principal = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'fill' => array(
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '66B2FF')
            ),
            'font' => array(
                'size'      =>  14,
                'bold'      =>  true
            )
        );

        $cabecera_titulos = array(
            'font' => array(
                'size'      =>  12,
                'bold'      =>  true
            )
        );

        $negrita = array(
            'font' => array(
                'bold'      =>  true
            )
        );

        return [
            'cabecera_principal'=>$cabecera_principal,
            'cabecera_titulos'=>$cabecera_titulos,
            'negrita'=>$negrita
        ];
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
        $fecha_ini_show =$request->fecha_ini;
        $fecha_fin_show =$request->fecha_fin;
        $mes = $fecha_ini->month;
        $data['prestamos'] = null;
        $info['reporte'] = [
                "fecha_ini" => $request->fecha_ini, 
                "fecha_fin" => $request->fecha_fin,
                "estado_id" => 0,
                "reporte_id" =>$tipo,
                "asesor_id" => $request->asesor_id
                ];
        $carbon = new Carbon();
        switch ($tipo) {
            case 1:
                $data = pago::getReporteInteres($fecha_ini, $fecha_fin, $request->asesor_id);
                $info['prestamos'] = $data;
                $info['tabla'] = 'reportes.prestamos_tabla_interes';
                if ($filtro == 'filtrar') {
                    return view('reportes.prestamos')->with($info);
                } else if ($filtro == 'xls') {
                    Excel::create('Reporte de interes cobrados', function($excel) use($data,$fecha_ini_show,$fecha_fin_show){
                        $excel->setTitle('Reporte de interes cobrados');
                        $excel->sheet('Intereses', function($sheet) use($data,$fecha_ini_show,$fecha_fin_show){
                            $styles = $this->getStyles();
                            $sheet->setOrientation('landscape');
                            $sheet->mergeCells('A1:L1');
                            $sheet->row(1, array("Reporte de Ingresos del {$fecha_ini_show} al {$fecha_fin_show}"));
                            $sheet->row(2, array('Fecha','ID abono','Codigo','Nombre','Apellido','Capital Abono','Capital refill','Interes','Mora','Multa','Tramites','Total'));
                            $sheet->getStyle('A1')->applyFromArray($styles['cabecera_principal']);
                            $sheet->getStyle('A2:H2')->applyFromArray($styles['cabecera_titulos']);#Using HT
                            $arrData = $data->toArray();
                            $finalData = [];
                            foreach ($arrData as $row){
                                $finalData[] = [
                                    'fecha'=>date('d-m-Y',strtotime($row['fecha'])),
                                    'id_abono'=>$row['id_abono'],
                                    'codigo'=>$row['codigo'],
                                    'nombre'=>$row['nombre'],
                                    'apellido'=>$row['apellido'],
                                    'capital'=>floatval($row['capital']),
                                    'refill'=>floatval($row['refill']),
                                    'interes'=>floatval($row['interes']),
                                    'mora'=>floatval($row['mora']),
                                    'multa'=>floatval($row['multa']),
                                    'tramites'=>floatval($row['tramites']),
                                ];
                            }
                            $sheet->fromArray($finalData, null, 'A3', false, false);
                            if(count($finalData)>0){
                                $inicio = 3;
                                $fin = count($data->toArray()) + $inicio - 1;
                                for($i=$inicio;$i<=$fin;$i++){
                                    $sheet->setCellValue(
                                        'L'.$i,
                                        '=SUM(F'.$i.':K'.$i.')'
                                    );
                                }
                                $sheet->getStyle('L'.$inicio.':L'.$fin)->applyFromArray($styles['negrita']);
                            }
                            $sheet->freezePane('A3');
                        });
                    })->export('xls');
                } else if ($filtro == "pdf") {
                    $pdf = PDF::loadView('reportes.prestamos_tabla_interes', $info);
                    return $pdf->download($carbon->format('dmYHis').'prestamos_tabla_interes.pdf');
                }
                break;
            case 2:
                $data = prestamo::getReporteColocacion($fecha_ini, $fecha_fin, $request->asesor_id);
                $info['prestamos'] = $data;
                $info['tabla'] = 'reportes.prestamos_tabla_colocacion_prestamo';
                if ($filtro == 'filtrar') {
                    return view('reportes.prestamos')->with($info);
                } else if ($filtro == 'xls') {
                    Excel::create('Reporte de prestamos colocados', function($excel) use($data){
                        $excel->setTitle('Reporte de prestamos colocados');
                        $excel->sheet('Prestamos', function($sheet) use($data){
                            $sheet->setOrientation('landscape');
                            $sheet->fromArray($data->toArray());
                        });
                    })->export('xls');
                } else if ($filtro == "pdf") {
                    $pdf = PDF::loadView('reportes.prestamos_tabla_colocacion_prestamo', $info);
                    return $pdf->download($carbon->format('dmYHis').'prestamos_tabla_colocacion_prestamo.pdf');
                }
                break;
            case 3:
                $data = prestamo::getPrestamosMes($fecha_ini, $fecha_fin);
                $info['prestamos'] = $data;
                $info['tabla'] = 'reportes.infored';
                if ($filtro == 'filtrar') {
                    return view('reportes.prestamos')->with($info);
                } else if ($filtro == 'xls') {
                    $xlsdata = array();
                    foreach($info['prestamos'] as $prestamo){
                        $fecha = Carbon::parse($prestamo->fecha);
                        $fecha_ultimo_pago = Carbon::parse($prestamo->getUltimaFecha());
                        $fecha_nacimiento = Carbon::parse($prestamo->cliente->fecha_nacimiento);
                        $xlsdetail["Año"] = $fecha_ini->year;
                        $xlsdetail["mes"] = str_pad($fecha_ini->month,2,'0',STR_PAD_LEFT);
                        $xlsdetail["nombre"] = $prestamo->nombre_completo;
                        $xlsdetail["Tipo_per"] = 1;
                        $xlsdetail["Num_ptmo"] = $prestamo->codigo;
                        $xlsdetail["inst"] = "";
                        $xlsdetail["fec_otor"] = $fecha->format("d/m/Y");
                        $xlsdetail["monto"] = $prestamo->monto;
                        $xlsdetail["plazo"] = number_format($prestamo->meses,0);
                        $xlsdetail["saldo"] = in_array($prestamo->estado_prestamo_id, array(2,3)) ? '=0' : $prestamo->saldoAnterior();
                        $xlsdetail["mora"] = in_array($prestamo->estado_prestamo_id, array(2,3)) ? '=0' : number_format($prestamo->montoCuotas() + $prestamo->getMora() + $prestamo->getMulta() + $prestamo->getInteres() + $prestamo->getCapitalPendiente(),2);
                        $xlsdetail["forma_pag"] = $prestamo->linea->id_infored;
                        $xlsdetail["tipo_rel"] = 1;
                        $xlsdetail["linea_cre"] = "COM";
                        $xlsdetail["dias"] = $fecha->diffInDays($fecha_ultimo_pago);
                        $xlsdetail["ult_pag"] = $fecha_ultimo_pago->format("d/m/Y");
                        $xlsdetail["tipo_gar"] = "-";
                        $xlsdetail["tipo_mon"] = "02";
                        $xlsdetail["valcuota"] = $prestamo->cuota;
                        $xlsdetail["dia"] = $fecha->endOfMonth()->day;
                        $xlsdetail["fechanac"] = $fecha_nacimiento->format("d/m/Y");
                        $xlsdetail["dui"] = $prestamo->cliente->dui;
                        $xlsdetail["nit"] = $prestamo->cliente->nit;
                        $xlsdetail["fecha_can"] = $prestamo->saldoAnterior() == 0 ? $fecha_ultimo_pago->format("d/m/Y") : "";
                        $xlsdetail["fecha_ven"] = $prestamo->getFechaVencimiento()->format('d/m/Y');
                        $xlsdetail["ncuotascre"] = $prestamo->cuotas;
                        $xlsdetail["calif_act"] = $prestamo->getClasificacion();
                        $xlsdetail["activi_eco"] = "COMERCIANTE";
                        $xlsdetail["sexo"] = str_limit($prestamo->cliente->getSexo(), 1, '');
                        $xlsdetail["estcredito"] = $prestamo->getEstadoInfored();
                        $xlsdata[] = $xlsdetail;
                    }                    
                    Excel::create('Reporte de Infored', function($excel) use($xlsdata){
                        $excel->setTitle('Reporte de Infored');
                        $excel->sheet('Prestamos', function($sheet) use($xlsdata){
                            $total_columns = count($xlsdata);
                            $sheet->setColumnFormat(array(
                                "H2:H".$total_columns => '0.00',
                                "J2:J".$total_columns => '0.00',
                                "K2:K".$total_columns => '0.00'
                            ));
                            $sheet->getStyle("H2:H".$total_columns)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                            $sheet->getStyle("J2:J".$total_columns)->getAlignment()->applyFromArray(array('horizontal' => 'right'));
                            $sheet->getStyle("K2:K".$total_columns)->getAlignment()->applyFromArray(array('horizontal' => 'right'));

                            $sheet->setOrientation('landscape');
                            $sheet->fromArray($xlsdata);
                        });
                    })->export('xls');
                } else if ($filtro == "pdf") {
                    $pdf = PDF::loadView('reportes.infored', $info);
                    return $pdf->download($carbon->format('dmYHis').'infored.pdf');
                }
                break;
            case 4:
                $data = pago::getReporteInteresSumarizado($fecha_ini, $fecha_fin, $request->asesor_id);
                $info['prestamos'] = $data;
                $info['tabla'] = 'reportes.prestamos_tabla_interes_sumarizado';
                if ($filtro == 'filtrar') {
                    return view('reportes.prestamos')->with($info);
                } else if ($filtro == 'xls') {
                    Excel::create('Reporte de interes cobrados', function($excel) use($data,$fecha_ini_show, $fecha_fin_show){
                        $excel->setTitle('Reporte de interes cobrados');
                        $excel->sheet('Intereses', function($sheet) use($data,$fecha_ini_show,$fecha_fin_show){
                            $styles = $this->getStyles();
                            $sheet->setOrientation('landscape');
                            $sheet->mergeCells('A1:H1');
                            $sheet->row(1, array("Reporte de Ingresos sumarizados del {$fecha_ini_show} al {$fecha_fin_show}"));
                            $sheet->row(2, array('Fecha','Ingreso Capital Abono','Ingreso Capital Refill','Interes','Mora','Multa','Tramites', 'Total'));
                            $sheet->getStyle('A1')->applyFromArray($styles['cabecera_principal']);
                            $sheet->getStyle('A2:H2')->applyFromArray($styles['cabecera_titulos']);#Using HT
                            $arrData = $data->toArray();
                            $finalData = [];
                            foreach ($arrData as $row){
                                $finalData[] = [
                                    'fecha'=>date('d-m-Y',strtotime($row['fecha'])),
                                    'capital'=>floatval($row['capital']),
                                    'refill'=>floatval($row['refill']),
                                    'interes'=>floatval($row['interes']),
                                    'mora'=>floatval($row['mora']),
                                    'multa'=>floatval($row['multa']),
                                    'tramites'=>floatval($row['tramites']),
                                ];
                            }
                            $sheet->fromArray($finalData, null, 'A3', false, false);
                            if(count($finalData)>0){
                                $inicio = 3;
                                $fin = count($data->toArray()) + $inicio - 1;
                                for($i=$inicio;$i<=$fin;$i++){
                                    $sheet->setCellValue(
                                        'H'.$i,
                                        '=SUM(B'.$i.':G'.$i.')'
                                    );
                                }
                                $sheet->getStyle('H'.$inicio.':H'.$fin)->applyFromArray($styles['negrita']);
                                /*$sheet->setCellValue(
                                    'H'.($fin+1),
                                    '=SUM(H'.$inicio.':H'.$fin.')'
                                );*/
                            }
                            $sheet->freezePane('A3');
                        });
                    })->export('xls');
                } else if ($filtro == "pdf") {
                    $pdf = PDF::loadView('reportes.prestamos_tabla_interes_sumarizado', $info);
                    return $pdf->download($carbon->format('dmYHis').'prestamos_tabla_interes_sumarizado.pdf');
                }
                break;
            case 5:
                $data = prestamo::getReporteColocacionSumarizado($fecha_ini, $fecha_fin, $request->asesor_id);
                $info['prestamos'] = $data;
                $info['tabla'] = 'reportes.prestamos_tabla_colocacion_prestamo_sumarizado';
                if ($filtro == 'filtrar') {
                    return view('reportes.prestamos')->with($info);
                } else if ($filtro == 'xls') {
                    Excel::create('Reporte de prestamos colocados', function($excel) use($data){
                        $excel->setTitle('Reporte de prestamos colocados');
                        $excel->sheet('Prestamos', function($sheet) use($data){
                            $sheet->setOrientation('landscape');
                            $sheet->fromArray($data->toArray());
                        });
                    })->export('xls');
                } else if ($filtro == "pdf") {
                    $pdf = PDF::loadView('reportes.prestamos_tabla_colocacion_prestamo_sumarizado', $info);
                    return $pdf->download($carbon->format('dmYHis').'prestamos_tabla_colocacion_prestamo_sumarizado.pdf');
                }
                break;
            default:
                $data['prestamos'] = null;
                break;
        }
    }
}