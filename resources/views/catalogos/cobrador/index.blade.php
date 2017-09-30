@extends('layouts.master')
@section('title')
    Cobradores
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Cobradores</h3>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('cobradores.create') !!}">Agregar</a>
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('catalogos.cobrador.permissions')
        @include('catalogos.cobrador.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var colnames = [
            { data: 'id', name: 'id' },
            { data: 'nombre_completo', name: 'nombre_completo' },
            { data: 'telefono', name: 'telefono' },
            { data: 'id', name: 'id' },
            //Array of columns to add in the datatable
            ];
    </script>
@endsection