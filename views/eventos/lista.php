<?php 
$pageTitle = "Eventos - El Mono Rumbero";
include '../layout/header.php'; 

require_once '../../config/Database.php';
require_once '../../models/Evento.php';

$database = new Database();
$db = $database->getConnection();
$evento = new Evento($db);
$stmt = $evento->obtenerTodos();
?>

<div class="container">
    <h2 class="section-title">🎊 Todos los Eventos</h2>
    <div class="cards-grid">
        <?php while($ev = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($ev['nombre_evento']); ?></h3>
            <p class="card-artist"> <?php echo htmlspecialchars($ev['artista']); ?></p>
            <p><?php echo htmlspecialchars($ev['descripcion']); ?></p>
            <div class="card-detail">
                <span class="card-date"><?php echo date('d M Y - H:i', strtotime($ev['fecha_evento'])); ?></span>
                <span class="card-price"> S/ <?php echo number_format($ev['precio_entrada'], 2); ?></span>
            </div>
            <p style="margin-top: 1rem; color: #00d4ff;">Género: <?php echo htmlspecialchars($ev['genero_musical']); ?></p>
            <p style="color: #00d4ff;"> Capacidad: <?php echo $ev['capacidad']; ?> personas</p>
            <a href="detalle.php?id=<?php echo $ev['id_evento']; ?>" class="btn btn-full">Ver Detalles</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../layout/footer.php'; ?>