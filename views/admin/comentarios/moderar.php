<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <h1> Moderar Comentarios</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($comentarios_pendientes)): ?>
            <p class="no-comentarios"> No hay comentarios pendientes de moderación.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Película</th>
                        <th>Puntaje</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comentarios_pendientes as $comentario): ?>
                        <tr>
                            <td><?php echo $comentario['usuario_nombre']; ?></td>
                            <td><?php echo $comentario['pelicula_titulo']; ?></td>
                            <td>⭐ <?php echo $comentario['puntaje']; ?>/5</td>
                            <td><?php echo $comentario['comentario']; ?></td>
                            <td><?php echo formatearFecha($comentario['fecha']); ?></td>
                            <td>
                                <form action="<?php echo BASE_URL; ?>?ruta=admin_comentarios" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $comentario['id_comentario']; ?>">
                                    <input type="hidden" name="accion" value="aprobar">
                                    <button type="submit" class="btn-approve"> Aprobar</button>
                                </form>
                                <form action="<?php echo BASE_URL; ?>?ruta=admin_comentarios" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $comentario['id_comentario']; ?>">
                                    <input type="hidden" name="accion" value="rechazar">
                                    <button type="submit" class="btn-reject"> Rechazar</button>
                                </form>
                                <form action="<?php echo BASE_URL; ?>?ruta=admin_comentarios" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $comentario['id_comentario']; ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar este comentario?')"> Eliminar</button>
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