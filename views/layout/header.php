<?php if(!isset($_SESSION)) { session_start(); } ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'El Mono Rumbero'; ?></title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <header>
        <nav>
            <a href="../home/index.php" class="logo">🐵 El Mono Rumbero</a>
            <ul class="nav-links">
                <li><a href="../home/index.php">Inicio</a></li>
                <li><a href="../eventos/lista.php">Eventos</a></li>
                <li><a href="../entradas/comprar.php">Comprar Entradas</a></li>
                <li><a href="../beneficios/lista.php">Beneficios</a></li>
            </ul>
            <div class="user-info">
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <span class="puntos-badge"> <?php echo $_SESSION['puntos']; ?> Puntos</span>
                    <a href="../usuarios/perfil.php" class="btn-login">👤 <?php echo $_SESSION['nombre']; ?></a>
                    <a href="../../controllers/UsuarioController.php?action=logout" class="btn-logout">Salir</a>
                <?php else: ?>
                    <a href="../usuarios/login.php" class="btn-login">Iniciar Sesión</a>
                    <a href="../usuarios/registro.php" class="btn-login">Registrarse</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="mensaje exito">
            <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="mensaje error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>