<?php
namespace App\Models\sistema;

use Eloquent as Model;
use Auth;
use DB;

class permiso extends Model
{
    public $table = "permisos";
    public $timestamps = false;
    public $fillable = [
        "nombre","ruta","descripcion","icono","parent_id","order_by"
    ];
    protected $casts = [];
    public static $rules = [];

    public static function getChilds($id)
    {
        return permiso::where("parent_id", $id)->get()->toArray();
    }

    public static function getPermisos()
    {
        $rol_id = Auth::user()->rol_id;
        $select =   "SELECT *
                    FROM (
                            SELECT 
                                 permisos.id AS id                        
                                ,permisos.ruta AS ruta
                                ,permisos.nombre AS nombre
                                ,permisos.descripcion AS descripcion
                                ,coalesce(permisos.parent_id,0) AS parent_id
                                ,permisos.icono AS icono
                                ,coalesce(permisosxrol.rol_id, 0) AS access
                                ,(SELECT COUNT(submod.id) FROM permisos AS submod WHERE submod.parent_id = permisos.id) AS childs
                                ,permisos.order_by
                            FROM permisos                    
                            INNER JOIN permisosxrol ON permisos.id = permisosxrol.permiso_id AND permisosxrol.rol_id = $rol_id
                        ) t
                    ORDER BY t.order_by";
        $permiso = DB::select($select);
        return $permiso;
    }

    public static function getbreadcrumb($url)
    {
        $select =   "WITH RECURSIVE TREE(id, ruta, parent_id) as 
                    (
                        SELECT permisos.id, permisos.ruta, permisos.parent_id
                        FROM permisos
                        WHERE permisos.ruta = '$url' 
                        UNION ALL
                        SELECT permisos.id, permisos.ruta,permisos.parent_id
                        FROM permisos                        
                        JOIN tree t ON (permisos.id = t.parent_id)
                    )
                    SELECT DISTINCT *
                    FROM TREE;";

        //$breadcrumb = DB::select($select);
        return [];
    }

    public static function getPermisosRol($rol_id)
    {
    	$permisos = permiso::select('*')                        
                    ->leftJoin('permisosxrol', function($join) use ($rol_id)
                         {
                             $join->on('permisosxrol.permiso_id', '=', 'permisos.id');
                             $join->where('permisosxrol.rol_id','=', $rol_id);
                         })
                    ->get();
        return $permisos;
    }
}
