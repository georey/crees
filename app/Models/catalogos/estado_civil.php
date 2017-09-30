<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class estado_civil extends Model
{
    public $table = "estados_civiles";
    public $timestamps = false;
    public $fillable = [
        "nombre"
    ];
    protected $casts = [];
    public static $rules = [];
}