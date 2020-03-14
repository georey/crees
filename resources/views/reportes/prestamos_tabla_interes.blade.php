<div class="box-body">
    <div class="box">
	    <div class="box-body">
	      <table class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>Codigo</th>
	        		<th>Cliente</th>
	        		<th>Capital</th>
	        		<th>Interes</th>
	        		<th>Mora</th>
	        		<th>Multa</th>	        		
	        		<th>Total</th>
	        	</tr>
	        </thead>
	        <tbody>
	        	@foreach($prestamos as $prestamo)
	        		<tr>
	        			<td>{{$prestamo->codigo}}</td>
	        			<td>{{$prestamo->nombre.' '.$prestamo->apellido}}</td>
	        			<td>{{number_format($prestamo->capital, 2)}}</td>
	        			<td>{{number_format($prestamo->interes, 2)}}</td>
	        			<td>{{number_format($prestamo->mora, 2)}}</td>
	        			<td>{{number_format($prestamo->multa, 2)}}</td>
	        			<th>{{number_format($prestamo->capital+$prestamo->interes+$prestamo->mora+$prestamo->multa, 2)}}</th>
	        		</tr>
	        	@endforeach
	        </tbody>
	        <tfoot>
	        	<th colspan="2" style="text-align:right">TOTAL:</th>
	        	<th>{{number_format($prestamos->sum('capital'), 2)}}</th>
	        	<th>{{number_format($prestamos->sum('interes'), 2)}}</th>
	        	<th>{{number_format($prestamos->sum('mora'), 2)}}</th>
	        	<th>{{number_format($prestamos->sum('multa'), 2)}}</th>
	        	<th>{{number_format($prestamos->sum('capital')+$prestamos->sum('interes')+$prestamos->sum('mora')+$prestamos->sum('multa'), 2)}}</th>
	        </tfoot>
	      </table>
	    </div>
	</div>
</div>