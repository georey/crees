<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Requests;
use App\Http\Requests\catalogos\CreatecobradorRequest;
use App\Http\Requests\catalogos\UpdatecobradorRequest;
use App\Models\catalogos\cobrador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class cobradorController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('catalogos.cobrador.index');
    }

    public function create()
    {
        return view('catalogos.cobrador.create');
    }

    public function store(CreatecobradorRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $cobrador = cobrador::create($emptyRemoved);
        return redirect(route('cobradores.index'));
    }

    public function show($id)
    {
        $cobrador = cobrador::findOrFail($id);
        return view('catalogos.cobrador.show')->with('cobrador', $cobrador);
    }

    public function edit($id)
    {
        $data['cobrador'] = cobrador::findOrFail($id);
        return view('catalogos.cobrador.edit')->with($data);
    }

    public function update($id, UpdatecobradorRequest $request)
    {
        $cobrador = cobrador::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $cobrador = cobrador::where('id', $id)->update($output);
        return redirect(route('cobradores.index'));
    }

     public function destroy($id)
    {
        $cobrador = cobrador::findOrFail($id);
        $result = $cobrador->delete($id);
        return redirect(route('cobradores.index'));
    }

    public function restore($id)
    {
        $cobrador = cobrador::onlyTrashed()->where('id', $id)->firstOrFail();
        $cobrador->restore();
        return redirect(route('cobradores.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(cobrador::ListaCobradores())
        ->filterColumn('nombre_completo', function($query, $keyword) {
                                                                    $query->whereRaw("LOWER(CAST(asesores.nombre as TEXT)) like ? or LOWER(CAST(asesores.apellido as TEXT)) like ?", ["%{$keyword}%", "%{$keyword}%"]);
                                                                })
        ->make(true);
    }

}
