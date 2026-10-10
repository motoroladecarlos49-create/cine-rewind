<?php
require_once __DIR__ . '/ConexionBD.php';

class ComentarioModelo {
    
    /**
     * Obtiene comentarios de una película (aprobados)
     */
    public static function obtenerPorPelicula($id_pelicula) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("
            SELECT c.*, u.nombre as usuario_nombre
            FROM comentarios c
            JOIN usuario u ON c.id_usuario = u.id_usuario
            WHERE c.id_pelicula = ? AND c.estado = 'aprobado' AND c.activo = 1
            ORDER BY c.fecha DESC
        ");
        $stmt->execute([$id_pelicula]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene comentarios pendientes (para admin)
     */
    public static function obtenerPendientes() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("
            SELECT c.*, u.nombre as usuario_nombre, p.titulo as pelicula_titulo
            FROM comentarios c
            JOIN usuario u ON c.id_usuario = u.id_usuario
            JOIN peliculas p ON c.id_pelicula = p.id_pelicula
            WHERE c.estado = 'pendiente' AND c.activo = 1
            ORDER BY c.fecha ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Crea un nuevo comentario
     */
    public static function crear($id_usuario, $id_pelicula, $comentario, $puntaje) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("INSERT INTO comentarios 
                              (id_usuario, id_pelicula, comentario, puntaje) 
                              VALUES (?, ?, ?, ?)");
        return $stmt->execute([$id_usuario, $id_pelicula, $comentario, $puntaje]);
    }
    
    /**
     * Aprueba un comentario
     */
    public static function aprobar($id_comentario) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE comentarios SET estado = 'aprobado' WHERE id_comentario = ?");
        return $stmt->execute([$id_comentario]);
    }
    
    /**
     * Rechaza un comentario
     */
    public static function rechazar($id_comentario) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE comentarios SET estado = 'rechazado' WHERE id_comentario = ?");
        return $stmt->execute([$id_comentario]);
    }
    
    /**
     * Elimina un comentario (borrado lógico)
     */
    public static function eliminar($id_comentario) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE comentarios SET activo = 0 WHERE id_comentario = ?");
        return $stmt->execute([$id_comentario]);
    }
    /**
    * Verifica si el usuario ya comentó esta película
    */
    public static function yaComento($id_usuario, $id_pelicula) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT id_comentario FROM comentarios 
                            WHERE id_usuario = ? AND id_pelicula = ? 
                            AND activo = 1");
        $stmt->execute([$id_usuario, $id_pelicula]);
        return $stmt->fetch() !== false;
    }

    /**
    * Obtiene TODOS los comentarios (para admin, con filtro de estado)
    */
    public static function obtenerTodos($estado = null) {
        $db = ConexionBD::getInstancia()->getConexion();
        $sql = "SELECT c.*, u.nombre as usuario_nombre, p.titulo as pelicula_titulo
                FROM comentarios c
                JOIN usuario u ON c.id_usuario = u.id_usuario
                JOIN peliculas p ON c.id_pelicula = p.id_pelicula
                WHERE c.activo = 1";
        $params = [];

        if ($estado && in_array($estado, ['pendiente','aprobado','rechazado'])) {
            $sql .= " AND c.estado = ?";
            $params[] = $estado;
        }

        $sql .= " ORDER BY c.fecha DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
    * Obtiene el promedio de puntaje de una película
    */
    public static function obtenerPromedio($id_pelicula) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT AVG(puntaje) as promedio, COUNT(*) as total 
                            FROM comentarios 
                            WHERE id_pelicula = ? AND estado = 'aprobado' AND activo = 1");
        $stmt->execute([$id_pelicula]);
        return $stmt->fetch();
    }
}
?>