@include('pdf.css')
<div style="width:50%; display: inline-block">

FICHA<br>
{{$fecha->format('d-m-Y')}}

</div>
<div style="width:50%; display: inline-block; text-align:right">
	<img src="{{ asset('img/logo_mini.jpg') }}" style="width:75px">
</div>

<h3 class="text-center">SERVICIOS CREDITICIOS DE ELSALVADOR S.A. DE C.V.</h3>

<div class="line-separator"></div>
<br><br>
<table border="0" width="100%" class="table-left">
	<tr>
		<th colspan="3">INFORMACION GENERAL</th>
	</tr>
	<tr>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>Fecha de ingreso</th>
	</tr>
	<tr>
		<td>{{$cliente->codigo}}</td>
		<td>{{$cliente->nombreCompleto()}}</td>
		<td>{{$cliente->created_at->format('d-m-Y')}}</td>
	</tr>
	<tr>
		<th>DUI</th>
		<th>NIT</th>
		<th>Profesion</th>
	</tr>
	<tr>		
		<td>{{$cliente->dui}}</td>
		<td>{{$cliente->nit}}</td>
		<td>{{$cliente->profesion->nombre}}</td>
	</tr>
	<tr>
		<th>Direccion</th>
		<th>Telefono</th>		
		<th>Estado Civil</th>			
	</tr>
	<tr>
		<td>{{$cliente->direccion}}</td>
		<td>{{$cliente->telefono}}</td>
		<td>{{$cliente->estado_civil->nombre}}</td>
	</tr>
	<tr>
		<th>Fecha Nacimiento</th>
		<th>Edad</th>
		<th>Sexo</th>
	</tr>
	<tr>
		<td>{{$cliente->fecha_nacimiento->format('d-m-Y')}}</td>	
		<td>{{floor($cliente->fecha_nacimiento->diffInDays($fecha) / 365)}}</td>
		<td>@if($cliente->sexo == 1)
				Masculino
			@else
   				Femenino
			@endif
		</td>
	</tr>
</table>
<br><br>
<table border="0" width="100%" class="table-left">
	<tr>
		<th colspan="3">NEGOCIOS</th>
	</tr>
	<tr>
		<th>Nombre</th>
		<th>Direccion</th>
		<th>Telefono</th>
		<th>Dias de trabajo	</th>
		<th>Horario</th>
		<th>Tipo de negocio</th>
	</tr>
	@foreach($cliente->negocios as $negocio)
		<tr>
			<td>{{$negocio->nombre}}</td>
			<td>{{$negocio->direccion}}</td>
			<td>{{$negocio->telefono}}</td>
			<td>{{$negocio->dias_trabajo	}}</td>
			<td>{{$negocio->horario}}</td>
			<td>{{$negocio->tipo_negocio->tipo}}</td>
		</tr>
	@endforeach
</table>