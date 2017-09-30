<label for="{{$name}}">{{ isset($label) ? $label . ': ' : ''}}</label>
<div class="input-group date">
	<div class="input-group-addon">
		<i class="fa fa-calendar"></i>
	</div>
	<input type="text" id="{{$name}}" name="{{$name}}" class="form-control pull-right
			date-picker validation_date
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
			@endif" readonly="true" value="{{isset($value[$name]) ? date('d-m-Y',strtotime($value[$name])) : ''}}">
</div>