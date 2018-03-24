@extends('layouts.master')
@section('title')
	Historial de pagos
@stop
@section('titleBreadcrumb')
    Historial: {{$cliente->nombreCompleto()}}
@stop

@section('content')
<div class="box">
    <div class="box-body">
      <table class="table table-bordered table-striped" id="tbl_historial">
		<tbody>
		@foreach($prestamos as $prestamo)
		<tr>
		<th colspan="11">
		<h3>
			<table style="width:50%; margin-left: auto; margin-right: auto;">
				<tr>
					<td>Cliente:</td><td>{{$cliente->nombreCompleto()}}</td>
				</tr>
				<tr>
					<td>Codigo:</td><td>{{$prestamo->codigo}}</td>
				</tr>
				<tr>
					<td>Monto:</td><td>${{$prestamo->monto}}</td>
				</tr>
				<tr>
					<td>Cuota:</td><td>${{$prestamo->cuota}}</td>
				</tr>
				<tr>
					<td>No:</td><td>{{$prestamo->cuotas}} {{$prestamo->linea->periodo}}</td>
				</tr>
				<tr>
					<td>Apertura:</td><td>{{$prestamo->fecha->format('d-m-Y')}}</td>
				</tr>
				<tr>
					<td>Vencimiento:</td><td>{{$prestamo->getFechaVencimiento()->format('d-m-Y')}}</td>
				</tr>
			</table>
			</h3>
			</th>
		</tr>
		<tr class="tr_fechas" data-date="{{$prestamo->fecha->format('d-m-Y')}}" data-inicial="1">
		    	<th>Numero</th>
		    	<th>Fecha</th>
		    	<th>Dias</th>
		    	<th>Cuota</th>
		    	<th>Capital</th>
		    	<th>Interes</th>
		    	<th>Mora</th>
		    	<th>Multa</th>
		    	<th>Interes Pendiente</th>
		    	<th>Mora Pendiente</th>
		    	<th>Multa Pendiente</th>
		    	<th>Saldo</th>
	    </tr>
		{{-- */$i = 1;/* --}}
		{{-- */$cuota = 0;/* --}}
		{{-- */$interes = 0;/* --}}
		{{-- */$mora = 0;/* --}}
		{{-- */$multa = 0;/* --}}
		{{-- */$capital = 0;/* --}}
		{{-- */$span_red = '<span class="badge bg-red">';/* --}}
		{{-- */$span_gray = '<span class="badge bg-gray">';/* --}}
		{{-- */$span_close = '</span">';/* --}}
			@foreach($prestamo->pagos as $pago)
			{{-- */$capital = $capital + $pago->capital;/* --}}
			{{-- */$cuota = $cuota + $pago->getCuotaCompleta();/* --}}
			{{-- */$interes = $interes + $pago->interes;/* --}}
			{{-- */$mora = $mora + $pago->mora;/* --}}
			{{-- */$multa = $multa + $pago->multa;/* --}}
				<tr class="tr_fechas" data-date="{{$pago->fecha->format('d-m-Y')}}" data-inicial="0">
					<td>{{$i}}</td>
			    	<td>{{$pago->fecha->format('d-m-Y')}}</td>
			    	<td class="td_dias"></td>
			    	<td><span class="badge bg-light-blue">{{number_format($pago->getCuotaCompleta(), 2)}}</span></td>
			    	<td><span class="badge bg-green">{{number_format($pago->capital, 2)}}</span></td>
			    	<td><span class="badge bg-red">{{number_format($pago->interes, 2)}}</span></td>
			    	<td>{!!$pago->mora > 0 ? $span_red . number_format($pago->mora, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<td>{!!$pago->multa > 0 ? $span_red . number_format($pago->multa, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<td>{!!$pago->interes_pendiente > 0 ? $span_red . number_format($pago->interes_pendiente, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<td>{!!$pago->mora_pendiente > 0 ? $span_red . number_format($pago->mora_pendiente, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<td>{!!$pago->multa_pendiente > 0 ? $span_red . number_format($pago->multa_pendiente, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<th><span class="badge bg-light-blue">{{number_format($prestamo->monto - $capital, 2)}}</span></th>
				</tr>
				{{-- */$i++;/* --}}
			@endforeach
			<tr>
				<th>Total:</th>
		    	<th></th>
		    	<th>{{number_format($cuota, 2)}}</th>
		    	<th>{{number_format($capital, 2)}}</th>
		    	<th>{{number_format($interes, 2)}}</th>
		    	<th>{{number_format($mora, 2)}}</th>
		    	<th>{{number_format($multa, 2)}}</th>
		    	<th></th>
		    	<th></th>
		    	<th></th>
		    	<th></th>
			</tr>
		@endforeach
		</tbody>
      </table>
    </div>
</div>
@endsection

@section('scripts')
	<script>
		$(document).ready(function(){
			var dateFormat = "D-M-YYYY";
			var fecha1;
			var fecha2;
			$(".tr_fechas").each(function(index, element) {
				if($(this).data("inicial") == 1)
					 fecha1 = moment($(this).data("date"), dateFormat);	
				else {
					fecha2 = moment($(this).data("date"), dateFormat);
					$(this).find('td.td_dias').html(fecha2.diff(fecha1, 'days'));
					fecha1 = fecha2;
				}
			});
		});
	</script>
@endsection