var calculadora = {};

function Calculadora() {
    var self = this;    

    self.initTasas = () => {
		$("#linea_id").change(function() {
			$("#tasa").val($("#linea_id option:selected").attr('data-tasa_anual'));
			$("#tasa_mora").val($("#linea_id option:selected").attr('data-tasa_mora'));
			$("#multa").val($("#linea_id option:selected").attr('data-multa'));
		});
	}

	self.initPrestamos = () => {
		$("#prestamo_id").change(function(){
			monto = $("option:selected",this).data("monto");
			cuotas = $("option:selected",this).data("cuotas");
			linea_id = $("option:selected",this).data("linea");
			$("#monto").val(monto);
			$("#cuotas").val(cuotas);
			$("#linea_id").val(linea_id);
			$("#linea_id").trigger("change");
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
	        periodo = parseInt(365 / indice_conversion);
	        var fecha_actual = $("#prestamo_id option:selected").val() > 0 ? new Date($("#prestamo_id option:selected").data("fecha")) : new Date();
			var fecha_pago = fecha_actual;
			tasa = (tasa_anual / 100) / 365;
			capital = parseFloat(0);
			var tabla = $("#tbl_plan > tbody");
			tabla.empty();
	        for (var i = 0 ; i < cuotas; i++) {
				fecha_pago.setDate(fecha_actual.getDate() + periodo);
		        montoActual = parseFloat(monto) - parseFloat(capital);
		        interes = (parseFloat(montoActual) * parseFloat(tasa) * parseFloat(periodo)).toFixed(2);
		        capitalCuota = (parseFloat(cuota) - parseFloat(interes)).toFixed(2);
		        capital += parseFloat(capitalCuota);
		        saldo = (parseFloat(monto) - parseFloat(capital)).toFixed(2);
		        fecha_actual = fecha_pago;
		        /*if(saldo < 0){
		        	capitalCuota = (parseFloat(capitalCuota) + parseFloat(saldo)).toFixed(2);
		        	interes = (parseFloat(cuota) - parseFloat(capitalCuota)).toFixed(2);
		        	saldo = 0;
		        }*/

		        if(cuotas == i +1) {
		        	capitalCuota = (parseFloat(capitalCuota) + parseFloat(saldo)).toFixed(2);
		        	cuota = (parseFloat(capitalCuota) + parseFloat(interes)).toFixed(2);
		        	saldo = 0;
		        }

		        tabla.append("<tr><td>"+ (i+1) + "</td><td>" +fecha_pago.getDate()+"-"+(fecha_pago.getMonth()+1)+"-"+ fecha_pago.getFullYear() + "</td><td class='sumCuota'>" + cuota + "</td><td class='sumCapital'>" + capitalCuota + "</td><td class='sumInteres'>" + interes + "</td><td>" + saldo + "</td></tr>");
	        }
	        totalCuota = self.sumatoria(".sumCuota");
	        totalCapital = self.sumatoria(".sumCapital");
	        totalInteres = self.sumatoria(".sumInteres");
	        $("#th_cuota").html(parseFloat(totalCuota).toFixed(2));
	        $("#th_capital").html(parseFloat(totalCapital).toFixed(2));
	        $("#th_interes").html(parseFloat(totalInteres).toFixed(2));
		});
	}

	self.sumatoria = (clase) => {
		total = 0.0;
		$(clase).each(function(index, element) {
			total += parseFloat($(element).html());    	    
        });
        	console.log(total)
        return total;
	}

    function init() {}

    init();

    return self;

}
$(document).ready(function () {
    calculadora = new Calculadora();
    calculadora.initTasas();
    calculadora.initCalculadora();
    calculadora.initPrestamos();
});