<?php

namespace App\Http\Controllers\principal;

use App\Http\Requests;
use App\Models\principal\pago;
use App\Models\principal\prestamo;
use App\Models\catalogos\linea;
use App\Models\catalogos\cobrador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;
use Carbon\Carbon;
use PDF;

class pagoController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
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
        $input['mora_pendiente'] = $mora_pendiente;
        $input['interes'] = $interes;
        $input['interes_pendiente'] = $interes_pendiente;
        $input['capital'] = $capital;
        $input['capital_pendiente'] = $capital_pendiente < 0 ? 0 : $capital_pendiente;

        $prestamo->pagos()->create($input);

        $prestamo = prestamo::findOrFail($input['prestamo_id']);
        if($prestamo->saldoAnterior() <= 0) {
            $prestamo->estado_prestamo_id = 3;
            $prestamo->save();
        }
        return redirect(route('pagos.create'));
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
        $pago->delete();
        $prestamo = prestamo::where('id', $prestamo_id)->update(["estado_prestamo_id"=>1]);
        return redirect('pagos/historial/'. $prestamo_id);
    }

    public function pdfNotaCobro($id)
    {
        $carbon = new Carbon();
        $data['fecha'] = $carbon;
        $data['prestamo'] = prestamo::findOrFail($id);
        $pdf = PDF::loadView('pdf.nota_cobro', $data);
        return $pdf->download($carbon->format('dmYHis').'nota_cobro.pdf');
    }
}
