<?php
require_once '../config/Database.php';
require_once '../models/Usuario.php';

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->usuario->nombre = $_POST['nombre'];
            $this->usuario->apellido = $_POST['apellido'];
            $this->usuario->email = $_POST['email'];
            $this->usuario->telefono = $_POST['telefono'];
            $this->usuario->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $this->usuario->password = $_POST['password'];

            if ($this->usuario->registrar()) {
                // Dar puntos de bienvenida
                $lastId = $this->db->lastInsertId();
                $this->usuario->actualizarPuntos($lastId, 50);
                
                $_SESSION['mensaje'] = "Registro exitoso. Has ganado 50 puntos de bienvenida.";
                header("Location: ../views/usuarios/login.php");
            } else {
                $_SESSION['error'] = "Error al registrar usuario.";
                header("Location: ../views/usuarios/registro.php");
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $usuarioData = $this->usuario->login($email, $password);

            if ($usuarioData) {
                $_SESSION['usuario_id'] = $usuarioData['id_usuario'];
                $_SESSION['nombre'] = $usuarioData['nombre'];
                $_SESSION['email'] = $usuarioData['email'];
                $_SESSION['puntos'] = $usuarioData['puntos_beneficio'];
                
                header("Location: ../views/home/index.php");
            } else {
                $_SESSION['error'] = "Credenciales incorrectas.";
                header("Location: ../views/usuarios/login.php");
            }
        }
    }

    public function perfil() {
        if (isset($_SESSION['usuario_id'])) {
            $usuarioData = $this->usuario->obtenerPorId($_SESSION['usuario_id']);
            return $usuarioData;
        }
        return null;
    }

    public function logout() {
        session_destroy();
        header("Location: ../views/home/index.php");
    }
}

// Manejo de acciones
if (isset($_GET['action'])) {
    session_start();
    $controller = new UsuarioController();
    
    switch ($_GET['action']) {
        case 'registrar':
            $controller->registrar();
            break;
        case 'login':
            $controller->login();
            break;
        case 'logout':
            $controller->logout();
            break;
        case 'perfil':
            $usuario = $controller->perfil();
            include '../views/usuarios/perfil.php';
            break;
    }
}
?>