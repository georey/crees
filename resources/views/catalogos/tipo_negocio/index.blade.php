@extends('layouts.master')
@section('title')
    Tipo de negocio
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Tipo de negocio</h3>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('tipo_negocio.create') !!}">Agregar</a>
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('catalogos.tipo_negocio.permissions')
        @include('catalogos.tipo_negocio.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var colnames = [
            { data: 'tipo', name: 'tipo' },
            { data: 'id', name: 'id' },
            //Array of columns to add in the datatable
            ];
    </script>
@endsection