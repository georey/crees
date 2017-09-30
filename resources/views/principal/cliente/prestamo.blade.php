@extends('layouts.master')
@section('title')
    Clientes
@stop
@section('content')
<form id="create" method="POST" action="{{action('principal\clienteController@prestamoSave')}}" autocomplete="off" class="form-horizontal form-with-validation">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="box-body">
	<div class="col-md-6">
		<div class="form-group col-md-10">
			<input type="hidden" name="cliente_id" value="{{$cliente->id}}">
			@include("layouts.form.input_text", array(
												'label' => 'Monto',
												'name' => 'monto',
												'value' => null,
												'validations' => array(['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'No cuotas',
												'name' => 'cuotas',
												'value' => null,
												'validations' => array(['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.select", array(
										'label' => 'Lineas',
										'name' => 'linea_id',
										'value' => null,
										'options' => $lineas,
										'option_value' => array('nombre', 'periodo'),
										'option_aditional_data' => array('tasa_anual', 'indice_conversion', 'tasa_mora', 'multa')
										))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Tasa',
												'name' => 'tasa',
												'value' => null,
												'validations' => array(['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			<button type="button" id="btn_calcular" class="btn btn-info pull-right">Calcular</button>
			<div class="info-box">
            	<span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
	            <div class="info-box-content">
	              <span class="info-box-text">Cuota</span>
	              <span class="info-box-number" id="spn_cuota">$0.0</span>
	              <input type="hidden" name="cuota" id="cuota">
	            </div>
			</div>
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Tasa moratoria',
												'name' => 'tasa_mora',
												'value' => null,
												'validations' => array(['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Multa',
												'name' => 'multa',
												'value' => null,
												'validations' => array(['type' => 'minlength', 'parameter' => 3])
												))
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group col-md-10">
			@include("layouts.form.input_text", array(
												'label' => 'Fecha',
												'name' => 'fecha',
												'value' => null,
												'mask' => '99-99-9999',
												'validations' => array(['type' => 'minlength', 'parameter' => 3])
												))
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.multiselect", array(
													'label' => 'Gastos',
													'name' => 'gastos',
													'value' => null,
													'options' => null,
													'option_value' => null,
													'selected_options' => null
													))
		</div>
		<div class="form-group col-md-10">
			<label for="prestamos">
			    <strong> Prestamos a liquidar: </strong>
			</label>
		    <select id="prestamos" name="prestamos[]" class="form-control select2" multiple="multiple">
		        @foreach($prestamos_activos as $prestamo)
		            <option value = "{{ $prestamo->id }}-{{ $prestamo->saldoAnterior() + $prestamo->getInteres() + $prestamo->getMora() + $prestamo->getMulta() }}">
		                {{ $prestamo->codigo }} - {{ $prestamo->saldoAnterior() + $prestamo->getInteres() + $prestamo->getMora() + $prestamo->getMulta() }}
		            </option>
		        @endforeach
		    </select>
		</div>
		<div class="form-group col-md-10">
			@include("layouts.form.multiselect", array(
													'label' => 'Fiadores',
													'name' => 'fiadores',
													'value' => null,
													'options' => $clientes,
													'option_value' => array('codigo', 'nombre', 'apellido'),
													'selected_options' => null
													))
		</div>
		<div class="form-group col-md-10">
				@include("layouts.form.textarea", array(
											'label' => 'Garantias',
											'name' => 'garantia',
											'value' => null,
											'validations' => array(['type' => 'minlength', 'parameter' => 3])
											))
		</div>
		<div class="form-group col-md-10">
				@include("layouts.form.textarea", array(
											'label' => 'Observaciones',
											'name' => 'observaciones',
											'value' => null,
											'validations' => array(['type' => 'minlength', 'parameter' => 3])
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
		    	<th>Codigo</th>
		    	<th>Monto</th>
		    	<th>Descuento</th>
		    	<th>Liquido a recibir</th>
		    	<th>Linea</th>
		    	<th>No Cuotas</th>
		    	<th>Monto Cuotas</th>
		    	<th>Fecha</th>
		    	<th>Estado</th>
		    	<th>Acciones</th>
		    </tr>
		</thead>
		<tbody>
			@if (isset($prestamos))
			@foreach($prestamos as $prestamo)
				<tr>
					<td>{{$prestamo->codigo}}</td>
					<td>{{$prestamo->monto}}</td>
					<td>{{$prestamo->descuento}}</td>
					<td>{{$prestamo->liquido}}</td>
			    	<td>{{$prestamo->linea->nombre}}</td>
			    	<td>{{$prestamo->cuotas}}</td>
			    	<td>{{$prestamo->cuota}}</td>
			    	<td>{{$prestamo->created_at}}</td>
			    	<td>{{$prestamo->estadoPrestamo->estado}}</td>
			    	<td>
			    		<a class="btn_permiso" href="{{url('clientes/pdf_pagare_sin_protesto/'.$prestamo->id)}}" title="Pagare sin protesto" target="_blank">
	    					<i class="glyphicon glyphicon-th-list"></i>
						</a>
						<a class="btn_permiso" href="{{url('clientes/pdf_hoja_liquidacion/'.$prestamo->id)}}" title="Hoja de liquidacion" target="_blank">
	    					<i class="glyphicon glyphicon-list-alt"></i>
						</a>
						@if($prestamo->estado_prestamo_id != 4)
						<a class="btn_permiso btn_anular_prestamo" data-prestamo-id="{{$prestamo->id}}" title="Anular prestamo">
	    					<i class="glyphicon glyphicon-remove-circle"></i>
						</a>
						@endif
			    	</td>
				</tr>
			@endforeach
			@endif
		</tbody>
      </table>
    </div>
</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('scripts/prestamos/calculadora.js') }}"></script>
@endsection