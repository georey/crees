<?php

namespace App\Http\Requests\catalogos;

use App\Http\Requests\Request;
use App\Models\catalogos\asesor;

class CreateasesorRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return asesor::$rules;
    }
}
