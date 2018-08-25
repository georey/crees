<?php namespace App\Http\Controllers\sistema;

use App\Http\Controllers\Controller;
use App\user;
use App\models\sistema\rol;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Datatables;
use Illuminate\Support\Facades\Hash;

class usuariosController extends Controller {

	function __construct()
    {
        $this->middleware('menu');
    }

    public function index(Request $request)
    {    	
        return view('sistema.usuarios.index');
    }

    public function create()
    {
        $data['roles'] = rol::all();
        return view('sistema.usuarios.create')->with($data);
    }

    public function store(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $emptyRemoved["password"] = Hash::make($emptyRemoved["password"]);
        $usuario = user::create($emptyRemoved);
        return redirect(route('usuarios.index'));
    }

    public function show($id)
    {
        $usuario = user::findOrFail($id);
        return view('sistema.usuarios.show')->with('usuario', $usuario);
    }

    public function edit($id)
    {
        $data['usuario'] = user::findOrFail($id);
        $data['roles'] = rol::all();
        return view('sistema.usuarios.edit')->with($data);
    }

    public function update($id, Request $request)
    {
        $usuario = user::findOrFail($id);
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        if(array_key_exists("password", $emptyRemoved))
            $emptyRemoved["password"] = Hash::make($emptyRemoved["password"]);
        $usuario = user::where('id', $id)->update($emptyRemoved);
        return redirect(route('usuarios.index'));
    }

     public function destroy($id)
    {
        $usuario = user::findOrFail($id);
        $result = $usuario->delete($id);
        return redirect(route('usuarios.index'));
    }

    public function restore($id)
    {
        $usuario = user::onlyTrashed()->where('id', $id)->firstOrFail();
        $usuario->restore();
        return redirect(route('usuarios.index'));
    }

    public function getDataTable()
    {
        return Datatables::of(user::ListaUsuarios())
        ->filterColumn('nombre_completo', function($query, $keyword) {
                                                                    $query->whereRaw("LOWER(CAST(usuarios.nombre as TEXT)) like ? or LOWER(CAST(usuarios.apellido as TEXT)) like ?", ["%{$keyword}%", "%{$keyword}%"]);
                                                                })
        ->make(true);
    }    
}