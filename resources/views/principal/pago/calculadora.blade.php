@extends('layouts.master')
@section('title')
    Plan de pagos
@stop
@section('content')
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Calculadora</a></li>
		<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Amortizacion</a></li>
	</ul>            
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1">
			<form id="create" method="POST" action="" autocomplete="off" class="form-horizontal form-with-validation">
			<div class="box-body">
				<div class="col-md-6">
					<div class="form-group col-md-10">
						<label for="prestamo_id">Cliente</label>
						<select style="width: 100%;" id="prestamo_id" name="prestamo_id" class="form-control select2 validation_required">
						    <option>-- Seleccione una opcion --</option>
						    @foreach($prestamos as $prestamo)
						         <option value = "{{ $prestamo->id }}" 
						         data-monto="{{$prestamo->monto}}" 
						         data-cuotas="{{$prestamo->cuotas}}"
						         data-linea="{{$prestamo->linea_id}}"
						         data-fecha="{{$prestamo->fecha->format('m-d-Y')}}"
						         >
						         	{{$prestamo->codigo . " - " . $prestamo->nombre_completo}}
						         </option>
						    @endforeach
						</select>
					</div>
					<div class="form-group col-md-10">
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
						@include("layouts.form.input_text", array(
															'label' => 'Tasa moratoria',
															'name' => 'tasa_mora',
															'value' => null
															))
					</div>
					<div class="form-group col-md-10">
						@include("layouts.form.input_text", array(
															'label' => 'Multa',
															'name' => 'multa',
															'value' => null
															))
					</div>
				</div>
				<div class="col-md-6">
				<div class="form-group col-md-10">
					<div class="info-box">
			        	<span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
			            <div class="info-box-content">
			              <span class="info-box-text">Cuota</span>
			              <span class="info-box-number" id="spn_cuota">$0.0</span>
			              <input type="hidden" name="cuota" id="cuota">
			            </div>
					</div>
				</div>
				</div>
			</div>
			<div class="box-footer">
				<a href="{!! route('clientes.index') !!}" class="btn btn-default pull-right">Cancelar</a>
				<button type="button" id="btn_calcular" class="btn btn-info pull-right">Calcular</button>
			</div>
			</form>
		</div>
		<div class="tab-pane" id="tab_2">
			<div class="box">
			    <div class="box-body">
			      <table class="table table-bordered table-striped" id="tbl_plan">
			        <thead>
					    <tr>
					    	<th>Numero</th>
					    	<th>Fecha</th>
					    	<th>Cuota</th>
					    	<th>Capital</th>
					    	<th>Interes</th>
					    	<th>Saldo</th>
					    </tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
					    	<th colspan="2">Total:</th>
					    	<th id="th_cuota">0.00</th>
					    	<th id="th_capital">0.00</th>
					    	<th id="th_interes">0.00</th>
					    	<th></th>
					    </tr>
					</tfoot>
			      </table>
			    </div>
			</div>
		</div>              
	</div>            
</div>

@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('scripts/pagos/planes.js') }}"></script>
@endsection