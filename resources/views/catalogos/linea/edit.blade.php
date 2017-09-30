@extends('layouts.master')
@section('title')
Linea
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'catalogos\lineaController@update',
									'id' => array('id' => $linea->id),
									'include' => 'catalogos.linea.fields'
									))
@endsection