@extends('layouts.master')
@section('title')
    Pagos
@stop
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Prestamos</h3>
        {{--<a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('pagos.calculadora') !!}">Calculadora</a>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('pagos.create') !!}">Pagos</a>--}}
    </div>
    <div class="box-body">
        <input type="hidden" id="hf_message" value="return confirm('{{trans('form.confirm'). ' '. trans('form.catalog.action_type')}}')">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('principal.pago.permissions')
        @include('principal.pago.table')
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var colnames = [
            { data: 'codigo', name: 'prestamos.codigo' },
            { data: 'nombre_completo', name: 'nombre_completo' },
            { data: 'estado', name: 'estados_prestamo.estado' },
            { data: 'id', name: 'prestamos.id' },
            //Array of columns to add in the datatable
            ];
    </script>
@endsection