<?php
namespace App\Models\hacienda;
class estadoFactura
{
    const CREADA = 1;//factura crerada en el sistema
    const ENVIADA = 2;//factura enviada a hacienda pendiente de certificacion
    const RECHAZADA = 3;//factura rechazada por errores en hacienda
    const CERTIFICADA = 4;//factua certificada poor hacienda
    const PENDIENTE = 5;//facturas pendientes de enviar posibles problemas de sistema    
    const CLIENTE = 6;//factura enviada al cliente
    const ANULADA = 7;//factura anulada en hacienda
}