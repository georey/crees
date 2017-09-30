@extends('layouts.master')
@section('title')
Cliente
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar cliente',
										'action' => 'principal\clienteController@store',
										'include' => 'principal.cliente.fields'
										))
@endsection