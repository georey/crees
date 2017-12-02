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
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;
use Carbon\Carbon;
use PDF;
use Lang;

class clienteController extends Controller
{

    function __construct()
    {
        // $this->middleware('menu');
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
        return view('principal.cliente.create')->with($data);
    }

    public function store(CreateclienteRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $input['fecha_nacimiento'] = Carbon::createFromFormat('d-m-Y', $input['fecha_nacimiento']);
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
        return view('principal.cliente.edit')->with($data);
    }

    public function update($id, UpdateclienteRequest $request)
    {
        $cliente = cliente::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $input['fecha_nacimiento'] = Carbon::createFromFormat('d-m-Y', $input['fecha_nacimiento']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
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
                        ->filterColumn('nombre_completo', function($query, $keyword) {
                            $query->whereRaw("LOWER(clientes.nombre) like LOWER(?) or LOWER(clientes.apellido) like LOWER(?)", ["%{$keyword}%", "%{$keyword}%"]);
                        })
                        ->make(true);
    }

    public function negocioSave(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $negocio = negocio::create($emptyRemoved);
        return redirect('clientes/negocios/'.$negocio->cliente_id);
    }

    public function negocioDelete($id)
    {
        $negocio = negocio::findOrFail($id);
        $cliente = $negocio->cliente_id;
        $result = $negocio->delete($id);
        return redirect('clientes/negocios/'.$cliente);
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
		if(isset($input['fiadores']))
			$fiadores = is_array ($input['fiadores']) ? $input['fiadores'] : array();
		else
			$fiadores = array();
		if(isset($input['gastos']))
			$gastos = is_array ($input['gastos']) ? $input['gastos'] : array();
		else
			$gastos = array();
		$input['fecha'] = Carbon::createFromFormat('d-m-Y', $input['fecha']);
        $emptyRemoved = array_filter($input);
        $prestamo = prestamo::create($emptyRemoved);
        $codigo_cliente = str_pad($prestamo->cliente_id, 5, "0", STR_PAD_LEFT);
        $codigo_prestamo = str_pad(prestamo::where('cliente_id', $prestamo->cliente_id)->where('estado_prestamo_id','!=',4)->count(), 2, "0", STR_PAD_LEFT);
        $prestamo->codigo = $codigo_cliente . '01' . $prestamo->linea->nombre . $codigo_prestamo;
        $prestamo->fiadores()->sync($fiadores);
        $prestamo->gastos()->sync($gastos);

        $prestamos_liquidados = isset($input['prestamos']) ? $input['prestamos'] : array();
        foreach ($prestamos_liquidados as $prestamo_liquidado) {
            $pl_info = explode('-', $prestamo_liquidado);
            $prestamo->prestamos_liquidados()->attach($pl_info[0], array("monto"=> $pl_info[1]));
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
                "cobrador_id" => array_key_exists('cobrador_id', $input) ? $input['cobrador_id']: null,
                "capital_pendiente" => 0,
                "interes_pendiente" => 0,
                "interes_mora_pendiente" => 0,
                "multa_pendiente" => 0
            ];
            $pl->pagos()->create($pago);
        }

        $gastos = $prestamo->getDescuento($prestamos_liquidados);

        $prestamo->descuento = $gastos['totalDescuento'];
        $prestamo->liquido = $gastos['totalLiquido'];
        $prestamo->save();

        return redirect('clientes/prestamos/'.$prestamo->cliente_id);
    }

    public function getPrestamo($id)
    {
        $data['cliente'] = cliente::findOrFail($id);
        $data['clientes'] =cliente::where('id','!=', $id)->get();
        $data['lineas'] = linea::all();
        $data['asesores'] = Asesor::all();
        $data['cobradores'] = Cobrador::all();
        $data['prestamos'] = prestamo::where('cliente_id', $id)->where('estado_prestamo_id','!=',4)->get();
        $data['prestamos_activos'] = prestamo::where('cliente_id', $id)->where('estado_prestamo_id',1)->get();
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
        return $pdf->download($carbon->format('dmYHis').'pagare_sin_protesto.pdf');
    }

    public function pdfHojaLiquidacion($id)
    {
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['prestamo'] = prestamo::findOrFail($id);
        $data['titulo'] = '<h1>Test</h1>';
        $pdf = PDF::loadView('pdf.hoja_liquidacion', $data);
        return $pdf->download($carbon->format('dmYHis').'hoja_liquidacion.pdf');
    }

    public function pdfFicha($id)
    {
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['cliente'] = cliente::findOrFail($id);
        $data['titulo'] = '<h1>Test</h1>';
        $pdf = PDF::loadView('pdf.ficha', $data);
        return $pdf->download($carbon->format('dmYHis').'ficha.pdf');
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

    public function getHistorial($id)
    {
        $data['cliente'] = cliente::findOrFail($id);
        $data["prestamos"] = prestamo::where("cliente_id",$id)->whereNotIn("estado_prestamo_id",[4])->get();
        return view('principal.cliente.historial')->with($data);
    }

}
