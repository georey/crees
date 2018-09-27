var calculadora = {};

function Calculadora() {
	var cuota;
	var cuota_acordada;
	var capital_pendiente;
	var mora;
	var multa;
	var saldo;
	var interes;
	var tasa_interes;
	var dias;
	var fecha;
	var cuotaTotal;
	var saldo_total;

    var self = this;
    self.initTasas = () => {
		$("#prestamo_id").change(function() {
			cuota = parseFloat($("#prestamo_id option:selected").attr('data-cuota'));
			cuota_acordada = parseFloat($("#prestamo_id option:selected").attr('data-cuota-acordada'));			
			capital_pendiente = parseFloat($("#prestamo_id option:selected").attr('data-capital-pendiente'));
			mora = parseFloat($("#prestamo_id option:selected").attr('data-mora'));
			multa = parseFloat($("#prestamo_id option:selected").attr('data-multa'));
			saldo = parseFloat($("#prestamo_id option:selected").attr('data-saldo'));
			interes = parseFloat($("#prestamo_id option:selected").attr('data-interes'));
			tasa_interes = parseFloat($("#prestamo_id option:selected").attr('data-tasa-interes'));
			tasa_mora = parseFloat($("#prestamo_id option:selected").attr('data-tasa-mora'));
			dias = parseInt($("#prestamo_id option:selected").attr('data-dias-transcurridos'));
			fecha = $("#prestamo_id option:selected").attr('data-fecha');
			cuotaTotal = parseFloat(cuota) + parseFloat(multa) + parseFloat(mora) + parseFloat(capital_pendiente)  + parseFloat(interes);
			saldo_total = (saldo + interes + multa + mora ).toFixed(2)
			$("#interes").val(tasa_interes.toFixed(2));			
			$("#tasa_mora").val(tasa_mora.toFixed(2));			
			$("#monto_pendiente").val(saldo_total);
			$("#monto_pendiente").data("valor", saldo_total);
			$("#chk_interes").data("valor", interes.toFixed(2));			
			$("#chk_mora").data("valor", mora.toFixed(2));			
			$("#chk_multa").data("valor", multa.toFixed(2));			
		});
	}

	self.initChk = () => {
		$(".chk_valor").change(function() {
			descuento = 0.0;
			$(".chk_valor").each(function(i, element){
				if(element.checked) {
	            	descuento += parseFloat($(element).data("valor"));
     	   		}
			});
			$("#monto_actual").val((saldo_total - descuento).toFixed(2));
    	});
	}

    function init() {}

    init();

    return self;
}

$(document).ready(function () {
    calculadora = new Calculadora();
    calculadora.initTasas();
    calculadora.initChk();
});