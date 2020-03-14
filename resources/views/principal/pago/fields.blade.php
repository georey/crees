<div class="box-body">
	<div class="col-md-3">
		<div class="form-group">
          	<div class="small-box bg-aqua">
            	<div class="inner">
              		<h3 id="h3_saldo_anterior">0.00</h3>
              		<p>Saldo anterior</p>
              		<h3 id="h3_cuota_acordada">0.00</h3>
              		<p>Cuota</p>
              		<h3 id="h3_proxma_fecha">dd/mm/yyyy</h3>
              		<p>Fecha vencimiento</p>
              		<h3 id="h3_dias_transcurridos">0</h3>
              		<p>Dias desde ultimo pago</p>
            	</div>
            <div class="icon">
              	<i class="ion-soup-can-outline"></i>
            </div>
          </div>
        </div>
        <div class="form-group">
          	<div class="small-box bg-green">
            	<div class="inner">
	              	<h4 id="h3_capital">0.00</h4>
	              	<p>Capital</p>
	              	<h4 id="h3_interes">0.00</h4>
              		<p>Interes</p>
              		<h3 id="h3_total_pagar">0.00</h3>
              		<p>Total a pagar</p>
              		<h3 id="h3_deuda_total">0.00</h3>
              		<p>Deuda total</p>
            	</div>
	            <div class="icon">
	              	<i class="ion-social-usd-outline"></i>
	            </div>
          	</div>
        </div>
	</div>
	<div class="col-md-9">
		<div class="form-group col-md-12">
			<label><strong> Fecha de pago </strong></label>
			<input type="text" class="form-control" placeholder="Ingrese fecha de pago" id="fecha" name="fecha" class="form-control" data-inputmask="'mask': '99-99-9999'" data-mask value="{{ $fecha->format('d-m-Y') }}">
			</input>
		</div>
		<div class="form-group col-md-12">
			<label for="prestamo_id">Cliente</label>
			<select style="width: 100%;" id="prestamo_id" name="prestamo_id" class="form-control select2 validation_required">
			    <option>-- Seleccione una opcion --</option>
			    @foreach($prestamos as $prestamo)
			         <option value = "{{ $prestamo->id }}" 
		         	 data-monto="{{$prestamo->monto}}" 
		         	 data-cuotas="{{$prestamo->cuotas}}" 
		         	 data-tasa="{{$prestamo->linea->tasa_anual}}" 
		         	 data-indice_conversion="{{$prestamo->linea->indice_conversion}}"
		         	 data-fecha-inicio="{{$prestamo->fecha->format('m-d-Y')}}"
			         data-saldo="{{$prestamo->saldoAnterior()}}" 
			         data-cuota-acordada="{{$prestamo->cuota}}"
			         data-cuota="{{$prestamo->montoCuotas()}}" 
			         data-mora="{{$prestamo->getMora()}}" 
			         data-mora-pendiente="{{$prestamo->getMoraPendiente()}}" 
			         data-multa="{{$prestamo->getMulta()}}" 
			         data-multa-pendiente="{{$prestamo->getMultaPendiente()}}" 
			         data-cobrador="{{$prestamo->cobrador_id}}" 
			         data-interes="{{$prestamo->getInteres()}}" 
			         data-interes-pendiente="{{$prestamo->getInteresesPendientes()}}"
			         data-fecha="{{$prestamo->getFechaVencimiento()->format('d/m/Y')}}" 
			         data-dias-transcurridos="{{$prestamo->getDias()}}" 
			         data-capital-pendiente="{{$prestamo->getCapitalPendiente()}}"
			         data-proxima-fecha="{{$prestamo->getProximaFecha()}} {{$prestamo->getFechaActualSinHora()}}" 
			         data-cuotas="{{$prestamo->getNumeroCuotas()}}"
			         data-cuotas-dia="{{$prestamo->getNumeroCuotas(true)}}" >
			         	{{$prestamo->codigo . " - " . $prestamo->nombre_completo}}
			         </option>
			    @endforeach
			</select>
		</div>
		<div class="form-group col-md-12">
			@include("layouts.form.input_text", array(
												'label' => 'Cuota',
												'name' => 'cuota',
												'value' => null,
												'validations' => array(['type' => 'required']),
												'required' => 'required'
												))
		</div>
		<div class="form-group col-md-12">
			@include("layouts.form.input_text", array(
												'label' => 'Mora',
												'name' => 'mora',
												'value' => null,
												'validations' => array(['type' => 'required']),
												'required' => 'required'
												))
		</div>
		<div class="form-group col-md-12">
			@include("layouts.form.input_text", array(
												'label' => 'Multa',
												'name' => 'multa',
												'value' => null,
												'validations' => array(['type' => 'required']),
												'required' => 'required'
												))
		</div>
		<div class="form-group col-md-12">
			@include("layouts.form.select", array(
										'label' => 'Cobrador',
										'name' => 'cobrador_id',
										'value' => null,
										'options' => $cobradores,
										'option_value' => array('nombre'),
										'validations' => array(['type' => 'required'])
										))
		</div>
		<div class="form-group col-md-12">
			<input class="form-control" placeholder="Capital pendiente" id="hdn_capital" name="hdn_capital" type="hidden">
		</div>
		<div class="clearfix"></div>
		<div class="box-footer">
			<a href="{!! route('pagos.index') !!}" class="btn btn-default pull-right">Regresar</a>&nbsp;&nbsp;&nbsp;
			<button type="submit" name="btn_enviar" value="recibo" class="btn btn-success pull-right">Guardar y Generar Recibo</button>&nbsp;&nbsp;&nbsp;
			<button tabindex="0" type="submit" name="btn_enviar" value="guardar" class="btn btn-info pull-right">Guardar</button>			
		</div>
	</div>
</div>
@section('scripts')
	<script type="text/javascript" src="{{ asset('scripts/pagos/calculadora.js') }}?2"></script>
@endsection