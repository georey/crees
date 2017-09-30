@extends('layouts.master')
@section('title')
Asesor
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar asesor',
										'action' => 'catalogos\asesorController@store',
										'include' => 'catalogos.asesor.fields'
										))
@endsection