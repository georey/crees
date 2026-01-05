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
        <div class="clearfix"></div>
        @include('hacienda.permissions')
        @include('hacienda.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
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
                    
                    // Recorrer cada botón de permiso
                    $.each($(".btn_permiso"), function (index, value){
                        var $btn = $(this);
                        var estadosPermitidos = $btn.attr('data-estados');
                        
                        // Si el botón tiene restricción de estados
                        if (estadosPermitidos) {
                            var estados = estadosPermitidos.split(',');
                            // Solo agregar si el estado actual está permitido
                            if (estados.indexOf(estado.toString()) !== -1) {
                                permiso = $btn[0].outerHTML;
                                permisos += permiso.replace("permiso_data_id", data).replace('class="btn_permiso', 'class="');
                            }
                        } else {
                            // Si no tiene restricción, agregar siempre
                            permiso = $btn[0].outerHTML;
                            permisos += permiso.replace("permiso_data_id", data).replace('class="btn_permiso', 'class="');
                        }
                    });
                    
                    return permisos;
                }
            }
        ];
    </script>
@endsection