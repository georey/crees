<div style="display: none">
    <a class="btn_permiso btn_permiso_correo" data-numero-control=""data-estados="4,6" title="Reenviar Correo" href="{{url(Request::url().'/correo/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-envelope"></i>
	</a>
	<a class="btn_permiso btn_permiso_reenvio" data-numero-control=""style="display:none" data-estados="3" title="Reenvio de factura a hacienda" href="{{url(Request::url().'/reenvio_hacienda/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-refresh"></i>
	</a>
	<a class="btn_permiso btn_permiso_pdf" data-numero-control=""data-estados="4,6" title="Generar PDF" href="{{url(Request::url().'/factura_pdf/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-download-alt"></i>
	</a>
	<a class="btn_permiso btn_permiso_anular" data-numero-control="" data-estados="4,6" title="Anular factura" href="{{url(Request::url().'/anular/permiso_data_id')}}" onclick="return confirmarAnulacionFactura(this);">
		<i class="glyphicon glyphicon-remove"></i>
	</a>
</div>

<script>
function confirmarAnulacionFactura(element) {
	// Extraer el número de control desde el href
	var numeroControl = element.getAttribute('data-numero-control');
	var mensaje = '¿Está seguro que desea anular la factura?\nNúmero de control: ' + numeroControl;
	return confirm(mensaje);
}
</script>
</div>