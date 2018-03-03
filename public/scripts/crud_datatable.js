var TableDatatablesButtons = function () {
    var initConfirmation = function () {
        //$('.btn_delete_confirmation').confirmation();
    }

    var findNumeric = function () {
        $('td').filter(function() {
          return this.innerHTML.match(/^[0-9\s\.,]+$/);
        }).addClass('numeric');
    }

    try {
        if (colnames == null)
            colnames = [];
        else {
            // $.each( colnames, function( key, value ) {
            //   value.className = "all";
            // });
        }
    }
    catch(err) {
        colnames = [];
    }


    var initTable1 = function () {
        var url = document.URL + '/datatable';

        $('.crud-datatable').dataTable({
            "fnInitComplete": function(oSettings, json) {
                findNumeric();
                initConfirmation();
                $('.btn_delete_confirmation').on('click', function(e){
                    if (!confirm('¿Esta seguro de realizar esta accion?')) e.preventDefault();
                })
            },

            "serverSide": true,
            "ajax": url,
            "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
              },
              {


                "searchable": false,
                "orderable": false,
                "targets": -1,
                "data": 'id',
                "render" : function(data,type,full,meta) {
                    permisos = "";
                    permiso = "";
                    try {
                        permiso = $(".btn_permiso_edit")[0].outerHTML;
                        permisos += permiso.replace("permiso_data_id", data).replace('class="btn_permiso_edit" ', '');
                    }
                    catch(err) {
                        console.log('No existe el permiso editar');
                    }
                    if(full.deleted_at == null) {
                        try {
                            permiso = $(".btn_permiso_delete")[0].outerHTML;
                            permisos += permiso.replace("permiso_data_id", data).replace('class="btn_permiso_delete" ', 'data-toggle="confirmation" data-singleton="true" class="btn_delete_confirmation"');
                        }
                        catch(err) {
                            console.log('No existe el permiso eliminar');
                        }
                    }
                    else{
                        try {
                            permiso = $(".btn_permiso_restore")[0].outerHTML;
                            permisos += permiso.replace("permiso_data_id", data).replace('class="btn_permiso_restore" ', '');
                        }
                        catch(err) {
                            console.log('No existe el permiso restaurar');
                        }
                    }

                    $.each($(".btn_permiso"), function (index, value){
                            permiso = $(this)[0].outerHTML;
                            permisos += permiso.replace("permiso_data_id", data).replace('class="btn_permiso" ', '');
                    });

                    return permisos

                    }
            }
            // { className: "text-right", "targets": [-1] }
              ],
              columns: colnames,


            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            buttons: [
                {
                    extend: 'pdf',
                    className: 'btn green btn-outline',
                    exportOptions: { columns: calcularColumnas()},
                    customize : function(doc){
                        var colCount = new Array();
                        columnas = $(".crud-datatable").find('tr')[0].cells.length;
                        width = Math.floor(120 / columnas);
                        for (var i = 1; i < columnas; i++) {
                            colCount.push(width +'%');
                        }
                        doc.content[1].table.widths = colCount;
                    }
                 },
                { extend: 'excel', className: 'btn yellow btn-outline ', exportOptions: { columns: calcularColumnas()} },
            ],
            responsive: true,

            //"order": [[1, 'asc']],

            "lengthMenu": [
                [10, 50, 100, 500],
                [10, 50, 100, 500] // change per page values here
            ],
            "pageLength": 10,
            "dom": "<'row' <'col-md-12'B>><'row'<'col-lg-10 col-md-8 col-sm-12'l><'col-lg-2 col-md-4 col-sm-12'f>r><'table-scrollable table-responsive't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
        })
    }

    var searchToLower = function() {
        $("#tbl_catalog_filter").children('label').children('input').keyup(function(e){
            if (e.which == 163) {
                e.preventDefault();
            }
            $(this).val($(this).val().toLowerCase());
        });
    }

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                console.log('Agregue el plugin datatable');
                return;
            }
            else
            {
                try {
                    initTable1();
                }
                catch (err){
                    console.log(err.message);
                }
            }
            searchToLower();
        }

    };

}();
jQuery(document).ready(function() {
    TableDatatablesButtons.init();
});
function calcularColumnas(){
    var columnas = new Array();
    var cols = $(".datatable").find("tr:first th");
    var count = 0;
    for(var i = 0; i < cols.length; i++)
    {
       var colspan = cols.eq(i).attr("colspan");
       if( colspan && colspan > 1)
       {
          count += colspan;
       }else{
            columnas.push(count);
            count++;
       }
    }
    columnas.pop();
    return columnas;
}