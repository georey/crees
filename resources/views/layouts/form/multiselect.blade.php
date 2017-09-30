<label for="{{$name}}">
    <strong> {{ isset($label) ? strtolower($label). ': ' : ''}} </strong>
</label>
    <select id="{{$name}}" name="{{$name}}[]" class="form-control select2
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
        multiple="multiple" 
        @if (!empty($attributes))
        @foreach ($attributes as $key => $val)
            {{ $key . "=" . $val}}
        @endforeach
        @endif >
        @if (isset($options))
            @foreach($options as $row)
                <option value = "{{ $row['id'] }}"
                @if (isset($selected_options))
                    @foreach ($selected_options as $key)
                        @if ($row['id'] == $key['id'])
                            {{'selected="selected"'}}
                        @endif
                    @endforeach
                @endif
                >
                    @foreach($option_value as $opt)
                    {{ $row[$opt] . ' ' }}
                    @endforeach
                </option>
            @endforeach
        @endif
    </select>
