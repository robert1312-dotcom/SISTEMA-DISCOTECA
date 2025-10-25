<?php 
$pageTitle = "Iniciar Sesión - El Mono Rumbero";
include '../layout/header.php'; 
?>

<div class="container">
    <h2 class="section-title">Iniciar Sesión</h2>
    
    <div class="form-container">
        <form action="../../controllers/UsuarioController.php?action=login" method="POST">
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="tucorreo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
            </div>

            <button type="submit" class="btn btn-full">Iniciar Sesión 🎉</button>

            <p class="form-text">
                ¿No tienes cuenta? 
                <a href="registro.php" class="form-link">Regístrate aquí</a>
            </p>
        </form>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
