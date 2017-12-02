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
        $input = $request->all();
        $data['filtro_asesor_id'] = null;
        $data['filtro_cobrador_id'] = null;
        $data['filtro_linea_id'] = null;
        $data['filtro_zona_id'] = null;
        $prestamos = prestamo
                ::where('estado_prestamo_id','!=',4)
                ->orderBy('estado_prestamo_id')
                ->orderBy('codigo');
        if(array_key_exists('filtro_asesor_id', $input) && $input['filtro_asesor_id'] > 0){
            $data['filtro_asesor_id'] = $input['filtro_asesor_id'];
            $prestamos->where('asesor_id', $input['filtro_asesor_id']);
        }
        if(array_key_exists('filtro_cobrador_id', $input) && $input['filtro_cobrador_id'] > 0){
            $data['filtro_cobrador_id'] = $input['filtro_cobrador_id'];
            $prestamos->where('cobrador_id', $input['filtro_cobrador_id']);
        }
        if(array_key_exists('filtro_linea_id', $input) && $input['filtro_linea_id'] > 0){
            $data['filtro_linea_id'] = $input['filtro_linea_id'];
            $prestamos->where('linea_id', $input['filtro_linea_id']);
        }
        if(array_key_exists('filtro_zona_id', $input) && $input['filtro_zona_id'] > 0){
            $data['filtro_zona_id'] = $input['filtro_zona_id'];
            $prestamos->where('zona_id', $input['filtro_zona_id']);
        }
        


    	$data['lineas'] = linea::all();
        $data['zonas'] = Zona::all();
        $data['municipio'] = Municipio::all();
        $data['asesores'] = Asesor::all();
        $data['cobradores'] = Cobrador::all();
        $data['prestamos'] = $prestamos->get();
        return view('configuracion.prestamos',$data);
    }

    public function postIndex(Request $request)
    {
        $input = $request->all();
        switch ($input['btn_enviar']) {
            case 'asignar_asesor':
                foreach ($input['id'] as $prestamo_id) {
                    $prestamo = prestamo::findOrFail($prestamo_id);
                    $prestamo->asesor_id = $input['asesor_id'];
                    $prestamo->save();
                }
                return redirect("configuracion/prestamos");
                break;

            case 'asignar_cobrador':
                foreach ($input['id'] as $prestamo_id) {
                    $prestamo = prestamo::findOrFail($prestamo_id);
                    $prestamo->cobrador_id = $input['cobrador_id'];
                    $prestamo->save();
                }
                return redirect("configuracion/prestamos");
                break;
            case 'filtrar':
                return $this->getIndex($request);
                break;
            default:
                # code...
                break;
        }
        
    }
}