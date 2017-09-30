<div class="box-body">
    <div class="box">
	    <div class="box-body">
	      <table class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>Codigo</th>
	        		<th>Cliente</th>
	        		<th>Monto</th>
	        		<th>Tramite refinanciado</th>
	        		<th>Desembolso</th>
	        		<th>Plazo</th>
	        		<th>Forma de pago</th>
	        		<th>Fecha</th>
	        	</tr>
	        </thead>
	        <tbody>
	        	@foreach($prestamos as $prestamo)
	        		<tr>
	        			<td>{{$prestamo->codigo}}</td>
	        			<td>{{$prestamo->cliente->nombreCompleto()}}</td>
	        			<td>{{$prestamo->monto}}</td>
	        			<td></td>
	        			<td></td>
	        			<td>{{$prestamo->cuotas}}</td>
	        			<td>{{$prestamo->linea->periodo}}</td>
	        			<td></td>
	        		</tr>
	        	@endforeach
	        </tbody>
	      </table>
	    </div>
	</div>
</div>