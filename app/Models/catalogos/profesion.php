<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class profesion extends Model
{
    public $table = "profesiones";
    public $timestamps = false;
    public $fillable = [
        "nombre"
    ];
    protected $casts = [];
    public static $rules = [];
}
