<?php
require_once __DIR__ . '/ConexionBD.php';

class PeliculaModelo {
    
    /**
     * Obtiene todas las películas activas
     */
    public static function obtenerTodas($limit = null, $offset = null) {
        $db = ConexionBD::getInstancia()->getConexion();
        $sql = "SELECT * FROM peliculas WHERE activo = 1 ORDER BY id_pelicula DESC";
        
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
     * Obtiene películas destacadas (para el carrusel)
     */
    public static function obtenerDestacadas($limit = 5) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM peliculas WHERE activo = 1 AND destacada = 1 ORDER BY RAND() LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene una película por su ID
     */
    public static function obtenerPorId($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM peliculas WHERE id_pelicula = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtiene películas por década
     */
    public static function obtenerPorDecada($decada) {
        $db = ConexionBD::getInstancia()->getConexion();
        $anio_inicio = intval($decada);
        $anio_fin = $anio_inicio + 9;
        
        $stmt = $db->prepare("SELECT * FROM peliculas 
                              WHERE activo = 1 
                              AND anio_estreno BETWEEN ? AND ? 
                              ORDER BY anio_estreno DESC");
        $stmt->execute([$anio_inicio, $anio_fin]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene películas por género
     */
    public static function obtenerPorGenero($genero) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM peliculas WHERE activo = 1 AND genero LIKE ?");
        $stmt->execute(['%' . $genero . '%']);
        return $stmt->fetchAll();
    }
    
    /**
     * Busca películas por título
     */
    public static function buscar($termino) {
        $db = ConexionBD::getInstancia()->getConexion();
        $termino = '%' . $termino . '%';
        $stmt = $db->prepare("SELECT * FROM peliculas 
                              WHERE activo = 1 
                              AND (titulo LIKE ? OR descripcion LIKE ?)");
        $stmt->execute([$termino, $termino]);
        return $stmt->fetchAll();
    }
    
    /**
     * Crea una nueva película
     */
    public static function crear($datos) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("INSERT INTO peliculas 
                              (titulo, descripcion, duracion, genero, clasificacion, 
                               anio_estreno, posterurl, trailerurl, destacada) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $datos['titulo'],
            $datos['descripcion'],
            $datos['duracion'],
            $datos['genero'],
            $datos['clasificacion'],
            $datos['anio_estreno'],
            $datos['posterurl'] ?? '',
            $datos['trailerurl'] ?? '',
            $datos['destacada'] ?? 0
        ]);
    }
    
    /**
     * Actualiza una película
     */
    public static function actualizar($id, $datos) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE peliculas SET 
                              titulo = ?, descripcion = ?, duracion = ?, 
                              genero = ?, clasificacion = ?, anio_estreno = ?, 
                              posterurl = ?, trailerurl = ?, destacada = ?
                              WHERE id_pelicula = ? AND activo = 1");
        
        return $stmt->execute([
            $datos['titulo'],
            $datos['descripcion'],
            $datos['duracion'],
            $datos['genero'],
            $datos['clasificacion'],
            $datos['anio_estreno'],
            $datos['posterurl'] ?? '',
            $datos['trailerurl'] ?? '',
            $datos['destacada'] ?? 0,
            $id
        ]);
    }
    
    /**
     * Elimina una película (borrado lógico)
     */
    public static function eliminar($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE peliculas SET activo = 0 WHERE id_pelicula = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Cuenta el total de películas
     */
    public static function contarTodas() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->query("SELECT COUNT(*) as total FROM peliculas WHERE activo = 1");
        $resultado = $stmt->fetch();
        return $resultado['total'];
    }
    /**
    * Búsqueda avanzada con filtros combinables
    */
    public static function buscarConFiltros($filtros = []) {
        $db = ConexionBD::getInstancia()->getConexion();

        $sql = "SELECT * FROM peliculas WHERE activo = 1";
        $params = [];

        if (!empty($filtros['q'])) {
            $sql .= " AND (titulo LIKE ? OR descripcion LIKE ?)";
            $params[] = '%' . $filtros['q'] . '%';
            $params[] = '%' . $filtros['q'] . '%';
        }

        if (!empty($filtros['genero'])) {
            $sql .= " AND genero LIKE ?";
            $params[] = '%' . $filtros['genero'] . '%';
        }

        if (!empty($filtros['anio'])) {
            $sql .= " AND anio_estreno = ?";
            $params[] = intval($filtros['anio']);
        }

        if (!empty($filtros['decada'])) {
            $inicio = intval($filtros['decada']);
            $sql .= " AND anio_estreno BETWEEN ? AND ?";
            $params[] = $inicio;
            $params[] = $inicio + 9;
        }

        $sql .= " ORDER BY titulo ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
    * Obtiene los géneros únicos para el filtro
    */
    public static function obtenerGeneros() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->query("SELECT DISTINCT genero FROM peliculas 
                            WHERE activo = 1 ORDER BY genero");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>