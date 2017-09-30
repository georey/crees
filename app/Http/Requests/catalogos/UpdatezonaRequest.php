<?php

namespace App\Http\Requests\catalogos;

use App\Http\Requests\Request;
use App\Models\catalogos\zona;

class UpdatezonaRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return zona::$rules;
    }
}
