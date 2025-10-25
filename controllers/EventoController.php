<?php
require_once '../config/Database.php';
require_once '../models/Evento.php';

class EventoController {
    private $db;
    private $evento;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->evento = new Evento($this->db);
    }

    public function listar() {
        $stmt = $this->evento->obtenerTodos();
        return $stmt;
    }

    public function detalle($id) {
        return $this->evento->obtenerPorId($id);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->evento->nombre_evento = $_POST['nombre_evento'];
            $this->evento->descripcion = $_POST['descripcion'];
            $this->evento->fecha_evento = $_POST['fecha_evento'];
            $this->evento->artista = $_POST['artista'];
            $this->evento->genero_musical = $_POST['genero_musical'];
            $this->evento->precio_entrada = $_POST['precio_entrada'];
            $this->evento->capacidad = $_POST['capacidad'];

            if ($this->evento->crear()) {
                $_SESSION['mensaje'] = "Evento creado exitosamente.";
                header("Location: ../views/eventos/lista.php");
            } else {
                $_SESSION['error'] = "Error al crear evento.";
            }
        }
    }
}

// Manejo de acciones
if (isset($_GET['action'])) {
    session_start();
    $controller = new EventoController();
    
    switch ($_GET['action']) {
        case 'listar':
            $eventos = $controller->listar();
            include '../views/eventos/lista.php';
            break;
        case 'detalle':
            $evento = $controller->detalle($_GET['id']);
            include '../views/eventos/detalle.php';
            break;
        case 'crear':
            $controller->crear();
            break;
    }
}
?>