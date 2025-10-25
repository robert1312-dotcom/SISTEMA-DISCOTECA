<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id_usuario;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $fecha_nacimiento;
    public $password;
    public $puntos_beneficio;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, apellido, email, telefono, fecha_nacimiento, password) 
                  VALUES (:nombre, :apellido, :email, :telefono, :fecha_nacimiento, :password)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":password", $this->password);
        
        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND estado = 'activo' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPuntos($id, $puntos) {
        $query = "UPDATE " . $this->table . " SET puntos_beneficio = puntos_beneficio + :puntos WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":puntos", $puntos);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>