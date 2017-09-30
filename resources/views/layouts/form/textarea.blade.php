@if(isset($label))
	<label for="{{$name}}" class="text-right control-label">
		<strong> {{ isset($label) ? strtolower($label) . ': ' : ''}} </strong>
	</label>
@endif
    <textarea class="ckeditor form-control
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
	    name="{{$name}}" id="{{$name}}" rows="5" data-error-container="#editor2_error">{{ $value[$name] or ''}}
    </textarea>