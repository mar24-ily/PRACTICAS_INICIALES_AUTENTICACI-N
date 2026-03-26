<?php
require_once dirname(__DIR__) . '/php/config.php';
require_once 'auth.php';

redirigir_si_autenticado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registro = preg_replace('/[^0-9]/', '', $_POST['registro'] ?? '');
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    $errores = [];
    if (strlen($registro) !== 9) $errores[] = 'El carnet debe tener 9 dígitos';
    if (strlen($nombres) < 2) $errores[] = 'Los nombres son requeridos';
    if (strlen($apellidos) < 2) $errores[] = 'Los apellidos son requeridos';
    if (!$email) $errores[] = 'Correo electrónico inválido';
    if (strlen($password) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres';
    
    if (!empty($errores)) {
        $_SESSION['error'] = implode('<br>', $errores);
        header('Location: ' . url('/registro.html'));
        exit;
    }
    
    // ============================================
    // PETICIÓN AL BACKEND REAL EN PUERTO 3000
    // ============================================
    
    $resultado = api_request('/auth/register', 'POST', [
        'registroAcademico' => $registro,
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'email' => $email,
        'password' => $password
    ], false);
    
    if ($resultado['success']) {
        // Intentar login automático después del registro
        $login = api_request('/auth/login', 'POST', [
            'email' => $email,
            'password' => $password
        ], false);
        
        if ($login['success'] && isset($login['data']['token'])) {
            guardar_sesion($login['data']['token'], $login['data']['user'] ?? $login['data']);
        } else {
            verificar_con_api();
        }
        
        $_SESSION['exito'] = '¡Registro exitoso! Bienvenido a la Red Social USAC';
        header('Location: ' . url('/dashboard.php'));
        exit;
    }
    
    $_SESSION['error'] = $resultado['error'] ?? 'Error en el registro';
    header('Location: ' . url('/registro.html'));
    exit;
}
?>