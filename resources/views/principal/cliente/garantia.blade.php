@extends('layouts.master')
@section('title')
	Garantias
@stop
@section('titleBreadcrumb')
    Garantias: {{$cliente->nombreCompleto()}}
@stop

@section('content')
<div class="box">	
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="box-body">      
    	<div class="col-md-8 col-md-offset-2">
    		<h3 class="text-center">{{$cliente->nombreCompleto()}}</h3>
	    	<dl>
			@foreach($prestamos as $prestamo)		
	            <dt>Prestamo: {{$prestamo->codigo}}</dt>
	            <dd>
	            	<div id="div_view_garantia">
		            	<a id="lnk_edit" title="Editar Garantia" href="#" data-prestamo="{{$prestamo->id}}"><i class="glyphicon glyphicon-edit"></i>
		            	</a><br>
		            	<label id="lbl_garantia">{{$prestamo->garantia}}</label>
	            	</div>
	            	<div id="div_edit_garantia" style="display: none;">
		            	<a id="lnk_save" title="Guardar Garantia" href="#" data-prestamo="{{$prestamo->id}}"><i class="glyphicon glyphicon-floppy-disk"></i>
		            	</a>
		            	<br>
		            	<textarea id="txt_garantia" rows="4" cols="100">{{$prestamo->garantia}}</textarea>
	            	</div>
	            </dd>
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

			$("#lnk_edit").click(function(){
				$("#div_view_garantia").hide();
				$("#div_edit_garantia").show();
			})

			$("#lnk_save").click(function(){
				$.ajax({
                    url: url + "clientes/actualizar_garantia",
                    data: {
                    	'_token': $('input[name=_token]').val(),
                    	'prestamo_id': $(this).data("prestamo"),
                    	'garantia': $("#txt_garantia").val()
                    },
                    type: 'post',
                    success: function(data){
                    	console.log(data);
                    }
                });
                $("#lbl_garantia").html($("#txt_garantia").val());
				$("#div_view_garantia").show();
				$("#div_edit_garantia").hide();
			})
		});
	</script>
@endsection