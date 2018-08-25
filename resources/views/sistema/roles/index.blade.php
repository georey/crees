@extends('layouts.master')
@section('title')
    Roles
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Roles</h3>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('roles.create') !!}">Agregar</a>
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('sistema.roles.permissions')
        @include('sistema.roles.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var colnames = [
            { data: 'nombre', name: 'nombre' },
            { data: 'id', name: 'id' },
            //Array of columns to add in the datatable
            ];
    </script>
@endsection