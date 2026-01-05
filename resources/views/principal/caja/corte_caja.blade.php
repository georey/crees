@extends('layouts.master')
@section('title')
    Corte Caja
@stop
@section('titleBreadcrumb')
	Corte Caja
@stop
@section('content')
<form method="POST">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
@include("layouts.form.input_text", array(
	'label' => 'Fecha Inicio',
	'name' => 'fecha_ini',
	'value' => $reporte,
	'mask' => '99-99-9999'
))
<button type="submit" id="btn_filtrar" name="btn_submit" value="filtrar" class="btn btn-info">Filtrar</button>
</form>
<table class="table table-bordered table-striped table-mini-text">
	<thead>
		<tr>
			<th>ID</th>
			<th>Prestamo</th>
			<th>Cliente</th>
			<th>Abono</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
	{{--*/ $total_abono = 0 /*--}}
		@foreach($pagos as $pago)		 
			{{--*/ $abono = $pago->capital+ $pago->interes+ $pago->mora+ $pago->multa  /*--}}
			<tr>
				<td>{{$pago->id}}</td>
				<td>{{$pago->prestamo->codigo}}</td>
				<td>{{$pago->prestamo->cliente->nombreCompleto()}}</td>				
				<td class="text-right" align="right">$ {{number_format($abono, 2)}}</td>				
				<td><a href="{{ route('pagos.recibo', ['id' => $pago->id]) }}">Recibos</a></td>
			</tr>
			{{--*/ $total_abono += $abono /*--}}	        
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th></th>			
			<th>TOTAL</th>
			<th class="text-right" align="right">$ {{number_format($total_abono,2)}}</th>			
			<th></th>			
		</tr>
	</tfoot>
</table>
@endsection