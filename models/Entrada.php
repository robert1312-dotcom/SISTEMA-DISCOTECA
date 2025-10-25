<?php
class Entrada {
    private $conn;
    private $table = "entradas";

    public $id_entrada;
    public $id_evento;
    public $id_usuario;
    public $tipo_entrada;
    public $precio;
    public $codigo_qr;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function comprar() {
        // Generar código QR único
        $this->codigo_qr = uniqid('MONO-', true);
        
        // Si id_usuario es null, insertamos NULL en la base de datos
        if($this->id_usuario === null || $this->id_usuario === '') {
            $query = "INSERT INTO " . $this->table . " 
                      (id_evento, id_usuario, tipo_entrada, precio, codigo_qr) 
                      VALUES (:id_evento, NULL, :tipo_entrada, :precio, :codigo_qr)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_evento", $this->id_evento);
            $stmt->bindParam(":tipo_entrada", $this->tipo_entrada);
            $stmt->bindParam(":precio", $this->precio);
            $stmt->bindParam(":codigo_qr", $this->codigo_qr);
        } else {
            $query = "INSERT INTO " . $this->table . " 
                      (id_evento, id_usuario, tipo_entrada, precio, codigo_qr) 
                      VALUES (:id_evento, :id_usuario, :tipo_entrada, :precio, :codigo_qr)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_evento", $this->id_evento);
            $stmt->bindParam(":id_usuario", $this->id_usuario);
            $stmt->bindParam(":tipo_entrada", $this->tipo_entrada);
            $stmt->bindParam(":precio", $this->precio);
            $stmt->bindParam(":codigo_qr", $this->codigo_qr);
        }
        
        return $stmt->execute();
    }

    public function obtenerPorUsuario($id_usuario) {
        $query = "SELECT e.*, ev.nombre_evento, ev.fecha_evento, ev.artista 
                  FROM " . $this->table . " e
                  INNER JOIN eventos ev ON e.id_evento = ev.id_evento
                  WHERE e.id_usuario = :id_usuario
                  ORDER BY e.fecha_compra DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->execute();
        return $stmt;
    }
    
    public function obtenerTodas() {
        $query = "SELECT e.*, ev.nombre_evento, ev.fecha_evento, ev.artista,
                  u.nombre as nombre_usuario, u.email as email_usuario
                  FROM " . $this->table . " e
                  INNER JOIN eventos ev ON e.id_evento = ev.id_evento
                  LEFT JOIN usuarios u ON e.id_usuario = u.id_usuario
                  ORDER BY e.fecha_compra DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>