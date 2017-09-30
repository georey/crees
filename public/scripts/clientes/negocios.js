var negocio = {};

function Negocio() {
    var self = this;

    self.initSelectMunicipio = () => {
		$("#departamento_id").change(function() {
			$.get(url + "clientes/getMunicipios",
                { departamento_id: $("#departamento_id").val()},
                function(data) {
                    var municipio = $('#municipio_id');
                    municipio.empty();
                    municipio.append("<option>-- Seleccione una opcion --</option>");
                    $.each(data, function(index, element) {
                        municipio.append("<option value='"+ element.id + "'>" + element.nombre + "</option>");
                    });
                    $(".select2").select2();
                });
		});
	}

    function init() {}

    init();

    return self;

}
$(document).ready(function () {
    negocio = new Negocio();
    negocio.initSelectMunicipio();
});