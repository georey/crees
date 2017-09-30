<label class="col-lg-1 col-md-2 {{ isset($off) ? 'col-md-offset-' . $off : '' }} text-right control-label">
	<strong> {{ isset($label) ? trans('dictionary.' . strtolower($label)) . ': ' : ''}} </strong>
</label>
<div class="col-lg-{{$col + 1}} col-md-{{$col}} input-icon right">
	<i class="fa"></i>
		<input type="text" id="{{$name}}" name="{{$name}}" class="form-control validation_email
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
</div>