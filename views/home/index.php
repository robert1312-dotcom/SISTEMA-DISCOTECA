<?php 
$pageTitle = "Inicio - El Mono Rumbero";
include '../layout/header.php'; 
?>

<section class="hero">
    <h1>🎉 El Mono Rumbero 🎉</h1>
    <p>La discoteca más vibrante de la ciudad. ¡Vive la fiesta al máximo!</p>
    <a href="../eventos/lista.php" class="btn">Ver Todos los Eventos</a>
</section>

<div class="container">
    <h2 class="section-title">🔥 Próximos Eventos</h2>
    <div class="cards-grid">
        <?php
        require_once '../../config/Database.php';
        require_once '../../models/Evento.php';
        
        $database = new Database();
        $db = $database->getConnection();
        $evento = new Evento($db);
        $stmt = $evento->obtenerTodos();
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $contador = 0;
        foreach($eventos as $ev):
            if($contador >= 3) break;
            $contador++;
        ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($ev['nombre_evento']); ?></h3>
            <p class="card-artist">🎤 <?php echo htmlspecialchars($ev['artista']); ?></p>
            <p><?php echo htmlspecialchars($ev['descripcion']); ?></p>
            <div class="card-detail">
                <span class="card-date">📅 <?php echo date('d M Y - H:i', strtotime($ev['fecha_evento'])); ?></span>
                <span class="card-price">💵 S/ <?php echo number_format($ev['precio_entrada'], 2); ?></span>
            </div>
            <a href="../entradas/comprar.php?evento=<?php echo $ev['id_evento']; ?>" class="btn btn-full">Comprar Entrada</a>
        </div>
        <?php endforeach; ?>
    </div>

    <h2 class="section-title">🎁 Beneficios para Miembros</h2>
    <div class="cards-grid">
        <?php
        require_once '../../models/Beneficio.php';
        $beneficio = new Beneficio($db);
        $stmt_ben = $beneficio->obtenerTodos();
        $beneficios = $stmt_ben->fetchAll(PDO::FETCH_ASSOC);
        
        $contador = 0;
        foreach($beneficios as $ben):
            if($contador >= 3) break;
            $contador++;
        ?>
        <div class="benefit-card">
            <span class="benefit-points">⭐ <?php echo $ben['puntos_requeridos']; ?> Puntos</span>
            <h3><?php echo htmlspecialchars($ben['nombre_beneficio']); ?></h3>
            <p><?php echo htmlspecialchars($ben['descripcion']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="register-section">
        <h2 class="section-title">¿No eres miembro aún?</h2>
        <p>Regístrate ahora y empieza a ganar puntos por cada compra</p>
        <a href="../usuarios/registro.php" class="btn">Registrarse Gratis</a>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
