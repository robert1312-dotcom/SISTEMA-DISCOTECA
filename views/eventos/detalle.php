<?php 
if(!isset($_GET['id'])) {
    header("Location: lista.php");
    exit();
}

require_once '../../config/Database.php';
require_once '../../models/Evento.php';

$database = new Database();
$db = $database->getConnection();
$eventoModel = new Evento($db);
$evento = $eventoModel->obtenerPorId($_GET['id']);

if(!$evento) {
    header("Location: lista.php");
    exit();
}

$pageTitle = $evento['nombre_evento'] . " - El Mono Rumbero";
include '../layout/header.php'; 
?>

<div class="container evento-detalle-container">
    <div class="evento-detalle">
        <h2 class="section-title"><?php echo htmlspecialchars($evento['nombre_evento']); ?></h2>

        <div class="card detalle-card">
            <div class="detalle-section">
                <h3 class="detalle-subtitulo"> Artista</h3>
                <p class="detalle-texto"><?php echo htmlspecialchars($evento['artista']); ?></p>
            </div>

            <div class="detalle-section">
                <h3 class="detalle-subtitulo">Descripción</h3>
                <p class="detalle-descripcion"><?php echo nl2br(htmlspecialchars($evento['descripcion'])); ?></p>
            </div>

            <div class="detalle-info-grid">
                <div class="detalle-info-item">
                    <h3 class="detalle-subtitulo"> Fecha y Hora</h3>
                    <p class="detalle-texto">
                        <?php echo date('d/m/Y - H:i', strtotime($evento['fecha_evento'])); ?>
                    </p>
                </div>

                <div class="detalle-info-item">
                    <h3 class="detalle-subtitulo"> Precio</h3>
                    <p class="detalle-texto">
                        S/ <?php echo number_format($evento['precio_entrada'], 2); ?>
                    </p>
                </div>
            </div>

            <div class="detalle-extra">
                <p class="detalle-texto"> Género musical: <?php echo htmlspecialchars($evento['genero_musical']); ?></p>
                <p class="detalle-texto">👥Capacidad: <?php echo $evento['capacidad']; ?> personas</p>
            </div>

            <div class="detalle-boton">
                <a href="../entradas/comprar.php?evento=<?php echo $evento['id_evento']; ?>" class="btn btn-full">
                    Comprar Entrada
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
