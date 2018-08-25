@extends('layouts.master')
@section('title')
Crear Permiso
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar Permiso',
										'action' => 'sistema\permisosController@store',
										'include' => 'sistema.permisos.fields'
										))
@endsection