<?php

namespace App\Services;
use App\Models\hacienda\estadoFactura;
use App\Models\hacienda\factura;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Services\MoneyService;
use App\Services\PdfService;
use App\Services\OAuthTransportFactory;
use Swift_Mailer;
use Mail;
use PDF;


class mhService
{
    protected $dte;

    protected $moneyService;
    
    protected $pdfService;

    public function __construct(MoneyService $moneyService, PdfService $pdfService)
    {
        $this->dte = Config::get('dte');
        $this->moneyService = $moneyService;
        $this->pdfService = $pdfService;
    }

    function generarSecuencia($value)
    {
        return str_pad($value, 15, '0', STR_PAD_LEFT);
    }

    public function auth()
    {
        $tokenData = session('token_data');

        $now = Carbon::now();
        $credentials = ['user' => $this->dte['user'], 'pwd' => $this->dte['password']];
        if ($tokenData) {
            $fechaGuardada = $tokenData['created_at'];
            $fechaHoy = $now->toDateString();

            if ($fechaGuardada !== $fechaHoy) {
                session()->forget('token_data');
                $client = new \GuzzleHttp\Client();
                $resp = $client->post($this->dte['url_auth'], [
                    'form_params' => $credentials,
                ]);
                $data_result = json_decode($resp->getBody(), true);
                $token = $data_result['body']['token'];
                session([
                    'token_data' => [
                        'token' => $token,
                        'created_at' => $now->toDateString()
                    ]
                ]);
            } else {
                $token = $tokenData['token'];
            }
        } else {
            $client = new \GuzzleHttp\Client();

            $resp = $client->post($this->dte['url_auth'], [
                'form_params' => $credentials,
            ]);
            $data_result = json_decode($resp->getBody(), true);
            $token = $data_result['body']['token'];

            session([
                'token_data' => [
                    'token' => $token,
                    'created_at' => $now->toDateString()
                ]
            ]);
        }

        return $token;

    }

    public function firmarJson($rutaJson, $pass = null, $xml = null)
    {
        // Leer y normalizar el archivo JSON
        $raw = file_get_contents($rutaJson);
        if ($raw === false) {
            throw new \Exception("No se pudo leer el archivo JSON: " . $rutaJson);
        }

        // Quitar BOM UTF-8 si existe
        if (substr($raw, 0, 3) === "\xEF\xBB\xBF") {
            $raw = substr($raw, 3);
        }

        // Normalizar JSON
        $data = json_decode($raw, true);

        // Si sigue siendo string → venía doble serializado
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data)) {
            throw new \Exception("El payload no es JSON válido");
        }

        // Generar el JSON normalizado (igual que en DTEFirmador)
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Guardar copia antes de firmar para debug
        $outDir = storage_path('signed_dtes');
        @mkdir($outDir, 0755, true);
        $outFile = $outDir . '/antesdefirma-' . basename($rutaJson) . '.json';
        file_put_contents($outFile, $payload);

        // Enviar al servicio de firmado de Hacienda
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->post('http://host.docker.internal:8113/firmardocumento/', [
                'json' => [
                    'nit' => $this->dte['nit'],
                    'dteJson' => $data,  // Enviar el array, no el string
                    'passwordPri' => $this->dte['passwordPri']
                ],
                'timeout' => 30
            ]);

            $responseBody = (string) $response->getBody();
            Log::info('Respuesta del servicio de firmado: ' . $responseBody);

            // Intentar decodificar si es JSON
            $responseData = json_decode($responseBody, true);
            
            if ($responseData && isset($responseData['body'])) {
                // Verificar si body es un string (JWT) o un array (error)
                if (is_string($responseData['body'])) {
                    $jwt = trim($responseData['body']);
                } else if (is_array($responseData['body'])) {
                    // Error del servicio de firmado
                    $errorMsg = 'Error del servicio de firmado: ';
                    if (isset($responseData['body']['mensaje'])) {
                        $errorMsg .= $responseData['body']['mensaje'];
                    }
                    if (isset($responseData['body']['codigo'])) {
                        $errorMsg .= ' (Código: ' . $responseData['body']['codigo'] . ')';
                    }
                    throw new \Exception($errorMsg);
                } else {
                    throw new \Exception("Formato de respuesta del servicio de firmado no reconocido");
                }
            } else {
                $jwt = trim($responseBody);
            }

            if (empty($jwt)) {
                throw new \Exception("El servicio de firmado retornó una respuesta vacía");
            }

            Log::info('JWT extraído: ' . substr($jwt, 0, 100) . '...');

            // Guardar JWT firmado
            $jwtFile = $outDir . '/' . basename($rutaJson) . '.jwt.txt';
            file_put_contents($jwtFile, $jwt);

            return $jwt;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorMsg = "Error al conectar con el servicio de firmado: " . $e->getMessage();
            if ($e->hasResponse()) {
                $errorMsg .= " - Respuesta: " . (string) $e->getResponse()->getBody();
            }
            throw new \Exception($errorMsg);
        }
    }

    public function enviarJson($json, $secuencia)
    {
        $client = new \GuzzleHttp\Client();

        try {

            // 3️⃣ Enviar al servicio de recepción
            $token = $this->auth();
            $now = Carbon::now();
            $json_path = storage_path('logs/json/' . $now->format('YmdHis') . '.json');
            $jsonString = json_encode(
                $json,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

            if ($jsonString === false) {
                throw new Exception('Error al generar JSON: ' . json_last_error_msg());
            }

            file_put_contents($json_path, $jsonString);

            $jwt = $this->firmarJson($json_path);

            // Version 3 para credito fiscal, version 1 para otros tipos
            $tipoDte = $json['identificacion']['tipoDte'];
            $wrapperVersion = ($tipoDte == '03') ? 3 : (int) $this->dte['version'];

            $body = [
                "ambiente" => "00", // pruebas
                "idEnvio" => $secuencia,
                "version" => $wrapperVersion,
                "tipoDte" => $tipoDte,
                "documento" => $jwt
            ];

            Log::error('body generado');

            file_put_contents(storage_path('signed_dtes/' . $now->format('YmdHis') . '.json'), json_encode($body));
            $response = $client->post($this->dte['url_envio'], [
                'headers' => [
                    'Authorization' => $token,
                    'Accept' => 'application/json'
                ],
                'json' => $body
            ]);

            // 4️⃣ Leer y registrar la respuesta
            $responseBody = (string) $response->getBody();
            $statusCode = $response->getStatusCode();

            Log::info('Código HTTP: ' . $statusCode);
            Log::info('Respuesta de recepción DTE: ' . $responseBody);

            $respuesta = json_decode($responseBody, true);

            return $respuesta;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $body = (string) $e->getResponse()->getBody();
                file_put_contents(storage_path('logs/response_error.log'), $body);
            } else {
                Log::error('Error al enviar Json' . $e->getMessage());
            }
            return false;
        }
    }

    function limpiarString($texto)
    {
        $texto = trim($texto);
        $texto = preg_replace('/\s+/', ' ', $texto);
        $buscar  = array(
            'á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ'
        );
        $reemplazar = array(
            'a','e','i','o','u','A','E','I','O','U','n','N'
        );
        $texto = str_replace($buscar, $reemplazar, $texto);
        $texto = preg_replace('/[^A-Za-z0-9 .,#\-\/]/', '', $texto);
        if (strlen($texto) > 200) {
            $texto = substr($texto, 0, 200);
        }
        return $texto;
    }

    public function generarFactura($cliente, $descripciones, $cantidades, $precios, $tipo, $unidades, $descuento, $no_suj, $exenta)
    {

        $now = Carbon::now();

        $fecha_emision = $now->format('Y-m-d');
        $hora_emision = $now->format('H:i:s');
        $tipoDte = "01";
        $secuencia = $this->generarSecuencia(factura::secuencia());

        $numero_control = 'DTE-' . $tipoDte . '-' . $this->dte['numero_establecimiento'] . '-' . $secuencia;
        $codigo_generacion = strtoupper(Uuid::uuid4()->toString());

        $identificacion = [
            'version' => (int) $this->dte['version'],
            'ambiente' => $this->dte['ambiente'],
            'tipoDte' => $tipoDte,
            'numeroControl' => $numero_control,
            'codigoGeneracion' => $codigo_generacion,
            "tipoModelo" => 1,
            "tipoOperacion" => 1,
           
            'fecEmi' => $fecha_emision,
            'horEmi' => $hora_emision,
            'tipoMoneda' => $this->dte['tipo_moneda'],
            "tipoContingencia" => null,
            "motivoContin"     => null,
        ];



        //Emisor
        $direccion_emisor = [
            'departamento' => $this->dte['departamento'],
            'municipio' => $this->dte['municipio'],
            'complemento' => $this->dte['direccion']
        ];

        $emisor = [
            'nit' => $this->dte['nit'],
            'nrc' => $this->dte['nrc'],
            'nombre' => $this->dte['nombre'],
            'nombreComercial' => $this->dte['nombre_comercial'],
            'codActividad' => $this->dte['cod_actividad_economica'],
            "tipoEstablecimiento" => "01",
            "descActividad" => $this->dte['actividad_economica'],
            'direccion' => $direccion_emisor,
            'telefono' => $this->dte['telefono'],
            'correo' => $this->dte['correo'],
            "codEstableMH"=>"0001",
            "codEstable"=>"0001",
            "codPuntoVentaMH"=>"0001",
            "codPuntoVenta"=>"0001"

        ];

        //Receptor
        $direccion = [
            'departamento' => $cliente->departamento->codigo,
            'municipio' => $cliente->municipio->codigo,
            'complemento' => $this->limpiarString($cliente->direccion)
        ];

        $receptor = [
            "tipoDocumento" => "36",
            "numDocumento" => str_replace('-', '', $cliente->dui),
            'nrc' => null,
            // 'nrc' => str_replace('-', '', $cliente->nrc),
            'nombre' => $cliente->nombreCompleto(),
            'direccion' => $direccion,
            'telefono' => $cliente->telefono,
            'correo' => ($cliente->correo !== null) ? $cliente->correo : $this->dte['correo'],
            "codActividad" => null,
            "descActividad" => null
        ];

        //Cuerpo
        $cuerpo = array();
        $apendice = array();

        $totalDescuentoNosujeto = 0.00;
        $totalDescuentoExento   = 0.00;
        $totalDescuentoGravado  = 0.00;

        $totalNoSujeto = 0.00;
        $totalExenta   = 0.00;
        $totalGravada  = 0.00; // BASE sin IVA
        $totalIVA      = 0.00;
        $totalNoGravado = 0.00;

        $numItemCuerpo = 1; // Contador separado para items del cuerpo

        for ($i = 0; $i < count($descripciones); $i++) {

            $precio   = round((float)$precios[$i], 2);
            $cantidad = (int)$cantidades[$i];
            $subtotal = round($precio * $cantidad, 2);

            $noSuj   = round((float)$no_suj[$i], 2);
            $exenta  = round((float)$exenta[$i], 2);

            // Si es capital (no sujeto), usar noGravado en lugar de ventaNoSuj
            $noGravado = 0.00;
            if ($noSuj > 0 && $subtotal == 0) {
                // Para noGravado, el precio unitario debe ser 0 y el total va en noGravado
                $noGravado = $noSuj;
                $noSuj = 0.00; // No usar ventaNoSuj para capital
            }

            // =========================
            // IVA INCLUIDO EN GRAVADOS
            // =========================
            if ($subtotal > 0 && $noSuj == 0 && $exenta == 0 && $noGravado == 0) {

                // Precio incluye IVA - ventaGravada lleva el precio completo
                $ventaGravadaItem = $subtotal;
                $baseParaIva = round($subtotal / 1.13, 2);
                $ivaItem     = round($subtotal - $baseParaIva, 2);

            } else {
                $ventaGravadaItem = 0.00;
                $ivaItem     = 0.00;
            }

            $item = array(
                'numItem'          => $numItemCuerpo,
                'tipoItem'         => $tipo[$i],
                'numeroDocumento'  => null,
                'codigo'           => strtoupper(substr($descripciones[$i], 0, 20)),
                'cantidad'         => $cantidad,
                'uniMedida'        => $unidades[$i],
                'descripcion'      => $descripciones[$i],

                // Valores unitarios
                'precioUni'        => $precio,
                'montoDescu'       => round((float)$descuento[$i], 2),

                // Ventas
                'ventaNoSuj'       => $noSuj,
                'ventaExenta'      => $exenta,
                'ventaGravada'     => $ventaGravadaItem,

                // Campos espejo requeridos
                'noGravado'        => $noGravado,
                'psv'              => 0,               
                'ivaItem'          => $ivaItem,
                'codTributo'       => null,
                'tributos'         => null
                );

            $cuerpo[] = $item;
            $numItemCuerpo++;

            // =========================
            // ACUMULADOS
            // =========================
            if ($ventaGravadaItem > 0) {
                $totalDescuentoGravado += (float)$descuento[$i];
            }
            if ($noSuj > 0) {
                $totalDescuentoNosujeto += (float)$descuento[$i];
            }
            if ($exenta > 0) {
                $totalDescuentoExento += (float)$descuento[$i];
            }

            $totalNoSujeto += $noSuj;
            $totalExenta   += $exenta;
            $totalGravada  += $ventaGravadaItem;
            $totalIVA      += $ivaItem;
            $totalNoGravado += $noGravado;
        }

        // =========================
        // TOTALES GENERALES
        // =========================
        // subTotalVentas NO incluye noGravado según el estándar del MH
        $subTotalVentas = round($totalNoSujeto + $totalExenta + $totalGravada, 2);
        $totalDescuento = round($totalDescuentoNosujeto + $totalDescuentoExento + $totalDescuentoGravado, 2);

        // montoTotalOperacion NO incluye noGravado (es el total de ventas gravables)
        $montoTotalOperacion = round($subTotalVentas - $totalDescuento, 2);
        
        // totalPagar SÍ incluye noGravado (total final a pagar)
        $totalPagar = round($montoTotalOperacion + $totalNoGravado, 2);

        // =========================
        // RESUMEN (CORRECTO MH)
        // =========================
        $resumen = array(            
            "tributos"            => [],
            'totalNoSuj'          => round($totalNoSujeto, 2),
            'totalExenta'         => round($totalExenta, 2),
            'totalGravada'        => round($totalGravada, 2),

            'subTotalVentas'      => $subTotalVentas,

            'descuNoSuj'          => round($totalDescuentoNosujeto, 2),
            'descuExenta'         => round($totalDescuentoExento, 2),
            'descuGravada'        => round($totalDescuentoGravado, 2),

            'porcentajeDescuento' => round($subTotalVentas > 0 ? ($totalDescuento / $subTotalVentas) * 100 : 0, 2),
            'totalDescu'          => round($totalDescuento, 2),

            'subTotal'            => $subTotalVentas,

            'reteRenta'           => 0.00,
            'ivaRete1'            => 0.00,

            'totalIva'            => round($totalIVA, 2),

            'montoTotalOperacion' => $montoTotalOperacion,
            'totalPagar'          => round($totalPagar, 2),

            'saldoFavor'          => 0.00,

            'totalLetras'         => $this->moneyService->convertirMontoADolares($totalPagar),

            'condicionOperacion'  => 1, // Contado

            'pagos' => array(
                array(
                    'codigo'     => '01', // Efectivo
                    'montoPago'  => round($totalPagar, 2),
                    'referencia' => null,
                    'plazo'      => null,
                    'periodo'    => null
                )
            ),

            'numPagoElectronico' => '',
            "totalNoGravado"=> round($totalNoGravado, 2)
        );



        $factura = [
            'identificacion' => $identificacion,
            'emisor' => $emisor,
            'receptor' => $receptor,
            'cuerpoDocumento' => $cuerpo,
            'resumen' => $resumen,
            "documentoRelacionado"=> null,
            "otrosDocumentos"=> null,
            "ventaTercero"=> null,
            "extension"=> null,
            "apendice"=> count($apendice) > 0 ? $apendice : null
        ];

        $jsonString = json_encode($factura);
        $mh_factura = factura::create(["cliente_id" => $cliente->id, "estado" => estadoFactura::CREADA, "numero_control" => $numero_control, "codigo_generacion" => $codigo_generacion, "json" => $jsonString]);
        Log::error('inciando enviar json');
        $jsonResponse = $this->enviarJson($factura, (int) $secuencia);
        Log::error('finalizando enviar json');
        if ($jsonResponse) {
            $mh_factura->estado = estadoFactura::CERTIFICADA;
            // Guardar respuesta del MH pero mantener JSON original para PDF
            $mh_factura->respuesta_mh = json_encode($jsonResponse);
            // Guardar sello de recepción para anulaciones futuras
            if (isset($jsonResponse['selloRecibido'])) {
                $mh_factura->sello_recepcion = $jsonResponse['selloRecibido'];
            }
        } else {
            $mh_factura->estado = estadoFactura::RECHAZADA;
        }
        $mh_factura->save();
        
        // Enviar correo si la factura fue certificada
        if ($mh_factura->estado == estadoFactura::CERTIFICADA) {
            $this->enviarCorreoFactura($mh_factura);
        }
        
        return $mh_factura;
    }

    protected function enviarCorreoFactura($factura)
    {
        try {
            $carbon = new Carbon();
            $data['fecha'] = $carbon;
            $data['factura'] = $factura;
            $data['json'] = json_decode($factura->json);
            $data["crees"] = [
                'nombre' => $this->dte['nombre'],
                'telefono' => $this->dte['telefono'],
                'direccion' => [
                    'departamento' => $this->dte['departamento'],
                    'municipio' => $this->dte['municipio'],
                    'complemento' => $this->dte['direccion']
                ],
                'actividad_economica' => $this->dte['actividad_economica'],
                'correo' => $this->dte['correo']
            ];
            $data['montoLetras'] = $this->moneyService->convertirMontoADolares($data["json"]->resumen->totalPagar);
            
            $pdf = PDF::loadView('pdf.mh_factura', $data);
            
            $correo = $factura->cliente->correo ? $factura->cliente->correo : $this->dte['correo'];
            $bccCorreo = $this->dte['correo'];
            $pdfContent = $pdf->output();
            $jsonContent = $factura->json;
            
            Mail::send('emails.factura', $data, function ($message) use ($pdfContent, $jsonContent, $correo, $bccCorreo) {
                $message->to($correo)
                        ->bcc($bccCorreo)
                        ->subject('Servicios crediticios de El Salvador')
                        ->attachData($pdfContent, 'factura.pdf', [
                            'mime' => 'application/pdf',
                        ])
                        ->attachData($jsonContent, 'factura.json', [
                            'mime' => 'application/json',
                        ]);
            });
            
            // Cambiar estado a CLIENTE después de enviar correo exitosamente
            $factura->estado = estadoFactura::CLIENTE;
            $factura->save();
            
            Log::info('Correo enviado exitosamente para factura: ' . $factura->id);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo para factura ' . $factura->id . ': ' . $e->getMessage());
        }
    }

    public function generarFacturaCustom($cliente, $descripciones, $cantidades, $precios, $tipo, $unidades, $descuento, $no_suj, $exenta, $tipoDte)
    {
        Log::info('=== INICIO generarFacturaCustom ===');
        Log::info('TipoDte: ' . $tipoDte);
        Log::info('Descripciones recibidas: ' . json_encode($descripciones));
        Log::info('Cantidades recibidas: ' . json_encode($cantidades));
        Log::info('Precios recibidos: ' . json_encode($precios));
        Log::info('Tipos recibidos: ' . json_encode($tipo));
        Log::info('Unidades recibidas: ' . json_encode($unidades));
        Log::info('Count descripciones: ' . count($descripciones));
        
        $now = Carbon::now();

        $fecha_emision = $now->format('Y-m-d');
        $hora_emision = $now->format('H:i:s');
        $secuencia = $this->generarSecuencia(factura::secuencia());

        $numero_control = 'DTE-' . $tipoDte . '-' . $this->dte['numero_establecimiento'] . '-' . $secuencia;
        $codigo_generacion = strtoupper(Uuid::uuid4()->toString());

        // Version 3 para credito fiscal, version 1 para otros tipos
        $version = ($tipoDte == '03') ? 3 : (int) $this->dte['version'];

        $identificacion = [
            'version' => $version,
            'ambiente' => $this->dte['ambiente'],
            'tipoDte' => $tipoDte,
            'numeroControl' => $numero_control,
            'codigoGeneracion' => $codigo_generacion,
            "tipoModelo" => 1,
            "tipoOperacion" => 1,
            'fecEmi' => $fecha_emision,
            'horEmi' => $hora_emision,
            'tipoMoneda' => $this->dte['tipo_moneda'],
            "tipoContingencia" => null,
            "motivoContin" => null,
        ];

        // Emisor
        $direccion_emisor = [
            'departamento' => $this->dte['departamento'],
            'municipio' => $this->dte['municipio'],
            'complemento' => $this->dte['direccion']
        ];

        $emisor = [
            'nit' => $this->dte['nit'],
            'nrc' => $this->dte['nrc'],
            'nombre' => $this->dte['nombre'],
            'nombreComercial' => $this->dte['nombre_comercial'],
            'codActividad' => $this->dte['cod_actividad_economica'],
            "tipoEstablecimiento" => "01",
            "descActividad" => $this->dte['actividad_economica'],
            'direccion' => $direccion_emisor,
            'telefono' => $this->dte['telefono'],
            'correo' => $this->dte['correo'],
            "codEstableMH" => "0001",
            "codEstable" => "0001",
            "codPuntoVentaMH" => "0001",
            "codPuntoVenta" => "0001"
        ];

        // Receptor - Para crédito fiscal (03)
        // Verificar si el cliente es un objeto de base de datos o un objeto manual
        $esClienteManual = !isset($cliente->id) || $cliente->id === null;
        
        if (!$esClienteManual) {
            // Cliente de base de datos - usar estructura original
            $direccion = [
                'departamento' => $cliente->departamento->codigo,
                'municipio' => $cliente->municipio->codigo,
                'complemento' => $this->limpiarString($cliente->direccion)
            ];
            $nombreCompleto = $cliente->nombreCompleto();
            $nit = str_replace('-', '', $cliente->nit ? $cliente->nit : $cliente->dui);
            $nrc = str_replace('-', '', $cliente->nrc ? $cliente->nrc : '');
            $telefono = $cliente->telefono;
            $correo = ($cliente->correo !== null) ? $cliente->correo : $this->dte['correo'];
            $codActividad = $cliente->cod_actividad_economica ? $cliente->cod_actividad_economica : '71102';
            $descActividad = $cliente->desc_actividad_economica ? $cliente->desc_actividad_economica : 'Servicios de ingenieria';
        } else {
            // Cliente manual del formulario
            $direccion = [
                'departamento' => isset($cliente->departamento_codigo) ? $cliente->departamento_codigo : '06',
                'municipio' => isset($cliente->municipio_codigo) ? $cliente->municipio_codigo : '14',
                'complemento' => $this->limpiarString(isset($cliente->direccion) ? $cliente->direccion : 'San Salvador')
            ];
            $nombreCompleto = trim($cliente->nombre . ' ' . $cliente->apellido);
            $nit = str_replace('-', '', $cliente->nit ? $cliente->nit : '');
            $nrc = str_replace('-', '', $cliente->nrc ? $cliente->nrc : '');
            $telefono = $cliente->telefono ? $cliente->telefono : '0000-0000';
            $correo = $cliente->correo ? $cliente->correo : $this->dte['correo'];
            
            // Obtener actividad económica desde el formulario o usar default
            if (isset($cliente->cod_actividad_economica) && $cliente->cod_actividad_economica) {
                $codActividad = $cliente->cod_actividad_economica;
                // Buscar descripción en la tabla
                $actividadEconomica = \App\Models\catalogos\mh_actividad_economica::where('codigo', $codActividad)->first();
                $descActividad = $actividadEconomica ? $actividadEconomica->descripcion : 'Servicios de ingenieria';
            } else {
                $codActividad = '71102';
                $descActividad = 'Servicios de ingenieria';
            }
        }

        // Estructura diferente según el tipo de DTE
        if ($tipoDte == '03') {
            // Crédito Fiscal
            $receptor = [
                'nit' => $nit,
                'nrc' => $nrc,
                'nombre' => $nombreCompleto,
                'codActividad' => $codActividad,
                'descActividad' => $descActividad,
                'nombreComercial' => null,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'correo' => $correo
            ];
        } elseif ($tipoDte == '14') {
            // Sujeto Excluido
            $receptor = [
                "tipoDocumento" => "36",
                "numDocumento" => $nit,
                'nombre' => $nombreCompleto,
                'codActividad' => $codActividad,
                'descActividad' => $descActividad,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'correo' => $correo
            ];
        } else {
            // Factura de consumidor final y otros
            $receptor = [
                "tipoDocumento" => "36",
                "numDocumento" => $nit,
                'nrc' => null,
                'nombre' => $nombreCompleto,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'correo' => $correo,
                "codActividad" => null,
                "descActividad" => null
            ];
        }

        // Cuerpo
        $cuerpo = array();
        $apendice = array();

        $totalDescuentoNosujeto = 0.00;
        $totalDescuentoExento = 0.00;
        $totalDescuentoGravado = 0.00;

        $totalNoSujeto = 0.00;
        $totalExenta = 0.00;
        $totalGravada = 0.00;
        $totalIVA = 0.00;
        $totalNoGravado = 0.00;

        $numItemCuerpo = 1;

        Log::info('Iniciando loop de items. Total items: ' . count($descripciones));
        
        for ($i = 0; $i < count($descripciones); $i++) {
            Log::info('Procesando item #' . $i);
            Log::info('  Descripcion[$i]: ' . (isset($descripciones[$i]) ? $descripciones[$i] : 'NO EXISTE'));
            Log::info('  Precio[$i]: ' . (isset($precios[$i]) ? $precios[$i] : 'NO EXISTE'));
            Log::info('  Cantidad[$i]: ' . (isset($cantidades[$i]) ? $cantidades[$i] : 'NO EXISTE'));
            Log::info('  Tipo[$i]: ' . (isset($tipo[$i]) ? $tipo[$i] : 'NO EXISTE'));
            Log::info('  Unidad[$i]: ' . (isset($unidades[$i]) ? $unidades[$i] : 'NO EXISTE'));
            Log::info('  Descuento[$i]: ' . (isset($descuento[$i]) ? $descuento[$i] : 'NO EXISTE'));
            
            // Validar que todos los índices existan
            if (!isset($descripciones[$i]) || !isset($precios[$i]) || !isset($cantidades[$i]) || 
                !isset($tipo[$i]) || !isset($unidades[$i]) || !isset($descuento[$i])) {
                Log::warning('Saltando item #' . $i . ' - Faltan datos requeridos');
                continue; // Saltar este item si faltan datos
            }
            
            Log::info('Item #' . $i . ' pasó validación de datos');

            $precio = round((float)$precios[$i], 2);
            $cantidad = (int)$cantidades[$i];
            $subtotal = round($precio * $cantidad, 2);

            $noSuj = isset($no_suj[$i]) ? round((float)$no_suj[$i], 2) : 0.00;
            $exemptaItem = isset($exenta[$i]) ? round((float)$exenta[$i], 2) : 0.00;

            // Si es capital (no sujeto), usar noGravado
            $noGravado = 0.00;
            if ($noSuj > 0 && $subtotal == 0) {
                $noGravado = $noSuj;
                $noSuj = 0.00;
            }

            // IVA INCLUIDO EN GRAVADOS
            if ($subtotal > 0 && $noSuj == 0 && $exemptaItem == 0 && $noGravado == 0) {
                $baseParaIva = round($subtotal / 1.13, 2);
                $ventaGravadaItem = $baseParaIva;
                $ivaItem = round($subtotal - $baseParaIva, 2);
            } else {
                $ventaGravadaItem = 0.00;
                $ivaItem = 0.00;
            }

            // Estructura de item según el tipo de DTE
            if ($tipoDte == '03') {
                // Crédito Fiscal
                $item = array(
                    'numItem' => $numItemCuerpo,
                    'tipoItem' => 2,
                    'numeroDocumento' => null,
                    'codigo' => null,
                    'codTributo' => null,
                    'cantidad' => $cantidad,
                    'uniMedida' => 99,
                    'descripcion' => $descripciones[$i],
                    'precioUni' => $baseParaIva,
                    'montoDescu' => round((float)$descuento[$i], 2),
                    'ventaNoSuj' => $noSuj,
                    'ventaExenta' => $exemptaItem,
                    'ventaGravada' => $ventaGravadaItem,
                    'tributos' => $ventaGravadaItem > 0 ? ['20'] : null,
                    'noGravado' => $noGravado,
                    'psv' => 0.0
                );
            } elseif ($tipoDte == '14') {
                // Sujeto Excluido - estructura simplificada
                $item = array(
                    'numItem' => $numItemCuerpo,
                    'tipoItem' => $tipo[$i],
                    'cantidad' => $cantidad,
                    'codigo' => null,
                    'uniMedida' => $unidades[$i],
                    'descripcion' => $descripciones[$i],
                    'precioUni' => $precio,
                    'montoDescu' => round((float)$descuento[$i], 2),
                    'compra' => $subtotal - round((float)$descuento[$i], 2)
                );
            } else {
                // Factura de consumidor final y otros
                $item = array(
                    'numItem' => $numItemCuerpo,
                    'tipoItem' => $tipo[$i],
                    'numeroDocumento' => null,
                    'codigo' => strtoupper(substr($descripciones[$i], 0, 20)),
                    'cantidad' => $cantidad,
                    'uniMedida' => $unidades[$i],
                    'descripcion' => $descripciones[$i],
                    'precioUni' => $precio,
                    'montoDescu' => round((float)$descuento[$i], 2),
                    'ventaNoSuj' => $noSuj,
                    'ventaExenta' => $exemptaItem,
                    'ventaGravada' => $ventaGravadaItem,
                    'noGravado' => $noGravado,
                    'psv' => 0,
                    'ivaItem' => $ivaItem,
                    'codTributo' => null,
                    'tributos' => null
                );
            }

            $cuerpo[] = $item;
            Log::info('Item agregado al cuerpo. NumItem: ' . $numItemCuerpo . ', Descripcion: ' . $item['descripcion']);
            $numItemCuerpo++;

            // Acumulados
            if ($ventaGravadaItem > 0) {
                $totalDescuentoGravado += (float)$descuento[$i];
            }
            if ($noSuj > 0) {
                $totalDescuentoNosujeto += (float)$descuento[$i];
            }
            if ($exemptaItem > 0) {
                $totalDescuentoExento += (float)$descuento[$i];
            }

            $totalNoSujeto += $noSuj;
            $totalExenta += $exemptaItem;
            $totalGravada += $ventaGravadaItem;
            $totalIVA += $ivaItem;
            $totalNoGravado += $noGravado;
        }
        
        Log::info('Loop finalizado. Total items en cuerpo: ' . count($cuerpo));
        Log::info('cuerpoDocumento: ' . json_encode($cuerpo));

        // Totales generales
        $subTotalVentas = round($totalNoSujeto + $totalExenta + $totalGravada, 2);
        $totalDescuento = round($totalDescuentoNosujeto + $totalDescuentoExento + $totalDescuentoGravado, 2);
        
        // Para CCF, montoTotalOperacion incluye IVA; para otros, no
        if ($tipoDte == '03') {
            $montoTotalOperacion = round($subTotalVentas - $totalDescuento + $totalIVA, 2);
            $totalPagar = round($montoTotalOperacion + $totalNoGravado, 2);
        } else {
            $montoTotalOperacion = round($subTotalVentas - $totalDescuento, 2);
            $totalPagar = round($montoTotalOperacion + $totalIVA + $totalNoGravado, 2);
        }

        // Resumen según tipo de DTE
        if ($tipoDte == '14') {
            // Resumen simplificado para Sujeto Excluido
            $totalCompra = round($totalPagar, 2);
            $resumen = array(
                'totalCompra' => $totalCompra,
                'descu' => round($totalDescuento, 2),
                'totalDescu' => round($totalDescuento, 2),
                'subTotal' => $totalCompra,
                'ivaRete1' => 0.00,
                'reteRenta' => 0.00,
                'totalPagar' => $totalCompra,
                'totalLetras' => $this->moneyService->convertirMontoADolares($totalCompra),
                'condicionOperacion' => 1,
                'pagos' => array(
                    array(
                        'codigo' => '01',
                        'montoPago' => $totalCompra,
                        'referencia' => null,
                        'plazo' => null,
                        'periodo' => null
                    )
                ),
                'observaciones' => null
            );
        } else {
            // Resumen para Crédito Fiscal y Factura
            $tributos = [];
            if ($tipoDte == '03' && $totalIVA > 0) {
                // Para crédito fiscal, agregar tributo del IVA
                $tributos[] = array(
                    'codigo' => '20',
                    'descripcion' => 'Impuesto al Valor Agregado 13%',
                    'valor' => round($totalIVA, 2)
                );
            }

            $resumen = array(
                'totalNoSuj' => round($totalNoSujeto, 2),
                'totalExenta' => round($totalExenta, 2),
                'totalGravada' => round($totalGravada, 2),
                'subTotalVentas' => $subTotalVentas,
                'descuNoSuj' => round($totalDescuentoNosujeto, 2),
                'descuExenta' => round($totalDescuentoExento, 2),
                'descuGravada' => round($totalDescuentoGravado, 2),
                'porcentajeDescuento' => round($subTotalVentas > 0 ? ($totalDescuento / $subTotalVentas) * 100 : 0, 2),
                'totalDescu' => round($totalDescuento, 2),
                'tributos' => count($tributos) > 0 ? $tributos : null,
                'subTotal' => $subTotalVentas,
                'ivaPerci1' => 0.00,
                'ivaRete1' => 0.00,
                'reteRenta' => 0.00,
                'montoTotalOperacion' => $montoTotalOperacion,
                'totalNoGravado' => round($totalNoGravado, 2),
                'totalPagar' => round($totalPagar, 2),
                'totalLetras' => $this->moneyService->convertirMontoADolares($totalPagar),
                'saldoFavor' => 0.00,
                'condicionOperacion' => 1,
                'pagos' => array(
                    array(
                        'codigo' => '01',
                        'montoPago' => round($totalPagar, 2),
                        'referencia' => null,
                        'plazo' => null,
                        'periodo' => null
                    )
                ),
                'numPagoElectronico' => null
            );
            
            // Para factura (no crédito fiscal), agregar totalIva
            if ($tipoDte != '03') {
                $resumen['totalIva'] = round($totalIVA, 2);
            }
        }

        // Construir documento según tipo de DTE
        if ($tipoDte == '14') {
            // Factura de Sujeto Excluido - usar "sujetoExcluido" en lugar de "receptor"
            $factura = [
                'identificacion' => $identificacion,
                'emisor' => $emisor,
                'sujetoExcluido' => $receptor,
                'cuerpoDocumento' => $cuerpo,
                'resumen' => $resumen,
                "apendice" => count($apendice) > 0 ? $apendice : null
            ];
        } else {
            // Crédito Fiscal y Factura - usar "receptor"
            $factura = [
                'identificacion' => $identificacion,
                'emisor' => $emisor,
                'receptor' => $receptor,
                'cuerpoDocumento' => $cuerpo,
                'resumen' => $resumen,
                "documentoRelacionado" => null,
                "otrosDocumentos" => null,
                "ventaTercero" => null,
                "extension" => null,
                "apendice" => count($apendice) > 0 ? $apendice : null
            ];
        }

        Log::info('Factura construida. Items en cuerpoDocumento: ' . count($cuerpo));
        Log::info('JSON Factura completa: ' . json_encode($factura, JSON_PRETTY_PRINT));
        
        $jsonString = json_encode($factura);
        $mh_factura = factura::create([
            "cliente_id" => isset($cliente->id) ? $cliente->id : null,
            "estado" => estadoFactura::CREADA,
            "numero_control" => $numero_control,
            "codigo_generacion" => $codigo_generacion,
            "json" => $jsonString
        ]);

        Log::info('Iniciando enviar json custom - Tipo DTE: ' . $tipoDte);
        $jsonResponse = $this->enviarJson($factura, (int) $secuencia);
        Log::info('Finalizando enviar json custom');

        if ($jsonResponse) {
            $mh_factura->estado = estadoFactura::CERTIFICADA;
            $mh_factura->respuesta_mh = json_encode($jsonResponse);
            if (isset($jsonResponse['selloRecibido'])) {
                $mh_factura->sello_recepcion = $jsonResponse['selloRecibido'];
            }
        } else {
            $mh_factura->estado = estadoFactura::RECHAZADA;
        }
        $mh_factura->save();

        // Enviar correo si fue certificada
        if ($mh_factura->estado == estadoFactura::CERTIFICADA) {
            $this->enviarCorreoFactura($mh_factura);
        }

        return $mh_factura;
    }

    public function reenviarFactura($factura_id)
    {
        $mh_factura = factura::findOrFail($factura_id);
        Log::error('reenvio json');
        $jsonResponse = $this->enviarJson($mh_factura->json, $factura_id);
        if ($jsonResponse) {
            $mh_factura->estado = estadoFactura::CERTIFICADA;
            $mh_factura->respuesta_mh = json_encode($jsonResponse);
            // Guardar sello de recepción para anulaciones futuras
            if (isset($jsonResponse['selloRecibido'])) {
                $mh_factura->sello_recepcion = $jsonResponse['selloRecibido'];
            }
        } else {
            $mh_factura->estado = estadoFactura::RECHAZADA;
        }
        $mh_factura->save();
        
        // Enviar correo si la factura fue certificada
        if ($mh_factura->estado == estadoFactura::CERTIFICADA) {
            $this->enviarCorreoFactura($mh_factura);
        }
        
        return $mh_factura;
    }

    public function anularFactura($factura_id, $motivo = 'Reversion de pago')
    {
        Log::info('Iniciando anulación de factura ID: ' . $factura_id);
        $factura = factura::findOrFail($factura_id);
        
        // Verificar que la factura esté certificada o ya enviada al cliente
        if ($factura->estado != estadoFactura::CERTIFICADA && $factura->estado != estadoFactura::CLIENTE) {
            Log::error('Intento de anular factura en estado inválido: ' . $factura->estado);
            throw new Exception('Solo se pueden anular facturas certificadas o enviadas al cliente. Estado actual: ' . $factura->estado);
        }
        
        Log::info('Factura en estado válido para anulación: ' . $factura->estado);

        // Decodificar JSON original para obtener datos necesarios
        $jsonOriginal = json_decode($factura->json);
        $montoIva = isset($jsonOriginal->resumen->totalIva) ? $jsonOriginal->resumen->totalIva : 0;
        
        // Generar documento de anulación
        $uuid = Uuid::uuid4()->toString();
        $now = Carbon::now();
        
        $dteAnulacion = [
            'identificacion' => [
                'version' => 2,
                'ambiente' => $this->dte['ambiente'],
                'codigoGeneracion' => strtoupper($uuid),
                'fecAnula' => $now->format('Y-m-d'),
                'horAnula' => $now->format('H:i:s')
            ],
            'emisor' => [
                'nit' => str_replace('-', '', $this->dte['nit']),
                'nombre' => $this->dte['nombre'],
                'tipoEstablecimiento' => '01',
                'nomEstablecimiento' => $this->dte['nombre_comercial'],
                'codEstableMH' => '0001',
                'codEstable' => '0001',
                'codPuntoVentaMH' => '0001',
                'codPuntoVenta' => '0001',
                'telefono' => $this->dte['telefono'],
                'correo' => $this->dte['correo']
            ],
            'documento' => [
                'tipoDte' => '01',
                'codigoGeneracion' => $factura->codigo_generacion,
                'selloRecibido' => $factura->sello_recepcion,
                'numeroControl' => $factura->numero_control,
                'fecEmi' => $factura->created_at->format('Y-m-d'),
                'montoIva' => round($montoIva, 2),
                'codigoGeneracionR' => null,
                'tipoDocumento' => isset($jsonOriginal->receptor->tipoDocumento) ? $jsonOriginal->receptor->tipoDocumento : null,
                'numDocumento' => isset($jsonOriginal->receptor->numDocumento) ? $jsonOriginal->receptor->numDocumento : null,
                'nombre' => isset($jsonOriginal->receptor->nombre) ? $jsonOriginal->receptor->nombre : null,
                'telefono' => isset($jsonOriginal->receptor->telefono) ? $jsonOriginal->receptor->telefono : null,
                'correo' => isset($jsonOriginal->receptor->correo) ? $jsonOriginal->receptor->correo : null
            ],
            'motivo' => [
                'tipoAnulacion' => 1,
                'motivoAnulacion' => substr($motivo, 0, 250),
                'nombreResponsable' => $this->dte['nombre'],
                'tipDocResponsable' => '36',
                'numDocResponsable' => str_replace('-', '', $this->dte['nit']),
                'nombreSolicita' => $this->dte['nombre'],
                'tipDocSolicita' => '36',
                'numDocSolicita' => str_replace('-', '', $this->dte['nit'])
            ]
        ];

        // Guardar JSON de anulación
        $jsonString = json_encode($dteAnulacion);
        $rutaJson = storage_path('signed_dtes/anulacion-' . $now->format('YmdHis') . '.json');
        file_put_contents($rutaJson, $jsonString);
        Log::info('JSON de anulación guardado en: ' . $rutaJson);

        // Firmar documento de anulación
        Log::info('Iniciando firmado de documento de anulación');
        $dteJsonFirmado = $this->firmarJson($rutaJson, $this->dte['passwordPri']);
        Log::info('Documento de anulación firmado exitosamente');

        // Enviar anulación al MH
        Log::info('Obteniendo token de autenticación');
        $token = $this->auth();
        $client = new \GuzzleHttp\Client();
        Log::info('Enviando anulación al MH');
        
        try {
            // Para anulaciones, usar estructura similar a DTEs normales
            $response = $client->post($this->dte['url_envio'], [
                'headers' => [
                    'Authorization' => $token,
                ],
                'json' => [
                    'ambiente' => '00',
                    'idEnvio' => $secuencia,
                    'version' => 2,
                    'tipoDte' => '07',
                    'documento' => $dteJsonFirmado,
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            Log::info('Respuesta de anulación DTE: ' . json_encode($result));

            if (isset($result['estado']) && $result['estado'] === 'PROCESADO') {
                $factura->estado = estadoFactura::ANULADA;
                $factura->respuesta_anulacion = json_encode($result);
                $factura->save();
                Log::info('Factura anulada exitosamente. ID: ' . $factura->id);
                return true;
            } else {
                $estado = isset($result['estado']) ? $result['estado'] : 'sin estado';
                Log::warning('Anulación no procesada. Estado: ' . $estado);
            }

            return false;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse() ? (string) $e->getResponse()->getBody() : 'No response body';
            Log::error('Error al anular DTE: ' . $e->getMessage());
            Log::error('Respuesta completa del MH: ' . $responseBody);
            Log::error('DTE Anulación enviado: ' . json_encode($dteAnulacion));
            return false;
        } catch (\Exception $e) {
            Log::error('Error al anular DTE: ' . $e->getMessage());
            return false;
        }
    }
}
