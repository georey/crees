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
	        			<td style="text-align:right">{{number_format($prestamo->Capital, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->Interes, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->Mora, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->Multa, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->Tramites, 2)}}</td>
	        			<td style="text-align:right">{{number_format($prestamo->Interes+$prestamo->Mora+$prestamo->Multa+$prestamo->Tramites, 2)}}</td>
	        		</tr>
	        	@endforeach
	        </tbody>
	        <tfoot>
	        	<tr>
		        	<th style="text-align:right">TOTAL:</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('Capital'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('Interes'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('Mora'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('Multa'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('Tramites'), 2)}}</th>
		        	<th style="text-align:right">{{number_format($prestamos->sum('Interes')+$prestamos->sum('Mora')+$prestamos->sum('Multa')+$prestamos->sum('Tramites'), 2)}}</th>
	        	</tr>
	        </tfoot>
	      </table>
	    </div>
	</div>
</div>