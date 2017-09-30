@extends('layouts.master')
@section('title')
Profesion
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar profesion',
										'action' => 'catalogos\profesionController@store',
										'include' => 'catalogos.profesion.fields'
										))
@endsection