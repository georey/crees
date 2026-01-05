<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Probar departamento Sonsonate (ID 5, codigo 03)
$dept = App\Models\catalogos\mh_departamento::find(5);
if ($dept) {
    echo "Departamento: {$dept->nombre} (id={$dept->id}, codigo={$dept->codigo})\n";
    $munis = $dept->municipios;
    echo "Municipios encontrados: {$munis->count()}\n";
    foreach ($munis as $m) {
        echo "  - {$m->nombre} (codigo={$m->codigo}, dept_codigo={$m->departamento_codigo})\n";
    }
} else {
    echo "Departamento no encontrado\n";
}

echo "\n--- Verificando query SQL ---\n";
$query = App\Models\catalogos\mh_municipio::where('departamento_codigo', '03');
echo "SQL: " . $query->toSql() . "\n";
echo "Resultados: " . $query->count() . "\n";
