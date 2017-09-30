var cliente = {};

function Cliente() {
    var self = this;

    self.initValidacionDui = () => {
		$("#dui").change(function() {
			$.get(url + "clientes/verificarDui",
                { dui: $("#dui").val()},
                function(data) {
                    if(data)
                    	alert("Este dui ya esta ingresado por favor verifique la informacion");
                });
		});
	}

    function init() {}

    init();

    return self;

}
$(document).ready(function () {
    cliente = new Cliente();
    cliente.initValidacionDui();
});