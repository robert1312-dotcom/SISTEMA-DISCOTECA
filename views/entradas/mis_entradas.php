<?php 
session_start();

if(!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para ver tus entradas";
    header("Location: ../usuarios/login.php");
    exit();
}

$pageTitle = "Mis Entradas - El Mono Rumbero";
include '../layout/header.php'; 

require_once '../../config/Database.php';
require_once '../../models/Entrada.php';

$database = new Database();
$db = $database->getConnection();
$entrada = new Entrada($db);
$stmt = $entrada->obtenerPorUsuario($_SESSION['usuario_id']);
$total_entradas = $stmt->rowCount();
?>

<div class="container">
    <h2 class="section-title"> Mis Entradas</h2>
    
    <!-- Estadísticas del Usuario -->
    <div class="profile-grid">
        <div class="points-display">
            <p><?php echo $total_entradas; ?></p>
            <p>Entradas Compradas</p>
        </div>
        
        <div class="points-display">
            <p><?php echo $_SESSION['puntos']; ?></p>
            <p>Puntos Acumulados</p>
        </div>
    </div>
    
    <?php if($total_entradas > 0): ?>
    <div class="cards-grid">
        <?php 
        // Reiniciar el statement para poder iterar
        $stmt = $entrada->obtenerPorUsuario($_SESSION['usuario_id']);
        while($ent = $stmt->fetch(PDO::FETCH_ASSOC)): 
            // Determinar iconos según el estado
            $icono_estado = '✅';
            
            if($ent['estado'] == 'usada') {
                $icono_estado = '✓';
            } elseif($ent['estado'] == 'cancelada') {
                $icono_estado = '❌';
            }
            
            // Verificar si el evento ya pasó
            $fecha_evento = strtotime($ent['fecha_evento']);
            $fecha_actual = time();
            $evento_pasado = $fecha_evento < $fecha_actual;
        ?>
        <div class="card">
            <div class="card-detail">
                <h3><?php echo htmlspecialchars($ent['nombre_evento']); ?></h3>
                <span class="benefit-points"><?php echo $icono_estado . ' ' . strtoupper($ent['estado']); ?></span>
            </div>
            
            <p class="card-artist"> <?php echo htmlspecialchars($ent['artista']); ?></p>
            
            <!-- Código QR -->
            <div class="benefit-card">
                <p class="card-artist"> Código QR:</p>
                <p><?php echo htmlspecialchars($ent['codigo_qr']); ?></p>
                <p class="card-date">Presenta este código en la entrada</p>
            </div>
            
            <!-- Detalles de la entrada -->
            <div class="profile-grid">
                <div>
                    <p class="card-date">Tipo:</p>
                    <p class="card-artist"><?php echo strtoupper($ent['tipo_entrada']); ?></p>
                </div>
                
                <div>
                    <p class="card-date">Precio:</p>
                    <p class="card-artist">S/ <?php echo number_format($ent['precio'], 2); ?></p>
                </div>
            </div>
            
            <div class="card-detail">
                <span class="card-date"> <?php echo date('d M Y - H:i', strtotime($ent['fecha_evento'])); ?></span>
                <span class="card-price"><?php echo $evento_pasado ? ' Evento Pasado' : ' Próximamente'; ?></span>
            </div>
            
            <p class="card-date">Comprada el <?php echo date('d/m/Y H:i', strtotime($ent['fecha_compra'])); ?></p>
            
            <?php if($ent['estado'] == 'activa' && !$evento_pasado): ?>
            <button class="btn btn-secondary btn-full" onclick="mostrarCodigo('<?php echo $ent['codigo_qr']; ?>', '<?php echo htmlspecialchars($ent['nombre_evento']); ?>')">
                Ver Código QR 
            </button>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Botones de acción -->
    <div class="container" style="text-align: center; margin-top: 3rem;">
        <a href="comprar.php" class="btn btn-secondary">Comprar Más Entradas </a>
        <a href="../beneficios/lista.php" class="btn">Ver Beneficios </a>
    </div>
    
    <?php else: ?>
    <!-- Sin entradas -->
    <div class="form-container">
        <div style="text-align: center; padding: 2rem;">
            <h3 class="section-title" style="font-size: 4rem;"></h3>
            <h3>No tienes entradas compradas aún</h3>
            <p class="card-date">¡Compra tu primera entrada y empieza a acumular puntos!</p>
            <a href="comprar.php" class="btn btn-full">Comprar Mi Primera Entrada</a>
            <a href="../eventos/lista.php" class="btn btn-secondary btn-full">Ver Eventos Disponibles</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function mostrarCodigo(codigo, evento) {
    alert('ENTRADA PARA: ' + evento + '\n\n' +
          'CÓDIGO QR:\n' + codigo + '\n\n' +
          ' IMPORTANTE:\n' +
          '• Presenta este código en la entrada\n' +
          '• Guarda una captura de pantalla\n' +
          '• No compartas tu código con nadie');
}
</script>

<?php include '../layout/footer.php'; ?>