<?php
class Beneficio {
    private $conn;
    private $table = "beneficios";

    public $id_beneficio;
    public $nombre_beneficio;
    public $descripcion;
    public $puntos_requeridos;
    public $tipo_beneficio;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " WHERE estado = 'activo' ORDER BY puntos_requeridos ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function canjear($id_usuario, $id_beneficio) {
        $query = "INSERT INTO usuario_beneficios (id_usuario, id_beneficio) VALUES (:id_usuario, :id_beneficio)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":id_beneficio", $id_beneficio);
        return $stmt->execute();
    }
}
?>