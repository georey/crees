@extends('layouts.master')
@section('title')
Pagos
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar pago',
										'action' => 'principal\pagoController@store',
										'include' => 'principal.pago.fields'
										))
@endsection