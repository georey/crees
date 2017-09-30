<label class="col-lg-1 col-md-2 {{ isset($off) ? 'col-md-offset-' . $off : '' }} text-right control-label">
	<strong> {{ isset($label) ? trans('dictionary.' . strtolower($label)) . ': ' : ''}} </strong>
</label>
<div class="col-lg-{{$col + 1}} col-md-{{$col}} text-justify">
    <div class="well" style="overflow-x:auto;">
	    {!!html_entity_decode($value)!!}
	</div>
</div>