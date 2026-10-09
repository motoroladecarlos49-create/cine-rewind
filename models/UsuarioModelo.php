<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/ConexionBD.php';

class UsuarioModelo {
    
    /**
     * Obtiene un usuario por su email
     */
    public static function obtenerPorEmail($email) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE email = ? AND activo = 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    /**
     * Obtiene un usuario por su ID
     */
    public static function obtenerPorId($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE id_usuario = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Crea un nuevo usuario
     */
    public static function crear($nombre, $email, $password) {
        $db = ConexionBD::getInstancia()->getConexion();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO usuario (nombre, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $email, $hash]);
    }
    
    /**
     * Verifica las credenciales de un usuario
     */
    public static function verificarCredenciales($email, $password) {
        $usuario = self::obtenerPorEmail($email);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }
    
    /**
     * Obtiene todos los usuarios (para admin)
     */
    public static function obtenerTodos($limit = null, $offset = null) {
        $db = ConexionBD::getInstancia()->getConexion();
        $sql = "SELECT id_usuario, nombre, email, rol, fecha_registro, activo 
                FROM usuario 
                WHERE activo = 1 
                ORDER BY id_usuario DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT " . intval($limit);
            if ($offset !== null) {
                $sql .= " OFFSET " . intval($offset);
            }
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Actualiza el rol de un usuario
     */
    public static function actualizarRol($id_usuario, $rol) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE usuario SET rol = ? WHERE id_usuario = ? AND activo = 1");
        return $stmt->execute([$rol, $id_usuario]);
    }
    
    /**
     * Elimina un usuario (borrado lógico)
     */
    public static function eliminar($id_usuario) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE usuario SET activo = 0 WHERE id_usuario = ?");
        return $stmt->execute([$id_usuario]);
    }
    
    /**
     * Actualiza el perfil de un usuario
     */
    public static function actualizarPerfil($id_usuario, $nombre, $email) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE usuario SET nombre = ?, email = ? WHERE id_usuario = ? AND activo = 1");
        return $stmt->execute([$nombre, $email, $id_usuario]);
    }
    
    /**
     * Cambia la contraseña de un usuario
     */
    public static function cambiarPassword($id_usuario, $nueva_password) {
        $db = ConexionBD::getInstancia()->getConexion();
        $hash = password_hash($nueva_password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE usuario SET password = ? WHERE id_usuario = ? AND activo = 1");
        return $stmt->execute([$hash, $id_usuario]);
    }
    
    /**
     * Cuenta el total de usuarios
     */
    public static function contarTodos() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->query("SELECT COUNT(*) as total FROM usuario WHERE activo = 1");
        $resultado = $stmt->fetch();
        return $resultado['total'];
    }
    /**
 * Muestra la cartelera con búsqueda y filtros
 */
public function buscar() {
    AuthControlador::verificarAutenticacion();

    $filtros = [
        'q'      => sanitizar($_GET['q'] ?? ''),
        'genero' => sanitizar($_GET['genero'] ?? ''),
        'anio'   => intval($_GET['anio'] ?? 0),
        'decada' => intval($_GET['decada'] ?? 0)
    ];

    $peliculas = PeliculaModelo::buscarConFiltros($filtros);
    $generos = PeliculaModelo::obtenerGeneros();
    $mensaje = getMensaje();

    require_once __DIR__ . '/../views/usuario/buscar.php';
}

    /**
    * Muestra el detalle de una película con sus comentarios
    */
    public function detallePelicula() {
        AuthControlador::verificarAutenticacion();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            redirigir('cartelera');
        }

        $pelicula = PeliculaModelo::obtenerPorId($id);
        if (!$pelicula) {
            setMensaje('error', 'Película no encontrada.');
            redirigir('cartelera');
        }

        $comentarios = ComentarioModelo::obtenerPorPelicula($id);
        $promedio = ComentarioModelo::obtenerPromedio($id);
        $yaComento = ComentarioModelo::yaComento($_SESSION['usuario_id'], $id);
        $mensaje = getMensaje();

        require_once __DIR__ . '/../views/usuario/detalle.php';
    }

    /**
    * Procesa el envío de un comentario
    */
    public function comentar() {
        AuthControlador::verificarAutenticacion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('cartelera');
        }

        $id_pelicula = intval($_POST['id_pelicula'] ?? 0);
        $comentario  = sanitizar($_POST['comentario'] ?? '');
        $puntaje     = intval($_POST['puntaje'] ?? 0);

        if ($id_pelicula <= 0 || empty($comentario) || $puntaje < 1 || $puntaje > 5) {
            setMensaje('error', 'Comentario inválido. Puntaje entre 1 y 5.');
            redirigir('detalle&id=' . $id_pelicula);
        }

        if (ComentarioModelo::yaComento($_SESSION['usuario_id'], $id_pelicula)) {
            setMensaje('error', 'Ya comentaste esta película.');
            redirigir('detalle&id=' . $id_pelicula);
        }

        $ok = ComentarioModelo::crear(
            $_SESSION['usuario_id'],
            $id_pelicula,
            $comentario,
            $puntaje
        );

        if ($ok) {
            setMensaje('success', '¡Comentario enviado! Será revisado por un administrador.');
        } else {
            setMensaje('error', 'Error al enviar el comentario.');
        }

        redirigir('detalle&id=' . $id_pelicula);
    }
}
?>