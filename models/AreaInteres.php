<?php
class AreaInteres {
    private $conn;
    private $table_name = "areas_interes";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtiene todas las areas tecnologicas disponibles
    public function obtenerTodas() {
        $query = "SELECT id, nombre FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>