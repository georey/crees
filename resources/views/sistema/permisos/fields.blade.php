<div class="box-body">
	<div class="col-md-6">
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Nombre',
												'name' => 'nombre',
												'value' => isset($permiso) ? $permiso : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Ruta',
												'name' => 'ruta',
												'value' => isset($permiso) ? $permiso : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Descripcion',
												'name' => 'descripcion',
												'value' => isset($permiso) ? $permiso : null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Icono',
												'name' => 'icono',
												'value' => isset($permiso) ? $permiso : null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Orden',
												'name' => 'order_by',
												'value' => isset($permiso) ? $permiso : null,
												'validations' => array(['type' => 'required'])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
										'label' => 'Padre',
										'name' => 'parent_id',
										'value' => isset($permiso) ? $permiso : null,
										'options' => $parents,
										'option_value' => array('nombre')
										))
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="box-footer">
	<a href="{!! route('permisos.index') !!}" class="btn btn-default pull-right">Cancelar</a>
	<button type="submit" class="btn btn-info pull-right">Guardar</button>
</div>