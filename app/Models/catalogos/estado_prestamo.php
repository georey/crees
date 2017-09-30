<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class estado_prestamo extends Model
{
    public $table = "estados_prestamo";
    public $timestamps = false;
    public $fillable = [
        "nombre"
    ];
    protected $casts = [];
    public static $rules = [];
}