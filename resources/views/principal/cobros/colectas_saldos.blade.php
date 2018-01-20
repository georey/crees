@extends('layouts.master')
@section('title')
    Colectas y Saldos
@stop
@section('content')
<div class="box-body">
    <div class="box">
	    <div class="box-body">
	      <table class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>Codigo</th>
	        		<th>Cliente</th>
	        		<th>Vencimiento</th>
	        		<th>Plazo/Linea</th>
	        		<th>Saldo</th>
	        		<th>Interes</th>
	        		<th>Cuota</th>
	        		<th>Acumulado</th>
	        		<th>Deuda Total</th>
	        	</tr>
	        </thead>
	        <tbody>
	        {{--*/ $total_saldo = 0 /*--}}
	        {{--*/ $total_interes = 0 /*--}}
	        {{--*/ $total_deuda = 0 /*--}}
			
	        	@foreach($prestamos as $prestamo)
	        		{{--*/ $saldo = $prestamo->saldoAnterior() /*--}}
	        		{{--*/ $interes = $prestamo->getInteres() /*--}}
	        		{{--*/ $deuda = $prestamo->saldoAnterior() + $prestamo->getInteres() + $prestamo->getMulta() + $prestamo->getMora() /*--}}
	        		<tr>
	        			<td>{{$prestamo->codigo}}</td>
	        			<td>{{$prestamo->cliente->nombreCompleto()}}</td>
	        			<td>@lang('dias.'. strtolower($prestamo->getFechaVencimiento()->format('D'))) - {{$prestamo->getFechaVencimiento()->format('d-m-Y')}}</td>	        			
	        			<td>{{$prestamo->cuotas}} {{str_limit($prestamo->linea->periodo,1,"")}}</td>
	        			<td>{{number_format($saldo,2)}}</td>
	        			<td>{{number_format($interes,2)}}</td>
	        			<td>{{number_format($prestamo->cuota,2)}}</td>
	        			<td>{{number_format($prestamo->getInteres() + $prestamo->getInteresesPendientes() + $prestamo->getCapitalPendiente(),2)}}</td>
	        			<td>{{number_format($deuda,2)}}</td>
	        		</tr>
	        		{{--*/ $total_saldo += $saldo /*--}}
			        {{--*/ $total_interes += $interes /*--}}
			        {{--*/ $total_deuda += $deuda /*--}}
	        	@endforeach
	        </tbody>
	        <tfoot>
	        	<tr>
	        		<th colspan="4">TOTAL</th>
	        		<th>{{number_format($total_saldo,2)}}</th>
	        		<th>{{number_format($total_interes,2)}}</th>
	        		<th></th>
	        		<th></th>
	        		<th>{{number_format($total_deuda,2)}}</th>
	        	</tr>
	        </tfoot>>
	      </table>
	    </div>
	</div>
</div>
@endsection