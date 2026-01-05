<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Services\CertificadoExtractor;
use App\Services\DTEFirmador;

class DteFirmarCommand extends Command {

    /**
     * Nombre del comando Artisan
     */
    protected $name = 'dte:firmar';

    /**
     * Descripción del comando
     */
    protected $description = 'Firma un DTE JSON usando certificados del Ministerio de Hacienda y genera un JWT.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Método principal (Laravel 5.0 / 5.1 usa fire(), NO handle())
     */
    public function fire()
    {
        $this->info(">>> COMANDO DTE:FIRMA EJECUTADO <<<");
        $jsonPath = $this->argument('json');
        $xmlPath  = $this->option('xml');
        $pass     = $this->option('privpass');

        try {

            $this->info("-------------------------------------------------");
            $this->info(" Firmado de DTE usando Certificado del MH");
            $this->info("-------------------------------------------------");
            $this->info("Archivo JSON:   $jsonPath");

            if (!file_exists($jsonPath)) {
                $this->error("ERROR: No se encontró el archivo JSON.");
                return;
            }

            // =====================================================================
            // 1. EXTRAER CERTIFICADOS DESDE EL XML (SI SE PROPORCIONA)
            // =====================================================================
            if ($xmlPath) {
                $this->info("XML de certificado: $xmlPath");

                if (!file_exists($xmlPath)) {
                    $this->error("ERROR: El archivo XML no existe.");
                    return;
                }

                $this->info("Extrayendo llaves desde XML...");

                $extractor = new CertificadoExtractor();
                $res = $extractor->extractFromXmlFile($xmlPath, storage_path('certs'));

                $this->info("Certificado público → " . ($res['public'] ?: 'NO GENERADO'));
                $this->info("Llave privada       → " . ($res['private'] ?: 'NO GENERADA'));
                $this->info("");
            }

            // =====================================================================
            // 2. VALIDAR QUE EXISTE LA LLAVE PRIVADA Y PÚBLICA
            // =====================================================================
            $privKey = storage_path('certs/llave_privada.pem');
            $pubKey  = storage_path('certs/certificado_publico.pem');

            if (!file_exists($privKey)) {
                $this->error("ERROR: No existe llave privada en: $privKey");
                $this->error("Colócala allí o pasa un XML con --xml=");
                return;
            }

            if (!file_exists($pubKey)) {
                $this->warn("ADVERTENCIA: No existe certificado público en: $pubKey");
            }

            // =====================================================================
            // 3. FIRMAR EL JSON COMO JWT (RS256)
            // =====================================================================
            $this->info("Firmando el DTE...");

            $firmador = new DTEFirmador();
            $this->info(">> LLEGÓ A PUNTO 3: FIRMA");
            $jwt = $firmador->firmarJsonAsJwt($jsonPath, $privKey, $pass);

            $this->info("✔ Firma generada correctamente.");
            $this->info("");

            // =====================================================================
            // 4. GUARDAR RESULTADO
            // =====================================================================
            $outDir = storage_path('signed_dtes');
            @mkdir($outDir, 0755, true);

            $outFile = $outDir . '/' . basename($jsonPath) . '.jwt.txt';
            file_put_contents($outFile, $jwt);

            $this->info("Archivo guardado en:");
            $this->info($outFile);
            $this->info("");

            $this->info("🎉 Proceso completado con éxito.");

        } catch (\Exception $e) {
            $this->error("ERROR durante el firmado:");
            $this->error($e->getMessage());
        }
    }

    /**
     * Argumentos requeridos
     */
    protected function getArguments()
    {
        return [
            ['json', InputArgument::REQUIRED, 'Ruta al archivo JSON del DTE'],
        ];
    }

    /**
     * Opciones permitidas
     */
    protected function getOptions()
    {
        return [
            ['xml', null, InputOption::VALUE_OPTIONAL, 'Ruta al XML del certificado MH', null],
            ['privpass', null, InputOption::VALUE_OPTIONAL, 'Contraseña de la llave privada', null],
        ];
    }

    public function handle()
{
    return $this->fire();
}
}
