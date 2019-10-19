<!DOCTYPE html>
<html lang="es-co">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Hoja de Liquidacion</title>    
    <meta name="author" content="Giovanni Reinoza">
    <meta name="description" content="PDF de hoja de liquidacion">
    <meta name="keywords" content="hoja,liquidacion">
    @include('pdf.css')
</head>
<body>
<div style="width:50%; display: inline-block">

HOJA DE LIQUIDACION<br>
{{$fecha->format('d-m-Y')}}

</div>
<div style="width:50%; display: inline-block; text-align:right">
	<img src="{{ asset('img/logo_mini.jpg') }}" style="width:75px">
</div>
@include('pdf.encabezado')
<div class="colspan-12">
			<label>Cliente:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->codigo}} {{$prestamo->cliente->nombreCompleto()}}</b></label>
</div>
<div class="colspan-12">
	<label>Linea de credito:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->linea->nombre}}</b></label>
</div>
<div class="div_table">
	<div class="div_tr">
		<div class="div_td colspan-4">
			<label>Monto:<b>&nbsp;&nbsp;&nbsp;${{$prestamo->monto}}</b></label>
		</div>
		<div class="div_td colspan-4">
			<label>Destino:<b>&nbsp;&nbsp;&nbsp;Comercio</b></label>
		</div>
		<div class="div_td colspan-4">
			<label>Garantia:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->fiadores->count() == 0 ? 'Prendaria' : 'Fiador'}}</b></label>
		</div>
	</div>	
	<div class="div_tr">
		<div class="div_td colspan-4">
			<label>Cuota:<b>&nbsp;&nbsp;&nbsp;${{$prestamo->cuota}}</b></label>
		</div>
		<div class="div_td colspan-4">
			<label>Apertura:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->fecha->format('d-m-Y')}}</b></label>
		</div>		
	</div>
	<div class="div_tr">
		<div class="div_td colspan-4">
			<label>Tasa Diaria:<b>&nbsp;&nbsp;&nbsp;{{number_format($prestamo->tasa / 365, 2)}}%</b></label>
		</div>
		<div class="div_td colspan-4">
			<label>Vencimiento:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->getVencimiento()->format('d-m-Y')}}</b></label>
		</div>		
	</div>
	<div class="div_tr">
		<div class="div_td colspan-4">
			<label>Plazo:<b>&nbsp;&nbsp;&nbsp;{{$prestamo->cuotas . ' ' . $prestamo->linea->periodo}}</b></label>
		</div>
		<div class="div_td colspan-4">
			<label>Multa por cuota atrasada:<b>&nbsp;&nbsp;&nbsp;${{$prestamo->multa}}</b></label>
		</div>		
	</div>	
</div>

		<div class="colspan-12">
			<label>Ejecutivo:<b>&nbsp;&nbsp;&nbsp;
				@if (empty($prestamo->asesor_id))
			      	{{strtoupper(Auth::user()->nombre . ' ' . Auth::user()->apellido)}}
			    @else
					{{strtoupper($prestamo->asesor->nombre . ' ' . $prestamo->asesor->apellido)}}
			    @endif
		</b></label>
		</div>		

	
<br><br>
<h3 class="text-center">DETALLE DE DEDUCCIONES</h3>
<div class="div_table table-left">
	<div class="div_tr">
		<div class="div_td colspan-3">
			<label><b>Tipo</b></label>
		</div>
		<div class="div_td colspan-3">
			<label><b>Cuenta</b></label>
		</div>
		<div class="div_td colspan-3">
			<label><b>Descripcion</b></label>
		</div>
		<div class="div_td colspan-3">
			<label><b>Valor</b></label>
		</div>
	</div>
	@foreach($prestamo->gastos as $gasto)
	<div class="div_tr">
		<div class="div_td colspan-3">Contable</div>
		<div class="div_td colspan-3">Descuentos</div>
		<div class="div_td colspan-3">{{$gasto->tipo}}</div>
		<div class="div_td colspan-3">${{$gasto->monto}}</div>
	</div>
	@endforeach
<?php $monto_liquidado = 0?>

	@foreach($prestamo->prestamos_liquidados as $liquidados)
	<div class="div_tr">
		<div class="div_td colspan-3">Contable</div>
		<div class="div_td colspan-3">Credito anterior</div>
		<div class="div_td colspan-3">{{$liquidados->codigo}}</div>
		<div class="div_td colspan-3">${{$liquidados->pivot->monto}}</div>
		<?php $monto_liquidado += $liquidados->pivot->monto;?>
	</div>	
	@endforeach
</div>

<div class="div_table table-left">
	<div class="div_tr">
		<div class="div_td colspan-6"></div>
		<div class="div_td colspan-3">TOTAL DEDUCCIONES</div>
		<div class="div_td colspan-3">${{number_format($prestamo->gastos->sum('monto') + $monto_liquidado,2)}}</div>
	</div>
	<div class="div_tr">
		<div class="div_td colspan-6"></div>
		<div class="div_td colspan-3"><div class="line-separator"></div></div>		
		<div class="div_td colspan-3"><div class="line-separator"></div></div>		
	</div>
	<div class="div_tr">
		<div class="div_td colspan-6"></div>
		<div class="div_td colspan-3">LIQUIDO A RECIBIR</div>
		<div class="div_td colspan-3">${{number_format($prestamo->monto - $prestamo->gastos->sum('monto') - $monto_liquidado,2)}}</div>
	</div>
</div>
<br><br><br><br><br><br>
<table width="100%">
	<tr>
		<td style="text-align:center!important">_________________________________</td>
		<td style="text-align:center!important">_________________________________</td>
	</tr>
	<tr>
		<td style="text-align:center!important">
			{{strtoupper(Auth::user()->nombre . ' ' . Auth::user()->apellido)}}			
		</td>
		<td style="text-align:center!important">{{$prestamo->cliente->nombreCompleto()}}</td>
	</tr>
	<tr>
		<td style="text-align:center!important">Autorizado por</td>
		<td style="text-align:center!important">Cliente</td>
	</tr>
</table>
</body>
</html>