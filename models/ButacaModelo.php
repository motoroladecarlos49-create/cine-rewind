<?php
require_once __DIR__ . '/ConexionBD.php';

class ButacaModelo {
    
    /**
     * Obtiene todas las butacas de una función
     */
    public static function obtenerPorFuncion($id_funcion) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM butacas WHERE id_funcion = ? AND activo = 1 ORDER BY numero_butaca");
        $stmt->execute([$id_funcion]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene butacas disponibles de una función
     */
    public static function obtenerDisponibles($id_funcion) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM butacas 
                              WHERE id_funcion = ? AND estado = 'disponible' AND activo = 1 
                              ORDER BY numero_butaca");
        $stmt->execute([$id_funcion]);
        return $stmt->fetchAll();
    }
    
    /**
     * Cuenta las butacas disponibles de una función
     */
    public static function contarDisponibles($id_funcion) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT COUNT(*) as disponibles 
                              FROM butacas 
                              WHERE id_funcion = ? AND estado = 'disponible' AND activo = 1");
        $stmt->execute([$id_funcion]);
        $resultado = $stmt->fetch();
        return $resultado['disponibles'];
    }
    
    /**
     * Reserva una butaca
     */
    public static function reservar($id_butaca) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE butacas SET estado = 'reservada' WHERE id_butaca = ? AND estado = 'disponible'");
        return $stmt->execute([$id_butaca]);
    }
    
    /**
     * Confirma una reserva (cambia a ocupada)
     */
    public static function confirmar($id_butaca) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE butacas SET estado = 'ocupada' WHERE id_butaca = ?");
        return $stmt->execute([$id_butaca]);
    }
    
    /**
     * Libera una butaca (cancela reserva)
     */
    public static function liberar($id_butaca) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE butacas SET estado = 'disponible' WHERE id_butaca = ?");
        return $stmt->execute([$id_butaca]);
    }
    
    /**
     * Obtiene una butaca por su ID
     */
    public static function obtenerPorId($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM butacas WHERE id_butaca = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>