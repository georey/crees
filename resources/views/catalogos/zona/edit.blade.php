@extends('layouts.master')
@section('title')
Zona
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'catalogos\zonaController@update', 
									'id' => array('id' => $zona->id),
									'include' => 'catalogos.zona.fields'
									))
@endsection