<label class="col-lg-1 col-md-2 {{ isset($off) ? 'col-md-offset-' . $off : '' }} text-right control-label">
	<strong> {{ isset($label) ? trans('dictionary.' . strtolower($label)) . ': ' : ''}} </strong>
</label>
<div class="col-lg-{{$col + 1 }} col-md-{{$col}} text-justify">
    @if (isset($color))
		<label class="rounded-4 control-label bg-{{$color}} bg-font-{{$color}}" style="padding: 5px"> {{$value or ''}} </label>
	@else
		<label class="control-label"> {{$value or ''}} </label>
	@endif
</div>
