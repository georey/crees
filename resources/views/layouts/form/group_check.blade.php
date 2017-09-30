<label class="col-lg-1 col-md-2 {{ isset($off) ? 'col-md-offset-' . $off : '' }} text-right control-label">
    <strong> {{ isset($label) ? trans('dictionary.' . strtolower($label)) . ': ' : ''}} </strong>
</label>
<div class="col-lg-{{$col + 1}} col-md-{{$col}} input-icon right">
    <i class="fa"></i>
    <div class="input-group">
        <div class="icheck-list">
            @foreach ($checks as $check)
            <label>
                <input type="checkbox" data-checkbox="icheckbox_minimal-blue" name="permission[]" value="{{$check->$value}}" class="icheck" {{ $check->$checked ? 'checked' : '' }}>                    
                 {{$check->$name}}
            </label>
            @endforeach
        </div>
    </div>
</div>