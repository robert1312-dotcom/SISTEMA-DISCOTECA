<?php
class Evento {
    private $conn;
    private $table = "eventos";

    public $id_evento;
    public $nombre_evento;
    public $descripcion;
    public $fecha_evento;
    public $artista;
    public $genero_musical;
    public $precio_entrada;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " WHERE estado = 'activo' ORDER BY fecha_evento ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_evento = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre_evento, descripcion, fecha_evento, artista, genero_musical, precio_entrada, capacidad) 
                  VALUES (:nombre, :descripcion, :fecha, :artista, :genero, :precio, :capacidad)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $this->nombre_evento);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fecha", $this->fecha_evento);
        $stmt->bindParam(":artista", $this->artista);
        $stmt->bindParam(":genero", $this->genero_musical);
        $stmt->bindParam(":precio", $this->precio_entrada);
        $stmt->bindParam(":capacidad", $this->capacidad);
        
        return $stmt->execute();
    }
}
?>