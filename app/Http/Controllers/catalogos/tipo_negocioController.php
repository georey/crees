<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Requests;
use App\Models\catalogos\tipo_negocio;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class tipo_negocioController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('catalogos.tipo_negocio.index');
    }

    public function create()
    {
        return view('catalogos.tipo_negocio.create');
    }

    public function store(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $tipo_negocio = tipo_negocio::create($emptyRemoved);
        return redirect(route('tipo_negocio.index'));
    }

    public function show($id)
    {
        $tipo_negocio = tipo_negocio::findOrFail($id);
        return view('catalogos.tipo_negocio.show')->with('tipo_negocio', $tipo_negocio);
    }

    public function edit($id)
    {
        $data['tipo_negocio'] = tipo_negocio::findOrFail($id);
        return view('catalogos.tipo_negocio.edit')->with($data);
    }

    public function update($id, Request $request)
    {
        $tipo_negocio = tipo_negocio::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $tipo_negocio = tipo_negocio::where('id', $id)->update($output);
        return redirect(route('estados.index'));
    }

     public function destroy($id)
    {
        $tipo_negocio = tipo_negocio::findOrFail($id);
        $result = $tipo_negocio->delete($id);
        return redirect(route('estados.index'));
    }

    public function restore($id)
    {
        $tipo_negocio = tipo_negocio::onlyTrashed()->where('id', $id)->firstOrFail();
        $tipo_negocio->restore();
        return redirect(route('estados.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(tipo_negocio::all())->make(true);
    }

}
