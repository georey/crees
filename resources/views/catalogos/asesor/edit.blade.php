@extends('layouts.master')
@section('title')
Asesor
@stop
@section('content')
 	@include("layouts.form.edit", array(
									'title' => 'Editar',
									'action' => 'catalogos\asesorController@update', 
									'id' => array('id' => $asesor->id),
									'include' => 'catalogos.asesor.fields'
									))
@endsection