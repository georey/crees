<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class zona extends Model
{
    public $table = "zonas";
    public $timestamps = false;
    public $fillable = [
        "nombre"
    ];
    protected $casts = [];
    public static $rules = [];
}
