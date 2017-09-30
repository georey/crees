@extends('layouts.master')
@section('title')
Tipo de negocio
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'catalogos\tipo_negocioController@update',
									'id' => array('id' => $tipo_negocio->id),
									'include' => 'catalogos.tipo_negocio.fields'
									))
@endsection