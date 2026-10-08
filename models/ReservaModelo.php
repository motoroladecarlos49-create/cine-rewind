<?php
require_once __DIR__ . '/ConexionBD.php';
require_once __DIR__ . '/ButacaModelo.php';
require_once __DIR__ . '/FuncionModelo.php';

class ReservaModelo {
    
    /**
     * Crea una nueva reserva (venta + detalle)
     */
    public static function crear($id_usuario, $id_funcion, $id_butaca) {
        $db = ConexionBD::getInstancia()->getConexion();
        
        // Iniciar transacción
        $db->beginTransaction();
        
        try {
            // Obtener información de la función
            $funcion = FuncionModelo::obtenerPorId($id_funcion);
            if (!$funcion) {
                throw new Exception("Función no encontrada");
            }
            
            // Verificar que la butaca esté disponible
            $butaca = ButacaModelo::obtenerPorId($id_butaca);
            if (!$butaca || $butaca['estado'] !== 'disponible') {
                throw new Exception("Butaca no disponible");
            }
            
            // Crear la venta
            $stmt = $db->prepare("INSERT INTO ventas (id_usuario, monto_total) VALUES (?, ?)");
            $resultado = $stmt->execute([$id_usuario, $funcion['precio']]);
            
            if (!$resultado) {
                throw new Exception("Error al crear la venta");
            }
            
            $id_venta = $db->lastInsertId();
            
            // Crear el detalle de la venta
            $stmt = $db->prepare("INSERT INTO detalleventa 
                                  (id_venta, id_funcion, id_butaca, precio_unitario) 
                                  VALUES (?, ?, ?, ?)");
            $resultado = $stmt->execute([
                $id_venta,
                $id_funcion,
                $id_butaca,
                $funcion['precio']
            ]);
            
            if (!$resultado) {
                throw new Exception("Error al crear el detalle de la venta");
            }
            
            // Reservar la butaca
            $resultado = ButacaModelo::reservar($id_butaca);
            if (!$resultado) {
                throw new Exception("Error al reservar la butaca");
            }
            
            $db->commit();
            return $id_venta;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Confirma una venta (cambia butaca a ocupada)
     */
    public static function confirmar($id_venta) {
        $db = ConexionBD::getInstancia()->getConexion();
        
        $db->beginTransaction();
        
        try {
            // Obtener los detalles de la venta
            $stmt = $db->prepare("SELECT id_butaca FROM detalleventa WHERE id_venta = ?");
            $stmt->execute([$id_venta]);
            $detalles = $stmt->fetchAll();
            
            foreach ($detalles as $detalle) {
                ButacaModelo::confirmar($detalle['id_butaca']);
            }
            
            $db->commit();
            return true;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Cancela una venta
     */
    public static function cancelar($id_venta) {
        $db = ConexionBD::getInstancia()->getConexion();
        
        $db->beginTransaction();
        
        try {
            // Obtener los detalles de la venta
            $stmt = $db->prepare("SELECT id_butaca FROM detalleventa WHERE id_venta = ?");
            $stmt->execute([$id_venta]);
            $detalles = $stmt->fetchAll();
            
            foreach ($detalles as $detalle) {
                ButacaModelo::liberar($detalle['id_butaca']);
            }
            
            // Marcar la venta como cancelada
            $stmt = $db->prepare("UPDATE detalleventa SET estado = 'cancelada' WHERE id_venta = ?");
            $stmt->execute([$id_venta]);
            
            $db->commit();
            return true;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Obtiene el historial de compras de un usuario
     */
    public static function obtenerHistorial($id_usuario) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("
            SELECT v.*, 
                   p.titulo as pelicula_titulo, 
                   f.fecha, f.horario,
                   dv.id_butaca,
                   b.numero_butaca,
                   dv.precio_unitario
            FROM ventas v
            JOIN detalleventa dv ON v.id_venta = dv.id_venta
            JOIN funciones f ON dv.id_funcion = f.id_funcion
            JOIN peliculas p ON f.id_pelicula = p.id_pelicula
            JOIN butacas b ON dv.id_butaca = b.id_butaca
            WHERE v.id_usuario = ? AND v.activo = 1
            ORDER BY v.fechacompra DESC
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene estadísticas de ventas (para admin)
     */
    public static function obtenerEstadisticas() {
        $db = ConexionBD::getInstancia()->getConexion();
        
        // Total de ventas
        $stmt = $db->query("SELECT COUNT(*) as total_ventas, SUM(monto_total) as total_recaudado 
                           FROM ventas WHERE activo = 1");
        $estadisticas = $stmt->fetch();
        
        // Ventas por película
        $stmt = $db->query("
            SELECT p.titulo, COUNT(v.id_venta) as cantidad, SUM(v.monto_total) as recaudado
            FROM ventas v
            JOIN detalleventa dv ON v.id_venta = dv.id_venta
            JOIN funciones f ON dv.id_funcion = f.id_funcion
            JOIN peliculas p ON f.id_pelicula = p.id_pelicula
            WHERE v.activo = 1
            GROUP BY p.id_pelicula
            ORDER BY cantidad DESC
            LIMIT 5
        ");
        $estadisticas['top_peliculas'] = $stmt->fetchAll();
        
        // Ventas por día (últimos 7 días)
        $stmt = $db->query("
            SELECT DATE(fechacompra) as fecha, COUNT(*) as cantidad, SUM(monto_total) as recaudado
            FROM ventas
            WHERE activo = 1 AND fechacompra >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(fechacompra)
            ORDER BY fecha DESC
        ");
        $estadisticas['ventas_por_dia'] = $stmt->fetchAll();
        
        return $estadisticas;
    }
}
?>