<?php
// Manejo de sesiones

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Regenerar ID de sesión por seguridad
if (!isset($_SESSION['creado'])) {
    session_regenerate_id(true);
    $_SESSION['creado'] = time();
}

// Cerrar sesión
function cerrarSesion() {
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}

// Verificar tiempo de inactividad (30 minutos)
function verificarInactividad() {
    $tiempo_maximo = 1800; // 30 minutos
    
    if (isset($_SESSION['ultimo_acceso'])) {
        $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];
        if ($tiempo_inactivo > $tiempo_maximo) {
            cerrarSesion();
            setMensaje('warning', 'Tu sesión ha expirado por inactividad.');
            redirigir('login');
        }
    }
    $_SESSION['ultimo_acceso'] = time();
}
?>