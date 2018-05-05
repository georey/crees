@include('pdf.table_report_css')
@include('pdf.encabezado')
<h4 class="text-center">CARTERA POR ASESOR</h4>
<h4>ASESOR: {{$asesor->nombre or ''}} {{$asesor->apellido or ''}}</h4>
<h4>FECHA:  {{$fecha->format('d-m-Y')}}</h4>
<h4>TOTAL DE CLIENTES:  {{$prestamos->count()}}</h4>

<table class="table table-bordered table-striped table-medium-text">
	<thead>
		<tr>
			<th>Codigo</th>
			<th>Cliente</th>			
			<th>Otorgamiento</th>
			<th>Vencimiento</th>
			<th>Plazo</th>
			<th>Monto</th>
			<th>Saldo Capital</th>
		</tr>
	</thead>
	<tbody>
	{{--*/ $total_saldo = 0 /*--}}
	{{--*/ $total_interes = 0 /*--}}
	{{--*/ $total_deuda = 0 /*--}}
	{{--*/ $total_monto = 0 /*--}}

		@foreach($prestamos as $prestamo)
			{{--*/ $saldo = $prestamo->saldoAnterior() /*--}}
			{{--*/ $interes = $prestamo->getInteres() /*--}}
			{{--*/ $monto = $prestamo->monto /*--}}
			{{--*/ $deuda = $prestamo->saldoAnterior() + $prestamo->getInteres() + $prestamo->getMulta() + $prestamo->getMora() /*--}}
			<tr>
				<td>{{$prestamo->codigo}}</td>
				<td>{{$prestamo->cliente->nombreCompleto()}}</td>
				<td>{{$prestamo->fecha->format('d-m-Y')}}</td>
				<td>{{$prestamo->getFechaVencimiento()->format('d-m-Y')}}</td>
				<td>{{$prestamo->cuotas}} {{str_limit($prestamo->linea->periodo,1,"")}}</td>
				<td>{{$prestamo->monto}}</td>
				<td class="text-right" align="right">{{number_format($saldo,2)}}</td>
			</tr>
			{{--*/ $total_saldo += $saldo /*--}}
	        {{--*/ $total_interes += $interes /*--}}
	        {{--*/ $total_deuda += $deuda /*--}}
	        {{--*/ $total_monto += $monto /*--}}
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>TOTAL</th>
			<th class="text-right" align="right">{{number_format($total_monto,2)}}</th>
			<th class="text-right" align="right">{{number_format($total_saldo,2)}}</th>
		</tr>
	</tfoot>
</table>