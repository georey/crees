<?php

namespace App\Http\Requests\catalogos;

use App\Http\Requests\Request;
use App\Models\catalogos\estado_civil;

class Createestado_civilRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return estado_civil::$rules;
    }
}
