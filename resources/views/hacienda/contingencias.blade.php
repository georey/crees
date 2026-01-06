@extends('layouts.master')
@section('title')
Contingencias Hacienda
@endsection
@section('titleBreadcrumb')
Contingencias Hacienda
@endsection
@section('content')
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Filtrar Contingencias por Fecha</h3>
    </div>
    <div class="box-body">
        <form method="POST" action="{{ url('/hacienda/contingencias') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="text" class="form-control" id="fecha" name="fecha" value="{{ $fecha }}" placeholder="dd-mm-yyyy">
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
    </div>

    @if(isset($facturas))
    <form method="POST" action="{{ url('/hacienda/crearContingencia') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Facturas Rechazadas para {{ $fecha }}</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <input type="checkbox" id="select_all"> <label for="select_all">Seleccionar todo</label>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Número Control</th>
                            <th>codigo Generacion</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($facturas as $factura)
                        <tr>
                            <td><input type="checkbox" name="facturas[]" value="{{ $factura->id }}"></td>
                            <td>{{ $factura->id }}</td>
                            <td>{{ $factura->cliente ? $factura->cliente->nombre . ' ' . $factura->cliente->apellido : 'N/A' }}</td>
                            <td>{{ $factura->numero_control }}</td>
                            <td>{{ $factura->codigo_generacion }}</td>
                            <td>{{ $factura->created_at->format('d-m-Y') }}</td>
                            <td>Rechazada</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No hay facturas rechazadas para esta fecha.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                    <input type="text" name="motivo" class="form-control" placeholder="Motivo" style="max-width: 300px; display: inline-block;">
                     <input type="text" name="fInicio" class="form-control" placeholder="Fecha Inicio" style="max-width: 300px; display: inline-block;">
                      <input type="text" name="fFin" class="form-control" placeholder="Fecha Fin" style="max-width: 300px; display: inline-block;">
                       <input type="text" name="hInicio" class="form-control" placeholder="Hora Inicio" style="max-width: 300px; display: inline-block;">
                        <input type="text" name="hFin" class="form-control" placeholder="Hora Fin" style="max-width: 300px; display: inline-block;">
                    <button type="submit" class="btn btn-success">Crear Contingencia</button>
                </div>
            </div>
        </div>
    </form>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectAll = document.getElementById('select_all');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                var checkboxes = document.querySelectorAll('input[name="facturas[]"]');
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = selectAll.checked;
                }
            });
        }
    });
    </script>
    @endif
</div>
@endsection
