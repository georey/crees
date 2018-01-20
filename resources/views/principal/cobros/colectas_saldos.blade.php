@extends('layouts.master')
@section('title')
    Colectas y Saldos
@stop
@section('content')
<div class="box-body">
    <div class="box">
	    <div class="box-body">
	      	<form method="POST">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="box-header with-border">
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
	        <div id="div_buttons" class="form-group col-md-12">
	        	<button type="submit" id="btn_filtrar" name="btn_submit" value="filtrar" class="btn btn-info">Filtrar</button>
	        	<button type="submit" id="btn_excel" name="btn_submit" value="xls" class="btn btn-info">Excel</button>
	        	<button type="submit" id="btn_pdf" name="btn_submit" value="pdf" class="btn btn-info">PDF</button>
	        </div>
    	</div>
    </form>
    @include('reportes.colectas_tabla', array('prestamos' => $prestamos))
	    </div>
	</div>
</div>
@endsection