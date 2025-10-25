<?php 
session_start();
$pageTitle = "Comprar Entrada - El Mono Rumbero";
include '../layout/header.php'; 

require_once '../../config/Database.php';
require_once '../../models/Evento.php';

$database = new Database();
$db = $database->getConnection();
$eventoModel = new Evento($db);
$stmt = $eventoModel->obtenerTodos();
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$evento_seleccionado = null;
if(isset($_GET['evento'])) {
    $evento_seleccionado = $eventoModel->obtenerPorId($_GET['evento']);
}
?>

<div class="container">
    <h2 class="section-title"> Compra tu Entrada</h2>
    
    <div class="form-container">
        <form action="../../controllers/EntradaController.php?action=comprar" method="POST" id="formCompra">
            
            <div class="form-group">
                <label for="id_evento">Selecciona el Evento *</label>
                <select id="id_evento" name="id_evento" required onchange="actualizarPrecio()">
                    <option value="">-- Selecciona un evento --</option>
                    <?php foreach($eventos as $ev): ?>
                    <option value="<?php echo $ev['id_evento']; ?>" 
                            data-precio="<?php echo $ev['precio_entrada']; ?>"
                            data-nombre="<?php echo htmlspecialchars($ev['nombre_evento']); ?>"
                            <?php echo ($evento_seleccionado && $evento_seleccionado['id_evento'] == $ev['id_evento']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($ev['nombre_evento']); ?> - S/ <?php echo number_format($ev['precio_entrada'], 2); ?>
                        (<?php echo date('d/m/Y', strtotime($ev['fecha_evento'])); ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo_entrada">Tipo de Entrada *</label>
                <select id="tipo_entrada" name="tipo_entrada" required onchange="actualizarPrecio()">
                    <option value="">-- Selecciona tipo --</option>
                    <option value="general" data-extra="0">General (Precio base)</option>
                    <option value="vip" data-extra="20">VIP (+S/ 20.00)</option>
                    <option value="palco" data-extra="50">Palco (+S/ 50.00)</option>
                </select>
            </div>

            <!-- Resumen de Compra -->
            <div class="benefit-card">
                <h3 class="section-title"> Resumen de Compra</h3>
            
                <div class="card-detail">
                    <span>Precio base:</span>
                    <span id="precioBase">S/ 0.00</span>
                </div>
                
                <div class="card-detail">
                    <span>Tipo de entrada:</span>
                    <span id="extraTipo">S/ 0.00</span>
                </div>
                
                <hr>
                
                <div class="card-detail">
                    <span>TOTAL:</span>
                    <span id="precioTotal">S/ 0.00</span>
                </div>
                
                <input type="hidden" name="precio" id="precio" value="0">
            </div>

            <?php if(!isset($_SESSION['usuario_id'])): ?>
            <!-- Formulario para usuarios NO registrados -->
            <div class="benefit-card">
                <p class="card-artist"> No estás registrado</p>
                <p>Completa tus datos para continuar con la compra</p>
            </div>

            <div class="form-group">
                <label for="nombre_comprador">Nombre Completo *</label>
                <input type="text" id="nombre_comprador" name="nombre_comprador" placeholder="Ej: Juan Pérez García" required>
            </div>

            <div class="form-group">
                <label for="email_comprador">Email *</label>
                <input type="email" id="email_comprador" name="email_comprador" placeholder="tucorreo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="telefono_comprador">Teléfono (Opcional)</label>
                <input type="tel" id="telefono_comprador" name="telefono_comprador" placeholder="+51 999 999 999">
            </div>
            <?php else: ?>
            <!-- Mensaje para usuarios registrados -->
            <div class="benefit-card">
                <p class="card-artist"> Comprando como: <?php echo htmlspecialchars($_SESSION['nombre']); ?></p>
                <p>Tienes <?php echo $_SESSION['puntos']; ?> puntos acumulados</p>
            </div>
            <?php endif; ?>

            <!-- Términos y Condiciones -->
            <div class="form-group">
                <label>
                    <input type="checkbox" id="terminos" name="terminos" required>
                    Acepto los términos y condiciones *
                </label>
            </div>

            <button type="submit" class="btn btn-full" id="btnComprar">
                Comprar Entrada 
            </button>

            <?php if(isset($_SESSION['usuario_id'])): ?>
            <p class="card-date"> Ganarás puntos por esta compra (1 punto por cada S/ 10 gastados)</p>
            <?php else: ?>
            <p class="card-date"> <a href="../usuarios/registro.php">Regístrate</a> para acumular puntos por cada compra</p>
            <?php endif; ?>
        </form>
    </div>

    <!-- Información Adicional -->
    <div class="form-container">
        <h3 class="section-title"> Información Importante</h3>
        
        <div class="cards-grid">
            <div class="card">
                <h4 class="card-artist">Código QR</h4>
                <p>Recibirás un código QR único que debes presentar en la entrada</p>
            </div>
            
            <div class="card">
                <h4 class="card-artist"> Gana Puntos</h4>
                <p>Usuarios registrados acumulan puntos canjeables por beneficios</p>
            </div>
            
            <div class="card">
                <h4 class="card-artist"> Pago Seguro</h4>
                <p>Todas las transacciones son procesadas de forma segura</p>
            </div>
        </div>
    </div>
</div>

<script>
function actualizarPrecio() {
    const eventoSelect = document.getElementById('id_evento');
    const tipoSelect = document.getElementById('tipo_entrada');
    const precioTotalElement = document.getElementById('precioTotal');
    const precioBaseElement = document.getElementById('precioBase');
    const extraTipoElement = document.getElementById('extraTipo');
    const precioInput = document.getElementById('precio');
    const btnComprar = document.getElementById('btnComprar');
    
    if(eventoSelect.value && tipoSelect.value) {
        const precioBase = parseFloat(eventoSelect.options[eventoSelect.selectedIndex].dataset.precio);
        const precioExtra = parseFloat(tipoSelect.options[tipoSelect.selectedIndex].dataset.extra);
        const total = precioBase + precioExtra;
        
        precioBaseElement.textContent = 'S/ ' + precioBase.toFixed(2);
        extraTipoElement.textContent = 'S/ ' + precioExtra.toFixed(2);
        precioTotalElement.textContent = 'S/ ' + total.toFixed(2);
        precioInput.value = total.toFixed(2);
        
        btnComprar.disabled = false;
        btnComprar.style.opacity = '1';
        btnComprar.style.cursor = 'pointer';
    } else {
        precioBaseElement.textContent = 'S/ 0.00';
        extraTipoElement.textContent = 'S/ 0.00';
        precioTotalElement.textContent = 'S/ 0.00';
        precioInput.value = '0';
        
        btnComprar.disabled = true;
        btnComprar.style.opacity = '0.5';
        btnComprar.style.cursor = 'not-allowed';
    }
}

// Validación del formulario
document.getElementById('formCompra').addEventListener('submit', function(e) {
    const eventoSelect = document.getElementById('id_evento');
    const tipoSelect = document.getElementById('tipo_entrada');
    const terminos = document.getElementById('terminos');
    
    if(!eventoSelect.value) {
        e.preventDefault();
        alert('Por favor selecciona un evento');
        return false;
    }
    
    if(!tipoSelect.value) {
        e.preventDefault();
        alert('Por favor selecciona el tipo de entrada');
        return false;
    }
    
    if(!terminos.checked) {
        e.preventDefault();
        alert('Debes aceptar los términos y condiciones');
        return false;
    }
    
    return true;
});

// Actualizar precio al cargar si hay evento preseleccionado
window.onload = function() {
    actualizarPrecio();
};
</script>

<?php include '../layout/footer.php'; ?>