@extends('layouts.master')
@section('title')
Estado civil
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'catalogos\estado_civilController@update',
									'id' => array('id' => $estado_civil->id),
									'include' => 'catalogos.estado_civil.fields'
									))
@endsection