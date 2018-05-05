@include('pdf.table_report_css')
@include('pdf.encabezado')
<h4 class="text-center">HOJA DE SALDOS</h4>
<h4>ASESOR: {{$asesor->nombre or ''}} {{$asesor->apellido or ''}}</h4>
<h4>FECHA:  {{$fecha->format('d-m-Y')}}</h4>
<table class="table table-bordered table-striped table-medium-text">
	<thead>
		<tr>
			<th>Codigo</th>
			<th>Cliente</th>			
			<th title="Dias desde el ultimo pago">DUP</th>
			<th title="Plazo/Linea">P/L</th>
			<th>Capital</th>
			<th>Interes Total</th>
			<th>Cuota</th>
			<th title="Ponerse al dia">P/Dia</th>
			<th>Deuda Total</th>
		</tr>
	</thead>
	<tbody>
	{{--*/ $total_saldo = 0 /*--}}
	{{--*/ $total_interes = 0 /*--}}
	{{--*/ $total_deuda = 0 /*--}}

		@foreach($prestamos as $prestamo)
			{{--*/ $saldo = $prestamo->saldoAnterior() /*--}}
			{{--*/ $interes = $prestamo->getInteres() + $prestamo->getMulta() + $prestamo->getMora() /*--}}
			{{--*/ $deuda = $prestamo->saldoAnterior() + $prestamo->getInteres() + $prestamo->getMulta() + $prestamo->getMora() /*--}}
			<tr>
				<td>{{$prestamo->codigo}}</td>
				<td>{{$prestamo->cliente->nombreCompleto()}}</td>
				<td>{{$prestamo->getDias()}}</td>
				<td>{{$prestamo->cuotas}} {{str_limit($prestamo->linea->periodo,1,"")}}</td>
				<td class="text-right" align="right">{{number_format($saldo,2)}}</td>
				<td class="text-right" align="right">{{number_format($interes,2)}}</td>
				<td class="text-right" align="right">{{number_format($prestamo->cuota,2)}}</td>
				<td class="text-right" align="right">{{number_format($prestamo->getInteres() + $prestamo->getInteresesPendientes() + $prestamo->getCapitalPendiente(),2)}}</td>
				<td class="text-right" align="right">{{number_format($deuda,2)}}</td>
			</tr>
			{{--*/ $total_saldo += $saldo /*--}}
	        {{--*/ $total_interes += $interes /*--}}
	        {{--*/ $total_deuda += $deuda /*--}}
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th>TOTAL</th>
			<th class="text-right" align="right">{{number_format($total_saldo,2)}}</th>
			<th class="text-right" align="right">{{number_format($total_interes,2)}}</th>
			<th></th>
			<th></th>
			<th class="text-right" align="right">{{number_format($total_deuda,2)}}</th>
		</tr>
	</tfoot>
</table>
<div class="page-break"></div>
@include('pdf.encabezado')
<h4 class="text-center">HOJA DE COLECTA</h4>
<h4>ASESOR: {{$asesor->nombre or ''}} {{$asesor->apellido or ''}}</h4>
<h4>FECHA:  {{$fecha->format('d-m-Y')}}</h4>


<table class="table table-bordered table-striped" width="100%">
	<thead>
		<tr>
			<th>COD. CLIENTE</th>
			<th>NOMBRE</th>
			<th>VENCIMIENTO</th>
			<th>ABONO</th>
			<th>FIRMA</th>
		</tr>
	</thead>
	<tbody>
		@foreach($prestamos as $prestamo)
			<tr>
				<td>{{$prestamo->codigo}}</td>
				<td>{{$prestamo->cliente->nombreCompleto()}}</td>
				<td>@lang('dias.'. strtolower($prestamo->getFechaVencimiento()->format('D'))) - {{$prestamo->getFechaVencimiento()->format('d-m-Y')}}</td>
				<td></td>
				<td></td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th>TOTAL</th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>
</table>
<br><br><br>
<table width="100%">
	<tr>
		<td style="text-align:center!important">_________________________________</td>
		<td style="text-align:center!important">_________________________________</td>
	</tr>
	<tr>
		<td style="text-align:center!important">RECIBIDO</td>
		<td style="text-align:center!important">ENTREGADO</td>
	</tr>
</table>