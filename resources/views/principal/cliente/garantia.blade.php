@extends('layouts.master')
@section('title')
	Garantias
@stop
@section('titleBreadcrumb')
    Garantias: {{$cliente->nombreCompleto()}}
@stop

@section('content')
<div class="box">	
    <div class="box-body">      
    	<div class="col-md-8 col-md-offset-2">
    		<h3 class="text-center">{{$cliente->nombreCompleto()}}</h3>
	    	<dl>
			@foreach($prestamos as $prestamo)		
	            <dt>Prestamo: {{$prestamo->codigo}}</dt>
	            <dd>{{$prestamo->garantia}}</dd>
			@endforeach		
			</dl>
	    </div>
	</div>
	<div class="clearfix"></div>
	<div class="box-footer">
  		<a href="{!! route('clientes.index') !!}" class="btn btn-default pull-right">Regresar</a>
  	</div>
</div>
@endsection

@section('scripts')
	<script>
		$(document).ready(function(){
			var dateFormat = "D-M-YYYY";
			var fecha1;
			var fecha2;
			$(".tr_fechas").each(function(index, element) {
				if($(this).data("inicial") == 1)
					 fecha1 = moment($(this).data("date"), dateFormat);	
				else {
					fecha2 = moment($(this).data("date"), dateFormat);
					$(this).find('td.td_dias').html(fecha2.diff(fecha1, 'days'));
					fecha1 = fecha2;
				}
			});
		});
	</script>
@endsection