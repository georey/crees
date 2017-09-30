@extends('layouts.master')
@section('title')
Lineas
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar linea',
										'action' => 'catalogos\lineaController@store',
										'include' => 'catalogos.linea.fields'
										))
@endsection