@extends('layouts.master')
@section('title')
	CUMPLEAÑEROS
@stop
@section('titleBreadcrumb')
    CUMPLEAÑEROS
@stop

@section('content')
<h4 class="text-center">CUMPLEAÑEROS</h4>
Mes: 
<select name="mes" id="mes">
	<option value="1" @if($mes==1) selected @endif>Enero</option>	
	<option value="2" @if($mes==2) selected @endif>Febrero</option>	
	<option value="3" @if($mes==3) selected @endif>Marzo</option>	
	<option value="4" @if($mes==4) selected @endif>Abril</option>	
	<option value="5" @if($mes==5) selected @endif>Mayo</option>	
	<option value="6" @if($mes==6) selected @endif>Junio</option>	
	<option value="7" @if($mes==7) selected @endif>Julio</option>	
	<option value="8" @if($mes==8) selected @endif>Agosto</option>	
	<option value="9" @if($mes==9) selected @endif>Septiembre</option>	
	<option value="10" @if($mes==10) selected @endif>Octubre</option>	
	<option value="11" @if($mes==11) selected @endif>Noviembre</option>	
	<option value="12" @if($mes==12) selected @endif>Diciembre</option>		
</select>
<button type="button" class="button" id="btn_filtrar">Filtrar</button>
<table class="table table-bordered table-striped table-medium-text">
	<thead>
		<tr>
			<th>Codigo</th>
			<th>Cliente</th>			
			<th>Fecha nacimiento</th>
		</tr>
	</thead>
	<tbody>
		@foreach($clientes as $cliente)			
			<tr>
				<td>{{$cliente->codigo}}</td>
				<td>{{$cliente->nombreCompleto()}}</td>
				<td>{{$cliente->fecha_nacimiento->format('d-m-Y')}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection
@section('scripts')
	<script>
	$(document).ready(function(){
		$("#btn_filtrar").click(function(){
			window.location.replace($("#mes").val());
		});
	});	
</script>
@endsection