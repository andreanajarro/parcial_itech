<?php
class Pais {
    private $conn;
    private $table_name = "paises";

    // Al instanciar el modelo, le pasamos la conexion a la BD
    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtiene todos los paises ordenados alfabeticamente
    public function obtenerTodos() {
        $query = "SELECT id, nombre FROM " . $this->table_name . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>