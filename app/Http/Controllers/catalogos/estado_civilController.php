<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Requests;
use App\Http\Requests\catalogos\Createestado_civilRequest;
use App\Http\Requests\catalogos\Updateestado_civilRequest;
use App\Models\catalogos\estado_civil;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class estado_civilController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('catalogos.estado_civil.index');
    }

    public function create()
    {
        return view('catalogos.estado_civil.create');
    }

    public function store(Createestado_civilRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $estado_civil = estado_civil::create($emptyRemoved);
        return redirect(route('estados.index'));
    }

    public function show($id)
    {
        $estado_civil = estado_civil::findOrFail($id);
        return view('catalogos.estado_civil.show')->with('estado_civil', $estado_civil);
    }

    public function edit($id)
    {
        $data['estado_civil'] = estado_civil::findOrFail($id);
        return view('catalogos.estado_civil.edit')->with($data);
    }

    public function update($id, Updateestado_civilRequest $request)
    {
        $estado_civil = estado_civil::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $estado_civil = estado_civil::where('id', $id)->update($output);
        return redirect(route('estados.index'));
    }

     public function destroy($id)
    {
        $estado_civil = estado_civil::findOrFail($id);
        $result = $estado_civil->delete($id);
        return redirect(route('estados.index'));
    }

    public function restore($id)
    {
        $estado_civil = estado_civil::onlyTrashed()->where('id', $id)->firstOrFail();
        $estado_civil->restore();
        return redirect(route('estados.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(estado_civil::all())->make(true);
    }

}
