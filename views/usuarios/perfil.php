<?php 
session_start();
if(!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Mi Perfil - El Mono Rumbero";
include '../layout/header.php'; 

require_once '../../config/Database.php';
require_once '../../models/Usuario.php';
require_once '../../models/Beneficio.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);
$usuarioData = $usuario->obtenerPorId($_SESSION['usuario_id']);
?>

<div class="container perfil-container">
    <h2 class="section-title">Mi Perfil</h2>
    
    <div class="form-container perfil-form">
        <div class="profile-grid">
            <div class="profile-section info-personal">
                <h3 class="titulo-rosa">Información Personal</h3>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuarioData['nombre'] . ' ' . $usuarioData['apellido']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($usuarioData['email']); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuarioData['telefono']); ?></p>
                <p><strong>Miembro desde:</strong> <?php echo date('M Y', strtotime($usuarioData['fecha_registro'])); ?></p>
            </div>

            <div class="profile-section puntos">
                <h3 class="titulo-celeste">Mis Puntos</h3>
                <div class="points-display">
                    <p class="puntos-cantidad"><?php echo $usuarioData['puntos_beneficio']; ?></p>
                    <p>Puntos Acumulados</p>
                </div>
                <a href="../beneficios/lista.php" class="btn btn-secondary btn-full">Canjear Beneficios</a>
            </div>
        </div>

        <div class="mis-entradas">
            <h3 class="titulo-naranja">Mis Entradas</h3>
            <a href="../entradas/mis_entradas.php" class="btn">Ver Mis Entradas</a>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
