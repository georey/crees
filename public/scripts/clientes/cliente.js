var clientemh = {};

function ClienteMH() {
    var self = this;

    self.initSelectMunicipio = () => {
		$("#departamento_id").change(function() {
            const codigo = $("#departamento_id option:selected").data("codigo");
			$.get(url + "clientes/getMHMunicipios",
                { departamento_id: codigo},
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
    clientemh = new ClienteMH();
    clientemh.initSelectMunicipio();
});