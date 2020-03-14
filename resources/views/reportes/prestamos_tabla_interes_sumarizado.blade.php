<style>
	table, th, td {
	  border: 1px solid black;
	  border-collapse: collapse;
	  padding: 2px;
	}
</style>
<div class="box-body">
    <div class="box">
	    <div class="box-body">
	      <table class="table table-bordered table-striped" style="width: 100%;">
	        <thead>
	        	<tr>
	        		<th>Fecha</th>
	        		<th>Capital</th>
					<th>Refill</th>
	        		<th>Interes</th>
	        		<th>Mora</th>
	        		<th>Multa</th>
	        		<th>Tramites</th>
	        		<th>Total</th>
	        	</tr>
	        </thead>
	        <tbody>
	        	@foreach($prestamos as $prestamo)
	        		<tr>
	        			<td>{{$prestamo->fecha->format('d-m-Y')}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->capital, 2)}}</td>
						<td style="text-align:right">{{number_format($prestamo->refill, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->interes, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->mora, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->multa, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->tramites, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->interes+$prestamo->mora+$prestamo->multa+$prestamo->tramites, 2)}}</td>
	        		</tr>
	        	@endforeach
	        </tbody>
	        <tfoot>
	        	<tr>
		        	<th style="text-align:right">TOTAL:</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('capital'), 2)}}</th>
					<th style="text-align:right">{{number_format($prestamos->sum('refill'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('interes'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('mora'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('multa'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('tramites'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('interes')+$prestamos->sum('mora')+$prestamos->sum('multa')+$prestamos->sum('tramites'), 2)}}</th>
	        	</tr>
	        </tfoot>
	      </table>
	    </div>
	</div>
</div>