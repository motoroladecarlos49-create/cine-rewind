<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Funciones - <?php echo SITE_NAME; ?></title>
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
        <h1> Gestionar Funciones</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>?ruta=admin_funciones_agregar" class="btn-add">+ Agregar Función</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Película</th>
                    <th>Sala</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($funciones as $funcion): ?>
                    <tr>
                        <td><?php echo $funcion['id_funcion']; ?></td>
                        <td><?php echo $funcion['pelicula_titulo']; ?></td>
                        <td><?php echo $funcion['sala_nombre']; ?></td>
                        <td><?php echo formatearFecha($funcion['fecha'], 'd/m/Y'); ?></td>
                        <td><?php echo substr($funcion['horario'], 0, 5); ?></td>
                        <td>$<?php echo number_format($funcion['precio'], 2); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_funciones_editar&id=<?php echo $funcion['id_funcion']; ?>" class="btn-edit">Editar</a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_funciones_eliminar&id=<?php echo $funcion['id_funcion']; ?>" class="btn-delete" onclick="return confirm('¿Eliminar esta función?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>