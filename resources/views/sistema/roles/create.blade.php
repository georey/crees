@extends('layouts.master')
@section('title')
Crear Rol
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar Rol',
										'action' => 'sistema\rolesController@store',
										'include' => 'sistema.roles.fields'
										))
@endsection