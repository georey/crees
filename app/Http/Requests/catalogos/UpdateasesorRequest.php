<?php

namespace App\Http\Requests\catalogos;

use App\Http\Requests\Request;
use App\Models\catalogos\asesor;

class UpdateasesorRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return asesor::$rules;
    }
}
