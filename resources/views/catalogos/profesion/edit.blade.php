@extends('layouts.master')
@section('title')
Profesion
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'catalogos\profesionController@update', 
									'id' => array('id' => $profesion->id),
									'include' => 'catalogos.profesion.fields'
									))
@endsection