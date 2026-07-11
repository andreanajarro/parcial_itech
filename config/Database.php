<?php
class Database {
    // Definicion de las credenciales de acuerdo al entorno local (WampServer)
    private $host = "127.1.1.1"; // La IP local que especifica 
    private $db_name = "parcial_itech"; // El nombre de la base de datos 
    private $username = "root";
    private $password = ""; // Por defecto password esta vacio
    public $conn;

    // Funcion para obtener la instancia de conexion
    public function getConnection() {
        $this->conn = null;
        
        try {
            // Creamos la conexion usando el driver PDO de MySQL
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            
            // Configuramos PDO para que lance excepciones en caso de errores de SQL
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Aseguramos que la comunicacion use UTF-8 para evitar problemas con tildes 
            $this->conn->exec("set names utf8mb4");
            
        } catch(PDOException $exception) {
            // Si hay un error en la conexion, detiene la ejecucion y muestra el mensaje
            die("Error crítico de conexión: " . $exception->getMessage());
        }
        
        return $this->conn;
    }
}
?>