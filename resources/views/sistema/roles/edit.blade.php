@extends('layouts.master')
@section('title')
Editar Rol
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar Rol',
									'action' => 'sistema\rolesController@update', 
									'id' => array('id' => $rol->id),
									'include' => 'sistema.roles.fields'
									))
@endsection