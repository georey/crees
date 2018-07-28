@extends('layouts.master')
@section('title')
    Pagos
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Acuerdos</h3>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('acuerdos_pagos.create') !!}">Agregar</a>
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('principal.acuerdos.permissions')
        @include('principal.acuerdos.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var colnames = [
            { data: 'id', name: 'acuerdos.id' },
            { data: 'codigo_prestamo', name: 'codigo_prestamo' },
            { data: 'nombre_completo', name: 'nombre_completo' },
            { data: 'user', name: 'acuerdos.user' },
            { data: 'id', name: 'acuerdos.id' },
            //Array of columns to add in the datatable
            ];
    </script>
@endsection