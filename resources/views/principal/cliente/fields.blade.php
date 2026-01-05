<div class="box-body">
	<div class="col-md-4">
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'DUI',
												'name' => 'dui',
												'mask' => '99999999-9',
												'value' => isset($cliente) ? $cliente : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'NIT',
												'name' => 'nit',
												'mask' => '9999-999999-999-9',
												'value' => isset($cliente) ? $cliente : null)
												)
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'NRC',
												'name' => 'nrc',
												'value' => isset($cliente) ? $cliente : null)
												)
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Nombre',
												'name' => 'nombre',
												'value' => isset($cliente) ? $cliente : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Apellido',
												'name' => 'apellido',
												'value' => isset($cliente) ? $cliente : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.textarea", array(
											'label' => 'Direccion',
											'name' => 'direccion',
											'value' => isset($cliente) ? $cliente : null,
											'validations' => array(['type' => 'minlength', 'parameter' => 3])
											))
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group col-md-10">
			<label for="departamento_id">Departamento</label>
			<select style="width: 100%;" id="departamento_id" name="departamento_id" class="form-control select2">
			    <option>-- Seleccione una opcion --</option>
			    @foreach($departamentos as $departamento)
			         <option value = "{{ $departamento->id }}" data-codigo="{{ $departamento->codigo }}" {{ $departamento->id == (isset($cliente->departamento_id) ? $cliente->departamento_id : '') ? 'selected="selected"': ''}}>
			                {{$departamento->nombre}}
			         </option>
			    @endforeach
			</select>
		</div>
		<div class="form-group col-md-10">
			<label for="municipio_id">Municipio</label>
			<select style="width: 100%;" id="municipio_id" name="municipio_id" class="form-control select2">
			    <option>-- Seleccione una opcion --</option>
				@foreach($municipios as $municipio)
			         <option value = "{{ $municipio->id }}" {{ $municipio->id == (isset($cliente->municipio_id) ? $cliente->municipio_id : '') ? 'selected="selected"': ''}}>
			                {{$municipio->nombre}}
			         </option>
			    @endforeach
			</select>
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Telefono',
												'name' => 'telefono',
												'mask' => '9999-9999',
												'value' => isset($cliente) ? $cliente : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Correo',
												'name' => 'correo',
												'value' => isset($cliente) ? $cliente : null)
												)
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
										'label' => 'Zona',
										'name' => 'zona_id',
										'value' => isset($cliente) ? $cliente : null,
										'options' => $zonas,
										'option_value' => array('nombre'),
										'attributes' => array('data-category' => isset($cliente['zona_id']) ? intval($cliente['zona_id']) : 0 ),
										'validations' => array(['type' => 'required'])
										))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
										'label' => 'Profesion',
										'name' => 'profesion_id',
										'value' => isset($cliente) ? $cliente : null,
										'options' => $profesiones,
										'option_value' => array('nombre'),
										'attributes' => array('data-category' => isset($cliente['profesion_id']) ? intval($cliente['profesion_id']) : 0 ),
										'validations' => array(['type' => 'required'])
										))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
                                                'col' => 3,
                                                'label' => 'Sexo',
                                                'name' => 'sexo',
                                                'value' => isset($cliente) ? $cliente : null,
                                                'options' => array(
                                                                    array('id' => 1, 'sexo' => 'Masculino'),
                                                                    array('id' => 2, 'sexo' => 'Femenino')),
                                                'option_value' => array('sexo'),
												'attributes' => array('data-category' => isset($cliente['sexo']) ? intval($cliente['sexo']) : 0 ),
                                                'validations' => array(['type' => 'required'])
                                                ))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
										'label' => 'Estado civil',
										'name' => 'estado_civil_id',
										'value' => isset($cliente) ? $cliente : null,
										'options' => $estados_civiles,
										'option_value' => array('nombre'),
										'attributes' => array('data-category' => isset($cliente['estado_civil_id']) ? intval($cliente['estado_civil_id']) : 0 ),
										'validations' => array(['type' => 'required'])
										))
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group col-md-10">
			<label><strong> Fecha de nacimiento </strong></label>
			<input type="text" class="form-control" placeholder="Ingrese fecha de nacimiento" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" data-inputmask='"mask": "99-99-9999"' data-mask value="{{ isset($cliente) ? $cliente->fecha_nacimiento->format('d-m-Y') : ''}}">
			</input>
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Conyuge',
												'name' => 'conyuge',
												'value' => isset($cliente) ? $cliente : null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Conyuge Telefono',
												'name' => 'conyuge_telefono',
												'value' => isset($cliente) ? $cliente : null,
												'mask' => '9999-9999',
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.textarea", array(
												'label' => 'Observaciones',
												'name' => 'observaciones',
												'value' => isset($cliente) ? $cliente : null,
												'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
												))
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="box-footer">
	<a href="{!! route('clientes.index') !!}" class="btn btn-default pull-right">Cancelar</a>
	<button type="submit" class="btn btn-info pull-right">Guardar</button>
</div>
<div class="clearfix"></div>
<div class="box">
    <div class="box-body">
      <table class="table table-bordered table-striped crud-datatable">
        <thead>
		    <tr>
		    	<th>Nombre</th>
		    	<th>Direccion</th>
		    	<th>Telefono</th>
		    	<th>Antiguedad</th>
		    	<th>Empleados</th>
		    	<th>Dias de trabajo</th>
		    	<th>Horario</th>
		    	<th>Tipo de negocio</th>
		    </tr>
		</thead>
		<tbody>
			@if (isset($negocios))
			@foreach($negocios as $negocio)
				<tr>
					<td>{{$negocio->nombre}}</td>
			    	<td>{{$negocio->direccion .', '. $negocio->municipio->nombre . '-' . $negocio->municipio->departamento->nombre}}</td>
			    	<td>{{$negocio->telefono}}</td>
			    	<td>{{$negocio->edad}}</td>
			    	<td>{{$negocio->empleados}}</td>
			    	<td>{{$negocio->dias_trabajo}}</td>
			    	<td>{{$negocio->horario}}</td>
			    	<td>{{$negocio->tipo_negocio->tipo or ''}}</td>
				</tr>
			@endforeach
			@endif
		</tbody>
      </table>
    </div>
</div>
@section('scripts')
	<script type="text/javascript" src="{{ asset('scripts/clientes/validaciones.js') }}"></script>
	<script type="text/javascript" src="{{ asset('scripts/clientes/cliente.js') }}"></script>
@endsection