@extends('layouts.master')
@section('title')
Cliente
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'principal\clienteController@update', 
									'id' => array('id' => $cliente->id),
									'include' => 'principal.cliente.fields'
									))
@endsection