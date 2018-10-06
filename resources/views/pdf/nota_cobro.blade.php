@include('pdf.css')
<table>
	<tr>
		<th style="width:20%; display: inline-block; text-align:right">			
		</th>
		<th class="text-center" style="width:60%; display: inline-block;font-size: x-small; font-weight: bold;">
SERVICIOS CREDITICIOS DE EL SALVADOR,<br>
SOCIEDAD ANONIMA DE CAPITAL VARIABLE<br>
NIT 0210-070416-101-0    NRC 250340-1<br>
1 CALLE PTE. LOCAL 204, Bo. SALVADOREÑO 2 NIVEL EDIF. BANCO SALVADOREÑO,<br>
SANTA ANA. SANTA ANA. TEL. 2421-9058
		</th>
		<th style="width:20%; display: inline-block; text-align:right">
			<img src="{{ asset('img/logo_mini_75.jpg') }}" style="width:25px">
		</th>
	</tr>
</table>
<div class="line-separator"></div>
<br><br>
<div style="margin: auto;width: 80%;padding: 10px; font-weight: bold;">
<div class="fecha_apertura">
Santa Ana, {{trans("dias.".$fecha->format('l'))}} {{$fecha->format('d')}} de {{trans('meses.'.$fecha->format('F'))}} de {{$fecha->format('Y')}}.
</div>
<br>
Señor(a):<br>
{{$prestamo->cliente->nombreCompleto()}}<br>
Presente:<br><br>
<p class="text-justify">
Por este medio se le  comunica que el crédito No.{{$prestamo->codigo}} concedido en fecha {{$prestamo->fecha->format('d/m/Y')}}, otorgado por Servicios Crediticios de El Salvador Sociedad Anónima de Capital Variable (CREES) con un monto de {{$prestamo->monto}} dólares, se encuentra actualmente EN MORA
@if($fecha > $prestamo->getVencimiento())
 y VENCIDO el día {{$prestamo->getVencimiento()->format('d/m/Y')}}
@endif
.</p>

<p class="text-justify">
El atraso que su cuenta refleja es de {{number_format($prestamo->getMora() + $prestamo->getMulta() + $prestamo->getInteres(),2)}} dólares de saldos de intereses y comisiones y de 
@if($prestamo->montoCuotas()==0){{number_format($prestamo->getCapitalPendiente(),2)}}@else{{number_format($prestamo->montoCuotas() - $prestamo->getInteres(),2)}}@endif de capital en mora al {{$fecha->format('d/m/Y')}} haciendo un total de {{number_format($prestamo->saldoAnterior() + $prestamo->getMora() + $prestamo->getMulta() + $prestamo->getInteres(),2)}}
</p>

<p class="text-justify">
Por lo que le sugerimos presentarse en nuestra oficina en 24 horas a más tardar al recibir esta nota; para solventar su situación crediticia.
</p>

<p class="text-justify">
De lo contrario iniciaremos las gestiones jurídicas necesarias para la recuperación de este adeudo, esto presenta un incremento que sería cargado a su cuenta y el embargo de los bienes autorizados por su persona; que forman parte de la garantía prendaria de este crédito.
</p>

<p class="text-justify">
En espera de una reacción positiva de parte de su persona, me suscribo.
</p>
<br>
<div class="text-center">Atentamente</div>
<br><br><br><br><br><br>
<table width="100%">
	<tr>
		<td style="text-align:center!important">F. _________________________________</td>
	</tr>
	<tr>
		<td style="text-align:center!important">Departamento de Cobros</td>
	</tr>
	<tr>
		<td style="text-align:center!important">Servicios Crediticios de El Salvador S.A. de C.V.</td>
	</tr>
	<tr>
		<td style="text-align:center!important">1 Calle Pte. Local 204, 2 Nivel. Edif. Banco Salvadoreño, Santa Ana.</td>
	</tr>
	<tr>
		<td style="text-align:center!important">Tel. 2421-9058</td>
	</tr>
</table>
</div>