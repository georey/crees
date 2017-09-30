<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-o font-red"></i> {{$title}}</h3>
    </div>
        <form id="create" method="POST" action="{{action($action)}}" autocomplete="off" class="form-horizontal form-with-validation" 
            @if (!empty($frm_attributes))
                @foreach ($frm_attributes as $key => $val)
                    {{ $key . "=" . $val }}
                @endforeach
            @endif >
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>Verifique que todos los campos sean llenados correctamente
            </div>
            <div class="alert alert-success display-hide">
                <button class="close" data-close="alert"></button>Informacion ingresada correctamente
            </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @include($include)
        </form>
</div>
