@extends('layouts.master')
@section('title')
Zona
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar zona',
										'action' => 'catalogos\zonaController@store',
										'include' => 'catalogos.zona.fields'
										))
@endsection