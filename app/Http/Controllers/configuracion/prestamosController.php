<?php
namespace App\Http\Controllers\configuracion;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\principal\prestamo;
use App\Models\catalogos\asesor;
use App\Models\catalogos\cobrador;
use App\Models\catalogos\zona;
use App\Models\catalogos\linea;
use App\Models\catalogos\municipio;
use Response;

class prestamosController extends Controller
{

    function __construct()
    {
        // $this->middleware('menu');
    }

    public function getIndex(Request $request)
    {
    	$data['lineas'] = linea::all();
        $data['zonas'] = Zona::all();
        $data['municipio'] = Municipio::all();
        $data['asesores'] = Asesor::all();
        $data['cobradores'] = Cobrador::all();
        $data['prestamos'] = prestamo::where('estado_prestamo_id','!=',4)->get();
        return view('configuracion.prestamos',$data);
    }
}