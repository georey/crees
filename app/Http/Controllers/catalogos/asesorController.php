<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Requests;
use App\Http\Requests\catalogos\CreateasesorRequest;
use App\Http\Requests\catalogos\UpdateasesorRequest;
use App\Models\catalogos\asesor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class asesorController extends Controller
{

    function __construct()
    {
        // $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('catalogos.asesor.index');
    }

    public function create()
    {
        return view('catalogos.asesor.create');
    }

    public function store(CreateasesorRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $asesor = asesor::create($emptyRemoved);
        return redirect(route('asesores.index'));
    }

    public function show($id)
    {
        $asesor = asesor::findOrFail($id);
        return view('catalogos.asesor.show')->with('asesor', $asesor);
    }

    public function edit($id)
    {
        $data['asesor'] = asesor::findOrFail($id);
        return view('catalogos.asesor.edit')->with($data);
    }

    public function update($id, UpdateasesorRequest $request)
    {
        $asesor = asesor::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $asesor = asesor::where('id', $id)->update($output);
        return redirect(route('asesores.index'));
    }

     public function destroy($id)
    {
        $asesor = asesor::findOrFail($id);
        $result = $asesor->delete($id);
        return redirect(route('asesores.index'));
    }

    public function restore($id)
    {
        $asesor = asesor::onlyTrashed()->where('id', $id)->firstOrFail();
        $asesor->restore();
        return redirect(route('asesores.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(asesor::ListaAsesores())
        ->filterColumn('nombre_completo', function($query, $keyword) {
                                                                    $query->whereRaw("LOWER(CAST(asesores.nombre as TEXT)) like ? or LOWER(CAST(asesores.apellido as TEXT)) like ?", ["%{$keyword}%", "%{$keyword}%"]);
                                                                })
        ->make(true);
    }

}
