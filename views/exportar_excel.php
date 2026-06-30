<?php
// Configuramos las cabeceras del navegador para forzar la descarga de un archivo Excel (.xls)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_Inscriptores_iTECH.xls");
header("Pragma: no-cache");
header("Expires: 0");

require_once '../config/Database.php';
require_once '../models/Inscriptor.php';

$database = new Database();
$db = $database->getConnection();
$inscriptorModel = new Inscriptor($db);
$registros = $inscriptorModel->obtenerReporte();

// Imprimimos la fila de encabezados separada por tabulaciones (\t)
echo "Identificacion\tNombre Completo\tCorreo\tCelular\tSexo\tPais Residencia\tNacionalidad\tTemas de Interes\n";

// Recorremos los datos para rellenar las celdas del Excel
foreach ($registros as $row) {
    echo $row['id'] . "\t" .
         $row['nombre'] . " " . $row['apellido'] . "\t" .
         $row['correo'] . "\t" .
         $row['celular'] . "\t" .
         $row['sexo'] . "\t" .
         $row['pais_residencia'] . "\t" .
         $row['nacionalidad'] . "\t" .
         ($row['temas_interes'] ?? 'Ninguno') . "\n";
}
exit();
?>