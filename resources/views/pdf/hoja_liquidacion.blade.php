@include('pdf.css')
<div style="width:50%; display: inline-block">

HOJA DE LIQUIDACION<br>
{{$fecha->format('d-m-Y')}}

</div>
<div style="width:50%; display: inline-block; text-align:right">
	<img src="{{ asset('img/logo_mini.jpg') }}" style="width:75px">
</div>

<h3 class="text-center">SERVICIOS CREDITICIOS DE ELSALVADOR S.A. DE C.V.</h3>

<div class="line-separator"></div>
<br><br>
<table border="0" width="100%">
	<tr>
		<td colspan="3"><label>Cliente:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->codigo}} {{$prestamo->cliente->nombreCompleto()}}</b></label></td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr>
		<td colspan="3"><label>Linea de credito:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->linea->nombre}}</b></label></td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr>
		<td>
			<label>Monto:<b>&nbsp;&nbsp;&nbsp;${{$prestamo->monto}}</b></label>
		</td>
		<td>
			<label>Destino:<b>&nbsp;&nbsp;&nbsp;Comercio</b></label>
		</td>
		<td>
			<label>Garantia:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->fiadores->count() == 0 ? 'Prendaria' : 'Fiador'}}</b></label>
		</td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr>
		<td>
			<label>Cuota:<b>&nbsp;&nbsp;&nbsp;${{$prestamo->cuota}}</b></label>
		</td>
		<td>
			<label>Apertura:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->fecha->format('d-m-Y')}}</b></label>
		</td>
		<td></td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr>
		<td>
			<label>Tasa Diaria:<b>&nbsp;&nbsp;&nbsp;{{number_format($prestamo->tasa / 365, 2)}}%</b></label>
		</td>
		<td><label>Vencimiento:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->getVencimiento()->format('d-m-Y')}}</b></label></td>
		<td></td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr>
		<td>
			<label>Plazo:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->cuotas . ' ' . $prestamo->linea->periodo}}</b></label>
		</td>
		<td>
			<label>Multa por cuota atrasada:<b>&nbsp;&nbsp;&nbsp;${{$prestamo->multa}}</b></label>
		</td>
		<td></td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr>
		<td colspan="3"><label>Ejecutivo:<b>&nbsp;&nbsp;&nbsp;{{strtoupper(Auth::user()->nombre . ' ' . Auth::user()->apellido)}}</b></label></td>
	</tr>
</table>
<br><br>
<h3 class="text-center">DETALLE DE DEDUCCIONES</h3>
<table width="100%" class="table-left">
	<tr>
		<th>Tipo</th>
		<th>Cuenta</th>
		<th>Descripcion</th>
		<th>Valor</th>
	</tr>
	@foreach($prestamo->gastos as $gasto)
	<tr>
		<td>Contable</td>
		<td>Descuentos</td>
		<td>{{$gasto->tipo}}</td>
		<td>${{$gasto->monto}}</td>
	</tr>
	@endforeach

<?php $monto_liquidado = 0?>

	@foreach($prestamo->prestamos_liquidados as $liquidados)
	<tr>
		<td>Contable</td>
		<td>Credito anterior</td>
		<td>{{$liquidados->codigo}}</td>
		<td>${{$liquidados->pivot->monto}}</td>
		<?php $monto_liquidado += $liquidados->pivot->monto;?>
	</tr>
	@endforeach
	<tr>
		<th colspan="2"></th>
		<th>TOTAL DEDUCCIONES</th>
		<th>${{number_format($prestamo->gastos->sum('monto') + $monto_liquidado,2)}}</th>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="2"><div class="line-separator"></div></td>
	</tr>
	<tr>
		<th colspan="2"></th>
		<th>LIQUIDO A RECIBIR</th>
		<th>${{number_format($prestamo->monto - $prestamo->gastos->sum('monto') - $monto_liquidado,2)}}</th>
	</tr>
</table>
<br><br><br><br><br><br>
<table width="100%">
	<tr>
		<td style="text-align:center!important">_________________________________</td>
		<td style="text-align:center!important">_________________________________</td>
	</tr>
	<tr>
		<td style="text-align:center!important">
			@if (empty($prestamo->asesor_id))
		      	{{strtoupper(Auth::user()->nombre . ' ' . Auth::user()->apellido)}}
		    @else
				{{strtoupper($prestamo->asesor->nombre . ' ' . $prestamo->asesor->apellido)}}
		    @endif
		</td>
		<td style="text-align:center!important">{{$prestamo->cliente->nombreCompleto()}}</td>
	</tr>
	<tr>
		<td style="text-align:center!important">Ejecutivo</td>
		<td style="text-align:center!important">Cliente</td>
	</tr>
</table>