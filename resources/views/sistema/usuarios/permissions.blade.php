<div style="display: none">
	@include('layouts.table.edit_permission')
    @include('layouts.table.delete_permission')
    @include('layouts.table.restore_permission')
    <a class="btn_permiso" href="{{url(Request::url().'/negocios/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-lock"></i>
	</a>
	<a class="btn_permiso" href="{{url(Request::url().'/prestamos/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-usd"></i>
	</a>
	<a class="btn_permiso" href="{{url(Request::url().'/historial/permiso_data_id')}}">
	    <i class="glyphicon glyphicon-list-alt"></i>
	</a>
</div>