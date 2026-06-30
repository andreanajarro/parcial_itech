<?php
class Validator {
    // Verifica si un campo obligatorio no esta vacio
    public static function validateRequired($data) {
        return !empty($data) && trim($data) !== '';
    }

    // Verifica si la edad es un numero entero valido en un rango real
    public static function validateAge($age) {
        return filter_var($age, FILTER_VALIDATE_INT) && $age > 0 && $age < 120;
    }

    // Verifica el formato del correo
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Verifica que el celular contenga solo numeros y una longitud prudente
    public static function validatePhone($phone) {
        return preg_match('/^[0-9]{7,15}$/', $phone);
    }
}
?>