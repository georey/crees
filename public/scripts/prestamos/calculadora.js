var calculadora = {};

function Calculadora() {
    var self = this;

    self.initTasas = () => {
		$("#linea_id").change(function() {
			$("#tasa").val($("#linea_id option:selected").attr('data-tasa_anual'));
			$("#tasa_mora").val($("#linea_id option:selected").attr('data-tasa_mora'));
			$("#multa").val($("#linea_id option:selected").attr('data-multa'));
			$.get(url + "clientes/getGastos",
                { linea_id: $("#linea_id").val(), monto: $("#monto").val()},
                function(data) {
                    var ddlGastos = $('#gastos');
                    ddlGastos.empty();
                    $.each(data, function(index, element) {
                        ddlGastos.append("<option value='"+ element.id + "'>" + element.tipo + ' ' + element.monto + "</option>");
                    });
                    $(".select2").select2();
                });
		});
	}
    self.initCalculadora = () => {
		$("#btn_calcular").click(function(){
			monto = $("#monto").val();
			cuotas = $("#cuotas").val();
			tasa_anual = $("#tasa").val();
			indice_conversion = $("#linea_id option:selected").attr('data-indice_conversion');
			tasa = tasa_anual / 100;
	        dividendo = (tasa / indice_conversion) * monto;
	        divisor = 1 - Math.pow((1 + (tasa / indice_conversion)), (-1 * Math.abs(cuotas)));
	        monto_cuota = dividendo / divisor;
	        cuota = monto_cuota.toFixed(2);
	        $("#spn_cuota").html(cuota);
	        $("#cuota").val(cuota);
		});
	}
	self.initAnulaciones = function() {
		$(".btn_anular_prestamo").click(function(event){
			event.preventDefault();
			prestamo_id = $(this).attr("data-prestamo-id");
			if (confirm("¿Esta seguro de anular este prestamo?")) {
				$.ajax({
                    url: url + "clientes/anular_prestamo",
                    data: {
                    	'_token': $('input[name=_token]').val(),
                    	'prestamo_id': prestamo_id
                    },
                    type: 'post',
                    success: function(data){
                    	alert(data);
                    	location.reload();
                    }
                });
			}
		})
	}

    function init() {}

    init();

    return self;

}
$(document).ready(function () {
    calculadora = new Calculadora();
    calculadora.initTasas();
    calculadora.initCalculadora();
    calculadora.initAnulaciones();
});