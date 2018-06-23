<?php

namespace App\Http\Requests\principal;

use App\Http\Requests\Request;
use App\Models\principal\acuerdo;

class CreateacuerdoRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return acuerdo::$rules;
    }
}
