<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
	<?php require_once __DIR__ . '/../../shared/navbar.php'; ?>

    <div class="admin-section">
        <h1> Gestionar Usuarios</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['email']; ?></td>
                        <td>
                            <span class="badge <?php echo $usuario['rol'] == 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                                <?php echo $usuario['rol']; ?>
                            </span>
                        </td>
                        <td><?php echo formatearFecha($usuario['fecha_registro']); ?></td>
                        <td>
                            <?php if ($usuario['id_usuario'] != $_SESSION['usuario_id']): ?>
                                <?php if ($usuario['rol'] == 'usuario'): ?>
                                    <a href="<?php echo BASE_URL; ?>?ruta=admin_usuarios_cambiar_rol&id=<?php echo $usuario['id_usuario']; ?>&rol=admin" class="btn-edit">Hacer Admin</a>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL; ?>?ruta=admin_usuarios_cambiar_rol&id=<?php echo $usuario['id_usuario']; ?>&rol=usuario" class="btn-warning">Quitar Admin</a>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>?ruta=admin_usuarios_eliminar&id=<?php echo $usuario['id_usuario']; ?>" class="btn-delete" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                            <?php else: ?>
                                <span class="badge badge-you">Tú</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>