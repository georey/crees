<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Collection;
use App\Models\sistema\permiso;
use Illuminate\Contracts\Auth\Guard;
use Log;

class menuMiddleware
{
    protected $auth;    
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $permisos = permiso::getPermisos();
        $access = ($request->ajax()) ? 1 : 0;
        $breadcrumb = permiso::getbreadcrumb($request->segment(1));
        $icon = '';
        $parent_icon = '';
        $menu = array();
        foreach ($permisos as $key => $permiso) {
            $parent_icon = $permiso->parent_id > 0 ? $parent_icon : $permiso->icono;
            $icon = isset($permiso->icono) ? $permiso->icono : $parent_icon;
            $menu[$key] = array(
                            'id' => $permiso->id,
                            'nombre' => $permiso->nombre,
                            'descripcion' => $permiso->descripcion,
                            'ruta' => $permiso->ruta,
                            'icono' => $icon,
                            'access' => $permiso->access,
                            'childs' => $permiso->childs,
                                );
            foreach ($breadcrumb as $bc_item) {
                if($menu[$key]['ruta'] == $bc_item->ruta) {
                    $menu[$key]['active'] = 1;
                }
            }

            if ($request->segment(1) == $permiso->ruta || $request->segment(1) == '') {
                $access = 1;
            }
        }

        if (!$request->ajax()) {
            if ($access == 1) {
                Log::info('Navigation. USER: '. $this->auth->user()->username. ' IP: '. $request->ip(). ' URL:'. $request->url());
            } else {
                Log::notice('Forbbiden permission to access. USER: '. $this->auth->user()->username. ' IP: '. $request->ip(). ' URL:'. $request->url());
            }    
        }

        $request->attributes->add(['menu' => $menu, 'breadcrumb' => array_reverse($breadcrumb), 'access' => $access]);
        return $next($request);
    }
    
}