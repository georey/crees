<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Requests;
use App\Http\Requests\catalogos\CreatezonaRequest;
use App\Http\Requests\catalogos\UpdatezonaRequest;
use App\Models\catalogos\zona;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;

class zonaController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {
        return view('catalogos.zona.index');
    }

    public function create()
    {
        return view('catalogos.zona.create');
    }

    public function store(CreatezonaRequest $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $zona = zona::create($emptyRemoved);
        return redirect(route('zonas.index'));
    }

    public function show($id)
    {
        $zona = zona::findOrFail($id);
        return view('catalogos.zona.show')->with('zona', $zona);
    }

    public function edit($id)
    {
        $data['zona'] = zona::findOrFail($id);
        return view('catalogos.zona.edit')->with($data);
    }

    public function update($id, UpdatezonaRequest $request)
    {
        $zona = zona::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $zona = zona::where('id', $id)->update($output);
        return redirect(route('zonas.index'));
    }

     public function destroy($id)
    {
        $zona = zona::findOrFail($id);
        $result = $zona->delete($id);
        return redirect(route('zonas.index'));
    }

    public function restore($id)
    {
        $zona = zona::onlyTrashed()->where('id', $id)->firstOrFail();
        $zona->restore();
        return redirect(route('zonas.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(zona::all())->make(true);
    }

}
