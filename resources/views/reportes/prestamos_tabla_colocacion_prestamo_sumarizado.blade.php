<div class="box-body">
    <div class="box">
	    <div class="box-body">
	      <table class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>Fecha</th>
	        		<th>Linea</th>
	        		<th>Monto</th>
	        		<th>Liquido</th>
	        		<th>Tramites</th>
	        		<th>Refinanciamiento</th>
	        	</tr>
	        </thead>
	        <tbody>
	        	@foreach($prestamos as $prestamo)
	        		<tr>
	        			<td>{{$prestamo->Fecha}}</td>
	        			<td>{{$prestamo->Linea}}</td>
	        			<td>{{$prestamo->Monto}}</td>
	        			<td>{{$prestamo->Liquido}}</td>
	        			<td>{{$prestamo->total_gastos}}</td>
	        			<td>{{$prestamo->total_liquidacion}}</td>
	        		</tr>
	        	@endforeach
	        </tbody>
	        <tfoot>
	        	<tr>
		        	<th colspan="2" style="text-align:right">TOTAL:</th>
		        	<th>{{number_format($prestamos->sum('Monto'), 2)}}</th>
		        	<th>{{number_format($prestamos->sum('Liquido'), 2)}}</th>
		        	<th>{{number_format($prestamos->sum('total_gastos'),2)}}</th>
		        	<th>{{number_format($prestamos->sum('total_liquidacion'),2)}}</th>
	        	</tr>
	        </tfoot>
	      </table>
	    </div>
	</div>
</div>