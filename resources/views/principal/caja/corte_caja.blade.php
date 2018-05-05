@extends('layouts.master')
@section('title')
    Corte Caja
@stop
@section('titleBreadcrumb')
	Corte Caja
@stop
@section('content')
<table class="table table-bordered table-striped table-mini-text">
	<thead>
		<tr>
			<th>ID</th>
			<th>Prestamo</th>
			<th>Cliente</th>
			<th>Abono</th>
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
		</tr>
	</tfoot>
</table>
@endsection