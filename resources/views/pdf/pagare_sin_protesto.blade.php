@include('pdf.css')
<h3 class="text-center">SERVICIOS CREDITICIOS DE EL SALVADOR, S.A. DE C.V.</h3>
<br>
<h2 class="text-center">PAGARÉ SIN PROTESTO</h2>
<br><br>
<p class="text-justify">
Por este <b>PAGARÉ SIN PROTESTO</b>, me obligo a pagar a la orden de <b>SERVICIOS CREDITICIOS DE EL SALVADOR, S.A. DE C.V.</b> el día {{$fecha->format('d')}} del mes de {{trans('meses.'.$fecha->format('F'))}} del año {{$fecha->format('Y')}} en sus oficinas en la ciudad de Santa Ana, la cantidad de <b>{{$prestamo->monto}}</b> dólares de los Estados Unidos de America, mas intereses del <b>{{$prestamo->tasa}}%</b> por ciento anual; y en caso de no ser pagados el capital y los intereses en la fecha de vencimiento, pagaré intereses moratorios del <b>{{$prestamo->tasa_mora}}%</b> por ciento anual.
</p>

<p class="text-justify">
Para los efectos legales de esta obligacion Mercantil, fijo como domicilio especial la ciudad de Santa Ana, Departamento de Santa Ana y en caso de ejecucion renuncio al derecho de apelar del decreto de embargo, sentencia de remate y de cualquier resolucion que admita este recurso y que se pronuncie en el Juicio Mercantil Ejecutivo correspondiente; sera depositario de los bienes que embarguen la persona designada por el Acreedor, a quien relevo de la obligacion  de rendir fianza; sera a mi cargo todos los gastos que hicierere el Acreedor en el cobro de esta deuda, inclusive los llamados personales y aunque no sea especialmente condenado en costas procesales.
</p>

<p class="text-justify">
En fe de lo anterior, firmo este <b>PAGARÉ SIN PROTESTO</b> en la ciudad de Santa Ana, Departamento de Santa Ana, el día {{$fecha->format('d')}} del mes de {{trans('meses.'.$fecha->format('F'))}} del año {{$fecha->format('Y')}}
</p>
<br><br>
<label>FIRMA:__________________________________</label><br>
<label>NOMBRE: {{$prestamo->cliente->nombreCompleto()}}</label><br>
<label>DUI: {{$prestamo->cliente->dui}}</label><br>
<label>DIRECCION: {{$prestamo->cliente->direccion}}</label><br>
@if($prestamo->fiadores->count() > 0)
@foreach($prestamo->fiadores as $fiador)
	<br><br>
	<label>FIRMA:__________________________________</label><br>
	<label>NOMBRE: {{$fiador->nombreCompleto()}}</label><br>
	<label>DUI: {{$fiador->dui}}</label><br>
	<label>DIRECCION: {{$fiador->direccion}}</label><br>
@endforeach
@endif