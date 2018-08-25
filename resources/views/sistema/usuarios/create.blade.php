@extends('layouts.master')
@section('title')
Agregar Usuario
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar usuario',
										'action' => 'sistema\usuariosController@store',
										'include' => 'sistema.usuarios.fields'
										))
@endsection