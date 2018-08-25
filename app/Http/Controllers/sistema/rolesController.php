<?php namespace App\Http\Controllers\sistema;

use App\Http\Controllers\Controller;
use App\Models\sistema\rol;
use App\Models\sistema\permiso;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Datatables;

class rolesController extends Controller {

	function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {    	
        return view('sistema.roles.index');
    }

    public function create()
    {
        return view('sistema.roles.create');
    }

    public function store(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $rol = rol::create($emptyRemoved);
        return redirect(route('roles.index'));
    }

    public function show($id)
    {
        $rol = rol::findOrFail($id);
        return view('sistema.roles.show')->with('rol', $rol);
    }

    public function edit($id)
    {
        $data['rol'] = rol::findOrFail($id);
        return view('sistema.roles.edit')->with($data);
    }

    public function update($id, Request $request)
    {
        $rol = rol::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $output = array_map(function($item) { return empty($item) ? '': $item; }, $input);
        $rol = rol::where('id', $id)->update($output);
        return redirect(route('roles.index'));
    }

     public function destroy($id)
    {
        $rol = rol::findOrFail($id);
        $result = $rol->delete($id);
        return redirect(route('roles.index'));
    }

    public function restore($id)
    {
        $rol = rol::onlyTrashed()->where('id', $id)->firstOrFail();
        $rol->restore();
        return redirect(route('roles.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(rol::all())->make(true);
    }    

    public function permisosxrol($id)
    {
        $data['rol'] = rol::findOrFail($id);
        $data['permisos'] = permiso::getPermisosRol($id);
        return view('sistema.roles.permisosxrol')->with($data);
    }

    public function setPermisosRol(Request $request)
    {        
        $input = array_except($request->all(), ['_method', '_token']);
        $rol = rol::findOrFail($input["id"]);
        $rol->permisos()->sync($input["permiso"]);
        
        return redirect('roles/permisosxrol/'.$input["id"]);
    }
}