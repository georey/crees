@extends('layouts.master')
@section('title')
Asesor
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar acuerdo',
										'action' => 'principal\acuerdoController@store',
										'include' => 'principal.acuerdos.fields'
										))
@endsection