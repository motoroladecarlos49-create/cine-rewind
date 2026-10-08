<?php
require_once __DIR__ . '/../models/UsuarioModelo.php';
require_once __DIR__ . '/../helpers/funciones.php';
require_once __DIR__ . '/../helpers/session.php';

class AuthControlador {
    
    /**
     * Muestra el formulario de login
     */
    public function mostrarLogin() {
        if (estaLogueado()) {
            if (esAdmin()) {
                redirigir('index.php?ruta=admin');
            } else {
                redirigir('index.php?ruta=home');
            }
        }
        
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/shared/login.php';
    }
    
    /**
     * Procesa el login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('index.php?ruta=login');
        }
        
        $email = sanitizar($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            setMensaje('error', 'Todos los campos son obligatorios.');
            redirigir('index.php?ruta=login');
        }
        
        if (!validarEmail($email)) {
            setMensaje('error', 'El email no es válido.');
            redirigir('index.php?ruta=login');
        }
        
        $usuario = UsuarioModelo::verificarCredenciales($email, $password);
        
        if (!$usuario) {
            setMensaje('error', 'Email o contraseña incorrectos.');
            redirigir('index.php?ruta=login');
        }
        
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['ultimo_acceso'] = time();
        
        setMensaje('success', '¡Bienvenido ' . $usuario['nombre'] . '!');
        
        if ($usuario['rol'] === 'admin') {
            redirigir('index.php?ruta=admin');
        } else {
            redirigir('index.php?ruta=home');
        }
    }
    
    /**
     * Muestra el formulario de registro
     */
    public function mostrarRegistro() {
        if (estaLogueado()) {
            redirigir('index.php?ruta=home');
        }
        
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/shared/registro.php';
    }
    
    /**
     * Procesa el registro
     */
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('index.php?ruta=registro');
        }
        
        $nombre = sanitizar($_POST['nombre'] ?? '');
        $email = sanitizar($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        if (empty($nombre) || empty($email) || empty($password)) {
            setMensaje('error', 'Todos los campos son obligatorios.');
            redirigir('index.php?ruta=registro');
        }
        
        if (!validarEmail($email)) {
            setMensaje('error', 'El email no es válido.');
            redirigir('index.php?ruta=registro');
        }
        
        if (strlen($password) < 6) {
            setMensaje('error', 'La contraseña debe tener al menos 6 caracteres.');
            redirigir('index.php?ruta=registro');
        }
        
        if ($password !== $password_confirm) {
            setMensaje('error', 'Las contraseñas no coinciden.');
            redirigir('index.php?ruta=registro');
        }
        
        $usuarioExistente = UsuarioModelo::obtenerPorEmail($email);
        if ($usuarioExistente) {
            setMensaje('error', 'El email ya está registrado.');
            redirigir('index.php?ruta=registro');
        }
        
        $resultado = UsuarioModelo::crear($nombre, $email, $password);
        
        if ($resultado) {
            setMensaje('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');
            redirigir('index.php?ruta=login');
        } else {
            setMensaje('error', 'Error al crear el usuario. Intenta nuevamente.');
            redirigir('index.php?ruta=registro');
        }
    }
    
    /**
     * Cierra la sesión
     */
    public function logout() {
        cerrarSesion();
        setMensaje('info', 'Has cerrado sesión correctamente.');
        redirigir('index.php?ruta=login');
    }
    
    /**
     * Verifica autenticación para rutas protegidas
     */
    public static function verificarAutenticacion() {
        if (!estaLogueado()) {
            setMensaje('warning', 'Debes iniciar sesión para acceder a esta página.');
            redirigir('index.php?ruta=login');
        }
        verificarInactividad();
    }
    
    /**
     * Verifica rol de administrador para rutas de admin
     */
    public static function verificarAdmin() {
        self::verificarAutenticacion();
        if (!esAdmin()) {
            setMensaje('error', 'No tienes permisos para acceder a esta página.');
            redirigir('index.php?ruta=home');
        }
    }
}
?>