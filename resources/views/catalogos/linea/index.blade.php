@extends('layouts.master')
@section('title')
    Lineas
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Lineas</h3>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('linea.create') !!}">Agregar</a>
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('catalogos.linea.permissions')
        @include('catalogos.linea.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var colnames = [
            { data: 'nombre', name: 'nombre' },
            { data: 'periodo', name: 'periodo' },
            { data: 'tasa_anual', name: 'tasa_anual' },
            { data: 'tasa_mora', name: 'tasa_mora' },
            { data: 'multa', name: 'multa' },
            { data: 'indice_conversion', name: 'indice_conversion' },
            { data: 'cobro_porcentaje', name: 'cobro_porcentaje' },
            { data: 'id', name: 'id' },
            //Array of columns to add in the datatable
            ];
    </script>
@endsection