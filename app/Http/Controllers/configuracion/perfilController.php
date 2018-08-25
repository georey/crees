<?php
namespace App\Http\Controllers\configuracion;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Response;
use File;
use Auth;

class perfilController extends Controller
{

    function __construct()
    {
        $this->middleware('menu');
    }

    public function getIndex(Request $request)
    {        
        return view('configuracion.perfil');
    }

    public function postIndex(Request $request)
    {
        $input = array_except($request->all(), ['_method', '_token']);
        $emptyRemoved = array_filter($input);
        $files = $input['attachtment'];
        $file = $files[0];
        $path = 'uploads/perfil/' . Auth::user()->id . '/';
        $full_path = base_path() . '/public/' . $path;
        $imagen = "";
        $usuario = [];
        if (isset($file)) {
            $fileName = strtolower(Auth::user()->username . '.' . $file->getClientOriginalExtension());
            if (!File::exists($full_path)) {
                $file->move($full_path , $fileName );
            }
            else {
                $file->move($full_path , $fileName );
            }
            $usuario["imagen"] = $path . $fileName;
        }

        if(array_key_exists("password", $emptyRemoved))
            $usuario["password"] = Hash::make($emptyRemoved["password"]);
        if(array_key_exists("username", $emptyRemoved))
            $usuario["username"] = $emptyRemoved["username"];

        $usuario = User::where('id', Auth::user()->id)->update($usuario);
        return redirect("configuracion/perfil");
    }
}