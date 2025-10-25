<?php
require_once '../config/Database.php';
require_once '../models/Beneficio.php';
require_once '../models/Usuario.php';

class BeneficioController {
    private $db;
    private $beneficio;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->beneficio = new Beneficio($this->db);
        $this->usuario = new Usuario($this->db);
    }

    public function listar() {
        return $this->beneficio->obtenerTodos();
    }

    public function canjear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario_id'])) {
                $_SESSION['error'] = "Debes iniciar sesión para canjear beneficios.";
                header("Location: ../views/usuarios/login.php");
                return;
            }

            $id_beneficio = $_POST['id_beneficio'];
            $puntos_requeridos = $_POST['puntos_requeridos'];
            
            $usuarioData = $this->usuario->obtenerPorId($_SESSION['usuario_id']);
            
            if ($usuarioData['puntos_beneficio'] >= $puntos_requeridos) {
                if ($this->beneficio->canjear($_SESSION['usuario_id'], $id_beneficio)) {
                    // Descontar puntos
                    $this->usuario->actualizarPuntos($_SESSION['usuario_id'], -$puntos_requeridos);
                    $_SESSION['puntos'] -= $puntos_requeridos;
                    $_SESSION['mensaje'] = "Beneficio canjeado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al canjear beneficio.";
                }
            } else {
                $_SESSION['error'] = "No tienes suficientes puntos.";
            }
            
            header("Location: ../views/beneficios/lista.php");
        }
    }
}

// Manejo de acciones
if (isset($_GET['action'])) {
    session_start();
    $controller = new BeneficioController();
    
    switch ($_GET['action']) {
        case 'listar':
            $beneficios = $controller->listar();
            include '../views/beneficios/lista.php';
            break;
        case 'canjear':
            $controller->canjear();
            break;
    }
}
?>