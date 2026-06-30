<?php
class Sanitizer {
    // Limpia espacios y caracteres peligrosos
    public static function cleanString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    // Convierte el texto a "Tipo Titulo" 
    public static function formatTitleCase($data) {
        $cleaned = self::cleanString($data);
        return mb_convert_case($cleaned, MB_CASE_TITLE, "UTF-8");
    }

    // Sanitiza especificamente correos electronicos
    public static function cleanEmail($data) {
        return filter_var(trim($data), FILTER_SANITIZE_EMAIL);
    }
}
?>