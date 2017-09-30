@extends('layouts.master')
@section('title')
Tipo negocio
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar tipo de negocio',
										'action' => 'catalogos\tipo_negocioController@store',
										'include' => 'catalogos.tipo_negocio.fields'
										))
@endsection