<p>Hola  {{$json->receptor->nombre}},</p>

<p>Le enviamos su factura con un total de <strong>${{ number_format($json->resumen->totalPagar, 2) }}</strong>.</p>

<p>Adjunto encontrará su factura en formato PDF.</p>

<p>Gracias por su preferencia.</p>