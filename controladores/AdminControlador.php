<?php
require_once __DIR__ . '/../models/PeliculaModelo.php';
require_once __DIR__ . '/../models/SalaModelo.php';
require_once __DIR__ . '/../models/FuncionModelo.php';
require_once __DIR__ . '/../models/UsuarioModelo.php';
require_once __DIR__ . '/../models/ReservaModelo.php';
require_once __DIR__ . '/../models/ComentarioModelo.php';
require_once __DIR__ . '/../helpers/funciones.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/AuthControlador.php';

class AdminControlador {
    
    public function dashboard() {
        AuthControlador::verificarAdmin();
        
        $total_peliculas = PeliculaModelo::contarTodas();
        $total_usuarios = UsuarioModelo::contarTodos();
        $estadisticas_ventas = ReservaModelo::obtenerEstadisticas();
        $comentarios_pendientes = ComentarioModelo::obtenerPendientes();
        $funciones_proximas = FuncionModelo::obtenerProximas(10);
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    // ==================== PELÍCULAS ====================
    
    public function listarPeliculas() {
        AuthControlador::verificarAdmin();
        
        $peliculas = PeliculaModelo::obtenerTodas();
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/peliculas/listar.php';
    }
    
    public function agregarPelicula() {
        AuthControlador::verificarAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'titulo' => sanitizar($_POST['titulo'] ?? ''),
                'descripcion' => sanitizar($_POST['descripcion'] ?? ''),
                'duracion' => intval($_POST['duracion'] ?? 0),
                'genero' => sanitizar($_POST['genero'] ?? ''),
                'clasificacion' => sanitizar($_POST['clasificacion'] ?? ''),
                'anio_estreno' => intval($_POST['anio_estreno'] ?? 0),
                'posterurl' => sanitizar($_POST['posterurl'] ?? ''),
                'trailerurl' => sanitizar($_POST['trailerurl'] ?? ''),
                'destacada' => isset($_POST['destacada']) ? 1 : 0
            ];
            
            if (empty($datos['titulo']) || empty($datos['descripcion']) || 
                $datos['duracion'] <= 0 || empty($datos['genero']) || 
                $datos['anio_estreno'] <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_peliculas_agregar');
            }
            
            $resultado = PeliculaModelo::crear($datos);
            
            if ($resultado) {
                setMensaje('success', 'Película agregada correctamente.');
            } else {
                setMensaje('error', 'Error al agregar la película.');
            }
            
            redirigir('admin_peliculas');
        }
        
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/peliculas/agregar.php';
    }
    
    public function editarPelicula() {
        AuthControlador::verificarAdmin();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setMensaje('error', 'ID de película no válido.');
            redirigir('admin_peliculas');
        }
        
        $pelicula = PeliculaModelo::obtenerPorId($id);
        if (!$pelicula) {
            setMensaje('error', 'Película no encontrada.');
            redirigir('admin_peliculas');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'titulo' => sanitizar($_POST['titulo'] ?? ''),
                'descripcion' => sanitizar($_POST['descripcion'] ?? ''),
                'duracion' => intval($_POST['duracion'] ?? 0),
                'genero' => sanitizar($_POST['genero'] ?? ''),
                'clasificacion' => sanitizar($_POST['clasificacion'] ?? ''),
                'anio_estreno' => intval($_POST['anio_estreno'] ?? 0),
                'posterurl' => sanitizar($_POST['posterurl'] ?? ''),
                'trailerurl' => sanitizar($_POST['trailerurl'] ?? ''),
                'destacada' => isset($_POST['destacada']) ? 1 : 0
            ];
            
            if (empty($datos['titulo']) || empty($datos['descripcion']) || 
                $datos['duracion'] <= 0 || empty($datos['genero']) || 
                $datos['anio_estreno'] <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_peliculas_editar&id=' . $id);
            }
            
            $resultado = PeliculaModelo::actualizar($id, $datos);
            
            if ($resultado) {
                setMensaje('success', 'Película actualizada correctamente.');
            } else {
                setMensaje('error', 'Error al actualizar la película.');
            }
            
            redirigir('admin_peliculas');
        }
        
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/peliculas/editar.php';
    }
    
    public function eliminarPelicula() {
        AuthControlador::verificarAdmin();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setMensaje('error', 'ID de película no válido.');
            redirigir('admin_peliculas');
        }
        
        $resultado = PeliculaModelo::eliminar($id);
        
        if ($resultado) {
            setMensaje('success', 'Película eliminada correctamente.');
        } else {
            setMensaje('error', 'Error al eliminar la película.');
        }
        
        redirigir('admin_peliculas');
    }
    
    // ==================== FUNCIONES ====================
    
    public function listarFunciones() {
        AuthControlador::verificarAdmin();
        
        $funciones = FuncionModelo::obtenerTodas();
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/funciones/listar.php';
    }
    
    public function agregarFuncion() {
        AuthControlador::verificarAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_pelicula' => intval($_POST['id_pelicula'] ?? 0),
                'id_sala' => intval($_POST['id_sala'] ?? 0),
                'fecha' => $_POST['fecha'] ?? '',
                'horario' => $_POST['horario'] ?? '',
                'precio' => floatval($_POST['precio'] ?? 0)
            ];
            
            if ($datos['id_pelicula'] <= 0 || $datos['id_sala'] <= 0 || 
                empty($datos['fecha']) || empty($datos['horario']) || 
                $datos['precio'] <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_funciones_agregar');
            }
            
            try {
                $resultado = FuncionModelo::crear($datos);
                
                if ($resultado) {
                    setMensaje('success', 'Función agregada correctamente con sus butacas.');
                } else {
                    setMensaje('error', 'Error al agregar la función.');
                }
            } catch (Exception $e) {
                setMensaje('error', $e->getMessage());
            }
            
            redirigir('admin_funciones');
        }
        
        $peliculas = PeliculaModelo::obtenerTodas();
        $salas = SalaModelo::obtenerTodas();
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/funciones/agregar.php';
    }
    
    public function editarFuncion() {
        AuthControlador::verificarAdmin();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setMensaje('error', 'ID de función no válido.');
            redirigir('admin_funciones');
        }
        
        $funcion = FuncionModelo::obtenerPorId($id);
        if (!$funcion) {
            setMensaje('error', 'Función no encontrada.');
            redirigir('admin_funciones');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_pelicula' => intval($_POST['id_pelicula'] ?? 0),
                'id_sala' => intval($_POST['id_sala'] ?? 0),
                'fecha' => $_POST['fecha'] ?? '',
                'horario' => $_POST['horario'] ?? '',
                'precio' => floatval($_POST['precio'] ?? 0)
            ];
            
            if ($datos['id_pelicula'] <= 0 || $datos['id_sala'] <= 0 || 
                empty($datos['fecha']) || empty($datos['horario']) || 
                $datos['precio'] <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_funciones_editar&id=' . $id);
            }
            
            $resultado = FuncionModelo::actualizar($id, $datos);
            
            if ($resultado) {
                setMensaje('success', 'Función actualizada correctamente.');
            } else {
                setMensaje('error', 'Error al actualizar la función.');
            }
            
            redirigir('admin_funciones');
        }
        
        $peliculas = PeliculaModelo::obtenerTodas();
        $salas = SalaModelo::obtenerTodas();
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/funciones/editar.php';
    }
    
    public function eliminarFuncion() {
        AuthControlador::verificarAdmin();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setMensaje('error', 'ID de función no válido.');
            redirigir('admin_funciones');
        }
        
        $resultado = FuncionModelo::eliminar($id);
        
        if ($resultado) {
            setMensaje('success', 'Función eliminada correctamente.');
        } else {
            setMensaje('error', 'Error al eliminar la función.');
        }
        
        redirigir('admin_funciones');
    }
    
    // ==================== SALAS ====================
    
    public function listarSalas() {
        AuthControlador::verificarAdmin();
        
        $salas = SalaModelo::obtenerTodas();
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/salas/listar.php';
    }
    
    public function agregarSala() {
        AuthControlador::verificarAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = sanitizar($_POST['nombre'] ?? '');
            $capacidad = intval($_POST['capacidad'] ?? 0);
            
            if (empty($nombre) || $capacidad <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_salas_agregar');
            }
            
            $resultado = SalaModelo::crear($nombre, $capacidad);
            
            if ($resultado) {
                setMensaje('success', 'Sala agregada correctamente.');
            } else {
                setMensaje('error', 'Error al agregar la sala.');
            }
            
            redirigir('admin_salas');
        }
        
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/salas/agregar.php';
    }
    
    public function editarSala() {
        AuthControlador::verificarAdmin();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setMensaje('error', 'ID de sala no válido.');
            redirigir('admin_salas');
        }
        
        $sala = SalaModelo::obtenerPorId($id);
        if (!$sala) {
            setMensaje('error', 'Sala no encontrada.');
            redirigir('admin_salas');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = sanitizar($_POST['nombre'] ?? '');
            $capacidad = intval($_POST['capacidad'] ?? 0);
            
            if (empty($nombre) || $capacidad <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_salas_editar&id=' . $id);
            }
            
            $resultado = SalaModelo::actualizar($id, $nombre, $capacidad);
            
            if ($resultado) {
                setMensaje('success', 'Sala actualizada correctamente.');
            } else {
                setMensaje('error', 'Error al actualizar la sala.');
            }
            
            redirigir('admin_salas');
        }
        
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/salas/editar.php';
    }
    
    public function eliminarSala() {
        AuthControlador::verificarAdmin();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setMensaje('error', 'ID de sala no válido.');
            redirigir('admin_salas');
        }
        
        $resultado = SalaModelo::eliminar($id);
        
        if ($resultado) {
            setMensaje('success', 'Sala eliminada correctamente.');
        } else {
            setMensaje('error', 'Error al eliminar la sala.');
        }
        
        redirigir('admin_salas');
    }
    
    // ==================== USUARIOS ====================
    
    public function listarUsuarios() {
        AuthControlador::verificarAdmin();
        
        $usuarios = UsuarioModelo::obtenerTodos();
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/usuarios/listar.php';
    }
    
    public function cambiarRol() {
        AuthControlador::verificarAdmin();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $rol = isset($_GET['rol']) ? $_GET['rol'] : '';
        
        if ($id <= 0 || !in_array($rol, ['usuario', 'admin'])) {
            setMensaje('error', 'Datos no válidos.');
            redirigir('admin_usuarios');
        }
        
        if ($id == $_SESSION['usuario_id']) {
            setMensaje('error', 'No puedes cambiar tu propio rol.');
            redirigir('admin_usuarios');
        }
        
        $resultado = UsuarioModelo::actualizarRol($id, $rol);
        
        if ($resultado) {
            setMensaje('success', 'Rol del usuario actualizado correctamente.');
        } else {
            setMensaje('error', 'Error al actualizar el rol.');
        }
        
        redirigir('admin_usuarios');
    }
    
    public function eliminarUsuario() {
        AuthControlador::verificarAdmin();
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setMensaje('error', 'ID de usuario no válido.');
            redirigir('admin_usuarios');
        }
        
        if ($id == $_SESSION['usuario_id']) {
            setMensaje('error', 'No puedes eliminar tu propia cuenta.');
            redirigir('admin_usuarios');
        }
        
        $resultado = UsuarioModelo::eliminar($id);
        
        if ($resultado) {
            setMensaje('success', 'Usuario eliminado correctamente.');
        } else {
            setMensaje('error', 'Error al eliminar el usuario.');
        }
        
        redirigir('admin_usuarios');
    }
    
    // ==================== COMENTARIOS ====================
    
    public function moderarComentarios() {
        AuthControlador::verificarAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
            
            if ($id <= 0 || !in_array($accion, ['aprobar', 'rechazar', 'eliminar'])) {
                setMensaje('error', 'Acción no válida.');
                redirigir('admin_comentarios');
            }
            
            $resultado = false;
            if ($accion === 'aprobar') {
                $resultado = ComentarioModelo::aprobar($id);
                $mensaje = 'Comentario aprobado.';
            } elseif ($accion === 'rechazar') {
                $resultado = ComentarioModelo::rechazar($id);
                $mensaje = 'Comentario rechazado.';
            } elseif ($accion === 'eliminar') {
                $resultado = ComentarioModelo::eliminar($id);
                $mensaje = 'Comentario eliminado.';
            }
            
            if ($resultado) {
                setMensaje('success', $mensaje);
            } else {
                setMensaje('error', 'Error al procesar el comentario.');
            }
            
            redirigir('admin_comentarios');
        }
        
        $comentarios_pendientes = ComentarioModelo::obtenerPendientes();
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/comentarios/moderar.php';
    }
}
?>