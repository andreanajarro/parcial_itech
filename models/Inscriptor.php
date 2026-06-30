<?php
class Inscriptor {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Guarda los datos principales en la tabla `inscriptores`
    public function registrar($datos) {
        $query = "INSERT INTO inscriptores 
                  (nombre, apellido, edad, sexo, pais_residencia_id, nacionalidad_id, correo, celular, observaciones, hash_verificacion) 
                  VALUES (:nombre, :apellido, :edad, :sexo, :pais_residencia_id, :nacionalidad_id, :correo, :celular, :observaciones, :hash_verificacion)";
        
        $stmt = $this->conn->prepare($query);

        // Vinculamos de manera segura con PDO (Previene SQL Injection)
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellido', $datos['apellido']);
        $stmt->bindParam(':edad', $datos['edad']);
        $stmt->bindParam(':sexo', $datos['sexo']);
        $stmt->bindParam(':pais_residencia_id', $datos['pais_residencia_id']);
        $stmt->bindParam(':nacionalidad_id', $datos['nacionalidad_id']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':celular', $datos['celular']);
        $stmt->bindParam(':observaciones', $datos['observaciones']);
        $stmt->bindParam(':hash_verificacion', $datos['hash_verificacion']);

        if($stmt->execute()) {
            return $this->conn->lastInsertId(); // Retorna el ID creado para usarlo en la tabla intermedia
        }
        return false;
    }

    // Inserta en la tabla intermedia `inscriptor_temas` la relacion de los checkboxes seleccionados
    public function registrarTemas($inscriptor_id, $areas_interes) {
        foreach($areas_interes as $area_id) {
            $query = "INSERT INTO inscriptor_temas (inscriptor_id, area_interes_id) VALUES (:in_id, :area_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':in_id', $inscriptor_id);
            $stmt->bindParam(':area_id', $area_id);
            $stmt->execute();
        }
    }

    // Consulta avanzada para el reporte uniendo tablas y agrupando temas por comas 
    public function obtenerReporte() {
        $query = "SELECT i.*, 
                         p1.nombre as pais_residencia, 
                         p2.nombre as nacionalidad,
                         GROUP_CONCAT(a.nombre SEPARATOR ', ') as temas_interes
                  FROM inscriptores i
                  INNER JOIN paises p1 ON i.pais_residencia_id = p1.id
                  INNER JOIN paises p2 ON i.nacionalidad_id = p2.id
                  LEFT JOIN inscriptor_temas it ON i.id = it.inscriptor_id
                  LEFT JOIN areas_interes a ON it.area_interes_id = a.id
                  GROUP BY i.id 
                  ORDER BY i.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>