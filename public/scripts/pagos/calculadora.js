var calculadora = {};

function Calculadora() {
    var self = this;

    self.initTasas = () => {
		$("#prestamo_id").change(function() {
			cuota = parseFloat($("#prestamo_id option:selected").attr('data-cuota'));
			cuota_acordada = parseFloat($("#prestamo_id option:selected").attr('data-cuota-acordada'));			
			capital_pendiente = parseFloat($("#prestamo_id option:selected").attr('data-capital-pendiente'));
			mora = parseFloat($("#prestamo_id option:selected").attr('data-mora'));
			mora_pendiente = parseFloat($("#prestamo_id option:selected").attr('data-mora-pendiente'));
			multa = parseFloat($("#prestamo_id option:selected").attr('data-multa'));
			multa_pendiente = parseFloat($("#prestamo_id option:selected").attr('data-multa-pendiente'));
			saldo = parseFloat($("#prestamo_id option:selected").attr('data-saldo'));
			interes = parseFloat($("#prestamo_id option:selected").attr('data-interes'));
			interes_pendiente = parseFloat($("#prestamo_id option:selected").attr('data-interes-pendiente'));
			dias = parseInt($("#prestamo_id option:selected").attr('data-dias-transcurridos'));
			fecha = $("#prestamo_id option:selected").attr('data-fecha');
			cuotaTotal = parseFloat(cuota) + parseFloat(multa) + parseFloat(mora) + parseFloat(capital_pendiente)  + parseFloat(interes) + parseFloat(mora_pendiente)
parseFloat(multa_pendiente);
			//$("#cuota").val(cuotaTotal.toFixed(2));
			$("#h3_cuota_acordada").html(cuota_acordada.toFixed(2));
			$("#mora").val((mora+mora_pendiente).toFixed(2));
			$("#multa").val((multa+multa_pendiente).toFixed(2));
			$("#h3_saldo_anterior").html(saldo.toFixed(2));
			$("#h3_capital").html((cuota == 0 ? capital_pendiente : cuota - interes).toFixed(2));
			$("#hdn_capital").val((cuota == 0 ? capital_pendiente : cuota - (interes + interes_pendiente)).toFixed(2));
			$("#h3_interes").html(interes.toFixed(2));
			$("#h3_proxma_fecha").html(fecha);
			$("#h3_total_pagar").html((cuotaTotal).toFixed(2));
			$("#h3_deuda_total").html((saldo + interes + multa + mora+ multa_pendiente + mora_pendiente ).toFixed(2));
			$("#h3_dias_transcurridos").html(dias);

		});
	}
    self.initCalculadora = () => {
		// $("#btn_calcular").click(function(){
		// 	monto = $("#monto").val();
		// 	cuotas = $("#cuotas").val();
		// 	tasa_anual = $("#tasa").val();
		// 	indice_conversion = $("#linea_id option:selected").attr('data-indice_conversion');
		// 	tasa = tasa_anual / 100;
	 //        dividendo = (tasa / indice_conversion) * monto;
	 //        divisor = 1 - Math.pow((1 + (tasa / indice_conversion)), (-1 * Math.abs(cuotas)));
	 //        monto_cuota = dividendo / divisor;
	 //        cuota = monto_cuota.toFixed(2);
	 //        $("#spn_cuota").html(cuota);
		// });
	}

	self.initCookies = () => {
        if (Cookies.get("fecha_pagos") != null) {
            $("#fecha").val(Cookies.get("fecha_pagos"));
        }
        else {
        	Cookies.set("fecha_pagos", $("#fecha").val());
        }
    }

    self.initFecha = () => {
    	$("#fecha").change(function() {
    		Cookies.set("fecha_pagos", $("#fecha").val());
    	});
    }

    self.initSubmitForm = () =>{
    	$("form").submit(function(e){
			if(cuotaTotal < $("#cuota").val()){
				//alert("El monto de la cuota no puede superar el monto total del prestamo");
				var r = confirm("Esta seguro de ingresar esta cantidad mayor al monto del prestamo?");
				if(r) {
					return true;
				} else {
					return false;
				}
				
			}
			else{
				return true;
			}
			return false;
		});
    }

    function init() {}

    init();

    return self;

}
$(document).ready(function () {
	$('form').trigger("reset");
	$("#prestamo_id").select2();
    calculadora = new Calculadora();
    calculadora.initTasas();
    calculadora.initCalculadora();
    calculadora.initCookies();
    calculadora.initFecha();
    calculadora.initSubmitForm();
});