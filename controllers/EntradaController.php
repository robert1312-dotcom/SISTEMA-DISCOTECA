<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Entrada.php';
require_once __DIR__ . '/../models/Usuario.php';

class EntradaController {
    private $db;
    private $entrada;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->entrada = new Entrada($this->db);
        $this->usuario = new Usuario($this->db);
    }

    public function comprar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos recibidos
            if(empty($_POST['id_evento']) || empty($_POST['tipo_entrada']) || empty($_POST['precio'])) {
                $_SESSION['error'] = "Datos incompletos. Por favor completa todos los campos.";
                header("Location: ../views/entradas/comprar.php");
                exit();
            }
            
            $this->entrada->id_evento = $_POST['id_evento'];
            $this->entrada->tipo_entrada = $_POST['tipo_entrada'];
            $this->entrada->precio = $_POST['precio'];
            
            // Verificar si el usuario está logueado
            if (isset($_SESSION['usuario_id'])) {
                $this->entrada->id_usuario = $_SESSION['usuario_id'];
            } else {
                $this->entrada->id_usuario = null;
            }

            if ($this->entrada->comprar()) {
                // Si el usuario está logueado, otorgar puntos
                if (isset($_SESSION['usuario_id'])) {
                    $puntos = floor($this->entrada->precio / 10); // 1 punto por cada 10 soles
                    $this->usuario->actualizarPuntos($_SESSION['usuario_id'], $puntos);
                    $_SESSION['puntos'] = $_SESSION['puntos'] + $puntos;
                    $_SESSION['mensaje'] = "¡Entrada comprada exitosamente! Has ganado " . $puntos . " puntos. Código: " . $this->entrada->codigo_qr;
                    header("Location: ../views/entradas/mis_entradas.php");
                } else {
                    $_SESSION['mensaje'] = "¡Entrada comprada exitosamente! Código: " . $this->entrada->codigo_qr . " - Guarda este código para presentarlo en la entrada.";
                    header("Location: ../views/home/index.php");
                }
            } else {
                $_SESSION['error'] = "Error al comprar entrada. Intenta nuevamente.";
                header("Location: ../views/entradas/comprar.php");
            }
        }
    }

    public function misEntradas() {
        if (isset($_SESSION['usuario_id'])) {
            return $this->entrada->obtenerPorUsuario($_SESSION['usuario_id']);
        }
        return null;
    }
}

// Manejo de acciones
if (isset($_GET['action'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $controller = new EntradaController();
    
    switch ($_GET['action']) {
        case 'comprar':
            $controller->comprar();
            break;
        case 'mis_entradas':
            $entradas = $controller->misEntradas();
            include '../views/entradas/mis_entradas.php';
            break;
    }
}
?>

-- ============================================
-- 9. controllers/BeneficioController.php
-- ============================================
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