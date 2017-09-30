<label for="{{$name}}">{{ isset($label) ? $label . ': ' : ''}}</label>
<select style="width: 100%;" id="{{$name}}" name="{{$name}}" class="form-control select2
        @if (isset($validations))
            @foreach ($validations as $validation)
                {{' validation_' . $validation['type']}}
            @endforeach
        @endif
        "
        @if (isset($validations))
            @foreach ($validations as $validation)
                @if (isset($validation['parameter']))
                    {{' attr_validation_' . $validation['type'] . '= ' . $validation['parameter']}}
                @endif
            @endforeach
        @endif
        @if (!empty($attributes))
        @foreach ($attributes as $key => $val)
            {{ $key . "=" . $val}}
        @endforeach
        @endif >
    <option>-- Seleccione una opcion --</option>
    @foreach($options as $row)
         <option value = "{{ $row['id'] }}" {{ $row['id']==(isset($value[$name]) ? $value[$name] : '') ? 'selected="selected"': ''}} 
         @if(isset($option_aditional_data))
                    @foreach($option_aditional_data as $data)
                    data-{{$data}}="{{$row[$data]}}"
                    @endforeach
        @endif
         >
                @foreach($option_value as $opt)
                {{ $row[$opt] . ' ' }}
                @endforeach
         </option>
    @endforeach
</select>