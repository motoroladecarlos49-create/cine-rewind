<?php
require_once __DIR__ . '/ConexionBD.php';
require_once __DIR__ . '/SalaModelo.php';

class FuncionModelo {
    
    /**
     * Obtiene todas las funciones activas con detalles de película y sala
     */
    public static function obtenerTodas($limit = null, $offset = null) {
        $db = ConexionBD::getInstancia()->getConexion();
        $sql = "SELECT f.*, p.titulo as pelicula_titulo, p.posterurl, s.nombre as sala_nombre 
                FROM funciones f
                JOIN peliculas p ON f.id_pelicula = p.id_pelicula
                JOIN salas s ON f.id_sala = s.id_sala
                WHERE f.activo = 1 AND p.activo = 1 AND s.activo = 1
                ORDER BY f.fecha ASC, f.horario ASC";
        
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
     * Obtiene funciones por película
     */
    public static function obtenerPorPelicula($id_pelicula) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT f.*, s.nombre as sala_nombre 
                              FROM funciones f
                              JOIN salas s ON f.id_sala = s.id_sala
                              WHERE f.id_pelicula = ? AND f.activo = 1 AND s.activo = 1
                              ORDER BY f.fecha ASC, f.horario ASC");
        $stmt->execute([$id_pelicula]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene funciones próximas (desde hoy en adelante)
     */
    public static function obtenerProximas($limit = 10) {
        $db = ConexionBD::getInstancia()->getConexion();
        $hoy = date('Y-m-d');
        $stmt = $db->prepare("SELECT f.*, p.titulo as pelicula_titulo, p.posterurl, s.nombre as sala_nombre 
                              FROM funciones f
                              JOIN peliculas p ON f.id_pelicula = p.id_pelicula
                              JOIN salas s ON f.id_sala = s.id_sala
                              WHERE f.activo = 1 AND p.activo = 1 AND s.activo = 1
                              AND f.fecha >= ?
                              ORDER BY f.fecha ASC, f.horario ASC
                              LIMIT ?");
        $stmt->execute([$hoy, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene una función por su ID
     */
    public static function obtenerPorId($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT f.*, p.titulo as pelicula_titulo, p.posterurl, s.nombre as sala_nombre, s.capacidad
                              FROM funciones f
                              JOIN peliculas p ON f.id_pelicula = p.id_pelicula
                              JOIN salas s ON f.id_sala = s.id_sala
                              WHERE f.id_funcion = ? AND f.activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Crea una nueva función
     */
    public static function crear($datos) {
        $db = ConexionBD::getInstancia()->getConexion();
        
        // Iniciar transacción
        $db->beginTransaction();
        
        try {
            // Insertar función
            $stmt = $db->prepare("INSERT INTO funciones 
                                  (id_pelicula, id_sala, fecha, horario, precio) 
                                  VALUES (?, ?, ?, ?, ?)");
            $resultado = $stmt->execute([
                $datos['id_pelicula'],
                $datos['id_sala'],
                $datos['fecha'],
                $datos['horario'],
                $datos['precio']
            ]);
            
            if ($resultado) {
                $id_funcion = $db->lastInsertId();
                
                // Generar butacas para esta función
                $sala = SalaModelo::obtenerPorId($datos['id_sala']);
                $capacidad = $sala['capacidad'];
                
                for ($i = 1; $i <= $capacidad; $i++) {
                    $fila = chr(65 + (($i - 1) / 10));
                    $asiento = str_pad((($i - 1) % 10) + 1, 2, '0', STR_PAD_LEFT);
                    $numero = "Fila " . $fila . " - Asiento " . $asiento;
                    
                    $stmtButaca = $db->prepare("INSERT INTO butacas (id_funcion, numero_butaca) VALUES (?, ?)");
                    $stmtButaca->execute([$id_funcion, $numero]);
                }
            }
            
            $db->commit();
            return $resultado;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Actualiza una función
     */
    public static function actualizar($id, $datos) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE funciones SET 
                              id_pelicula = ?, id_sala = ?, fecha = ?, 
                              horario = ?, precio = ?
                              WHERE id_funcion = ? AND activo = 1");
        return $stmt->execute([
            $datos['id_pelicula'],
            $datos['id_sala'],
            $datos['fecha'],
            $datos['horario'],
            $datos['precio'],
            $id
        ]);
    }
    
    /**
     * Elimina una función (borrado lógico)
     */
    public static function eliminar($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE funciones SET activo = 0 WHERE id_funcion = ?");
        return $stmt->execute([$id]);
    }
}
?>