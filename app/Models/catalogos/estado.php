<?php
namespace App\Models\catalogos;

use Eloquent as Model;

class estado extends Model
{
    public $table = "estados";
    public $timestamps = false;
    public $fillable = [
        "nombre"
    ];
    protected $casts = [];
    public static $rules = [];
}