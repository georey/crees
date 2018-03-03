<div class="box-body">
	<div class="col-md-6">
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Nombre',
												'name' => 'nombre',
												'value' => isset($linea) ? $linea : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Periodo',
												'name' => 'periodo',
												'value' => isset($linea) ? $linea : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Tasa anual',
												'name' => 'tasa_anual',
												'value' => isset($linea) ? $linea : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Tasa moratoria',
												'name' => 'tasa_mora',
												'value' => isset($linea) ? $linea : null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Multa',
												'name' => 'multa',
												'value' => isset($linea) ? $linea : null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Factor de tiempo',
												'name' => 'indice_conversion',
												'value' => isset($linea) ? $linea : null,
												'validations' => array(['type' => 'required'])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Porcentaje',
												'name' => 'cobro_porcentaje',
												'value' => isset($linea) ? $linea : null,
												'validations' => array(['type' => 'required'])
												))
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="box-footer">
	<a href="{!! route('linea.index') !!}" class="btn btn-default pull-right">Cancelar</a>
	<button type="submit" class="btn btn-info pull-right">Guardar</button>
</div>