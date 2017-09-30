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
	        	@foreach($prestamos as $prestamo)
	        		<tr>
	        			<td>{{$prestamo->codigo}}</td>
	        			<td>{{$prestamo->cliente->nombreCompleto()}}</td>
	        			<td>{{$prestamo->getFechaVencimiento()->format('d-m-Y')}}</td>	        			
	        			<td>{{$prestamo->cuotas}} {{str_limit($prestamo->linea->periodo,1,"")}}</td>
	        			<td>{{$prestamo->saldoAnterior()}}</td>
	        			<td>{{$prestamo->getInteres()}}</td>
	        			<td>{{$prestamo->cuota}}</td>
	        			<td>{{$prestamo->getInteres() + $prestamo->getInteresesPendientes() + $prestamo->getCapitalPendiente()}}</td>
	        			<td>{{number_format($prestamo->saldoAnterior() + $prestamo->getInteres() + $prestamo->getMulta() + $prestamo->getMora(),2)}}</td>
	        		</tr>
	        	@endforeach
	        </tbody>
	      </table>
	    </div>
	</div>
</div>
@endsection