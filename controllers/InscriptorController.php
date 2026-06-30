<?php
// Requerimos los archivos necesarios para el funcionamiento de la logica
require_once '../config/Database.php';
require_once '../models/Inscriptor.php';
require_once '../utils/Sanitizer.php';
require_once '../utils/Validator.php';

class InscriptorController {
    
    public function procesarFormulario() {
        // Verificar que la peticion sea estrictamente por el metodo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. CAPTURA Y SANITIZACION DE DATOS 
            // Usamos formatTitleCase para asegurar mayusculas iniciales tipo titulo en Nombre y Apellido
            $nombre              = Sanitizer::formatTitleCase($_POST['nombre'] ?? '');
            $apellido            = Sanitizer::formatTitleCase($_POST['apellido'] ?? '');
            
            // Limpieza estandar para el resto de los campos de texto
            $identidad           = Sanitizer::cleanString($_POST['identidad'] ?? '');
            $edad                = Sanitizer::cleanString($_POST['edad'] ?? '');
            $sexo                = Sanitizer::cleanString($_POST['sexo'] ?? '');
            $pais_residencia_id  = Sanitizer::cleanString($_POST['pais_residencia_id'] ?? '');
            $nacionalidad_id     = Sanitizer::cleanString($_POST['nacionalidad_id'] ?? '');
            $celular             = Sanitizer::cleanString($_POST['celular'] ?? '');
            $observaciones       = Sanitizer::cleanString($_POST['observaciones'] ?? '');
            
            // Sanitizacion especifica para el correo
            $correo              = Sanitizer::cleanEmail($_POST['correo'] ?? '');
            
            // Los checkboxes se reciben en un array. Si no se selecciona ninguno, definimos un array vacío.
            $areas_interes       = isset($_POST['areas_interes']) ? $_POST['areas_interes'] : [];

            // 2. VALIDACION LOGICA DEL LADO DEL SERVIDOR (Punto 21)
            // Comprobamos que ningun campo obligatorio venga vacio
            if (!Validator::validateRequired($nombre) || !Validator::validateRequired($apellido) || 
                !Validator::validateRequired($identidad) || !Validator::validateRequired($sexo) ||
                !Validator::validateRequired($pais_residencia_id) || !Validator::validateRequired($nacionalidad_id)) {
                die("Error: Hay campos obligatorios que se encuentran vacíos.");
            }

            // Validamos que el formato del correo, telefono y rango de edad sean correctos
            if (!Validator::validateEmail($correo)) {
                die("Error: El formato del correo electrónico no es válido.");
            }
            if (!Validator::validateAge($edad)) {
                die("Error: La edad debe ser un número entero válido.");
            }
            if (!Validator::validatePhone($celular)) {
                die("Error: El formato de número celular no es válido.");
            }

            // 3. AUDITORIA DE INTEGRIDAD Y FIRMA DE DATOS (Punto 20)
            // Concatenamos las variables criticas solicitadas por la rubrica (Nombre, Identificacion, Correo, Teléfono, Sexo)
            $cadena_auditoria = $nombre . $identidad . $correo . $celular . $sexo;
            
            // Generamos una firma criptografica unica usando un algoritmo HMAC SHA256 con una clave secreta del sistema
            $hash_verificacion = hash_hmac('sha256', $cadena_auditoria, 'clave_secreta_itech_2026');

            // 4. INSTANCIACION DE MODELOS E INSERCION EN BASE DE DATOS
            $database = new Database();
            $db = $database->getConnection();
            $inscriptorModel = new Inscriptor($db);

            // Estructuramos el array que espera el metodo registrar de nuestro modelo
            $datosRegistro = [
                'nombre'             => $nombre,
                'apellido'           => $apellido,
                'edad'               => $edad,
                'sexo'               => $sexo,
                'pais_residencia_id' => $pais_residencia_id,
                'nacionalidad_id'    => $nacionalidad_id,
                'correo'             => $correo,
                'celular'            => $celular,
                'observaciones'      => $observaciones,
                'hash_verificacion'  => $hash_verificacion
            ];

            // Insertamos el registro principal
            $inscriptor_id = $inscriptorModel->registrar($datosRegistro);
            
            // Si el inscriptor se guardo con exito y selecciono tecnologias, las guardamos en la tabla intermedia
            if ($inscriptor_id && !empty($areas_interes)) {
                $inscriptorModel->registrarTemas($inscriptor_id, $areas_interes);
            }

            // Una vez procesado todo, redirigimos a la pantalla del reporte
            header("Location: ../views/reporte.php");
            exit();
        }
    }
}

// Bloque de ejecucion automatica al recibir el POST del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new InscriptorController();
    $controller->procesarFormulario();
}
?>