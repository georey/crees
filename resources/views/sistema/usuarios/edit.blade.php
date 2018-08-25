@extends('layouts.master')
@section('title')
Editar Usuario
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar Usuario',
									'action' => 'sistema\usuariosController@update', 
									'id' => array('id' => $usuario->id),
									'include' => 'sistema.usuarios.fields'
									))
@endsection