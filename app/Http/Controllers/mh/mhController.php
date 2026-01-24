<?php

namespace App\Http\Controllers\mh;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\hacienda\estadoFactura;
use App\Models\hacienda\factura;

use App\Services\mhService;
use Illuminate\Http\Request;
use App\Services\OAuthTransportFactory;
use Swift_Mailer;
use Mail;
use Response;
use Carbon\Carbon;
use PDF;
use App\Services\PdfService;
 use App\Services\MoneyService;
use Illuminate\Support\Facades\Config;
use App\Models\principal\prestamo;
use App\Models\principal\cliente;
use App\Models\catalogos\mh_actividad_economica;
use App\Models\catalogos\mh_departamento;
use App\Models\catalogos\mh_municipio;
use Yajra\Datatables\Datatables;
use ZipArchive;

class mhController extends Controller
{
    protected $pdfService;
    protected $dte;

     protected $moneyService;

     protected $mhService;

    public function __construct(PdfService $pdfService, MoneyService $moneyService,mhService $mhService)
    {
        $this->middleware('menu');
        $this->pdfService = $pdfService;
         $this->moneyService = $moneyService;
        $this->dte = Config::get('dte');
        $this->mhService = $mhService;
    }

    public function index()
    {
        $data['prestamos'] = prestamo::getPrestamoActivosCliente();
        $data['actividades_economicas'] = mh_actividad_economica::all();
        $data['departamentos'] = mh_departamento::all();
        $data['municipios'] = mh_municipio::all();

        return view('hacienda.index')->with($data);
    }

    function generarSecuencia($value)
    {
        return str_pad($value, 15, '0', STR_PAD_LEFT);
    }

    public function generarFactura(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $tipoDte = $request->input('tipo_dte', '01');
        $retiene_renta = $request->input('retiene_renta', false);
        $descripciones = $request->input('descripcion', []);
        $cantidades = $request->input('cantidad', []);
        $unidades = $request->input('unidad_medida', []);
        $precios = $request->input('precio_unitario', []);
        $tipo = $request->input('tipo', []);
        $descuento = $request->input('descuento', []);
        $no_suj = $request->input('no_suj', []);
        $exenta = $request->input('exenta', []);

        // Si es factura (01), usar el método original con cliente de BD
        if ($tipoDte == '01') {
            $cliente = cliente::findOrFail($input['cliente_id']);
            $mh_factura = $this->mhService->generarFactura($cliente,$descripciones,$cantidades,$precios,$tipo,$unidades,$descuento,$no_suj,$exenta);
        } else {
            // Para crédito fiscal (03) y sujeto excluido (14), crear objeto con datos del formulario
            \Log::info('=== DATOS RECIBIDOS DEL FORMULARIO ===');
            \Log::info('Nombre: ' . $request->input('nombre'));
            \Log::info('Apellido: ' . $request->input('apellido'));
            \Log::info('NIT: ' . $request->input('nit'));
            \Log::info('NRC: ' . $request->input('nrc'));
            \Log::info('Correo: ' . $request->input('correo'));
            \Log::info('Teléfono: ' . $request->input('telefono'));
            \Log::info('Actividad económica: ' . $request->input('actividad_economica'));
            \Log::info('Departamento: ' . $request->input('departamento'));
            \Log::info('Municipio: ' . $request->input('municipio'));
            \Log::info('Dirección: ' . $request->input('complemento'));
            
            $cliente = new \stdClass();
            $cliente->nombre = $request->input('nombre');
            $cliente->apellido = $request->input('apellido');
            $cliente->nit = $request->input('nit');
            $cliente->nrc = $request->input('nrc');
            $cliente->correo = $request->input('correo');
            $cliente->telefono = $request->input('telefono');
            $cliente->cod_actividad_economica = $request->input('actividad_economica');
            $cliente->departamento_codigo = $request->input('departamento');
            $cliente->municipio_codigo = $request->input('municipio');
            $cliente->direccion = $request->input('complemento');
            $cliente->id = null;
            
            $mh_factura = $this->mhService->generarFacturaCustom($cliente,$descripciones,$cantidades,$precios,$tipo,$unidades,$descuento,$no_suj,$exenta,$tipoDte,$retiene_renta);
        }
        
        // Generar PDF
        $this->generarFacturaPDF($mh_factura->id);
        
        // Determinar mensaje según el estado de la factura
        $mensaje = '';
        if ($mh_factura->estado == estadoFactura::CERTIFICADA || $mh_factura->estado == estadoFactura::CLIENTE) {
            $mensaje = 'Factura generada y certificada exitosamente. Correo enviado al cliente.';
        } else if ($mh_factura->estado == estadoFactura::RECHAZADA) {
            $mensaje = 'La factura fue rechazada por el Ministerio de Hacienda.';
        }
        
        return redirect('hacienda/facturas')->with('success', $mensaje);
    }

    function obtenerInfoEmpresa(){
        $direccion_emisor = [
            'departamento' => $this->dte['departamento'],
            'municipio' => $this->dte['municipio'],
            'complemento' => $this->dte['direccion']
        ];
        $nombre_comercial = $this->dte['nombre'];
        $telefono =$this->dte['telefono'];
        return ["nombre"=>$nombre_comercial,"telefono"=>$telefono,"direccion"=>$direccion_emisor,'actividad_economica' => $this->dte['actividad_economica'],'correo' => $this->dte['correo']];
    }

     public function generarFacturaPDF($id, $enviar_correo=false)
    {
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $factura = factura::findOrFail($id);
        $data['factura'] = $factura;
        $data['json'] = json_decode($factura->json) ;
        $data["crees"] = $this->obtenerInfoEmpresa();
        $data['montoLetras'] = $this->moneyService->convertirMontoADolares($data["json"]->resumen->totalPagar);
        $data['destinatario'] = isset($data['json']->sujetoExcluido) ? $data['json']->sujetoExcluido : $data['json']->receptor;
       
        $pdf = PDF::loadView('pdf.mh_factura', $data);        
        if($enviar_correo){
            $transport = OAuthTransportFactory::make();
            $mailer = new Swift_Mailer($transport);
            Mail::setSwiftMailer($mailer);
            $correo = ($factura->cliente && $factura->cliente->correo) ? $factura->cliente->correo : $this->dte['correo'];
            $bccCorreo = $this->dte['correo'];
            $pdfContent = $pdf->output();
            Mail::send('emails.factura', $data, function ($message) use ($pdfContent,$correo, $bccCorreo)  {
                $message->to($correo)
                        ->bcc($bccCorreo)
                        ->subject('Servicios crediticios de El Salvador')
                        ->attachData($pdfContent, 'factura.pdf', [
                            'mime' => 'application/pdf',
                        ]);
            });
            return response()->json(['status' => 'Correo enviado']);
        }
        $nombreArchivo = $factura->cliente ? $factura->cliente->nombreCompleto() : 'CLIENTE';
        return $pdf->download('FACTURA - '.$nombreArchivo.'.pdf');
    }

    public function facturas()
    {
        return view('hacienda.facturas');
    }

    public function getDataTable(Request $request)
    {
        return Datatables::of(factura::getFacturas($request->input('fecha_inicio'), $request->input('fecha_fin')))
        ->addColumn('estado', function ($row) {
            return $row->estado;
        })
        ->addColumn('nombre_completo', function ($row) {
            // Si tiene cliente_id, usar nombre del cliente
            if ($row->nombre_completo && trim($row->nombre_completo) != '') {
                return $row->nombre_completo;
            }
            
            // Si no tiene cliente, obtener nombre del JSON
            $json = json_decode($row->json);
            if (isset($json->sujetoExcluido->nombre)) {
                return $json->sujetoExcluido->nombre;
            } elseif (isset($json->receptor->nombre)) {
                return $json->receptor->nombre;
            }
            
            return 'Sin nombre';
        })
        ->addColumn('tipo_dte_nombre', function ($row) {
            switch ($row->tipo_dte) {
                case 1:
                    return 'Factura';
                case 3:
                    return 'Crédito Fiscal';
                case 14:
                    return 'Sujeto Excluido';
                default:
                    return 'Tipo ' . $tipoDte;
            }
        })
        ->addColumn('estado_nombre', function ($row) {
        switch ($row->estado) {
            case estadoFactura::CREADA:
                return 'Creada';
            case estadoFactura::ENVIADA:
                return 'Enviada';
            case estadoFactura::RECHAZADA:
                return 'Rechazada';
            case estadoFactura::CERTIFICADA:
                return 'Certificada';
            case estadoFactura::PENDIENTE:
                return 'Pendiente';
            case estadoFactura::CLIENTE:
                return 'Cliente';
            case estadoFactura::ANULADA:
                return 'Anulada';
            default:
                return 'Desconocido';
        }
    })
         ->filterColumn('nombre_completo', function($query, $keyword) {
                            $query->whereRaw("LOWER(clientes.nombre) like LOWER(?) or LOWER(clientes.apellido) like LOWER(?)", ["%{$keyword}%", "%{$keyword}%"]);
                        })
        ->filterColumn('tipo_dte_nombre', function($query, $keyword) {
            $keyword = strtolower(trim($keyword));
            if (strpos($keyword, 'factura') !== false && strpos($keyword, 'crédito') === false && strpos($keyword, 'credito') === false) {
                $query->whereRaw("mh_factura.json LIKE '%\"tipoDte\":\"01\"%'");
            } elseif (strpos($keyword, 'crédito') !== false || strpos($keyword, 'credito') !== false || strpos($keyword, 'fiscal') !== false) {
                $query->whereRaw("mh_factura.json LIKE '%\"tipoDte\":\"03\"%'");
            } elseif (strpos($keyword, 'excluido') !== false || strpos($keyword, 'sujeto') !== false) {
                $query->whereRaw("mh_factura.json LIKE '%\"tipoDte\":\"14\"%'");
            } else {
                // Si busca por número de tipo directamente, buscar en el JSON
                $query->whereRaw("mh_factura.json LIKE ?", ['%"tipoDte":"' . $keyword . '"%']);
            }
        })
        ->filterColumn('estado_nombre', function($query, $keyword) {
        $keyword = strtolower(trim($keyword));
        if ($keyword === 'creada') {
            $query->where('estado', estadoFactura::CREADA);
        } elseif ($keyword === 'enviada') {
            $query->where('estado', estadoFactura::ENVIADA);
        } elseif ($keyword === 'rechazada') {
            $query->where('estado', estadoFactura::RECHAZADA);
        } elseif ($keyword === 'certificada') {
            $query->where('estado', estadoFactura::CERTIFICADA);
        } elseif ($keyword === 'pendiente') {
            $query->where('estado', estadoFactura::PENDIENTE);
        } elseif ($keyword === 'cliente') {
            $query->where('estado', estadoFactura::CLIENTE);
        }
            elseif ($keyword === 'anulada') {
            $query->where('estado', estadoFactura::ANULADA);
        } else {
            $query->whereRaw('1 = 0');
        }
    })
        ->filterColumn('fecha_factura', function($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(mh_factura.created_at, '%d/%m/%Y') LIKE ?", ["%{$keyword}%"]);
        })
        ->make(true);
    }

    function correo($factura_id){         
        $factura = factura::findOrFail($factura_id);
        
        // Solo permitir si está en CERTIFICADA o CLIENTE
        if ($factura->estado != estadoFactura::CERTIFICADA && $factura->estado != estadoFactura::CLIENTE) {
            return redirect('hacienda/facturas')->with('error', 'Solo se puede reenviar correo si la factura está certificada o enviada al cliente');
        }
        $this->mhService->enviarCorreoFactura($factura);
        
        // $this->generarFacturaPDF($factura_id, true);
        
        // Cambiar estado a CLIENTE si estaba en CERTIFICADA
        // if ($factura->estado == estadoFactura::CERTIFICADA) {
        //     $factura->estado = estadoFactura::CLIENTE;
        //     $factura->save();
        // }
        
        return redirect('hacienda/facturas');
    }

    public function reenviarFactura($factura_id){
        $factura = factura::findOrFail($factura_id);
        
        // Solo permitir si está en RECHAZADA
        if ($factura->estado != estadoFactura::RECHAZADA) {
            return redirect('hacienda/facturas')->with('error', 'Solo se puede reenviar a hacienda si la factura está rechazada');
        }
        
        $this->mhService->reenviarFactura($factura_id);
        return redirect('hacienda/facturas');
    }

    public function contingenciaIndex()
    {
        $fecha = date('d-m-Y');
        // Buscar facturas rechazadas en la fecha seleccionada
        $facturas = \App\Models\hacienda\factura::where('estado', \App\Models\hacienda\estadoFactura::RECHAZADA)
            ->whereRaw('DATE_FORMAT(created_at, "%d-%m-%Y") = ?', [$fecha])
            ->get();
        return view('hacienda.contingencias')->with(['fecha' => $fecha, 'facturas' => $facturas]);
    }

    public function contingenciaFiltrar(Request $request)
    {
        $fecha = $request->input('fecha');
        $facturas = \App\Models\hacienda\factura::where('estado', \App\Models\hacienda\estadoFactura::RECHAZADA)
            ->whereRaw('DATE_FORMAT(created_at, "%d-%m-%Y") = ?', [$fecha])
            ->get();
        return view('hacienda.contingencias')->with(['fecha' => $fecha, 'facturas' => $facturas]);
    }

        /**
     * Procesa la creación de contingencia para facturas seleccionadas.
     */
    public function crearContingencia(Request $request)
    {
        $ids = $request->input('facturas', []);
        $motivo = $request->input('motivo', 'Falla en el servicio de internet');
        $fInicio = $request->input('fInicio', '');
        $fFin = $request->input('fFin', '');
        $hInicio = $request->input('hInicio', '');
        $hFin = $request->input('hFin', '');
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Debe seleccionar al menos una factura.');
        }
        $this->mhService->contingencia($ids, $motivo, $fInicio, $fFin, $hInicio, $hFin  );
        return redirect()->back()->with('success', 'Contingencia creada para las facturas: ' . implode(', ', $ids));
    }

        /**
     * Descargar todas las facturas en el rango de fechas
     */
    public function descargarTodo(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        // Aquí deberías obtener las facturas del rango y generar un ZIP o PDF múltiple
        // Ejemplo: descargar PDFs individuales en un ZIP
        $facturas = factura::getFacturas($fecha_inicio, $fecha_fin)->get();
        if ($facturas->isEmpty()) {
            return back()->with('error', 'No hay facturas en el rango seleccionado.');
        }


        $zip = new ZipArchive();
        $zipFileName = storage_path('app/facturas_descarga_' . date('Ymd_His') . '.zip');
        if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
            return back()->with('error', 'No se pudo crear el archivo ZIP.');
        }

        foreach ($facturas as $factura) {
            // Usar la función individual para generar el PDF y obtener el contenido
            $pdfResponse = $this->generarFacturaPDFZip($factura->id);
            if ($pdfResponse && isset($pdfResponse['content']) && isset($pdfResponse['filename'])) {
                $zip->addFromString($pdfResponse['filename'], $pdfResponse['content']);
            }
        }
            
        $zip->close();

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Genera el PDF de una factura y retorna el contenido y nombre de archivo (para uso en ZIP)
     */
    protected function generarFacturaPDFZip($id)
    {
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $factura = factura::findOrFail($id);
        $data['factura'] = $factura;
        $data['json'] = json_decode($factura->json) ;
        $data["crees"] = $this->obtenerInfoEmpresa();
        $data['montoLetras'] = $this->moneyService->convertirMontoADolares($data["json"]->resumen->totalPagar);
        $data['destinatario'] = isset($data['json']->sujetoExcluido) ? $data['json']->sujetoExcluido : $data['json']->receptor;
        $pdf = PDF::loadView('pdf.mh_factura', $data);
        $pdfContent = $pdf->output();
        $nombreArchivo = 'FACTURA_' . ($factura->cliente ? $factura->cliente->nombreCompleto() : 'CLIENTE') . '_' . $factura->id . '.pdf';
        return [
            'content' => $pdfContent,
            'filename' => $nombreArchivo
        ];
    }
}