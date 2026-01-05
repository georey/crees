@include('pdf.css')
<table>
    <tr>
        <td>Cliente: {{$prestamo->cliente->nombreCompleto()}} Fecha:{{$pago->fecha->format('d-m-Y')}}</td>        
    </tr>
    <tr>
        <td>Direccion: {{$prestamo->cliente->direccion || ''}}</td>
    </tr>
    <tr>
        <td>Venta a cuenta de: _____________ NIT/DUI: {{$prestamo->cliente->dui}}</td>
    </tr>
</table>

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