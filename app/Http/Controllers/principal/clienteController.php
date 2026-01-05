<?php

namespace App\Http\Controllers\principal;

use App\Http\Requests;
use App\Http\Requests\principal\CreateclienteRequest;
use App\Http\Requests\principal\UpdateclienteRequest;
use App\Models\principal\cliente;
use App\Models\principal\negocio;
use App\Models\principal\prestamo;
use App\Models\catalogos\asesor;
use App\Models\catalogos\cobrador;
use App\Models\catalogos\profesion;
use App\Models\catalogos\zona;
use App\Models\catalogos\estado_civil;
use App\Models\catalogos\tipo_negocio;
use App\Models\catalogos\tipo_gasto;
use App\Models\catalogos\linea;
use App\Models\catalogos\municipio;
use App\Models\catalogos\departamento;
use App\Models\catalogos\mh_municipio;
use App\Models\catalogos\mh_departamento;
use App\Http\Controllers\Controller;
use App\Services\mhService;
use Illuminate\Http\Request;
use Response;
use Datatables;
use Carbon\Carbon;
use PDF;
use Lang;

class clienteController extends Controller
{
    protected $mhService;

    function __construct(mhService $mhService)
    {
        $this->middleware('menu');
        $this->mhService = $mhService;
    }

    public function index(Request $request)
    {
        return view('principal.cliente.index');
    }

    public function create()
    {
        $data['profesiones'] = profesion::all();
        $data['zonas'] = zona::all();
        $data['estados_civiles'] = estado_civil::all();
        $data['departamentos'] = mh_departamento::all();
        $data['municipios'] = [];
        return view('principal.cliente.create')->with($data);
    }

    public function store(CreateclienteRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $input['fecha_nacimiento'] = Carbon::createFromFormat('d-m-Y', $input['fecha_nacimiento']);
        $input['nombre'] = strtoupper($input['nombre']);
        $input['apellido'] = strtoupper($input['apellido']);
        $emptyRemoved = array_filter($input);
        $cliente = cliente::create($emptyRemoved);
        $cliente->codigo = str_pad($cliente->id, 5, "0", STR_PAD_LEFT);
        $cliente->save();
        return redirect("clientes/negocios/{$cliente->id}");
    }

    public function show($id)
    {
        $cliente = cliente::findOrFail($id);
        return view('principal.cliente.show')->with('cliente', $cliente);
    }

    public function edit($id)
    {
        $data['cliente'] = cliente::findOrFail($id);
        $data['profesiones'] = profesion::all();
        $data['zonas'] = zona::all();
        $data['estados_civiles'] = estado_civil::all();
        $data['negocios'] = negocio::where('cliente_id', $id)->get();
        $data['departamentos'] = mh_departamento::all();

        if (isset($data['cliente']->departamento)) {
            $data['municipios'] = $data['cliente']->departamento->municipios;
        } else {
            $data['municipios'] = collect();
        }
        
        return view('principal.cliente.edit')->with($data);
    }

    public function update($id, UpdateclienteRequest $request)
    {
        $cliente = cliente::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $input['fecha_nacimiento'] = Carbon::createFromFormat('d-m-Y', $input['fecha_nacimiento']);
        $output = array_map(function ($item) {
            return empty($item) ? '' : $item; }, $input);
        $cliente = cliente::where('id', $id)->update($output);
        return redirect(route('clientes.index'));
    }

    public function destroy($id)
    {
        $cliente = cliente::findOrFail($id);
        $result = $cliente->delete($id);
        return redirect(route('clientes.index'));
    }

    public function restore($id)
    {
        $cliente = cliente::onlyTrashed()->where('id', $id)->firstOrFail();
        $cliente->restore();
        return redirect(route('clientes.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(cliente::listaClientes())
            ->filterColumn('nombre_completo', function ($query, $keyword) {
                $query->whereRaw("LOWER(clientes.nombre) like LOWER(?) or LOWER(clientes.apellido) like LOWER(?)", ["%{$keyword}%", "%{$keyword}%"]);
            })
            ->filterColumn('domicilio', function ($query, $keyword) {
                $query->whereRaw("LOWER(mh_departamentos.nombre) like LOWER(?) or LOWER(mh_municipios.nombre) like LOWER(?)", ["%{$keyword}%", "%{$keyword}%"]);
            })
            ->make(true);
    }

    public function negocioSave(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $negocio = negocio::create($emptyRemoved);
        return redirect('clientes/negocios/' . $negocio->cliente_id);
    }

    public function negocioDelete($id)
    {
        $negocio = negocio::findOrFail($id);
        $cliente = $negocio->cliente_id;
        $result = $negocio->delete($id);
        return redirect('clientes/negocios/' . $cliente);
    }

    public function getNegocio($id)
    {
        $data['cliente'] = cliente::findOrFail($id);
        $data['tipo_negocio'] = tipo_negocio::all();
        $data['departamentos'] = departamento::all();
        $data['negocios'] = negocio::where('cliente_id', $id)->get();
        return view('principal.cliente.negocio')->with($data);
    }

    public function prestamoSave(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        if (isset($input['fiadores']))
            $fiadores = is_array($input['fiadores']) ? $input['fiadores'] : array();
        else
            $fiadores = array();
        if (isset($input['gastos']))
            $gastos = is_array($input['gastos']) ? $input['gastos'] : array();
        else
            $gastos = array();
        $input['fecha'] = Carbon::createFromFormat('d-m-Y', $input['fecha']);
        $emptyRemoved = array_filter($input);
        $prestamo = prestamo::create($emptyRemoved);
        $codigo_cliente = str_pad($prestamo->cliente_id, 5, "0", STR_PAD_LEFT);
        $codigo_prestamo = str_pad(prestamo::where('cliente_id', $prestamo->cliente_id)->where('estado_prestamo_id', '!=', 4)->count(), 2, "0", STR_PAD_LEFT);
        $prestamo->codigo = $codigo_cliente . '01' . $prestamo->linea->nombre . $codigo_prestamo;
        $prestamo->fiadores()->sync($fiadores);
        $prestamo->gastos()->sync($gastos);

        $prestamos_liquidados = isset($input['prestamos']) ? $input['prestamos'] : array();
        $pagoCreado = null;
        foreach ($prestamos_liquidados as $prestamo_liquidado) {
            $pl_info = explode('-', $prestamo_liquidado);
            $prestamo->prestamos_liquidados()->attach($pl_info[0], array("monto" => $pl_info[1]));
            $pl = prestamo::findOrFail($pl_info[0]);
            $pl->estado_prestamo_id = 2;
            $pl->save();
            $saldoAnterior = $pl->saldoAnterior();
            $interes = $pl->getInteres();
            $mora = $pl->getMora();
            $multa = $pl->getMulta();
            $pago = [
                "capital" => $saldoAnterior,
                "interes" => $interes,
                "mora" => $mora,
                "multa" => $multa,
                "prestamo_id" => $pl->id,
                "saldo" => 0,
                "fecha" => $input['fecha'],
                "cobrador_id" => array_key_exists('cobrador_id', $input) ? $input['cobrador_id'] : null,
                "capital_pendiente" => 0,
                "interes_pendiente" => 0,
                "interes_mora_pendiente" => 0,
                "multa_pendiente" => 0
            ];
            $pagoCreado = $pl->pagos()->create($pago);
        }

        $gastos = $prestamo->getDescuento($prestamos_liquidados);

        $prestamo->descuento = $gastos['totalDescuento'];
        $prestamo->liquido = $gastos['totalLiquido'];
        $prestamo->save();
        
        // Generar factura siempre que haya gastos o pago creado
        if($pagoCreado){
            // Si hay pago creado (liquidación), incluir todos los conceptos
            $this->generarFacturaMH($pagoCreado, $prestamo);
        } else {
            // Si es préstamo nuevo sin liquidación, solo incluir gastos
            $this->generarFacturaSoloGastos($prestamo);
        }

        return redirect('clientes/prestamos/' . $prestamo->cliente_id);
    }

    public function getPrestamo($id)
    {
        $data['cliente'] = cliente::findOrFail($id);
        $data['clientes'] = cliente::where('id', '!=', $id)->get();
        $data['lineas'] = linea::all();
        $data['asesores'] = Asesor::all();
        $data['cobradores'] = Cobrador::all();
        $data['prestamos'] = prestamo::where('cliente_id', $id)->where('estado_prestamo_id', '!=', 4)->orderBy("id", "desc")->get();
        $data['prestamos_activos'] = prestamo::where('cliente_id', $id)->where('estado_prestamo_id', 1)->get();
        $data['fecha'] = $carbon = new Carbon();
        return view('principal.cliente.prestamo')->with($data);
    }

    public function getGastos(Request $request)
    {
        $input = $request->all();
        $tipo_gastos = tipo_gasto::
            where('linea_id', $input['linea_id'])
            ->where('monto_min', '<=', $input['monto'])
            ->where('monto_max', '>=', $input['monto'])
            ->get();
        return Response::json($tipo_gastos);
    }

    public function getMunicipios(Request $request)
    {
        $input = $request->all();
        $municipios = municipio::
            where('departamento_id', $input['departamento_id'])
            ->get();
        return Response::json($municipios);
    }

    public function getMHMunicipios(Request $request)
    {
        $input = $request->all();
        
        \Log::info('getMHMunicipios - departamento_id recibido: ' . $input['departamento_id']);
        
        // El JavaScript envía el código del departamento, no el ID
        $departamento = mh_departamento::where('codigo', $input['departamento_id'])->first();
        
        if ($departamento) {
            \Log::info('Departamento encontrado: ' . $departamento->nombre . ' (id=' . $departamento->id . ', codigo=' . $departamento->codigo . ')');
            $municipios = $departamento->municipios;
            \Log::info('Municipios encontrados: ' . $municipios->count());
        } else {
            \Log::warning('Departamento no encontrado con codigo: ' . $input['departamento_id']);
            $municipios = collect();
        }
        
        return Response::json($municipios);
    }

    public function verificarDui(Request $request)
    {
        $input = $request->all();
        $cliente = cliente::where('dui', $input['dui'])->count();
        if ($cliente > 0) {
            return Response::json(true);
        } else {
            return Response::json(false);
        }
    }

    public function pdfPagareSinProtesto($id)
    {
        Lang::setLocale('es');
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['prestamo'] = prestamo::findOrFail($id);
        $data['titulo'] = '<h1>Test</h1>';
        $pdf = PDF::loadView('pdf.pagare_sin_protesto', $data);
        return $pdf->download($carbon->format('dmYHis') . 'pagare_sin_protesto.pdf');
    }

    public function pdfHojaLiquidacion($id)
    {
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['prestamo'] = prestamo::findOrFail($id);
        $data['titulo'] = '<h1>Test</h1>';
        $pdf = PDF::loadView('pdf.hoja_liquidacion', $data);
        return $pdf->download($carbon->format('dmYHis') . 'hoja_liquidacion.pdf');
    }

    public function pdfFicha($id)
    {
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['cliente'] = cliente::findOrFail($id);
        $data['titulo'] = '<h1>Ficha</h1>';
        $pdf = PDF::loadView('pdf.ficha', $data);
        return $pdf->download($carbon->format('dmYHis') . 'ficha.pdf');
    }

    public function anularPrestamo(Request $request)
    {
        $prestamo = prestamo::findOrFail($request->prestamo_id);
        if ($prestamo->pagos->count() > 0) {
            return "Este prestamo no puede anularse, porque ya tiene registrado pagos";
        } else {
            $prestamo->estado_prestamo_id = 4;
            $prestamo->save();
            return "prestamo anulado";
        }
    }

    public function actualizarGarantia(Request $request)
    {
        $prestamo = prestamo::findOrFail($request->prestamo_id);
        $prestamo->garantia = trim($request->garantia);
        $prestamo->save();
        return "garantia actualizada";
    }

    public function getHistorial($id)
    {
        $data['cliente'] = cliente::findOrFail($id);
        $data["prestamos"] = prestamo::where("cliente_id", $id)->whereNotIn("estado_prestamo_id", [4])->orderBy("id", "desc")->get();
        return view('principal.cliente.historial')->with($data);
    }

    public function getGarantias($id)
    {
        $data['cliente'] = cliente::findOrFail($id);
        $data["prestamos"] = prestamo::where("cliente_id", $id)->whereNotIn("estado_prestamo_id", [4])->orderBy("id", "desc")->get();
        return view('principal.cliente.garantia')->with($data);
    }

    public function cumpleaneros($mes = 0)
    {
        if ($mes == 0)
            return redirect("cumpleaneros/" . Date("n"));
        $data['clientes'] = cliente::whereRaw("MONTH(fecha_nacimiento) = {$mes}")
            ->whereRaw("id in (SELECT cliente_id FROM prestamos WHERE estado_prestamo_id = 1)")
            ->orderByRaw("DAYOFMONTH(fecha_nacimiento)")
            ->get();
        $data["mes"] = $mes;
        return view('principal.cliente.cumpleaneros')->with($data);
    }

    function generarFacturaMH($pago, $prestamo){
        $descripciones = [];
        $cantidades = [];
        $unidades = [];
        $precios = [];
        $tipo = [];
        $descuento = [];
        $no_suj = [];
        $exenta = [];
        
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

        if($pago->mora>0){
            $descripciones[] = "Mora";
            $cantidades[] = 1;
            $unidades[] = 99;
            $precios[] = $pago->mora;
            $tipo[] = 1;
            $descuento[] = null;
            $no_suj[] = 0.00;
            $exenta[] = 0.00;
        }

        if($pago->multa>0){
            $descripciones[] = "Multa";
            $cantidades[] = 1;
            $unidades[] = 99;
            $precios[] = $pago->multa;
            $tipo[] = 1;
            $descuento[] = null;
            $no_suj[] = 0.00;
            $exenta[] = 0.00;
        }

        // Obtener los gastos directamente del préstamo y sumar sus montos
        $totalGastos = 0;
        foreach($prestamo->gastos as $gasto){
            $totalGastos += $gasto->monto;
        }
        
        \Log::info('Total gastos del prestamo ID ' . $prestamo->id . ': ' . $totalGastos);
        
        // Agregar gastos como un solo item sumando todos los trámites
        if($totalGastos > 0){
            $descripciones[] = "Tramite";
            $cantidades[] = 1;
            $unidades[] = 99;
            $precios[] = $totalGastos; // Precio incluye IVA
            $tipo[] = 1;
            $descuento[] = null;
            $no_suj[] = 0.00;
            $exenta[] = 0.00;
        }
        
        \Log::info('Items generados para factura: ' . json_encode(['descripciones' => $descripciones, 'precios' => $precios]));
        
        return $this->mhService->generarFactura($pago->prestamo->cliente,$descripciones,$cantidades,$precios,$tipo,$unidades,$descuento,$no_suj,$exenta); 
    }

    function generarFacturaSoloGastos($prestamo){
        $descripciones = [];
        $cantidades = [];
        $unidades = [];
        $precios = [];
        $tipo = [];
        $descuento = [];
        $no_suj = [];
        $exenta = [];
        
        // Obtener los gastos directamente del préstamo y sumar sus montos
        $totalGastos = 0;
        foreach($prestamo->gastos as $gasto){
            $totalGastos += $gasto->monto;
        }
        
        \Log::info('Total gastos del prestamo nuevo ID ' . $prestamo->id . ': ' . $totalGastos);
        
        // Solo agregar gastos si hay
        if($totalGastos > 0){
            $descripciones[] = "Tramite";
            $cantidades[] = 1;
            $unidades[] = 99;
            $precios[] = $totalGastos; // Precio incluye IVA
            $tipo[] = 1;
            $descuento[] = null;
            $no_suj[] = 0.00;
            $exenta[] = 0.00;
            
            \Log::info('Generando factura solo gastos para cliente: ' . $prestamo->cliente->nombreCompleto());
            return $this->mhService->generarFactura($prestamo->cliente,$descripciones,$cantidades,$precios,$tipo,$unidades,$descuento,$no_suj,$exenta);
        }
        
        return null;
    }

}
