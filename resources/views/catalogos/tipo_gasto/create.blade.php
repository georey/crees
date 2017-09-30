@extends('layouts.master')
@section('title')
Tipo gasto
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar tipo de gasto',
										'action' => 'catalogos\tipo_gastoController@store',
										'include' => 'catalogos.tipo_gasto.fields'
										))
@endsection