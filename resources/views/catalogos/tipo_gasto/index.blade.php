@extends('layouts.master')
@section('title')
    Tipo de gasto
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Tipo de gasto</h3>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('tipo_gasto.create') !!}">Agregar</a>
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('catalogos.tipo_gasto.permissions')
        @include('catalogos.tipo_gasto.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var colnames = [
            { data: 'tipo', name: 'tipo' },
            { data: 'monto', name: 'monto' },
            { data: 'monto_min', name: 'monto_min' },
            { data: 'monto_max', name: 'monto_max' },
            { data: 'linea', name: 'linea' },
            { data: 'id', name: 'id' },
            //Array of columns to add in the datatable
            ];
    </script>
@endsection