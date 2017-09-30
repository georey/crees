@extends('layouts.master')
@section('title')
Estado civil
@stop
@section('content')
	@include("layouts.form.create", array(
										'title' => 'Agregar estado civil',
										'action' => 'catalogos\estado_civilController@store',
										'include' => 'catalogos.estado_civil.fields'
										))
@endsection