<?php

namespace App\Http\Controllers\principal;

use App\Http\Requests;
use App\Models\principal\pago;
use App\Models\principal\prestamo;
use App\Models\principal\cliente;
use App\Models\catalogos\linea;
use App\Models\catalogos\cobrador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Datatables;
use Carbon\Carbon;

class variosController extends Controller
{	
	public function corteCaja()
    {
    	$data=[];
        return view('principal.caja.corte_caja')->with($data);
    }

    public function colectasSaldos()
    {
    	$data['asesores']=[];
    	$data['prestamos']=prestamo::where("estado_prestamo_id",1)->get();
        return view('principal.cobros.colectas_saldos')->with($data);
    }
}