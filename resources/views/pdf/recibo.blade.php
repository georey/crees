@include('pdf.css')
<table>
	<tr>
		<th style="width:20%; display: inline-block; text-align:right">			
		</th>
		<th class="text-center" style="width:60%; display: inline-block;font-size: x-small; font-weight: bold;">
SERVICIOS CREDITICIOS DE EL SALVADOR,<br>
SOCIEDAD ANONIMA DE CAPITAL VARIABLE<br>
NIT 0210-070416-101-0    NRC 250340-1<br>
1 CALLE PTE. LOCAL 106, Bo. SALVADOREÑO 1 NIVEL EDIF. BANCO SALVADOREÑO,<br>
SANTA ANA. SANTA ANA. TEL. 2421-9058
		</th>
		<th style="width:20%; display: inline-block; text-align:right">
			<img src="{{ asset('img/logo_mini_75.jpg') }}" style="width:25px">
		</th>
	</tr>
</table>
<div class="line-separator"></div>
<br><br>
<table style="width: 100%">
	<tr>
		<td>Prestamo:</td>
		<td>{{$prestamo->codigo}}</td>
		<td>Recibo No.:</td>
		<td>{{$pago->id or ''}}</td>
	</tr>
	<tr>
		<td>Cliente:</td>
		<td>{{$prestamo->cliente->nombreCompleto()}}</td>
		<td>Codigo:</td>
		<td>{{$prestamo->cliente->codigo}}</td>
	</tr>
	<tr>
		<td>Fecha de pago:</td>
		<td>{{$pago->fecha->format('d-m-Y')}}</td>
		<td>Cajero:</td>
		<td>{{strtoupper(Auth::user()->nombre . ' ' . Auth::user()->apellido)}}</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td>Agencia:</td>
		<td>Santa Ana, Centro</td>
	</tr>
</table>
<div class="line-separator"></div>
<br><br>
<table style="width: 100%">
	<tr>
		<th style="text-align: right; width: 50%; font-size: 20px">Saldo Capital antes de pago:</th>
		<th style="text-align: left;padding-left: 15px; font-size: 20px">$ {{number_format($prestamo->saldoAnterior() + $pago->capital,2)}}</th>
	</tr>
	<tr>
		<th style="text-align: right; width: 50%; font-size: 20px">Abonos:</th>
		<th style="text-align: left;padding-left: 15px; font-size: 20px"></th>
	</tr>
	<tr>
		<td style="text-align: right; width: 50%; font-size: 14px">Capital:</td>
		<td style="text-align: left;padding-left: 15px; font-size: 14px">$ {{number_format($pago->capital,2)}}</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 50%; font-size: 14px">Interes:</td>
		<td style="text-align: left;padding-left: 15px; font-size: 14px">$ {{number_format($pago->interes,2)}}</td>
	</tr>
	<tr>
		<td style="text-align: right; width: 50%; font-size: 14px">Interes Moratorio:</td>
		<td style="text-align: left;padding-left: 15px;; font-size: 14px">$ {{number_format($pago->mora + $pago->multa,2)}}</td>
	</tr>
	<tr>
		<th style="text-align: right; width: 50%; font-size: 20px">Total:</th>
		<th style="text-align: left;padding-left: 15px; font-size: 20px">$ {{number_format($pago->getCuotaCompleta(),2)}}</th>
	</tr>
	<tr>
		<th style="text-align: right; width: 50%; font-size: 20px">Saldo Capital despues de pago:</th>
		<th style="text-align: left;padding-left: 15px; font-size: 20px">$ {{number_format($prestamo->saldoAnterior(),2)}}</th>
	</tr>
	<tr>
		<th style="text-align: right; width: 50%; font-size: 20px">Intereses pendientes:</th>
		<th style="text-align: left;padding-left: 15px; font-size: 20px">$ {{number_format($prestamo->getInteresesPendientes(),2)}}</th>
	</tr>
	<tr>
		<th style="text-align: right; width: 50%; font-size: 20px">Deuda Total:</th>
		<th style="text-align: left;padding-left: 15px; font-size: 20px">$ {{number_format($prestamo->saldoAnterior() + $prestamo->getInteresesPendientes(),2)}}</th>
	</tr>
</table>
<div class="line-separator"></div>
<br><br>
<table style="width: 100%">
	<tr>
		<td>F:</td>
		<td>________________</td>
		<td>F:</td>
		<td>________________</td>
		<td>F:</td>
		<td>________________</td>
	</tr>
	<tr>
		<td></td>
		<td>Autorizado</td>
		<td></td>
		<td>Cajero Colector</td>
		<td></td>
		<td>Cliente</td>
	</tr>
</table>