<label class="col-lg-1 col-md-2 {{ isset($off) ? 'col-md-offset-' . $off : '' }} text-right control-label">
	<strong> {{ isset($label) ? trans('dictionary.' . strtolower($label)) . ': ' : ''}} </strong>
</label>
<div class="col-lg-{{$col + 1}} col-md-{{$col}} input-icon right">
	<i class="fa"></i>
	<div class="input-group">
		<div class="md-checkbox">
			<input type="checkbox" id="{{$name}}" name="{{$name}}" class="md-check" {{ isset($value[$name]) ? $value[$name] ? 'checked' : '123' : '456'}}>
			<label for="{{$name}}">
				<span></span>
				<span class="check"></span>
				<span class="box"></span></label>
		</div>
	</div>
</div>