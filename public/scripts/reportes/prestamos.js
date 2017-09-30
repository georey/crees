var reporte = {};

function Reporte() {
    var self = this;

    self.validarTipo = function() {
        if($("#tipo_reporte").val() > 0) {
            $("#div_buttons :button").attr("disabled", false);
        }
        else {
            $("#div_buttons :button").attr("disabled", true);
        }
        console.log("entra")
    }

    self.initTipo = function() {
        $("#tipo_reporte").change(function(){
            self.validarTipo();
        })
    }

    self.btnFiltrar = () => {
		$("#btn_filtrar").click(function() {
            
			$.ajax({
                    url: url + "reportes/filtrar",
                    data: {
                    	'_token': $('input[name=_token]').val(),
                    	'fecha_ini': $("#fecha_ini").val(),
                    	'fecha_fin': $("#fecha_fin").val(),
                    	'estado_id': $("#estado_id").val(),
                    	'cobrador_id': $("#cobrador_id").val(),
                    	'asesor_id': $("#asesor_id").val()
                    },
                    type: 'post',
                    success: function(data){
                    	$('#div_tabla').empty();
                    	$('#div_tabla').html(data);
                    }
                });
		});
	}

    function init() {}

    init();

    return self;

}
$(document).ready(function () {
    reporte = new Reporte();
    reporte.btnFiltrar();
    reporte.validarTipo();
    reporte.initTipo();
});