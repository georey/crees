<?php
namespace App\Http\Controllers\configuracion;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class prestamosController extends Controller
{

    function __construct()
    {
        // $this->middleware('menu');
    }

    public function getIndex(Request $request)
    {
        return view('catalogos.asesor.index');
    }
}