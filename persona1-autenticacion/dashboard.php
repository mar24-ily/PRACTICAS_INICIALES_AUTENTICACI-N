<?php 
require_once 'php/config.php';
require_once 'php/auth.php';
proteger_pagina();
$usuario = obtener_usuario_actual();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Red Social USAC</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Red Social USAC</div>
        <div class="user-info">
            <span>👤 <?php echo htmlspecialchars($usuario['nombre']); ?></span>
            <a href="php/logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>
    
    <div class="dashboard">
        <h1>✅ BIENVENIDO A RESEÑAS USAC</h1>
        
        <div class="info-card">
            <h3>Información del usuario autenticado:</h3>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($usuario['id']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
        </div>
    </div>
    
    <script src="js/funciones.js"></script>
</body>
</html>