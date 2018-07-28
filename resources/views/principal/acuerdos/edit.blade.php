@extends('layouts.master')
@section('title')
Acuerdos
@stop
@section('content')
	<div class="box-body">
	<div class="form-group col-md-12">
		<label class="col-md-3">Cliente: </label>
		{{$acuerdo->prestamo->codigo}} - {{$acuerdo->prestamo->cliente->nombreCompleto()}}
	</div>	
	<div class="form-group col-md-12">
		<label class="col-md-3">Justificacion: </label>
		{{$acuerdo->justificacion}}
	</div>		
	<div class="form-group col-md-12">
		<label class="col-md-3">Monto Anterior: </label>
		${{$acuerdo->monto_anterior}}
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-3">Monto Posterior: </label>
		${{$acuerdo->monto_posterior}}
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-3">Usuario: </label>
		{{$acuerdo->user}}
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-3">Fecha: </label>
		{{$acuerdo->fecha}}
	</div>
</div>
<div class="clearfix"></div>
<div class="box-footer">
	<a href="{!! route('acuerdos_pagos.index') !!}" class="btn btn-default pull-right">Regresar</a>
</div>
@endsection