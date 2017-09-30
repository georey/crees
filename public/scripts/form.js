var Formulario = function () {
    var initSelect = function () {
		$(".select2").select2();
	}
	var initDatePicker = function () {
		$('.date-picker').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
	}

	var initConfirmtaion = function() {
		$('.link-confirmation').click(function(e){
			if (!confirm('¿Esta seguro de realizar esta accion?')) e.preventDefault();
		})
		$('.btn_delete_confirmation').on('click', function(e){
			if (!confirm('¿Esta seguro de realizar esta accion?')) e.preventDefault();
		})
	}

	var initMask= function() {
		$("[data-mask]").inputmask();
	}
	return {
        init: function () {
           initSelect();
           initDatePicker();
           initMask();
           initConfirmtaion();
        }
    };
}();

jQuery(document).ready(function() {
    Formulario.init();
});