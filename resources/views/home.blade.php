@extends('layouts.master')
@section('title')
	Inicio
@stop
@section('content')
	<div class="box-header with-border">
		<h3 class="box-title">Bienvenido</h3>
	</div>
	<div class="box-body">
		<img src="{{ asset('img/background.jpg') }}" style="width:100%">
	</div>
	<div class="box-footer">
	</div>
@endsection
