<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Requests;
use App\Http\Requests\catalogos\CreateprofesionRequest;
use App\Http\Requests\catalogos\UpdateprofesionRequest;
use App\Models\catalogos\profesion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class profesionController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('catalogos.profesion.index');
    }

    public function create()
    {
        return view('catalogos.profesion.create');
    }

    public function store(CreateprofesionRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $profesion = profesion::create($emptyRemoved);
        return redirect(route('profesiones.index'));
    }

    public function show($id)
    {
        $profesion = profesion::findOrFail($id);
        return view('catalogos.profesion.show')->with('profesion', $profesion);
    }

    public function edit($id)
    {
        $data['profesion'] = profesion::findOrFail($id);
        return view('catalogos.profesion.edit')->with($data);
    }

    public function update($id, UpdateprofesionRequest $request)
    {
        $profesion = profesion::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $profesion = profesion::where('id', $id)->update($output);
        return redirect(route('profesiones.index'));
    }

     public function destroy($id)
    {
        $profesion = profesion::findOrFail($id);
        $result = $profesion->delete($id);
        return redirect(route('profesiones.index'));
    }

    public function restore($id)
    {
        $profesion = profesion::onlyTrashed()->where('id', $id)->firstOrFail();
        $profesion->restore();
        return redirect(route('profesiones.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(profesion::all())->make(true);
    }

}
