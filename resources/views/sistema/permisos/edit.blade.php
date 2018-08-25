@extends('layouts.master')
@section('title')
Editar Permiso
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar Permiso',
									'action' => 'sistema\permisosController@update', 
									'id' => array('id' => $permiso->id),
									'include' => 'sistema.permisos.fields'
									))
@endsection