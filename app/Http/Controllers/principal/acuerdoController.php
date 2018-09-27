<?php

namespace App\Http\Controllers\principal;

use App\Http\Requests;
use App\Http\Requests\principal\CreateacuerdoRequest;
use App\Http\Requests\principal\UpdateacuerdoRequest;
use App\Models\principal\acuerdo;
use App\Models\principal\prestamo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;
use Auth;

class acuerdoController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('principal.acuerdos.index');
    }

    public function create()
    {
        $data['prestamos'] = prestamo::getPrestamoActivosCliente();
        return view('principal.acuerdos.create', $data);
    }

    public function store(CreateacuerdoRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $prestamo = prestamo::findOrFail($input["prestamo_id"]);
        $prestamo->update(["tasa" => $input["interes"], "tasa_mora" => $input["tasa_mora"]]);
        $pago = [
            "capital" => 0,
            "interes" => $prestamo->getInteres(),
            "multa" => $prestamo->getMulta(),
            "mora" => $prestamo->getMora(),
            "prestamo_id" => $input["prestamo_id"],
            "interes_pendiente" => $input["interes_pendiente"]
        ];

        if(isset($input["chk_interes"]))
            $pago["interes"] = 0;
        if(isset($input["chk_multa"]))
            $pago["multa"] = 0;
        if(isset($input["chk_mora"]))
            $pago["mora"] = 0;

        $prestamo->pagos()->create($pago);

        $arrAcuerdo = [
                "prestamo_id" => $input["prestamo_id"],
                "user" => Auth::user()->username,
                "justificacion" => $input["justificacion"],
                "monto_anterior" => $input["monto_pendiente"],
                "monto_posterior" => $input["monto_actual"]
            ];
        $acuerdo = acuerdo::create($arrAcuerdo);
        return redirect(route('cobradores.index'));
    }

    public function show($id)
    {
        $cobrador = acuerdo::findOrFail($id);
        return view('catalogos.cobrador.show')->with('cobrador', $cobrador);
    }

    public function edit($id)
    {
        $data['acuerdo'] = acuerdo::findOrFail($id);
        return view('principal.acuerdos.edit')->with($data);
    }

    public function update($id, UpdateacuerdoRequest $request)
    {
        $cobrador = acuerdo::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $cobrador = acuerdo::where('id', $id)->update($output);
        return redirect(route('cobradores.index'));
    }

     public function destroy($id)
    {
        $cobrador = acuerdo::findOrFail($id);
        $result = $cobrador->delete($id);
        return redirect(route('cobradores.index'));
    }

    public function restore($id)
    {
        $cobrador = acuerdo::onlyTrashed()->where('id', $id)->firstOrFail();
        $cobrador->restore();
        return redirect(route('cobradores.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(acuerdo::getAcuerdos())
            ->filterColumn('nombre_completo', function($query, $keyword) {
                                $query->whereRaw("LOWER(clientes.nombre) like LOWER(?) or LOWER(clientes.apellido) like LOWER(?)", ["%{$keyword}%", "%{$keyword}%"]);
                            })
            ->filterColumn('codigo_prestamo', function($query, $keyword) {
                                $query->whereRaw("LOWER(prestamos.codigo) like LOWER(?)", ["%{$keyword}%"]);
                            })
            ->make(true);
    }

}
