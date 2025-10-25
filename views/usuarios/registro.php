<?php 
$pageTitle = "Registro - El Mono Rumbero";
include '../layout/header.php'; 
?>

<div class="container registro-container">
    <h2 class="section-title">Regístrate y Gana Beneficios</h2>

    <div class="form-container">
        <form action="../../controllers/UsuarioController.php?action=registrar" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido *</label>
                <input type="text" id="apellido" name="apellido" placeholder="Tu apellido" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="tucorreo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="+51 999 999 999">
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña *</label>
                <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
            </div>

            <button type="submit" class="btn btn-full">Registrarse Ahora</button>

            <p class="form-footer-text">
                ¿Ya tienes cuenta? 
                <a href="login.php" class="link-acceso">Inicia sesión</a>
            </p>
        </form>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
