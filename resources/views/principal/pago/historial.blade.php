@extends('layouts.master')
@section('title')
	Historial de pagos
@stop
@section('titleBreadcrumb')
    {{$prestamo->codigo}} - {{$prestamo->cliente->nombreCompleto()}} - {{$prestamo->monto}}
@stop

@section('content')
<input type="hidden" id="fecha_otorgamiento" value="{{$prestamo->fecha->format('d-m-Y')}}">
<div class="box">
    <div class="box-body">
      <table class="table table-bordered table-striped" id="tbl_historial">
        <thead>
		    <tr>
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
		    	<th>Acciones</th>
		    </tr>
		</thead>
		<tbody>
		{{-- */$i = 1;/* --}}
		{{-- */$capital = 0;/* --}}
		{{-- */$span_red = '<span class="badge bg-red">';/* --}}
		{{-- */$span_gray = '<span class="badge bg-gray">';/* --}}
		{{-- */$span_close = '</span">';/* --}}
			@foreach($prestamo->pagos as $pago)
			{{-- */$capital = $capital + $pago->capital;/* --}}
				<tr>
					<td>{{$i}}</td>
			    	<td class="td_fecha">{{$pago->fecha->format('d-m-Y')}}</td>
			    	<td class="td_dias"></td>
			    	<td><span class="badge bg-light-blue monto-cuota">{{number_format($pago->getCuotaCompleta(), 2)}}</span></td>
			    	<td><span class="badge bg-green monto-capital">{{number_format($pago->capital, 2)}}</span></td>
			    	<td><span class="badge bg-red monto-interes">{{number_format($pago->interes, 2)}}</span></td>
			    	<td class="monto-mora">{!!$pago->mora > 0 ? $span_red . number_format($pago->mora, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<td class="monto-multa">{!!$pago->multa > 0 ? $span_red . number_format($pago->multa, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<td>{!!$pago->interes_pendiente > 0 ? $span_red . number_format($pago->interes_pendiente, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<td>{!!$pago->mora_pendiente > 0 ? $span_red . number_format($pago->mora_pendiente, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<td>{!!$pago->multa_pendiente > 0 ? $span_red . number_format($pago->multa_pendiente, 2) . $span_close : $span_gray. '0.00' . $span_close!!}</td>
			    	<th><span class="badge bg-light-blue">{{number_format($prestamo->monto - $capital, 2)}}</span></th>
			    	<td>
			    		@if($prestamo->pagos->last()->id == $pago->id)
			    		<a href="{{url('pagos/revertir/'.$prestamo->id.'/'.$pago->id)}}" class="link-confirmation" title="Realizar reversion">
						    <i class="glyphicon glyphicon-share-alt"></i>
						</a>
						@endif
			    	</td>
				</tr>
				{{-- */$i++;/* --}}
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th colspan="3">Total:</th>
				<th id="total-cuota"></th>
				<th id="total-capital"></th>
				<th id="total-interes"></th>
				<th id="total-mora"></th>
				<th id="total-multa"></th>
				<th colspan="5"></th>
			</tr>
		</tfoot>
      </table>
    </div>
</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('scripts/pagos/historial.js') }}"></script>
@endsection