$(document).ready(function(){
	var dateFormat = "D-M-YYYY";
	var fecha1 = moment($("#fecha_otorgamiento").val(), dateFormat);	
	var fecha2;
	$("#tbl_historial > tbody > tr").each(function(index, element) {		
		fecha2 = moment($(this).find('td.td_fecha').html(), dateFormat);
		$(this).find('td.td_dias').html(fecha2.diff(fecha1, 'days'));
		fecha1 = fecha2;
	});

	var total_cuota = 0.0;
	$(".monto-cuota").each(function(){ total_cuota += parseFloat($(this).html()); });
	var total_capital = 0.0;
	$(".monto-capital").each(function(){ total_capital += parseFloat($(this).html()); });
	var total_interes = 0.0;
	$(".monto-interes").each(function(){ total_interes += parseFloat($(this).html()); });
	var total_mora = 0.0;
	$(".monto-mora").each(function(){ total_mora += parseFloat($(this).find("span").html()); });
	var total_multa = 0.0;
	$(".monto-multa").each(function(){ total_multa += parseFloat($(this).find("span").html()); });

	$("#total-cuota").html(total_cuota.toFixed(2));
	$("#total-capital").html(total_capital.toFixed(2));
	$("#total-interes").html(total_interes.toFixed(2));
	$("#total-mora").html(total_mora.toFixed(2));
	$("#total-multa").html(total_multa.toFixed(2));
});