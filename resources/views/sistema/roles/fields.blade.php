<div class="box-body">
	<div class="col-md-6">
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Nombre',
												'name' => 'nombre',
												'value' => isset($rol) ? $rol : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="box-footer">
	<a href="{!! route('roles.index') !!}" class="btn btn-default pull-right">Cancelar</a>
	<button type="submit" class="btn btn-info pull-right">Guardar</button>
</div>