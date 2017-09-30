<div class="box-body">
	<div class="col-md-6">
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Tipo',
												'name' => 'tipo',
												'value' => isset($tipo_gasto) ? $tipo_gasto : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Monto',
												'name' => 'monto',
												'value' => isset($tipo_gasto) ? $tipo_gasto : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Rango inferior',
												'name' => 'monto_min',
												'value' => isset($tipo_gasto) ? $tipo_gasto : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Rango superior',
												'name' => 'monto_max',
												'value' => isset($tipo_gasto) ? $tipo_gasto : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
										'label' => 'Linea',
										'name' => 'linea_id',
										'value' => null,
										'options' => $lineas,
										'option_value' => array('nombre'),
										'validations' => array(['type' => 'required'])
										))
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="box-footer">
	<a href="{!! route('tipo_gasto.index') !!}" class="btn btn-default pull-right">Cancelar</a>
	<button type="submit" class="btn btn-info pull-right">Guardar</button>
</div>