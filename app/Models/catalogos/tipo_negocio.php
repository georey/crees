<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class tipo_negocio extends Model
{
    public $table = "tipo_negocios";
    public $timestamps = false;
    public $fillable = [
        "tipo"
    ];
    protected $casts = [];
    public static $rules = [];
}