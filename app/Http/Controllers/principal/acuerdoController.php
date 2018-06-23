<?php

namespace App\Http\Controllers\principal;

use App\Http\Requests;
use App\Http\Requests\principal\CreateacuerdoRequest;
use App\Http\Requests\principal\UpdateacuerdoRequest;
use App\Models\principal\acuerdo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class acuerdoController extends Controller
{

    function __construct()
    {
        // $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('principal.pago.acuerdos');
    }

    public function create()
    {
        return view('catalogos.cobrador.create');
    }

    public function store(CreateacuerdoRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $cobrador = acuerdo::create($emptyRemoved);
        return redirect(route('cobradores.index'));
    }

    public function show($id)
    {
        $cobrador = acuerdo::findOrFail($id);
        return view('catalogos.cobrador.show')->with('cobrador', $cobrador);
    }

    public function edit($id)
    {
        $data['cobrador'] = acuerdo::findOrFail($id);
        return view('catalogos.cobrador.edit')->with($data);
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
        return Datatables::of(acuerdo::all())->make(true);
    }

}
