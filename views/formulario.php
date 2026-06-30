<?php
// Requerimos los archivos de configuracion y modelos para cargar los select e intereses
require_once '../config/Database.php';
require_once '../models/Pais.php';
require_once '../models/AreaInteres.php';

// Inicializamos la conexion a la base de datos
$database = new Database();
$db = $database->getConnection();

// Instanciamos los modelos para jalar la informacion de los catalogos
$paisModel = new Pais($db);
$areaModel = new AreaInteres($db);

// Obtenemos los registros desde la base de datos
$listaPaises = $paisModel->obtenerTodos();
$listaAreas = $areaModel->obtenerTodas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Inscripcion - iTECH</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="container">
    <h1>UNIVERSIDAD TECNOLOGICA DE PANAMA</h1>
    <h2>Formulario de Registro - iTECH Eventos</h2>
    <h3>Estudiante: Andrea Torrente | Fecha de Examen: 30/06/2026</h3>

    <form action="../controllers/InscriptorController.php" method="POST">
        
        <div class="form-group">
            <label for="identidad">Documento de Identificacion (Cedula o Pasaporte):</label>
            <input type="text" id="identidad" name="identidad" required placeholder="Ej: 8-999-9999">
        </div>

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Ej: Andrea">
        </div>

        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required placeholder="Ej: Torrente">
        </div>

        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required min="1" max="120" placeholder="Ej: 20">
        </div>

        <div class="form-group">
            <label>Sexo:</label>
            <div class="radio-group">
                <div class="radio-item">
                    <input type="radio" id="masculino" name="sexo" value="Masculino" required>
                    <label for="masculino">Masculino</label>
                </div>
                <div class="radio-item">
                    <input type="radio" id="femenino" name="sexo" value="Femenino" required>
                    <label for="femenino">Femenino</label>
                </div>
                <div class="radio-item">
                    <input type="radio" id="otro" name="sexo" value="Otro" required>
                    <label for="otro">Otro</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="pais_residencia_id">Pais de Residencia:</label>
            <select id="pais_residencia_id" name="pais_residencia_id" required>
                <option value="">-- Seleccione un Pais --</option>
                <?php foreach($listaPaises as $pais): ?>
                    <option value="<?php echo $pais['id']; ?>"><?php echo htmlspecialchars($pais['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nacionalidad_id">Nacionalidad:</label>
            <select id="nacionalidad_id" name="nacionalidad_id" required>
                <option value="">-- Seleccione su Nacionalidad --</option>
                <?php foreach($listaPaises as $pais): ?>
                    <option value="<?php echo $pais['id']; ?>"><?php echo htmlspecialchars($pais['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="correo">Correo Electronico:</label>
            <input type="email" id="correo" name="correo" required placeholder="nombre@ejemplo.com">
        </div>

        <div class="form-group">
            <label for="celular">Numero Celular:</label>
            <input type="text" id="celular" name="celular" required placeholder="Ej: 66667777">
        </div>

        <div class="form-group">
            <label>Temas Tecnologicos de interes:</label>
            <div class="checkbox-group">
                <?php foreach($listaAreas as $area): ?>
                    <div class="checkbox-item">
                        <input type="checkbox" id="area_<?php echo $area['id']; ?>" name="areas_interes[]" value="<?php echo $area['id']; ?>">
                        <label for="area_<?php echo $area['id']; ?>"><?php echo htmlspecialchars($area['nombre']); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="observaciones">Observaciones o Consulta sobre el evento:</label>
            <textarea id="observaciones" name="observaciones" rows="4" placeholder="Escriba aqui sus comentarios..."></textarea>
        </div>

        <button type="submit">Registrar Participante</button>
    </form>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> iTECH. All rights reserved.</p>
        <p>Contacto: soporte@itech.utp.ac.pa | Universidad Tecnologica de Panama</p>
    </footer>
</div>

</body>
</html>