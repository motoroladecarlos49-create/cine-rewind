<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Salas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
	<?php require_once __DIR__ . '/../../shared/navbar.php'; ?>

    <div class="admin-section">
        <h1> Gestionar Salas</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>?ruta=admin_salas_agregar" class="btn-add">+ Agregar Sala</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Capacidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salas as $sala): ?>
                    <tr>
                        <td><?php echo $sala['id_sala']; ?></td>
                        <td><?php echo $sala['nombre']; ?></td>
                        <td><?php echo $sala['capacidad']; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_salas_editar&id=<?php echo $sala['id_sala']; ?>" class="btn-edit">Editar</a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_salas_eliminar&id=<?php echo $sala['id_sala']; ?>" class="btn-delete" onclick="return confirm('¿Eliminar esta sala?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>