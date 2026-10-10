<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Moderar Comentarios - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <header>
        <nav class="navbar">
            <h1><?php echo SITE_NAME; ?></h1>
            <ul>
                <li><a href="<?php echo BASE_URL; ?>?ruta=admin">Dashboard</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=admin_peliculas">Películas</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=admin_funciones">Funciones</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=admin_salas">Salas</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=admin_usuarios">Usuarios</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=admin_comentarios">Comentarios</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=home">Ver sitio</a></li>
            </ul>
            <div class="button">
                <a href="<?php echo BASE_URL; ?>?ruta=logout">
                    <p>Cerrar Sesión</p>
                </a>
            </div>
        </nav>
    </header>

    <div class="admin-section">
        <h1>Moderar Comentarios</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <div class="filtros-admin">
            <a href="?ruta=admin_comentarios" 
               class="btn-g <?php echo !isset($_GET['estado']) ? 'active' : ''; ?>">Todos</a>
            <a href="?ruta=admin_comentarios&estado=pendiente" 
               class="btn-g <?php echo ($_GET['estado'] ?? '') === 'pendiente' ? 'active' : ''; ?>">Pendientes</a>
            <a href="?ruta=admin_comentarios&estado=aprobado" 
               class="btn-g <?php echo ($_GET['estado'] ?? '') === 'aprobado' ? 'active' : ''; ?>">Aprobados</a>
            <a href="?ruta=admin_comentarios&estado=rechazado" 
               class="btn-g <?php echo ($_GET['estado'] ?? '') === 'rechazado' ? 'active' : ''; ?>">Rechazados</a>
        </div>

        <?php if (empty($comentarios)): ?>
            <p class="no-comentarios">No hay comentarios.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Película</th>
                        <th>Puntaje</th>
                        <th>Comentario</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comentarios as $c): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['usuario_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($c['pelicula_titulo']); ?></td>
                            <td><?php echo str_repeat('⭐', $c['puntaje']); ?></td>
                            <td><?php echo htmlspecialchars(truncarTexto($c['comentario'], 80)); ?></td>
                            <td><span class="badge badge-<?php echo $c['estado']; ?>">
                                <?php echo $c['estado']; ?>
                            </span></td>
                            <td><?php echo formatearFecha($c['fecha']); ?></td>
                            <td>
                                <?php if ($c['estado'] === 'pendiente'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $c['id_comentario']; ?>">
                                        <input type="hidden" name="accion" value="aprobar">
                                        <button class="btn-approve">Aprobar</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $c['id_comentario']; ?>">
                                        <input type="hidden" name="accion" value="rechazar">
                                        <button class="btn-reject">Rechazar</button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $c['id_comentario']; ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button class="btn-delete" 
                                            onclick="return confirm('¿Eliminar este comentario?')">🗑</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>