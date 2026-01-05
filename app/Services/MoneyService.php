<?php

namespace App\Services;



class MoneyService
{
    protected $mpdf;

    public function __construct(array $config = [])
    {
    }

public function convertirMontoADolares($monto) {
        $num =$monto;
        $letras = "";

        // Separar la parte entera (dólares) y la parte decimal (centavos)
        $parteEntera = floor($num);
        $parteDecimal = round(($num - $parteEntera) * 100);

        // Definir arrays de palabras para las unidades, decenas, centenas, etc.
        $unidades = [
            "", "uno", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve",
            "diez", "once", "doce", "trece", "catorce", "quince", "dieciséis", "diecisiete", 
            "dieciocho", "diecinueve", "veinte"
        ];
        $decenas = [
            "", "", "veinti", "treinta", "cuarenta", "cincuenta", "sesenta", "setenta", 
            "ochenta", "noventa"
        ];
        $centenas = [
            "", "ciento", "doscientos", "trescientos", "cuatrocientos", "quinientos", 
            "seiscientos", "setecientos", "ochocientos", "novecientos"
        ];

        // Función interna para convertir números menores a 1000
        $convertirMenosDeMil = function($num) use ($unidades, $decenas, $centenas) {
            $resultado = "";

            // Centenas
            if ($num >= 100) {
                $centena = floor($num / 100);
                $resultado .= ($centena == 1 && $num % 100 == 0) ? "cien" : $centenas[$centena];
                $num %= 100;
            }

            // Decenas
            if ($num >= 20) {
                $decena = floor($num / 10);
                $resultado .= ($resultado ? " " : "") . $decenas[$decena];
                $num %= 10;
                if ($num > 0) {
                    $resultado .= " y " . $unidades[$num];
                }
            } elseif ($num > 0) {
                $resultado .= ($resultado ? " " : "") . $unidades[$num];
            }

            return trim($resultado);
        };

        // Miles
        if ($parteEntera >= 1000) {
            $miles = floor($parteEntera / 1000);
            $letras .= $miles == 1 ? "mil" : $convertirMenosDeMil($miles) . " mil";
            $parteEntera %= 1000;
            
        }

        // Resto (menor a mil)
        if ($parteEntera > 0) {
            $letras .= ($letras ? " " : "") . $convertirMenosDeMil($parteEntera);
        }

        $letras .= " dolares";

        // Convertir la parte decimal (centavos)
        if ($parteDecimal > 0) {
            $letras .= " con " . $convertirMenosDeMil($parteDecimal) . " centavos";
        } else {
            $letras .= " exactos";
        }

        return ucfirst($letras);
    }
}
