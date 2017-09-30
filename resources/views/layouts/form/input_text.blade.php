<label><strong> {{ isset($label) ? $label . ': ' : ''}} </strong></label>
<input type="text" class="form-control" placeholder="Ingrese {{ isset($label) ? $label : ''}}" id="{{$name}}" name="{{$name}}" class="form-control
		@if (isset($validations))
			@foreach ($validations as $validation)
				{{' validation_' . $validation['type']}}
			@endforeach
		@endif
		"
		@if (isset($mask))
				data-inputmask='"mask": "{{$mask}}"' data-mask
			@endif
		@if (isset($validations))
			@foreach ($validations as $validation)
				@if (isset($validation['parameter']))
					{{' attr_validation_' . $validation['type'] . '= ' . $validation['parameter']}}
				@endif
			@endforeach
		@endif
		 value="{{ $value[$name] or ''}}">
</input>