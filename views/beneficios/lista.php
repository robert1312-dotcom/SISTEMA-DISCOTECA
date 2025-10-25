<?php 
$pageTitle = "Beneficios - El Mono Rumbero";
include '../layout/header.php'; 

require_once '../../config/Database.php';
require_once '../../models/Beneficio.php';

$database = new Database();
$db = $database->getConnection();
$beneficio = new Beneficio($db);
$stmt = $beneficio->obtenerTodos();

$puntos_usuario = isset($_SESSION['puntos']) ? $_SESSION['puntos'] : 0;
?>

<div class="container">
    <h2 class="section-title">Beneficios y Recompensas</h2>
    
    <?php if(isset($_SESSION['usuario_id'])): ?>
    <div class="points-container">
        <div class="points-display">
            <p class="points-value"><?php echo $puntos_usuario; ?></p>
            <p class="points-label">Puntos Disponibles</p>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="cards-grid">
        <?php while($ben = $stmt->fetch(PDO::FETCH_ASSOC)): 
            $puede_canjear = isset($_SESSION['usuario_id']) && $puntos_usuario >= $ben['puntos_requeridos'];
        ?>
        <div class="benefit-card <?php echo (!$puede_canjear && isset($_SESSION['usuario_id'])) ? 'benefit-disabled' : ''; ?>">
            <span class="benefit-points"><?php echo $ben['puntos_requeridos']; ?> Puntos</span>
            <h3><?php echo htmlspecialchars($ben['nombre_beneficio']); ?></h3>
            <p><?php echo htmlspecialchars($ben['descripcion']); ?></p>
            
            <?php if($ben['descuento_porcentaje'] > 0): ?>
            <p class="benefit-discount">
                🎉 <?php echo $ben['descuento_porcentaje']; ?>% de descuento
            </p>
            <?php endif; ?>
            
            <p class="benefit-type">
                📌 Tipo: <?php echo ucfirst(str_replace('_', ' ', $ben['tipo_beneficio'])); ?>
            </p>
            
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <?php if($puede_canjear): ?>
                <form action="../../controllers/BeneficioController.php?action=canjear" method="POST">
                    <input type="hidden" name="id_beneficio" value="<?php echo $ben['id_beneficio']; ?>">
                    <input type="hidden" name="puntos_requeridos" value="<?php echo $ben['puntos_requeridos']; ?>">
                    <button type="submit" class="btn btn-full">Canjear Ahora</button>
                </form>
                <?php else: ?>
                <button class="btn btn-full btn-disabled" disabled>
                    Puntos Insuficientes
                </button>
                <?php endif; ?>
            <?php else: ?>
            <a href="../usuarios/registro.php" class="btn btn-full">Regístrate para Canjear</a>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
    
    <?php if(!isset($_SESSION['usuario_id'])): ?>
    <div class="register-banner">
        <h3>¿Quieres obtener estos beneficios?</h3>
        <p>Regístrate gratis y empieza a acumular puntos</p>
        <a href="../usuarios/registro.php" class="btn">Registrarse Gratis</a>
    </div>
    <?php endif; ?>
</div>

<?php include '../layout/footer.php'; ?>
