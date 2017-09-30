<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class departamento extends Model
{
    public $table = "departamentos";
    public $timestamps = false;
    public $fillable = [
        "nombre"
    ];
    protected $casts = [];
    public static $rules = [];
}
