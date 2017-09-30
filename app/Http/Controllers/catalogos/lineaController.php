<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Requests;
use App\Models\catalogos\linea;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class lineaController extends Controller
{

    function __construct()
    {
        // $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('catalogos.linea.index');
    }

    public function create()
    {
        return view('catalogos.linea.create');
    }

    public function store(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $linea = linea::create($emptyRemoved);
        return redirect('linea');
    }

    public function show($id)
    {
        $linea = linea::findOrFail($id);
        return view('catalogos.linea.show')->with('linea', $linea);
    }

    public function edit($id)
    {
        $data['linea'] = linea::findOrFail($id);
        return view('catalogos.linea.edit')->with($data);
    }

    public function update($id, Request $request)
    {
        $linea = linea::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $linea = linea::where('id', $id)->update($output);
        return redirect('linea');
    }

     public function destroy($id)
    {
        $linea = linea::findOrFail($id);
        $result = $linea->delete($id);
        return redirect('linea');
    }

    public function restore($id)
    {
        $linea = linea::onlyTrashed()->where('id', $id)->firstOrFail();
        $linea->restore();
        return redirect('linea');
    }

    public function getDataTable()
    {
        return Datatables::of(linea::all())->make(true);
    }

}
