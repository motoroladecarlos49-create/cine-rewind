<?php
// Front Controller - Punto de entrada único

// Cargar configuración
require_once __DIR__ . '/../config/config.php';

// Iniciar sesión
session_start();

// Cargar helpers
require_once __DIR__ . '/../helpers/funciones.php';
require_once __DIR__ . '/../helpers/session.php';

// Definir BASE_URL para usar en las vistas
if (!defined('BASE_URL')) {
    define('BASE_URL', '/cine-rewind/public/');
}

// Obtener la ruta solicitada (desde GET o desde la URL amigable)
$ruta = isset($_GET['ruta']) ? $_GET['ruta'] : 'home';

// Si la ruta está vacía, redirigir a home
if (empty($ruta)) {
    $ruta = 'home';
}

// Enrutamiento
switch ($ruta) {
    // ==================== RUTAS PÚBLICAS ====================
    case 'login':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        $controlador = new AuthControlador();
        $controlador->mostrarLogin();
        break;
        
    case 'login_post':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        $controlador = new AuthControlador();
        $controlador->login();
        break;
        
    case 'registro':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        $controlador = new AuthControlador();
        $controlador->mostrarRegistro();
        break;
        
    case 'registro_post':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        $controlador = new AuthControlador();
        $controlador->registro();
        break;
        
    case 'logout':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        $controlador = new AuthControlador();
        $controlador->logout();
        break;
        
    case 'nosotros':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->nosotros();
        break;
        
    case 'contacto':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->contacto();
        break;
    
    // ==================== RUTAS DE USUARIO ====================
    case 'home':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->index();
        break;
        
    case 'cartelera':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->cartelera();
        break;
        
    case 'decada':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->decada();
        break;
        
    case 'reservar':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->reservar();
        break;
        
    case 'entrada':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->entrada();
        break;
        
    case 'procesar_compra':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->procesarCompra();
        break;
        
    case 'perfil':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->perfil();
        break;
        
    case 'actualizar_perfil':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->actualizarPerfil();
        break;
        
    case 'cambiar_password':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->cambiarPassword();
        break;
        
    case 'historial':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        $controlador->historial();
        break;
    
    // ==================== RUTAS DE ADMINISTRADOR ====================
    case 'admin':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->dashboard();
        break;
        
    case 'admin_peliculas':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->listarPeliculas();
        break;
        
    case 'admin_peliculas_agregar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->agregarPelicula();
        break;
        
    case 'admin_peliculas_editar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->editarPelicula();
        break;
        
    case 'admin_peliculas_eliminar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->eliminarPelicula();
        break;
        
    case 'admin_funciones':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->listarFunciones();
        break;
        
    case 'admin_funciones_agregar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->agregarFuncion();
        break;
        
    case 'admin_funciones_editar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->editarFuncion();
        break;
        
    case 'admin_funciones_eliminar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->eliminarFuncion();
        break;
        
    case 'admin_salas':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->listarSalas();
        break;
        
    case 'admin_salas_agregar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->agregarSala();
        break;
        
    case 'admin_salas_editar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->editarSala();
        break;
        
    case 'admin_salas_eliminar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->eliminarSala();
        break;
        
    case 'admin_usuarios':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->listarUsuarios();
        break;
        
    case 'admin_usuarios_cambiar_rol':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->cambiarRol();
        break;
        
    case 'admin_usuarios_eliminar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->eliminarUsuario();
        break;
        
    case 'admin_comentarios':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        $controlador = new AdminControlador();
        $controlador->moderarComentarios();
        break;
        case 'buscar':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->buscar();
        break;

    case 'detalle':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->detallePelicula();
        break;

    case 'comentar':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->comentar();
        break;
        
    // ==================== RUTA POR DEFECTO (404) ====================
    default:
        http_response_code(404);
        echo "<h1>Error 404 - Página no encontrada</h1>";
        echo "<p>La ruta solicitada no existe: " . htmlspecialchars($ruta) . "</p>";
        echo "<a href='" . BASE_URL . "?ruta=home'>Volver al inicio</a>";
        break;
}
?>