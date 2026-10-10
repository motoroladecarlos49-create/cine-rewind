<?php
require_once __DIR__ . '/ConexionBD.php';
require_once __DIR__ . '/ButacaModelo.php';
require_once __DIR__ . '/FuncionModelo.php';

class ReservaModelo {
    
    /**
     * Crea una reserva con MÚLTIPLES butacas y código único
     */
    public static function crear($id_usuario, $id_funcion, array $ids_butacas) {
        $db = ConexionBD::getInstancia()->getConexion();

        if (empty($ids_butacas)) {
            throw new Exception("No seleccionaste ninguna butaca.");
        }

        $db->beginTransaction();

        try {
            $funcion = FuncionModelo::obtenerPorId($id_funcion);
            if (!$funcion) {
                throw new Exception("Función no encontrada");
            }

            // Validar TODAS las butacas antes de tocar nada
            foreach ($ids_butacas as $id_butaca) {
                $butaca = ButacaModelo::validarDisponibilidad($id_butaca, $id_funcion);
                if (!$butaca) {
                    throw new Exception("La butaca seleccionada ya no está disponible.");
                }
            }

            $cantidad = count($ids_butacas);
            $monto_total = $funcion['precio'] * $cantidad;
            $codigo_reserva = self::generarCodigoReserva();

            // Crear venta
            $stmt = $db->prepare("INSERT INTO ventas 
                (id_usuario, codigo_reserva, cantidad_entradas, monto_total, estado) 
                VALUES (?, ?, ?, ?, 'confirmada')");
            $stmt->execute([
                $id_usuario,
                $codigo_reserva,
                $cantidad,
                $monto_total
            ]);

            $id_venta = $db->lastInsertId();

            // Crear detalles + reservar butacas
            $stmtDetalle = $db->prepare("INSERT INTO detalleventa 
                (id_venta, id_funcion, id_butaca, precio_unitario) 
                VALUES (?, ?, ?, ?)");

            foreach ($ids_butacas as $id_butaca) {
                $stmtDetalle->execute([
                    $id_venta,
                    $id_funcion,
                    $id_butaca,
                    $funcion['precio']
                ]);
                ButacaModelo::confirmar($id_butaca);
            }

            $db->commit();
            return ['id_venta' => $id_venta, 'codigo' => $codigo_reserva];

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Genera un código único tipo RR-2025-XXXX
     */
    private static function generarCodigoReserva() {
        return 'RR-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }
    
    /**
     * Confirma una venta (cambia butaca a ocupada)
     */
    public static function confirmar($id_venta) {
        $db = ConexionBD::getInstancia()->getConexion();
        
        $db->beginTransaction();
        
        try {
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
            $stmt = $db->prepare("SELECT id_butaca FROM detalleventa WHERE id_venta = ?");
            $stmt->execute([$id_venta]);
            $detalles = $stmt->fetchAll();
            
            foreach ($detalles as $detalle) {
                ButacaModelo::liberar($detalle['id_butaca']);
            }
            
            $stmt = $db->prepare("UPDATE detalleventa SET estado = 'cancelada' WHERE id_venta = ?");
            $stmt->execute([$id_venta]);
            
            $stmt = $db->prepare("UPDATE ventas SET estado = 'cancelada' WHERE id_venta = ?");
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
            SELECT v.id_venta, v.codigo_reserva, v.cantidad_entradas, 
                   v.monto_total, v.fechacompra, v.estado,
                   GROUP_CONCAT(b.numero_butaca SEPARATOR ', ') as butacas,
                   p.titulo as pelicula_titulo,
                   f.fecha, f.horario,
                   s.nombre as sala_nombre
            FROM ventas v
            JOIN detalleventa dv ON v.id_venta = dv.id_venta
            JOIN funciones f ON dv.id_funcion = f.id_funcion
            JOIN peliculas p ON f.id_pelicula = p.id_pelicula
            JOIN salas s ON f.id_sala = s.id_sala
            JOIN butacas b ON dv.id_butaca = b.id_butaca
            WHERE v.id_usuario = ? AND v.activo = 1
            GROUP BY v.id_venta
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
        
        $stmt = $db->query("SELECT COUNT(*) as total_ventas, SUM(monto_total) as total_recaudado 
                           FROM ventas WHERE activo = 1 AND estado = 'confirmada'");
        $estadisticas = $stmt->fetch();
        
        $stmt = $db->query("
            SELECT p.titulo, COUNT(DISTINCT v.id_venta) as cantidad, SUM(v.monto_total) as recaudado
            FROM ventas v
            JOIN detalleventa dv ON v.id_venta = dv.id_venta
            JOIN funciones f ON dv.id_funcion = f.id_funcion
            JOIN peliculas p ON f.id_pelicula = p.id_pelicula
            WHERE v.activo = 1 AND v.estado = 'confirmada'
            GROUP BY p.id_pelicula
            ORDER BY cantidad DESC
            LIMIT 5
        ");
        $estadisticas['top_peliculas'] = $stmt->fetchAll();
        
        $stmt = $db->query("
            SELECT DATE(fechacompra) as fecha, COUNT(*) as cantidad, SUM(monto_total) as recaudado
            FROM ventas
            WHERE activo = 1 AND estado = 'confirmada' 
            AND fechacompra >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(fechacompra)
            ORDER BY fecha DESC
        ");
        $estadisticas['ventas_por_dia'] = $stmt->fetchAll();
        
        return $estadisticas;
    }
}
?>