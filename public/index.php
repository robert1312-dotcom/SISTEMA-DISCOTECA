<?php
session_start();

// Definir la ruta base del proyecto
define('BASE_PATH', dirname(__DIR__));

// Redirigir al home
header("Location: ../views/home/index.php");
exit();
?>