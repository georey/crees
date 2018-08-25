<?php namespace App\Http\Controllers\sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\sistema\permiso;
use Carbon\Carbon;
use Datatables;

class permisosController extends Controller {

	function __construct()
    {
        // $this->middleware('menu');
    }

    public function index(Request $request)
    {    	
        return view('sistema.permisos.index');
    }

    public function create()
    {
        $data["parents"] = permiso::whereNull('parent_id')->get();
        return view('sistema.permisos.create')->with($data);
    }

    public function store(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $permiso = permiso::create($emptyRemoved);
        return redirect(route('permisos.index'));
    }

    public function show($id)
    {
        $permiso = permiso::findOrFail($id);
        return view('sistema.permisos.show')->with('permiso', $permiso);
    }

    public function edit($id)
    {
        $data['permiso'] = permiso::findOrFail($id);
        $data["parents"] = permiso::whereNull('parent_id')->get();
        return view('sistema.permisos.edit')->with($data);
    }

    public function update($id, Request $request)
    {
        $permiso = permiso::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $permiso = permiso::where('id', $id)->update($output);
        return redirect(route('permisos.index'));
    }

     public function destroy($id)
    {
        $permiso = permiso::findOrFail($id);
        $result = $permiso->delete($id);
        return redirect(route('permisos.index'));
    }

    public function restore($id)
    {
        $permiso = permiso::onlyTrashed()->where('id', $id)->firstOrFail();
        $permiso->restore();
        return redirect(route('permisos.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(permiso::all())->make(true);
    }    
}