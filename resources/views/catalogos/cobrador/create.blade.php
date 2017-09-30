@extends('layouts.master')
@section('title')
Cobrador
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar cobrador',
										'action' => 'catalogos\cobradorController@store',
										'include' => 'catalogos.cobrador.fields'
										))
@endsection