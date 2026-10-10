<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <div class="admin-dashboard">
        <h1> Panel de Administración</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3> Películas</h3>
                <p class="stat-number"><?php echo $total_peliculas; ?></p>
            </div>
            <div class="stat-card">
                <h3> Usuarios</h3>
                <p class="stat-number"><?php echo $total_usuarios; ?></p>
            </div>
            <div class="stat-card">
                <h3> Recaudado</h3>
                <p class="stat-number">$<?php echo number_format($estadisticas_ventas['total_recaudado'] ?? 0, 2); ?></p>
            </div>
            <div class="stat-card">
                <h3> Ventas</h3>
                <p class="stat-number"><?php echo $estadisticas_ventas['total_ventas'] ?? 0; ?></p>
            </div>
            <div class="stat-card">
                <h3> Comentarios Pendientes</h3>
                <p class="stat-number"><?php echo count($comentarios_pendientes); ?></p>
            </div>
        </div>

        <!-- Funciones Próximas -->
        <div class="admin-section">
            <h2> Funciones Próximas</h2>
            <?php if (empty($funciones_proximas)): ?>
                <p>No hay funciones programadas.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Película</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Sala</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($funciones_proximas as $funcion): ?>
                            <tr>
                                <td><?php echo $funcion['pelicula_titulo']; ?></td>
                                <td><?php echo formatearFecha($funcion['fecha'], 'd/m/Y'); ?></td>
                                <td><?php echo substr($funcion['horario'], 0, 5); ?></td>
                                <td><?php echo $funcion['sala_nombre']; ?></td>
                                <td>$<?php echo number_format($funcion['precio'], 2); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>?ruta=admin_funciones_editar&id=<?php echo $funcion['id_funcion']; ?>" class="btn-edit">Editar</a>
                                    <a href="<?php echo BASE_URL; ?>?ruta=admin_funciones_eliminar&id=<?php echo $funcion['id_funcion']; ?>" class="btn-delete" onclick="return confirm('¿Eliminar esta función?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Top Películas -->
        <div class="admin-section">
            <h2> Películas Más Vendidas</h2>
            <?php if (empty($estadisticas_ventas['top_peliculas'])): ?>
                <p>No hay ventas registradas.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Película</th>
                            <th>Ventas</th>
                            <th>Recaudado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estadisticas_ventas['top_peliculas'] as $pelicula): ?>
                            <tr>
                                <td><?php echo $pelicula['titulo']; ?></td>
                                <td><?php echo $pelicula['cantidad']; ?></td>
                                <td>$<?php echo number_format($pelicula['recaudado'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>