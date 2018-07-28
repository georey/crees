<div style="overflow-y: auto;">
<table class="table table-responsive table-bordered table-striped">
	<thead>
		<tr>
			<th>Año</th>
			<th>mes</th>
			<th>nombre</th>
			<th>Tipo_per</th>
			<th>Num_ptmo</th>
			<th>inst</th>
			<th>fec_otor</th>
			<th>monto</th>
			<th>plazo</th>
			<th>saldo</th>
			<th>mora</th>
			<th>forma_pag</th>
			<th>tipo_rel</th>
			<th>linea_cre</th>
			<th>dias</th>
			<th>ult_pag</th>
			<th>tipo_gar</th>
			<th>tipo_mon</th>
			<th>valcuota</th>
			<th>dia</th>
			<th>fechanac</th>
			<th>dui</th>
			<th>nit</th>
			<th>fecha_can</th>
			<th>fecha_ven</th>
			<th>ncuotascre</th>
			<th>calif_act</th>
			<th>activi_eco</th>
			<th>sexo</th>
			<th>estcredito</th>
		</tr>
	</thead>
	<tbody>
		@foreach($prestamos as $prestamo)
			{{--*/ $fecha = Carbon\Carbon::parse($prestamo->fecha) /*--}}
			{{--*/ $fecha_ultimo_pago = Carbon\Carbon::parse($prestamo->getUltimaFecha()) /*--}}
			{{--*/ $fecha_nacimiento = Carbon\Carbon::parse($prestamo->cliente->fecha_nacimiento) /*--}}
    		<tr>
    			<td>{{$fecha->year}}</td>
    			<td>{{str_pad($fecha->month,2,'0',STR_PAD_LEFT)}}</td>
    			<td>{{$prestamo->nombre_completo}}</td>
    			<td>1</td>
    			<td>{{$prestamo->codigo}}</td>
    			<td></td>
    			<td>{{$fecha->format("d/m/Y")}}</td>
    			<td>{{$prestamo->monto}}</td>
    			<td>{{number_format($prestamo->meses,0)}}</td>
    			<td>{{$prestamo->saldoAnterior()}}</td>
    			<td>{{number_format($prestamo->montoCuotas() + $prestamo->getMora() + $prestamo->getMulta() + $prestamo->getInteres() + $prestamo->getCapitalPendiente(),2)}}</td>
    			<td>{{$prestamo->linea->id_infored}}</td>    			
    			<td>1</td>
    			<td>COM</td>
    			<td>{{$fecha->diffInDays($fecha_ultimo_pago)}}</td>
    			<td>{{$fecha_ultimo_pago->format("d/m/Y")}}</td>
    			<td>TIPO GARANTIA PENDIENTE</td>
    			<td>02</td>
    			<td>{{$prestamo->cuota}}</td>
    			<td>{{$fecha->endOfMonth()->day}}</td>
    			<td>{{$fecha_nacimiento->format("d/m/Y")}}</td>
    			<td>{{$prestamo->cliente->dui}}</td>
    			<td>{{$prestamo->cliente->nit}}</td>
    			<td>
    				@if($prestamo->saldoAnterior() == 0)
   						{{$fecha_ultimo_pago->format("d/m/Y")}}
   					@endif
   				</td>
    			<td>{{$prestamo->getFechaVencimiento()->format('d/m/Y')}}</td>
    			<td>{{$prestamo->cuotas}}</td>
    			<td>{{$prestamo->getClasificacion()}}</td>
    			<td>COMERCIANTE</td>
    			<td>{{str_limit($prestamo->cliente->getSexo(), 1, '')}}</td>
    			<td>{{$prestamo->getEstadoInfored()}}</td>
    		</tr>
    	@endforeach
	</tbody>
</table>
</div>
