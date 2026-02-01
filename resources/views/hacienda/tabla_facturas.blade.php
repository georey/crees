<table class="table table-bordered table-striped table-medium-text">
	<thead>
		<tr>
			<th>Fecha</th>
            <th>Estado</th>
			<th>Cliente</th>			
			<th>Numero Control</th>
			<th>Cod Generacion</th>
			<th>Sello Recepcion</th>
			<th>Exentas</th>
			<th>No sujetas</th>
			<th>Gravadas</th>
            <th>Total</th>
		</tr>
	</thead>
	<tbody>
		@foreach($facturas as $factura)
			<tr>
				<td>{{$factura->created_at->format('d-m-Y')}}</td>
                <td>AUTORIZADO</td>
				<td>{{$factura->getDestinatario()->nombre}}</td>
				<td>{{$factura->numero_control}}</td>
				<td>{{$factura->codigo_generacion}}</td>
				<td>{{$factura->sello_recepcion}}</td>
				<td>{{isset($factura->getJsonDecode()->resumen->totalExenta) ? $factura->getJsonDecode()->resumen->totalExenta : 0}}</td>
                <td>{{isset($factura->getJsonDecode()->resumen->totalNoSuj) ? $factura->getJsonDecode()->resumen->totalNoSuj : 0}}</td>
                <td>{{isset($factura->getJsonDecode()->resumen->totalGravada) ? $factura->getJsonDecode()->resumen->totalGravada : 0}}</td>
                <td>{{isset($factura->getJsonDecode()->resumen->montoTotalOperacion) ? $factura->getJsonDecode()->resumen->montoTotalOperacion : 0}}</td>
            </tr>			
		@endforeach
	</tbody>
</table>