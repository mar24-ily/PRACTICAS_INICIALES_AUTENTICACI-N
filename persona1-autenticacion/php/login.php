<?php
require_once dirname(__DIR__) . '/php/config.php';
require_once 'auth.php';

redirigir_si_autenticado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if (!$email || strlen($password) < 6) {
        $_SESSION['error'] = 'Credenciales inválidas';
        header('Location: ' . url('/login.html'));
        exit;
    }
    
    $resultado = api_request('/auth/login', 'POST', [
        'email' => $email,
        'password' => $password
    ], false);
    
    if ($resultado['success']) {
        // Guardar token si el backend lo devuelve
        if (isset($resultado['data']['token'])) {
            guardar_sesion($resultado['data']['token'], $resultado['data']['user'] ?? $resultado['data']);
        } else {
            verificar_con_api();
        }
        
        $_SESSION['exito'] = '¡Bienvenido! Has iniciado sesión correctamente.';
        header('Location: ' . url('/dashboard.php'));
        exit;
    }
    
    $_SESSION['error'] = $resultado['error'] ?? 'Correo o contraseña incorrectos';
    header('Location: ' . url('/login.html'));
    exit;
}
?>