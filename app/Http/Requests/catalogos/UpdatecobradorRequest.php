<?php

namespace App\Http\Requests\catalogos;

use App\Http\Requests\Request;
use App\Models\catalogos\cobrador;

class UpdatecobradorRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return cobrador::$rules;
    }
}
