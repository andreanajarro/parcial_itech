<?php
// Requerimos la configuracion de la base de datos y el modelo principal
require_once '../config/Database.php';
require_once '../models/Inscriptor.php';

// Conectamos a la base de datos
$database = new Database();
$db = $database->getConnection();

// Instanciamos el modelo para obtener los registros
$inscriptorModel = new Inscriptor($db);
$registros = $inscriptorModel->obtenerReporte();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inscriptores - iTECH</title>
    <style>
        /* Estilos rapidos  */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f7f6;
            padding: 30px;
            margin: 0;
        }
        .report-container {
            max-width: 1100px;
            background: #fff;
            margin: 0 auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            margin-top: 0;
        }
        .actions-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            background-color: #27ae60;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn:hover {
            background-color: #219150;
        }
        .btn-form {
            background-color: #3498db;
        }
        .btn-form:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #e0e0e0;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        /* Distintivos de auditoria (Punto 20) */
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
        }
        .badge-verde {
            background-color: #2ecc71;
            color: white;
        }
        .badge-rojo {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>

<div class="report-container">
    <h2>Sistema de Auditoria e Inscripciones - iTECH</h2>
    
    <div class="actions-bar">
        <a href="formulario.php" class="btn btn-form">+ Nuevo Registro</a>
        <a href="exportar_excel.php" class="btn">Descargar Excel </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Identificacion</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Celular</th>
                <th>Sexo</th>
                <th>Residencia / Nacionalidad</th>
                <th>Areas de Interes </th>
                <th>Auditoria de Integridad </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($registros)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: #7f8c8d;">No hay participantes registrados actualmente.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($registros as $row): 
                    // RE-VERIFICACION CRIPTOGRAFICA DE INTEGRIDAD (Punto 20)
                    // Volvemos a armar la cadena exacta con los datos guardados en la BD
                    $cadenaVerificar = $row['nombre'] . $row['apellido'] . $row['correo'] . $row['celular'] . $row['sexo'];
                    
                    // Calculamos el hash con la misma clave secreta del controlador
                    $hashCalculado = hash_hmac('sha256', $cadenaVerificar, 'clave_secreta_itech_2026');
                    
                    // Comparamos el hash de la BD contra el que acabamos de calcular
                    if ($hashCalculado === $row['hash_verificacion']) {
                        $claseBadge = "badge-verde";
                        $textoBadge = "Integridad Completa";
                    } else {
                        $claseBadge = "badge-rojo";
                        $textoBadge = "Registro Corrompido/Vulnerado";
                    }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($row['correo']); ?></td>
                    <td><?php echo htmlspecialchars($row['celular']); ?></td>
                    <td><?php echo htmlspecialchars($row['sexo']); ?></td>
                    <td><?php echo htmlspecialchars($row['pais_residencia'] . ' / ' . $row['nacionalidad']); ?></td>
                    
                    <td><?php echo htmlspecialchars($row['temas_interes'] ?? 'Ninguno'); ?></td>
                    
                    <td>
                        <span class="badge <?php echo $claseBadge; ?>">
                            <?php echo $textoBadge; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>