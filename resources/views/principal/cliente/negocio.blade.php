@extends('layouts.master')
@section('title')
    Clientes
@stop
@section('content')
<form id="create" method="POST" action="{{action('principal\clienteController@negocioSave')}}" autocomplete="off" class="form-horizontal form-with-validation">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="box-body">
	<div class="col-md-6">
		<div class="form-group col-md-10">
			<input type="hidden" name="cliente_id" value="{{$cliente->id}}">
			@include("layouts.form.input_text", array(
												'label' => 'Nombre',
												'name' => 'nombre',
												'value' => null,
												'validations' => array(['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.textarea", array(
											'label' => 'Direccion',
											'name' => 'direccion',
											'value' => null,
											'validations' => array(['type' => 'minlength', 'parameter' => 3])
											))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Telefono',
												'name' => 'telefono',
												'value' => null,
												'validations' => array(['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			<label for="departamento_id">Departamento</label>
			<select style="width: 100%;" id="departamento_id" name="departamento_id" class="form-control select2">
			    <option>-- Seleccione una opcion --</option>
			    @foreach($departamentos as $departamento)
			         <option value = "{{ $departamento->id }}" {{ $departamento->id == (isset($negocio->municipio->departamento_id) ? $negocio->municipio->departamento_id : '') ? 'selected="selected"': ''}}>
			                {{$departamento->nombre}}
			         </option>
			    @endforeach
			</select>
		</div>
		<div class="form-group col-md-10">
			<label for="municipio_id">Municipio</label>
			<select style="width: 100%;" id="municipio_id" name="municipio_id" class="form-control select2">
			    <option>-- Seleccione una opcion --</option>
			</select>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Antiguedad',
												'name' => 'edad',
												'value' => null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Empleados',
												'name' => 'empleados',
												'value' => null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Dias de trabajo',
												'name' => 'dias_trabajo',
												'value' => null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Horario',
												'name' => 'horario',
												'value' => null
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
										'label' => 'Tipo de negocio',
										'name' => 'tipo_negocio_id',
										'value' => null,
										'options' => $tipo_negocio,
										'option_value' => array('tipo')
										))
		</div>
	</div>
</div>
<div class="box-footer">
	<a href="{!! route('clientes.index') !!}" class="btn btn-default pull-right">Cancelar</a>
	<button type="submit" class="btn btn-info pull-right">Guardar</button>
</div>
</form>
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
		    	<th></th>
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
			    	<th>
			    		<a data-toggle="confirmation" data-singleton="true" class="btn_delete_confirmation" href="{{ url("clientes/negocioDelete/" . $negocio->id) }}">
			    		    <i class="glyphicon glyphicon-trash"></i>
		    		    </a>
					</th>
				</tr>
			@endforeach
			@endif
		</tbody>
      </table>
    </div>
</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('scripts/clientes/negocios.js') }}"></script>
@endsection