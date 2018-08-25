@extends('layouts.master')
@section('title')
Permisos para {{$rol->nombre}}
@stop
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-o font-red"></i> Permisos por Rol</h3>
    </div>
        <form id="create" method="POST" action="{{action('sistema\rolesController@setPermisosRol')}}" autocomplete="off" class="form-horizontal form-with-validation">
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>Verifique que todos los campos sean llenados correctamente
            </div>
            <div class="alert alert-success display-hide">
                <button class="close" data-close="alert"></button>Informacion ingresada correctamente
            </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ $rol->id }}">
                    <div class="box-body">
					@foreach ($permisos as $permiso)
						<input type="checkbox" name="permiso[]" value="{{$permiso->id}}" title="{{$permiso->descripcion}}" {{ isset($permiso->rol_id) ? "checked" : ""}}> {{$permiso->nombre}}
						<br>
					@endforeach
				</div>
					<div class="clearfix"></div>
				<div class="box-footer">
					<a href="{!! route('roles.index') !!}" class="btn btn-default pull-right">Cancelar</a>
					<button type="submit" class="btn btn-info pull-right">Guardar</button>
				</div>
        </form>
</div>


@endsection