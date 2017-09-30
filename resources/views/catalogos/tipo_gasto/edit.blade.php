@extends('layouts.master')
@section('title')
Tipo de gasto
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'catalogos\tipo_gastoController@update',
									'id' => array('id' => $tipo_gasto->id),
									'include' => 'catalogos.tipo_gasto.fields'
									))
@endsection