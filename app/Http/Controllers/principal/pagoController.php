<?php

namespace App\Http\Controllers\principal;

use App\Http\Controllers\mh\mhController;
 use App\Http\Requests;
use App\Models\principal\pago;
use App\Models\principal\prestamo;
use App\Models\catalogos\linea;
use App\Models\catalogos\cobrador;
use App\Http\Controllers\Controller;
use App\Services\mhService;
 use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
 use Response;
use Datatables;
use Carbon\Carbon;
use PDF;
use ZipArchive;
use setasign\Fpdi\Fpdi;
 use DB;
 use App\Services\MoneyService;

class pagoController extends Controller
{
    protected $dte;
    protected $moneyService;
    protected $mhService;

    function __construct(MoneyService $moneyService,mhService $mhService)
    {
        $this->middleware('menu');
        $this->dte = Config::get('dte');
        $this->moneyService = $moneyService;
        $this->mhService = $mhService;
    }

    public function index(Request $request)
    {
        return view('principal.pago.index');
    }

    public function create()
    {
        $data['prestamos'] = prestamo::getPrestamoActivosCliente();
        $data['cobradores'] = cobrador::all();
        $data['fecha'] = $carbon = new Carbon();
        return view('principal.pago.create')->with($data);
    }

    public function store(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $prestamo = prestamo::findOrFail($input['prestamo_id']);
        $input['fecha'] = Carbon::createFromFormat('d-m-Y', $input['fecha']);
        $cuota = $input['cuota'];
        $capital_total = $input['hdn_capital'];
        $interes = $prestamo->getInteres($input['fecha']);
        $mora = $input['mora'];
        $multa =  $input['multa'];
        if ($multa < $cuota) {
            $cuota = $cuota - $multa;
            $multa_pendiente = 0.0;
        }
        else {
            $multa_pendiente = $multa - $cuota;
            $multa = $cuota;
            $cuota = 0.0;
        }
        if ($mora < $cuota) {
            $cuota = $cuota - $mora;
            $mora_pendiente = 0.0;
        }
        else {
            $mora_pendiente = $mora - $cuota;
            $mora = $cuota;
            $cuota = 0.0;
        }
        if ($interes < $cuota) {
            $cuota = $cuota - $interes;
            $interes_pendiente = 0.0;
        }
        else {
            $interes_pendiente = $interes - $cuota;
            $interes = $cuota;
            $cuota = 0.0;
        }
        $capital = $cuota;
        $capital_pendiente = $capital_total - $cuota + $prestamo->getCapitalPendienteAcumulado();

        $input['multa'] = $multa;
        $input['multa_pendiente'] = $multa_pendiente;
        $input['mora'] = $mora;
        $input['interes_mora_pendiente'] = $mora_pendiente;
        $input['interes'] = $interes;
        $input['interes_pendiente'] = $interes_pendiente;
        $input['capital'] = $capital;
        $input['capital_pendiente'] = $capital_pendiente < 0 ? 0 : $capital_pendiente;

        $pago = $prestamo->pagos()->create($input); 
        $this->generarFacturaMH($pago);      
       

        $prestamo = prestamo::findOrFail($input['prestamo_id']);
        if($prestamo->saldoAnterior() <= 0) {
            $prestamo->estado_prestamo_id = 3;
            $prestamo->save();
        }
        if($input['btn_enviar'] == "guardar")
            return redirect(route('pagos.create'));
        if($input['btn_enviar'] == "recibo")
            return redirect(route('pagos.recibo',$pago->id));
        return false;
    }

    function generarFacturaMH($pago){
        /*if($pago->capital>0){
            $descripciones[] = "Capital";
            $cantidades[] = 1;
            $unidades[] = 99;
            $precios[] = 0; // Precio en 0 para que se detecte como noGravado
            $tipo[] = 2;
            $descuento[] = null;
            $no_suj[] = $pago->capital;
            $exenta[] = 0.00;
        }*/

        if($pago->interes>0){
            $descripciones[] = "Interes";
            $cantidades[] = 1;
            $unidades[] = 99;
            $precios[] = $pago->interes;
            $tipo[] = 1;
            $descuento[] = null;
            $no_suj[] = 0.00;
            $exenta[] = 0.00;
        }

        if( $pago->mora>0){
            $descripciones[] = "Mora";
            $cantidades[] = 1;
            $unidades[] = 99;
            $precios[] = $pago->mora;
            $tipo[] = 1;
            $descuento[] = null;
            $no_suj[] = 0.00;
            $exenta[] = 0.00;
        }

         if( $pago->multa>0){
            $descripciones[] = "Multa";
            $cantidades[] = 1;
            $unidades[] = 99;
            $precios[] = $pago->multa;
            $tipo[] = 1;
            $descuento[] = null;
            $no_suj[] = 0.00;
            $exenta[] = 0.00;
        }
        
        $factura = $this->mhService->generarFactura($pago->prestamo->cliente,$descripciones,$cantidades,$precios,$tipo,$unidades,$descuento,$no_suj,$exenta, '01');
        
        // Asignar pago_id a la factura
        if ($factura) {
            $factura->pago_id = $pago->id;
            $factura->save();
        }
        
        return $factura;
    }

    public function show($id)
    {
        $pago = pago::findOrFail($id);
        return view('principal.pago.show')->with('pago', $pago);
    }

    public function edit($id)
    {
        $data['pago'] = pago::findOrFail($id);
        return view('principal.pago.edit')->with($data);
    }

    public function update($id, Request $request)
    {
        $pago = pago::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $pago = pago::where('id', $id)->update($output);
        return redirect(route('pagos.index'));
    }

     public function destroy($id)
    {
        $pago = pago::findOrFail($id);
        $result = $pago->delete($id);
        return redirect(route('pagos.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(prestamo::getPrestamoCliente())
                        ->filterColumn('nombre_completo', function($query, $keyword) {
                            $query->whereRaw("LOWER(clientes.nombre) like LOWER(?) or LOWER(clientes.apellido) like LOWER(?)", ["%{$keyword}%", "%{$keyword}%"]);
                        })
                        ->make(true);
    }

    public function getCalculadora()
    {
        $data['lineas'] = linea::all();
        $data['prestamos'] = prestamo::getPrestamoActivosCliente();
        return view('principal.pago.calculadora')->with($data);
    }

    public function getHistorial($id)
    {
        $data['prestamo'] = prestamo::findOrFail($id);
        return view('principal.pago.historial')->with($data);
    }

    public function getRevertir($prestamo_id, $pago_id)
    {
        $pago = pago::findOrFail($pago_id);
        
        // Buscar factura asociada al pago (puede estar en CERTIFICADA o CLIENTE)
        $factura = \App\Models\hacienda\factura::where('cliente_id', $pago->prestamo->cliente_id)
            ->whereBetween('created_at', [
                $pago->created_at->copy()->subMinutes(5),
                $pago->created_at->copy()->addMinutes(5)
            ])
            ->whereIn('estado', [
                \App\Models\hacienda\estadoFactura::CERTIFICADA,
                \App\Models\hacienda\estadoFactura::CLIENTE
            ])
            ->first();
        
        \Log::info('Revertir pago - Cliente ID: ' . $pago->prestamo->cliente_id . ', Fecha pago: ' . $pago->created_at);
        \Log::info('Factura encontrada: ' . ($factura ? 'SÍ (ID: ' . $factura->id . ', Estado: ' . $factura->estado . ')' : 'NO'));
        
        // Si hay factura certificada, intentar anularla
        if ($factura) {
            try {
                $anulacionExitosa = $this->mhService->anularFactura($factura->id, 'Reversión de pago');
                
                if (!$anulacionExitosa) {
                    return redirect()->back()->with('error', 'No se pudo anular la factura electrónica');
                }
            } catch (\Exception $e) {
                \Log::error('Error al anular factura: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error al anular factura: ' . $e->getMessage());
            }
        }
        
        // Eliminar pago y actualizar estado del préstamo
        $pago->delete();
        $prestamo = prestamo::where('id', $prestamo_id)->update(["estado_prestamo_id"=>1]);
        
        return redirect('pagos/historial/'. $prestamo_id)->with('success', 'Pago revertido y factura anulada exitosamente');
    }

    function obtenerInfoEmpresa(){
        $direccion_emisor = [
            'departamento' => $this->dte['departamento'],
            'municipio' => $this->dte['municipio'],
            'complemento' => $this->dte['direccion']
        ];
        $nombre_comercial = $this->dte['nombre'];
        $telefono =$this->dte['telefono'];
        return ["nombre"=>$nombre_comercial,"telefono"=>$telefono,"direccion"=>$direccion_emisor];
    }

    public function pdfNotaCobro($id)
    {
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['prestamo'] = prestamo::findOrFail($id);
        $data['crees']= $this->obtenerInfoEmpresa();
       
        $content = view('pdf.nota_cobro')->with($data)->render();
        $doc_name = $carbon->format('dmYHis').'nota_cobro.doc';

        $headers = array(
            "Content-type"=>"text/html",
            "Content-Disposition"=>"attachment;Filename={$doc_name}",
            //"charset" =>"utf-8"
        );

        return Response::make(utf8_decode($content),200, $headers);

        /*$phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText(($vista));
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($doc_name);
        header("Content-Disposition: attachment; filename='{$doc_name}';'Content-Type: text/html; charset=utf-8'");
        readfile($doc_name);
        unlink($doc_name);*/


        /*$carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['prestamo'] = prestamo::findOrFail($id);
        $pdf = PDF::loadView('pdf.nota_cobro', $data);
        return $pdf->download($carbon->format('dmYHis').'nota_cobro.pdf');*/
    }

    public function pdfRecibo($pago_id){        
        $pago = pago::findOrFail($pago_id);        
        $prestamo = prestamo::findOrFail($pago->prestamo_id);
        $prestamo_id = $pago->prestamo_id;
        $fecha_pago = $pago->fecha->format('Y-m-d');
        $tramites = prestamo::whereHas('prestamos_liquidados', 
            function ($query) use ($prestamo_id) {
                $query->where('prestamo_liquidado_id', $prestamo_id);
            })
        ->where(DB::raw('DATE(fecha)'), $fecha_pago)
        ->first();
       if($tramites){
        $gastos = $tramites->gastos->sum('monto');
        $data['tramites'] = $gastos;
        }
        else
        $data['tramites'] = 0;

        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['prestamo'] = $prestamo;
        $data['pago'] = $pago;
        
        $data['titulo'] = '<h1>Recibo</h1>';
        $data['crees']= $this->obtenerInfoEmpresa();
        $pdfRecib = PDF::loadView('pdf.recibo', $data);
        $this->generarFactura($data);
        $path1 = storage_path('recibo.pdf');
        $path2 = storage_path('factura.pdf');
        $pdfRecib->save($path1);
        
        $zipPath = storage_path('archivos.zip');
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($path1, 'recibo.pdf');
            $zip->addFile($path2, 'factura.pdf');
            $zip->close();
        }

       
        unlink($path1);
        unlink($path2);
        return response()->download($zipPath)->deleteFileAfterSend(true);
        // return $pdf->download($carbon->format('dmYHis').'recibo.pdf');
    }

    function generarFactura($data){
        $pdfPath = public_path('pdf/factura.pdf');
        // $pdf = new \setasign\Fpdi\Fpdi();
        $pdf = new Fpdi();
        // $pageCount = $pdf->setSourceFile($pdfPath);
        // $tplIdx = $pdf->importPage(1);
        $pdf->AddPage();
        // $pdf->useTemplate($tplIdx, 0, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(35, 60);        
        $pdf->Write(0, utf8_decode($data['prestamo']->cliente->nombreCompleto()));

        $pdf->SetFont('Times', '', 12);
        $pdf->SetXY(110, 53);
        $pdf->Write(0, $data['pago']->fecha->format('d-m-Y'));

        $pdf->SetFont('Times', '',10);
        $pdf->SetXY(35, 67);
        $pdf->Write(0, utf8_decode($data['prestamo']->cliente->direccion));

        $pdf->SetFont('Times', '', 12);
        $pdf->SetXY(110, 75);
        $pdf->Write(0, $data['prestamo']->cliente->dui);

        $pdf->SetFont('Times', '', 12);
        $pdf->SetXY(35, 90);
        $pdf->Write(0, 'Interes');

        $pdf->SetFont('Times', '', 12);
        $pdf->SetXY(130, 90);
        $pdf->Write(0, "$".number_format($data['pago']->interes,2));

        if($data['pago']->mora > 0){
            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(35, 96);
            $pdf->Write(0, 'Interes moratorio');

            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(130, 96);
            $pdf->Write(0, "$".number_format($data['pago']->mora,2));
        }

        if($data['pago']->multa > 0){
            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(35, 102);
            $pdf->Write(0, 'Multa');

            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(130, 102);
            $pdf->Write(0, "$".number_format($data['pago']->multa,2));
        }

        if($data['tramites'] > 0){
            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(35, 108);
            $pdf->Write(0, 'Tramites');

            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(130, 108);
            $pdf->Write(0, "$".number_format($data['tramites'],2));
        }

        $total = $data['pago']->interes + $data['pago']->mora + $data['pago']->multa + $data['tramites'];
        
        $pdf->SetFont('Times', '',10);
        $pdf->SetXY(35, 167);
        $pdf->Write(0, $this->moneyService->convertirMontoADolares($total));

        $pdf->SetFont('Times', '',12);
        $pdf->SetXY(130, 165);
        $pdf->Write(0, "$".number_format($total,2));

        $pdf->SetFont('Times', '', 12);
        $pdf->SetXY(130, 188);
        $pdf->Write(0, "$".number_format($total,2));

        $tempPdfPath = storage_path('factura.pdf');
        $pdf->Output($tempPdfPath, 'F');
    }
}
