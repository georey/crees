@extends('layouts.master')
@section('title')
    Reportes
@stop
@section('content')
	<form method="POST">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="box-header with-border">
	        <div class="form-group col-md-2">
				@include("layouts.form.input_text", array(
												'label' => 'Fecha Inicio',
												'name' => 'fecha_ini',
												'value' => $reporte,
												'mask' => '99-99-9999'
												))
	        </div>
	        <div class="form-group col-md-2">
				@include("layouts.form.input_text", array(
												'label' => 'Fecha Fin',
												'name' => 'fecha_fin',
												'value' => $reporte,
												'mask' => '99-99-9999'
												))
	        </div>
	        <div class="form-group col-md-2">
				<label for="estado_id">Estado</label>
				<select style="width: 100%;" id="estado_id" name="estado_id" class="form-control select2">
				    <option value="0">-- Seleccione una opcion --</option>
				    @foreach($estados as $estado)
				         <option value = "{{ $estado->id }}" {{$estado->id == $reporte['estado_id'] ?  'selected': ''}}>
				                {{$estado->estado}}
				         </option>
				    @endforeach
				</select>
	        </div>
	        <div class="form-group col-md-2">
				<label for="estado_id">Asesor</label>
				<select style="width: 100%;" id="asesor_id" name="asesor_id" class="form-control select2">
				    <option value="0">-- Seleccione una opcion --</option>
				    @foreach($asesores as $asesor)
				         <option value = "{{ $asesor->id }}" {{$asesor->id == $reporte['asesor_id'] ?  'selected': ''}}>
				                {{$asesor->nombre}} {{$asesor->apellido}}
				         </option>
				    @endforeach
				</select>
	        </div>
	        <div class="form-group col-md-3">
				<label for="tipo_reporte">Tipo de reporte</label>
				<select style="width: 100%;" id="tipo_reporte" name="tipo_reporte" class="form-control select2">
				    <option value="0">-- Seleccione una opcion --</option>
					<option value = "1" {{$reporte['reporte_id'] == 1 ?  'selected': ''}}>Intereses cobrados</option>
					<option value = "4" {{$reporte['reporte_id'] == 4 ?  'selected': ''}}>Intereses cobrados sumarizado</option>
					<option value = "2" {{$reporte['reporte_id'] == 2?  'selected': ''}}>Colocacion de creditos</option>
					<option value = "5" {{$reporte['reporte_id'] == 5 ?  'selected': ''}}>Colocacion de creditos sumarizado</option>
					<option value = "3" {{$reporte['reporte_id'] == 3?  'selected': ''}}>INFORED</option>
				</select>
	        </div>
	        <div id="div_buttons" class="form-group col-md-12">
	        	<button type="submit" id="btn_filtrar" name="btn_submit" value="filtrar" class="btn btn-info">Filtrar</button>
	        	<button type="submit" id="btn_excel" name="btn_submit" value="xls" class="btn btn-info">Excel</button>
	        	<button type="submit" id="btn_pdf" name="btn_submit" value="pdf" class="btn btn-info">PDF</button>
	        </div>
    	</div>
    </form>
    <div id="div_tabla">
    	@include($tabla, array('prestamos' => $prestamos))
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('scripts/reportes/prestamos.js') }}"></script>
@endsection