<div class="box-body">
	<div class="form-group col-md-12">
		<label for="prestamo_id">Cliente</label>
		<select style="width: 100%;" id="prestamo_id" name="prestamo_id" class="form-control select2 validation_required">
		    <option>-- Seleccione una opcion --</option>
		    @foreach($prestamos as $prestamo)
		         <option value = "{{ $prestamo->id }}" 
		         data-saldo="{{$prestamo->saldoAnterior()}}" 
		         data-cuota-acordada="{{$prestamo->cuota}}"
		         data-cuota="{{$prestamo->montoCuotas()}}" 
		         data-mora="{{$prestamo->getMora()}}" 
		         data-multa="{{$prestamo->getMulta()}}"
		         data-cobrador="{{$prestamo->cobrador_id}}"
		         data-interes="{{$prestamo->getInteres()}}"
		         data-tasa-interes="{{$prestamo->tasa}}"
		         data-tasa-mora="{{$prestamo->tasa_mora}}"
		         data-fecha="{{$prestamo->getFechaVencimiento()->format('d/m/Y')}}" 
		         data-dias-transcurridos="{{$prestamo->getDias()}}" 
		         data-capital-pendiente="{{$prestamo->getCapitalPendiente()}}">
		         	{{$prestamo->codigo . " - " . $prestamo->nombre_completo}}
		         </option>
		    @endforeach
		</select>
	</div>
	<div class="form-group col-md-12">
		@include("layouts.form.textarea", array(
											'label' => 'Justificacion',
											'name' => 'justificacion',
											'value' => isset($asesor) ? $asesor : null,
											'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
											))
	</div>
	<div class="form-group col-md-12">
		<label for="prestamo_id">Eliminar</label>
		<div class="form-group">
			<div class="checkbox col-md-4">
				<label>
					<input class="chk_valor" type="checkbox" id="chk_interes" name="chk_interes" data-valor="0">
					Interes
				</label>
			</div>
			<div class="checkbox col-md-4">
				<label>
					<input class="chk_valor" type="checkbox" id="chk_multa" name="chk_multa" data-valor="0">
					Multa
				</label>
			</div>			
			<div class="checkbox col-md-4">
				<label>
					<input class="chk_valor" type="checkbox" id="chk_mora" name="chk_mora" data-valor="0">
					Mora
				</label>
			</div>
		</div>
	</div>
	<div class="form-group col-md-12">
		@include("layouts.form.input_text", array(
											'label' => 'Tasa Interes',
											'name' => 'interes',
											'value' => null,
											'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
											))
	</div>
	<div class="form-group col-md-12">
		@include("layouts.form.input_text", array(
											'label' => 'Tasa Moratoria',
											'name' => 'tasa_mora',
											'value' => null,
											'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
											))
	</div>
	<div class="form-group col-md-12">
		@include("layouts.form.input_text", array(
											'label' => 'Monto Pendiente',
											'name' => 'monto_pendiente',
											'value' => null,
											'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
											))
	</div>
	<div class="form-group col-md-12">
		@include("layouts.form.input_text", array(
											'label' => 'Monto Actual',
											'name' => 'monto_actual',
											'value' => null,
											'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
											))
	</div>
	<div class="form-group col-md-12">
		@include("layouts.form.input_text", array(
											'label' => 'Interes Nuevo',
											'name' => 'interes_pendiente',
											'value' => 0,
											'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
											))
	</div>
</div>
<div class="clearfix"></div>
<div class="box-footer">
	<a href="{!! route('acuerdos_pagos.index') !!}" class="btn btn-default pull-right">Cancelar</a>
	<button type="submit" class="btn btn-info pull-right">Guardar</button>
</div>
@section('scripts')
	<script type="text/javascript" src="{{ asset('scripts/acuerdos/calculadora.js') }}"></script>
@endsection