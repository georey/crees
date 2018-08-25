@extends('layouts.master')
@section('title')
Perfil
@stop
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-o font-red"></i> Perfil</h3>
    </div>
        <form id="create" method="POST" action="{{action('configuracion\perfilController@postIndex')}}" autocomplete="off" class="form-horizontal form-with-validation" enctype='multipart/form-data'>
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>Verifique que todos los campos sean llenados correctamente
            </div>
            <div class="alert alert-success display-hide">
                <button class="close" data-close="alert"></button>Informacion ingresada correctamente
            </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="box-body">
                    <div class="col-md-10 col-md-offset-1">
        <div class="form-group col-md-10">
            @include("layouts.form.input_text", array(
                                                'label' => 'Username',
                                                'name' => 'username',
                                                'value' => isset($usuario) ? $usuario : null,
                                                'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
                                                ))
        </div>
        <div class="form-group col-md-10">
            @include("layouts.form.input_pass", array(
                                                'label' => 'Password',
                                                'name' => 'password',
                                                'value' => null,
                                                'validations' => array(['type' => 'required'], ['type' => 'minlength', 'parameter' => 3])
                                                ))
        </div>
        <div class="form-group col-md-10">
            @include("layouts.form.input_file", array(
                                                'label' => 'Imagen',
                                                'name' => 'imagen',
                                                'value'=>null
                                                ))
        </div>
        
    </div>  
                </div>
                    <div class="clearfix"></div>
                <div class="box-footer">
                    <a href="{!! route('roles.index') !!}" class="btn btn-default pull-right">Cancelar</a>
                    <button type="submit" class="btn btn-info pull-right">Guardar</button>
                </div>
        </form>
</div>
@endsection