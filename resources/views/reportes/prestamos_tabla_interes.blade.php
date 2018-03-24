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
	        			<td>{{$prestamo->Codigo}}</td>
	        			<td>{{$prestamo->Nombre.' '.$prestamo->Apellido}}</td>
	        			<td>{{number_format($prestamo->Capital, 2)}}</td>
	        			<td>{{number_format($prestamo->Interes, 2)}}</td>
	        			<td>{{number_format($prestamo->Mora, 2)}}</td>
	        			<td>{{number_format($prestamo->Multa, 2)}}</td>
	        			<th>{{number_format($prestamo->Capital+$prestamo->Interes+$prestamo->Mora+$prestamo->Multa, 2)}}</th>
	        		</tr>
	        	@endforeach
	        </tbody>
	        <tfoot>
	        	<th colspan="2" style="text-align:right">TOTAL:</th>
	        	<th>{{number_format($prestamos->sum('Capital'), 2)}}</th>
	        	<th>{{number_format($prestamos->sum('Interes'), 2)}}</th>
	        	<th>{{number_format($prestamos->sum('Mora'), 2)}}</th>
	        	<th>{{number_format($prestamos->sum('Multa'), 2)}}</th>
	        	<th>{{number_format($prestamos->sum('Capital')+$prestamos->sum('Interes')+$prestamos->sum('Mora')+$prestamos->sum('Multa'), 2)}}</th>
	        </tfoot>
	      </table>
	    </div>
	</div>
</div>