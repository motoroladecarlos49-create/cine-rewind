<?php
require_once __DIR__ . '/ConexionBD.php';

class SalaModelo {
    
    /**
     * Obtiene todas las salas activas
     */
    public static function obtenerTodas() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->query("SELECT * FROM salas WHERE activo = 1 ORDER BY id_sala");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene una sala por su ID
     */
    public static function obtenerPorId($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM salas WHERE id_sala = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Crea una nueva sala
     */
    public static function crear($nombre, $capacidad) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("INSERT INTO salas (nombre, capacidad) VALUES (?, ?)");
        return $stmt->execute([$nombre, $capacidad]);
    }
    
    /**
     * Actualiza una sala
     */
    public static function actualizar($id, $nombre, $capacidad) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE salas SET nombre = ?, capacidad = ? WHERE id_sala = ? AND activo = 1");
        return $stmt->execute([$nombre, $capacidad, $id]);
    }
    
    /**
     * Elimina una sala (borrado lógico)
     */
    public static function eliminar($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE salas SET activo = 0 WHERE id_sala = ?");
        return $stmt->execute([$id]);
    }
}
?>