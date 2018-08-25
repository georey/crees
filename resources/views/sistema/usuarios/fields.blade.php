<div class="box-body">
	<div class="col-md-10 col-md-offset-1">
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Nombre',
												'name' => 'nombre',
												'value' => isset($usuario) ? $usuario : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Apellido',
												'name' => 'apellido',
												'value' => isset($usuario) ? $usuario : null)
												)
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Username',
												'name' => 'username',
												'value' => isset($usuario) ? $usuario : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_pass", array(
												'label' => 'Password',
												'name' => 'password',
												'value' => null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
										'label' => 'Rol',
										'name' => 'rol_id',
										'value' => isset($usuario) ? $usuario : null,
										'options' => $roles,
										'option_value' => array('nombre'),
										'validations' => array(['type' => 'required'])
										))
		</div>		
	</div>	
</div>
<div class="clearfix"></div>
<div class="box-footer">
	<a href="{!! route('usuarios.index') !!}" class="btn btn-default pull-right">Cancelar</a>
	<button type="submit" class="btn btn-info pull-right">Guardar</button>
</div>
@section('scripts')
@endsection