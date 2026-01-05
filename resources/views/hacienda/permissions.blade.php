<div style="display: none">
    <a class="btn_permiso btn_permiso_correo" data-estados="4,6" title="Reenviar Correo" href="{{url(Request::url().'/correo/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-envelope"></i>
	</a>
	<a class="btn_permiso btn_permiso_reenvio" style="display:none" data-estados="3" title="Reenvio de factura a hacienda" href="{{url(Request::url().'/reenvio_hacienda/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-refresh"></i>
	</a>
	<a class="btn_permiso btn_permiso_pdf" data-estados="4,6" title="Generar PDF" href="{{url(Request::url().'/factura_pdf/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-download-alt"></i>
	</a>
</div>