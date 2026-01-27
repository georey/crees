@include('pdf.css')
<table>
    <tr>
        <td style="width:50%;font-size:12px">
            <table>
                <tr>
                    <td>
                        <img
                            src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('https://admin.factura.gob.sv/consultaPublica?ambiente=01&codGen=' . $factura->codigo_generacion . '&fechaEmi=' . $factura->created_at->format('Y-m-d'))) !!} ">
                    </td>
                    <td style="text-align: center;">
                        <img src="{{ $logo_mini_75_base64 }}" style="width:75px;"><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $crees["nombre"] }}<br>
                        Giro: {{ $crees["actividad_economica"] }}<br>
                        {{ $crees["direccion"]["complemento"] }}. Santa Ana. Santa Ana<br>
                        Telefono: {{ substr($crees["telefono"], 0, 4) . '-' . substr($crees["telefono"], 4) }}<br>
                        Correo electornico: {{ $crees["correo"] }}<br>
                        NIT:0210-070416-101-0 NRC:250340-1
                    </td>
                </tr>
            </table>


        </td>
        <td style="width:50%">
            <table style="width:100%; border: solid 1px;">

                <thead style="width:100%;background: #A6B9C9;font-size:14px">
                    <tr>
                        <th>
                            DOCUMENTO TRIBUTARIO ELECTRONICO<br>
                            @if($json->identificacion->tipoDte == '03')
                                CREDITO FISCAL
                            @elseif($json->identificacion->tipoDte == '14')
                                SUJETO EXCLUIDO
                            @else
                                FACTURA
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody style="font-size:10px">
                    <tr>
                        <td>Codigo de generacion: {{ $factura->codigo_generacion }}</td>
                    </tr>
                    <tr>
                        <td>Sello de recepcion: {{ $factura->sello_recepcion }}</td>
                    </tr>
                    <tr>
                        <td>Numero de control: {{ $factura->numero_control }}</td>
                    </tr>
                    <tr>
                        <td>Modelo Fact: Previo</td>

                    </tr>
                    <tr>
                        <td>Ver. del JSON: 1</td>
                    </tr>
                    <tr>
                        <td>Tipo de Transaccion: Normal</td>

                    </tr>
                    <tr>

                        <td>Fecha emision: {{$factura->created_at->format('d/m/Y')}}</td>
                    </tr>
                    <tr>
                        <td>Hora Emision: {{$factura->created_at->format('H:i')}}</td>

                    </tr>


                </tbody>
            </table>
        </td>
    </tr>
</table>
<br><br>
<div class="line-separator"></div>
<br><br>
<table>
    <tr>
        <td>
            Nombre: {{$destinatario->nombre}}<br>
            @if($json->identificacion->tipoDte == '03')
                NIT: {{$destinatario->nit}}<br>
                NRC: {{$destinatario->nrc}}<br>
                Actividad Economica: {{$destinatario->descActividad}}<br>
            @elseif($json->identificacion->tipoDte == '14')
                Tipo de doc. de identificacion: {{$destinatario->tipoDocumento == '36' ? 'NIT' : 'DUI'}}<br>
                No de doc de identificacion: {{$destinatario->numDocumento}}<br>
                Actividad Economica: {{$destinatario->descActividad}}<br>
            @else
                Tipo de doc. de identificacion: DUI<br>
                No de doc de identificacion: {{$destinatario->numDocumento}}<br>
            @endif
            Direccion: {{$destinatario->direccion->complemento}}<br>
            Departamento: {{$destinatario->direccion->departamento}}
            Municipio: {{$destinatario->direccion->municipio}}<br>
            @if(isset($destinatario->telefono))
                Telefono: {{$destinatario->telefono}}<br>
            @endif
            Correo electronico: {{$destinatario->correo}}<br>
        </td>
    </tr>
</table>
<br><br>
<div class="line-separator"></div>

<table style="font-size: 12px">
    @if($json->identificacion->tipoDte == '14')
        {{-- Encabezado para Sujeto Excluido --}}
        <tr style="background: #FFD6A6">
            <th>ITEM</th>
            <th>TIPO</th>
            <th>CANT</th>
            <th>DESCRIPCION</th>
            <th>UNID MED</th>
            <th>PRECIO UNITARIO</th>
            <th>DESCUENTOS</th>
            <th>COMPRA</th>
        </tr>
        @foreach ($json->cuerpoDocumento as $item)
            <tr>
                <td>{{isset($item->numItem)? $item->numItem:''}}</td>
                <td>{{isset($item->tipoItem)? $item->tipoItem:''}}</td>
                <td>{{isset($item->cantidad)? $item->cantidad:''}}</td>
                <td>{{isset($item->descripcion)? $item->descripcion:''}}</td>
                <td>{{isset($item->uniMedida)? $item->uniMedida:''}}</td>
                <td style="text-align: right">{{isset($item->precioUni)? $item->precioUni:''}}</td>
                <td style="text-align: right">{{isset($item->montoDescu)? $item->montoDescu:''}}</td>
                <td style="text-align: right">{{isset($item->compra)? $item->compra:''}}</td>
            </tr>
        @endforeach
    @else
        {{-- Encabezado para Crédito Fiscal y Factura --}}
        <tr style="background: #FFD6A6">
            <th>ITEM</th>
            <th>TIPO</th>
            <th>CANT</th>
            <th>DESCRIPCION</th>
            <th>UNID MED</th>
            <th>PRECIO UNITARIO</th>
            <th>DESCUENTOS</th>
            <th>NO GRAVADO</th>
            <th>VENTAS NO SUJETAS</th>
            <th>VENTAS EXENTAS</th>
            <th>VENTAS GRAVADAS</th>
        </tr>
        @foreach ($json->cuerpoDocumento as $item)
            <tr>
                <td>{{isset($item->numItem)? $item->numItem:''}}</td>
                <td>{{isset($item->tipoItem)? $item->tipoItem:''}}</td>
                <td>{{isset($item->cantidad)? $item->cantidad:''}}</td>
                <td>{{isset($item->descripcion)? $item->descripcion:''}}</td>
                <td>{{isset($item->uniMedida)? $item->uniMedida:''}}</td>
                <td style="text-align: right">{{isset($item->precioUni)? $item->precioUni:''}}</td>
                <td style="text-align: right">{{isset($item->montoDescu)? $item->montoDescu:''}}</td>
                <td style="text-align: right">{{isset($item->noGravado)? $item->noGravado:''}}</td>
                <td style="text-align: right">{{isset($item->ventaNoSuj)? $item->ventaNoSuj:''}}</td>
                <td style="text-align: right">{{isset($item->ventaExenta)? $item->ventaExenta:''}}</td>
                <td style="text-align: right">{{isset($item->ventaGravada)? $item->ventaGravada:''}}</td>
            </tr>
        @endforeach
    @endif
</table>

<div class="line-separator"></div>
<br><br>
<table style="width:100%">
    <tr>
        <td style="width:50%;font-size:12px; vertical-align:top">
            <table style="width:100%; ">
                <tr>
                    <td style="text-align: center;background: #C8F7CE; padding-bottom: 24px;padding-top:24px;">
                        Valor en letras<br>
                        {{ strtoupper($montoLetras) }}
                    </td>
                </tr>
                <tr>
                    <td><br><br><br><br>
                        Entregado<br>
                        {{ strtoupper(Auth::user()->nombre . ' ' . Auth::user()->apellido) }}
                        <br><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        Recibido<br>
                        {{$destinatario->nombre}}
                    </td>
                </tr>
            </table>


        </td>
        <td style="width:50%">
            <table style="width:100%; border: solid 1px;">
                @if($json->identificacion->tipoDte == '14')
                    {{-- Resumen para Sujeto Excluido --}}
                    <tr>
                        <td>TOTAL COMPRA</td>
                        <td>{{$json->resumen->totalCompra}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL DESCUENTOS</td>
                        <td>{{$json->resumen->totalDescu}}</td>
                    </tr>
                    <tr>
                        <td>SUBTOTAL</td>
                        <td>{{$json->resumen->subTotal}}</td>
                    </tr>
                    <tr>
                        <td>RENTA RETENIDA</td>
                        <td>{{$json->resumen->reteRenta}}</td>
                    </tr>
                    <tr>
                        <td><b>TOTAL A PAGAR</b></td>
                        <td><b>{{$json->resumen->totalPagar}}</b></td>
                    </tr>
                @else
                    {{-- Resumen para Crédito Fiscal y Factura --}}
                    <tr>
                        <td>TOTAL DE VENTAS NO SUJETAS</td>
                        <td>{{$json->resumen->totalNoSuj}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL DE VENTAS EXENTAS</td>
                        <td>{{$json->resumen->totalExenta}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL DE VENTAS GRAVADAS</td>
                        <td>{{$json->resumen->totalGravada}}</td>
                    </tr>
                    <tr>
                        <td>SUBTOTAL DE VENTAS</td>
                        <td>{{$json->resumen->subTotalVentas}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL DESCUENTOS, BONIFICACIONES REB. Y OTROS</td>
                        <td>{{$json->resumen->totalDescu}}</td>
                    </tr>
                    <tr>
                        <td>SUBTOTAL</td>
                        <td>{{$json->resumen->subTotalVentas}}</td>
                    </tr>
                    <tr>
                        <td>IVA RETENIDO</td>
                        <td>{{$json->resumen->ivaRete1}}</td>
                    </tr>
                    <tr>
                        <td>MONTO TOTAL DE LA OPERACION</td>
                        <td>{{$json->resumen->montoTotalOperacion}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL NO GRAVADO</td>
                        <td>{{isset($json->resumen->totalNoGravado) ? $json->resumen->totalNoGravado : '0.00'}}</td>
                    </tr>
                    <tr>
                        <td><b>TOTAL A PAGAR</b></td>
                        <td><b>{{$json->resumen->totalPagar}}</b></td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
</table>