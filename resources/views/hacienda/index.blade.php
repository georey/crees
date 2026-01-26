@extends('layouts.master')
@section('title')
Facturacion Electronica
@stop
@section('titleBreadcrumb')
Facturacion Electronica
@stop
@section('content')
	<div class="box-body">
		<div class="box">
			<div class="box-body">
				<form class="form-horizontal" role="form" method="POST" action="{{ url('/hacienda/generar_factura') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group col-md-12">
						<label for="tipo_dte">Tipo de Documento</label>
						<select style="width: 100%;" id="tipo_dte" name="tipo_dte" required class="form-control select2">
							<option value="01" selected>Factura (Consumidor Final)</option>
							<option value="03">Crédito Fiscal (Contribuyente)</option>
							<option value="14">Sujeto Excluido</option>
						</select>
					</div>

					<!-- Campos para Factura Consumidor Final (01) -->
					<div id="cliente_fields" class="form-group col-md-12">
						<label for="cliente_id">Cliente</label>
						<select style="width: 100%;" id="cliente_id" name="cliente_id"
							class="form-control select2 validation_required">
							<option>-- Seleccione una opcion --</option>
							@foreach($prestamos as $prestamo)
								<option value="{{ $prestamo->cliente->id }}">
									{{$prestamo->codigo . " - " . $prestamo->nombre_completo}}
								</option>
							@endforeach
						</select>
						<div id="missing-data" class="hidden alert alert-error"></div>
					</div>

					<!-- Campos para Crédito Fiscal (03) y Sujeto Excluido (14) -->
					<div id="receptor_manual" style="display: none;">
						<!-- Checkbox Retiene Renta para Sujeto Excluido -->
						<div class="form-group col-md-12" id="retiene_renta_field" style="display:none;">
							<div class="checkbox">
								<label>
									<input type="checkbox" id="retiene_renta" name="retiene_renta" checked>
									Retiene renta
								</label>
							</div>
						</div>
						<div class="form-group col-md-3">
							<label for="nombre">Nombre</label>
							<input type="text" class="form-control" id="nombre" name="nombre">
						</div>

						<div class="form-group col-md-3">
							<label for="apellido">Apellido</label>
							<input type="text" class="form-control" id="apellido" name="apellido">
						</div>

						<div class="form-group col-md-3">
							<label for="nit">NIT</label>
							<input type="text" class="form-control" id="nit" name="nit" placeholder="0614-000000-000-0">
						</div>

<div class="form-group col-md-3" id="nrc_field">
							<label for="nrc">NRC</label>
							<input type="text" class="form-control" id="nrc" name="nrc" placeholder="000000-0">
						</div>

						<div class="form-group col-md-6">
							<label for="correo">Correo Electrónico</label>
							<input type="email" class="form-control" id="correo" name="correo">
						</div>

						<div class="form-group col-md-6">
							<label for="telefono">Teléfono</label>
							<input type="text" class="form-control" id="telefono" name="telefono">
						</div>
					<div class="form-group col-md-4">
						<label for="departamento">Departamento</label>
						<select style="width: 100%;" id="departamento" name="departamento" class="form-control select2">
							<option value="">-- Seleccione departamento --</option>
							@foreach($departamentos as $depto)
								<option value="{{ $depto->codigo }}" data-codigo="{{ $depto->codigo }}">{{ $depto->nombre }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="municipio">Municipio</label>
						<select style="width: 100%;" id="municipio" name="municipio" class="form-control select2">
							<option value="">-- Seleccione municipio --</option>
							@foreach($municipios as $muni)
								<option value="{{ $muni->codigo }}">{{ $muni->nombre }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="complemento">Dirección</label>
						<input type="text" class="form-control" id="complemento" name="complemento" placeholder="Dirección completa">
					</div>
					<div class="form-group col-md-12" id="actividad_economica_field">
						<label for="actividad_economica">Actividad Económica</label>
						<select style="width: 100%;" id="actividad_economica" name="actividad_economica" class="form-control select2">
							<option value="">-- Seleccione una actividad económica --</option>
							@foreach($actividades_economicas as $actividad)
								<option value="{{ $actividad->codigo }}">{{ $actividad->codigo }} - {{ $actividad->descripcion }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-12" id="actividad_economica_field">
								<label for="tipo">Tipo</label>
							<select style="width: 100%;" id="tipo" name="tipo[]" class="form-control select2 validation_required">
								<option value="1">Bien</option>
								<option value="2" selected>Servicio</option>
								<option value="3">Bien y servicio</option>
							</select>
							</div>
						</div>
						@for ($i = 0; $i < 5; $i++)
						<div class="form-row col-md-12">
						<div class="form-group col-md-1">
							<label for="unidad_medida">Unidad</label>
							<select style="width: 100%;" id="unidad_medida" name="unidad_medida[]" class="form-control select2 validation_required">
									<option value="99">Otra</option>
									<option value="59">Unidad</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="descripcion">Descripcion</label>
								<input type="text" class="form-control" name="descripcion[]">
							</div>
							<div class="form-group col-md-2">
								<label for="cantidad">Cantidad</label>
								<input type="text" class="form-control" name="cantidad[]">
							</div>
							
							<div class="form-group col-md-2">
								<label for="precio_unitario">Precio U</label>
								<input type="text" class="form-control" name="precio_unitario[]">
							</div>
							<div class="form-group col-md-1">
								<label for="descuento">Descuento</label>
								<input type="text" class="form-control" name="descuento[]">							
							</div>
							<div class="form-group col-md-1">
								<label for="no_suj">No Suj</label>
								<input type="text" class="form-control" name="no_suj[]">										
							</div>
							<div class="form-group col-md-1">
								<label for="exenta">Exenta</label>
								<input type="text" class="form-control" name="exenta[]">		
							</div>
					
</div>
						@endfor
					</div>
 <div id="items-container"></div>
					<button type="submit">Generar</button>
				</form>
			</div>
				</div>
			</div>
@endsection
@section('scripts')
	
	<script type="text/javascript">
		$(document).ready(function() {
			// Función para mostrar/ocultar campos según tipo de documento
			function toggleReceptorFields() {
				var tipoDte = $('#tipo_dte').val();
				if (tipoDte == '01') {
					// Factura consumidor final - mostrar select de cliente
					$('#cliente_fields').show();
					$('#receptor_manual').hide();				$('#nrc_field').show();					$('#actividad_economica_field').hide();
					$('#cliente_id').prop('required', true);
					$('#nombre, #apellido').prop('required', false);
					$('#actividad_economica').prop('required', false);
			} else if (tipoDte == '03') {
				// Crédito fiscal - mostrar campos manuales, NRC y actividad económica
				$('#cliente_fields').hide();
				$('#receptor_manual').show();
				$('#nrc_field').show();
				$('#actividad_economica_field').show();
				$('#cliente_id').prop('required', false);
				$('#nombre, #apellido, #actividad_economica').prop('required', true);
			} else if (tipoDte == '14') {
				// Sujeto excluido - mostrar campos manuales y actividad económica, ocultar NRC
				$('#cliente_fields').hide();
				$('#receptor_manual').show();
				$('#nrc_field').hide();
				$('#actividad_economica_field').show();
				$('#cliente_id').prop('required', false);
				$('#nombre, #apellido').prop('required', true);
				$('#actividad_economica').prop('required', false);
				$('#retiene_renta_field').show();
				
			} else {
				// Otros tipos - mostrar campos manuales sin actividad económica
					$('#cliente_fields').hide();
					$('#receptor_manual').show();
					$('#actividad_economica_field').hide();
					$('#cliente_id').prop('required', false);
					$('#nombre, #apellido').prop('required', true);
					$('#actividad_economica').prop('required', false);
				}
				}

				// Ejecutar al cargar la página
				toggleReceptorFields();

				// Ejecutar cuando cambia el tipo de documento
				$('#tipo_dte').on('change', toggleReceptorFields);
			});
		</script>
	@endsection