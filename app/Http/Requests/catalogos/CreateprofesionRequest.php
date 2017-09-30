<?php

namespace App\Http\Requests\catalogos;

use App\Http\Requests\Request;
use App\Models\catalogos\profesion;

class CreateprofesionRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return profesion::$rules;
    }
}
