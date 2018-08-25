<label><strong> {{ isset($label) ? $label . ': ' : ''}} </strong></label>
<input type="password" id="{{$name}}" name="{{$name}}" class="form-control
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
	 value="{{ $value[$name] or ''}}">
 </input>