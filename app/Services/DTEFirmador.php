<?php
namespace App\Services;

class DTEFirmador
{
    /**
     * Base64URL encoder (sin padding)
     */
    function base64url($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Firma un JSON DTE y retorna JWT válido para Hacienda MH
     *
     * @param string $jsonPath
     * @param string $privateKeyPemPath
     * @param string|null $passphrase
     * @return string
     * @throws \Exception
     */
    public function firmarJsonAsJwt($jsonPath, $privateKeyPemPath, $passphrase = null)
    {
        // ================================
        // 1. LEER ARCHIVO
        // ================================
        $raw = file_get_contents($jsonPath);
        if ($raw === false) {
            throw new \Exception("No se pudo leer el archivo JSON");
        }

        // Quitar BOM UTF-8
        if (substr($raw, 0, 3) === "\xEF\xBB\xBF") {
            $raw = substr($raw, 3);
        }

        // ================================
        // 2. NORMALIZAR JSON (CLAVE)
        // ================================
        // Primer decode
        $data = json_decode($raw, true);

        // Si sigue siendo string → venía doble serializado (DB)
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data)) {
            throw new \Exception("El payload no es JSON válido");
        }

        // ESTE es el JSON que MH espera
        $payload = json_encode($data);
        $outDir = storage_path('signed_dtes');
            @mkdir($outDir, 0755, true);

            $outFile = $outDir . '/antesdefirma-' . basename($jsonPath) . '.json';
        file_put_contents($outFile, $payload);

        // ================================
        // 3. HEADER JWT
        // ================================
        $header = json_encode(array(
            'alg' => 'RS256',
            'typ' => 'JWT'
        ));

        // ================================
        // 4. BASE64URL
        // ================================
        $encodedHeader  = $this->base64url($header);
        $encodedPayload = $this->base64url($payload);

        $dataToSign = $encodedHeader . '.' . $encodedPayload;

        // ================================
        // 5. CARGAR LLAVE PRIVADA
        // ================================
        $keyContent = file_get_contents($privateKeyPemPath);
        if ($keyContent === false) {
            throw new \Exception("No se pudo leer la llave privada");
        }

        $privateKey = $passphrase
            ? openssl_pkey_get_private($keyContent, $passphrase)
            : openssl_pkey_get_private($keyContent);     

        if ($privateKey === false) {
            throw new \Exception("No se pudo cargar la llave privada");
        }

        // ================================
        // 6. FIRMAR
        // ================================
        $ok = openssl_sign(
            $dataToSign,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        openssl_free_key($privateKey);

        if (!$ok) {
            throw new \Exception("Error al firmar con OpenSSL");
        }

        // ================================
        // 7. JWT FINAL
        // ================================
        

        $outFile = $outDir . '/dataTosign_' . basename($jsonPath) . '.txt';
        file_put_contents($outFile, $dataToSign);
        $jwtFinal = $dataToSign . '.' . $this->base64url($signature);
        $outFile = $outDir . '/jwtFinal_' . basename($jsonPath) . '.txt';
        file_put_contents($outFile, $jwtFinal);
        return $jwtFinal;
    }
}
