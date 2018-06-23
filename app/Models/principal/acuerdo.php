<?php

namespace App\Models\principal;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Eloquent as Model;

class acuerdo extends Model
{
    // use SoftDeletes;

    public $table = "acuerdos";

    protected $primaryKey = 'id';

    public $fillable = [
        "prestamo_id", "user", "fecha", "justificacion", "monto_anterior", "monto_posterior"
    ];

    protected $casts = [];

    public static $rules = [];
    
}
