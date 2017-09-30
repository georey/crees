@extends('layouts.master')
@section('title')
Cobrador
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'catalogos\cobradorController@update', 
									'id' => array('id' => $cobrador->id),
									'include' => 'catalogos.cobrador.fields'
									))
@endsection