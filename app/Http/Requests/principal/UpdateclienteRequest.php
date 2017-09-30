<?php

namespace App\Http\Requests\principal;

use App\Http\Requests\Request;
use App\Models\principal\cliente;

class UpdateclienteRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return cliente::$rules;
    }
}
