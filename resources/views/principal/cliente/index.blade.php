@extends('layouts.master')
@section('title')
    Clientes
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Clientes</h3>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('clientes.create') !!}">Agregar</a>
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('principal.cliente.permissions')
        @include('principal.cliente.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var colnames = [
            { data: 'codigo', name: 'codigo' },
            { data: 'dui', name: 'nit' },
            { data: 'nit', name: 'nit' },
            { data: 'nombre_completo', name: 'nombre_completo' },
            { data: 'id', name: 'id' },
            //Array of columns to add in the datatable
            ];
    </script>
@endsection