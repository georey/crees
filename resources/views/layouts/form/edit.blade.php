<div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-red-sunglo">
                <strong><i class="fa fa-file-o font-red"></i> {{$title}}</strong>
            </div>
        </div>
        <div class="portlet-body form">
            <form id="create" method="POST" action="{{action($action, $id)}}" autocomplete="off" class="form-horizontal form-with-validation"
            @if (!empty($frm_attributes))
                @foreach ($frm_attributes as $key => $val)
                    {{ $key . "=" . $val }}
                @endforeach
            @endif >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="PUT">
                    @include($include)                    
            </form>
      </div>
</div>