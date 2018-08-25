<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Requests;
use App\Models\catalogos\tipo_gasto;
use App\Models\catalogos\linea;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class tipo_gastoController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('catalogos.tipo_gasto.index');
    }

    public function create()
    {
        $data['lineas'] = linea::all();
        return view('catalogos.tipo_gasto.create')->with($data);
    }

    public function store(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $tipo_gasto = tipo_gasto::create($emptyRemoved);
        return redirect(route('tipo_gasto.index'));
    }

    public function show($id)
    {
        $tipo_gasto = tipo_gasto::findOrFail($id);
        return view('catalogos.tipo_gasto.show')->with('tipo_gasto', $tipo_gasto);
    }

    public function edit($id)
    {
        $data['tipo_gasto'] = tipo_gasto::findOrFail($id);
        $data['lineas'] = linea::all();
        return view('catalogos.tipo_gasto.edit')->with($data);
    }

    public function update($id, Request $request)
    {
        $tipo_gasto = tipo_gasto::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $tipo_gasto = tipo_gasto::where('id', $id)->update($output);
        return redirect(route('tipo_gasto.index'));
    }

     public function destroy($id)
    {
        $tipo_gasto = tipo_gasto::findOrFail($id);
        $result = $tipo_gasto->delete($id);
        return redirect(route('estados.index'));
    }

    public function restore($id)
    {
        $tipo_gasto = tipo_gasto::onlyTrashed()->where('id', $id)->firstOrFail();
        $tipo_gasto->restore();
        return redirect(route('estados.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(tipo_gasto::ListaGastos())->make(true);
    }

}
