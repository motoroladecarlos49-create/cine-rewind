<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Películas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    	<?php require_once __DIR__ . '/../../shared/navbar.php'; ?>

    <div class="admin-section">
        <h1> Gestionar Películas</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>?ruta=admin_peliculas_agregar" class="btn-add">+ Agregar Película</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Póster</th>
                    <th>Título</th>
                    <th>Año</th>
                    <th>Género</th>
                    <th>Destacada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($peliculas as $pelicula): ?>
                    <tr>
                        <td><?php echo $pelicula['id_pelicula']; ?></td>
                        <td><img src="<?php echo BASE_URL; ?>imagenes/<?php echo $pelicula['posterurl']; ?>" alt="<?php echo $pelicula['titulo']; ?>" style="width:50px;height:70px;object-fit:cover;"></td>
                        <td><?php echo $pelicula['titulo']; ?></td>
                        <td><?php echo $pelicula['anio_estreno']; ?></td>
                        <td><?php echo $pelicula['genero']; ?></td>
                        <td><?php echo $pelicula['destacada'] ? '⭐ Sí' : 'No'; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_peliculas_editar&id=<?php echo $pelicula['id_pelicula']; ?>" class="btn-edit">Editar</a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_peliculas_eliminar&id=<?php echo $pelicula['id_pelicula']; ?>" class="btn-delete" onclick="return confirm('¿Eliminar esta película?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>