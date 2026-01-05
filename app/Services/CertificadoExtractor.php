<?php
namespace App\Services;

class CertificadoExtractor
{
    /**
     * Extrae del XML estilo <CertificadoMH> los campos encodied de certificado y privateKey
     * y genera archivos PEM en storage_path('certs').
     *
     * @param string $xmlPath Ruta al archivo XML (el .crt que te dio MH)
     * @param string $outDir  Directorio donde dejará certificado_publico.pem y llave_privada.pem
     * @return array ['public' => path, 'private' => path|null]
     * @throws \Exception
     */
    public function extractFromXmlFile($xmlPath, $outDir = null)
    {
        if (!$outDir) {
            $outDir = storage_path('certs');
        }

        if (!file_exists($xmlPath)) {
            throw new \Exception("Archivo XML no encontrado: {$xmlPath}");
        }

        if (!is_dir($outDir)) {
            @mkdir($outDir, 0755, true);
        }

        $xmlString = file_get_contents($xmlPath);
        if ($xmlString === false) {
            throw new \Exception("No se pudo leer el archivo XML");
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlString);
        if ($xml === false) {
            throw new \Exception("El archivo no es XML válido o tiene encoding distinto.");
        }

        // Intentamos ubicar publicKey/encodied o certificado/basicEstructure/encodied
        $publicB64 = null;
        $privateB64 = null;

        // 1) public key ubicado como certificado/basicEstructure/encodied
        if (isset($xml->certificado->basicEstructure->encodied)) {
            $publicB64 = (string) $xml->certificado->basicEstructure->encodied;
        }

        // 2) si existe subjectPublicKey (como en tu ejemplo)
        if (!$publicB64 && isset($xml->certificado->basicEstructure->subjectPublicKeyInfo->subjectPublicKey)) {
            $publicB64 = (string)$xml->certificado->basicEstructure->subjectPublicKeyInfo->subjectPublicKey;
        }

        // 3) publicKey/encodied
        if (!$publicB64 && isset($xml->publicKey->encodied)) {
            $publicB64 = (string) $xml->publicKey->encodied;
        }

        // private key
        if (isset($xml->privateKey->encodied)) {
            $privateB64 = (string) $xml->privateKey->encodied;
        } elseif (isset($xml->clavePri)) {
            $privateB64 = (string) $xml->clavePri;
        }

        $result = ['public' => null, 'private' => null];

        if ($publicB64) {
            // limpiar posibles saltos de línea y espacios
            $publicB64 = preg_replace("/\s+/", "", $publicB64);
            // Construir PEM de clave pública (SubjectPublicKeyInfo)
            $pemPublic = "-----BEGIN PUBLIC KEY-----\n" .
                chunk_split($publicB64, 64, "\n") .
                "-----END PUBLIC KEY-----\n";
            $pubPath = rtrim($outDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'certificado_publico.pem';
            file_put_contents($pubPath, $pemPublic);
            $result['public'] = $pubPath;
        }

        if ($privateB64) {
            $privateB64 = preg_replace("/\s+/", "", $privateB64);
            
            $pemPrivate = "-----BEGIN PRIVATE KEY-----\n" .
                chunk_split($privateB64, 64, "\n") .
                "-----END PRIVATE KEY-----\n";
            $privPath = rtrim($outDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'llave_privada.pem';
            file_put_contents($privPath, $pemPrivate);
            $result['private'] = $privPath;
        }

        return $result;
    }
}
