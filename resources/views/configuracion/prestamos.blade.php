@extends('layouts.master')
@section('title')
    Prestamos
@stop
@section('content')
<form method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="box-header with-border">
        <h3 class="box-title">Prestamos</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-3">
                @include("layouts.form.select", array(
                                            'label' => 'Asesor',
                                            'name' => 'asesor_id',
                                            'value' => null,
                                            'options' => $asesores,
                                            'option_value' => array('nombre', 'apellido')
                                            ))
            </div>
            <div class="form-group col-md-3">
                <br>
                <button type="submit" class="btn btn-info" name="btn_enviar" value="asignar_asesor">Asignar asesor</button>
            </div>
            <div class="form-group col-md-3">
                @include("layouts.form.select", array(
                                            'label' => 'Cobrador',
                                            'name' => 'cobrador_id',
                                            'value' => null,
                                            'options' => $cobradores,
                                            'option_value' => array('nombre', 'apellido')
                                            ))
            </div>
            <div class="form-group col-md-3">
                <br>
                <button type="submit" class="btn btn-info" name="btn_enviar" value="asignar_cobrador">Asignar Cobrador</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2">
                @include("layouts.form.select", array(
                                            'label' => 'Asesor',
                                            'name' => 'filtro_asesor_id',
                                            'value' => $filtro_asesor_id or null,
                                            'options' => $asesores,
                                            'option_value' => array('nombre', 'apellido')
                                            ))
            </div>    
            <div class="form-group col-md-2">
                @include("layouts.form.select", array(
                                            'label' => 'Cobrador',
                                            'name' => 'filtro_cobrador_id',
                                            'value' => $filtro_cobrador_id or null,
                                            'options' => $cobradores,
                                            'option_value' => array('nombre', 'apellido')
                                            ))
            </div>    
            <div class="form-group col-md-2">
                @include("layouts.form.select", array(
                                            'label' => 'Linea',
                                            'name' => 'filtro_linea_id',
                                            'value' => $filtro_linea_id or null,
                                            'options' => $lineas,
                                            'option_value' => array('nombre', 'periodo')
                                            ))
            </div>    
            <div class="form-group col-md-2">
                @include("layouts.form.select", array(
                                            'label' => 'Zona',
                                            'name' => 'filtro_zona_id',
                                            'value' => $filtro_zona_id or null,
                                            'options' => $zonas,
                                            'option_value' => array('nombre')
                                            ))
            </div> 
            <div class="form-group col-md-2">
                <br>
                <button type="submit" class="btn btn-info " name="btn_enviar" value="filtrar"> Filtrar</button>
            </div>   
        </div>
        <div class="row">
            <table class="table table-bordered table-striped crud-datatable">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Cliente</th>
                        <th>Asesor</th>
                        <th>Cobrador</th>
                        <th>Monto</th>
                        <th>Linea</th>
                        <th>No Cuotas</th>
                        <th>Monto Cuotas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @if (isset($prestamos))
                @foreach($prestamos as $prestamo)
                    <tr>
                        <td>{{$prestamo->codigo}}</td>
                        <td>{{$prestamo->cliente->nombre}} {{$prestamo->cliente->apellido}}</td>
                        <td>{{$prestamo->asesor->nombre or ''}} {{$prestamo->asesor->apellido or ''}}</td>
                        <td>{{$prestamo->cobrador->nombre or ''}} {{$prestamo->cobrador->apellido or ''}}</td>
                        <td>{{$prestamo->monto}}</td>
                        <td>{{$prestamo->linea->nombre}}</td>
                        <td>{{$prestamo->cuotas}}</td>
                        <td>{{$prestamo->cuota}}</td>
                        <td>{{$prestamo->estadoPrestamo->estado}}</td>
                        <td>
                            <input id="id" value="{{$prestamo->id}}" name="id[]" type="checkbox">
                        </td>
                    </tr>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer"></div>
</form>
@endsection
@section('scripts')
    
@endsection