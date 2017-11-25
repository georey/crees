@extends('layouts.master')
@section('title')
    Prestamos
@stop
@section('content')
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
                <button type="submit" class="btn btn-info" name="btn_enviar" value="asignar">Asignar</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2">
                @include("layouts.form.select", array(
                                            'label' => 'Asesor',
                                            'name' => 'asesor_id',
                                            'value' => null,
                                            'options' => $asesores,
                                            'option_value' => array('nombre', 'apellido')
                                            ))
            </div>    
            <div class="form-group col-md-2">
                @include("layouts.form.select", array(
                                            'label' => 'Cobrador',
                                            'name' => 'cobrador_id',
                                            'value' => null,
                                            'options' => $cobradores,
                                            'option_value' => array('nombre', 'apellido')
                                            ))
            </div>    
            <div class="form-group col-md-2">
                @include("layouts.form.select", array(
                                            'label' => 'Linea',
                                            'name' => 'linea_id',
                                            'value' => null,
                                            'options' => $lineas,
                                            'option_value' => array('nombre', 'periodo')
                                            ))
            </div>    
            <div class="form-group col-md-2">
                @include("layouts.form.select", array(
                                            'label' => 'Zona',
                                            'name' => 'zona_id',
                                            'value' => null,
                                            'options' => $zonas,
                                            'option_value' => array('nombre')
                                            ))
            </div> 
            <div class="form-group col-md-2">
                <br>
                <button type="submit" class="btn btn-info " name="btn_enviar" value="asignar"> Filtrar</button>
            </div>   
        </div>
        <div class="row">
            <table class="table table-bordered table-striped crud-datatable">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Monto</th>
                        <th>Descuento</th>
                        <th>Liquido a recibir</th>
                        <th>Linea</th>
                        <th>No Cuotas</th>
                        <th>Monto Cuotas</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @if (isset($prestamos))
                @foreach($prestamos as $prestamo)
                    <tr>
                        <td>{{$prestamo->codigo}}</td>
                        <td>{{$prestamo->monto}}</td>
                        <td>{{$prestamo->descuento}}</td>
                        <td>{{$prestamo->liquido}}</td>
                        <td>{{$prestamo->linea->nombre}}</td>
                        <td>{{$prestamo->cuotas}}</td>
                        <td>{{$prestamo->cuota}}</td>
                        <td>{{$prestamo->created_at}}</td>
                        <td>{{$prestamo->estadoPrestamo->estado}}</td>
                        <td></td>
                    </tr>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer"></div>
@endsection
@section('scripts')
    
@endsection