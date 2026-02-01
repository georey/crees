@extends('layouts.master')
@section('title')
    Facturas
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Facturas</h3>
        {{--<a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('pagos.calculadora') !!}">Calculadora</a>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('pagos.create') !!}">Pagos</a>--}}
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <!-- Filtros de fecha -->
        <form id="descargar-todo-form" method="POST" action="{{ url('hacienda/facturas/descargar_todo') }}">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-3">
                    <label for="fecha_inicio">Fecha inicio</label>
                    <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label for="fecha_fin">Fecha fin</label>
                    <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" autocomplete="off">
                </div>
                <div class="col-md-2" style="padding-top: 25px;">
                    <button type="button" class="btn btn-primary" id="btn-filtrar">Filtrar</button>
                    <button type="submit"  name="btn_submit" value="zip"  class="btn btn-success" id="btn-descargar-todo" style="margin-left: 5px;">Descargar todas</button>
                    <button type="submit"  name="btn_submit" value="xls" class="btn btn-success" id="btn-descargar-xls" style="margin-left: 5px;">Descargar xls</button>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
        @include('hacienda.permissions')
        @include('hacienda.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        // Columnas del datatable
        var colnames = [
            { data: 'fecha_factura', name: 'fecha_factura' },
            { data: 'nombre_completo', name: 'nombre_completo' },
            { data: 'tipo_dte_nombre', name: 'tipo_dte_nombre' },
            { data: 'estado_nombre', name: 'estado_nombre' },
            { data: 'numero_control', name: 'numero_control' },
            { data: 'codigo_generacion', name: 'codigo_generacion' },
            { 
                data: 'id', 
                name: 'sid',
                render: function(data, type, full, meta) {
                    var permisos = "";
                    var permiso = "";
                    var estado = full.estado;
                    $.each($(".btn_permiso"), function (index, value){
                        var $btn = $(this);
                        var estadosPermitidos = $btn.attr('data-estados');
                        if (estadosPermitidos) {
                            var estados = estadosPermitidos.split(',');
                            if (estados.indexOf(estado.toString()) !== -1) {
                                permiso = $btn[0].outerHTML;
                                permisos += permiso.replace("permiso_data_id", data).replace('class="btn_permiso', 'class="');
                            }
                        } else {
                            permiso = $btn[0].outerHTML;
                            permisos += permiso.replace("permiso_data_id", data).replace('class="btn_permiso', 'class="');
                        }
                    });
                    return permisos;
                }
            }
        ];

        // Función para obtener el primer y último día del mes actual en formato dd-mm-yyyy
        function getFirstLastDayCurrentMonth() {
            var now = new Date();
            var first = new Date(now.getFullYear(), now.getMonth(), 1);
            var last = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            function format(d) {
                var day = (d.getDate()<10?'0':'') + d.getDate();
                var month = (d.getMonth()+1<10?'0':'') + (d.getMonth()+1);
                return day + '-' + month + '-' + d.getFullYear();
            }
            return { first: format(first), last: format(last) };
        }

        $(document).ready(function() {
            // Setear valores por defecto
            var fechas = getFirstLastDayCurrentMonth();
            $('#fecha_inicio').val(fechas.first);
            $('#fecha_fin').val(fechas.last);

            // Inicializar datepicker si está disponible
            if ($.fn.datepicker) {
                $('#fecha_inicio, #fecha_fin').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true
                });
            }

            // Modificar la inicialización del datatable para filtrar por fechas
            if ($.fn.dataTable) {
                // Destruir si ya existe
                if ($('.crud-datatable').hasClass('dataTable')) {
                    $('.crud-datatable').DataTable().destroy();
                }
                var url = document.URL + '/datatable';
                var table = $('.crud-datatable').DataTable({
                    serverSide: true,
                    ajax: {
                        url: url,
                        data: function(d) {
                            d.fecha_inicio = $('#fecha_inicio').val();
                            d.fecha_fin = $('#fecha_fin').val();
                        }
                    },
                    columns: colnames,
                    // ...otros parámetros si es necesario...
                });

                // Botón filtrar
                $('#btn-filtrar').on('click', function() {
                    table.ajax.reload();
                });
            }

            // Al enviar el form de descargar todas, asegurarse que los valores estén actualizados
            $('#btn-descargar-todo').on('click', function() {
                // Los valores ya están en los inputs, el submit GET los enviará
            });
        });
    </script>
@endsection