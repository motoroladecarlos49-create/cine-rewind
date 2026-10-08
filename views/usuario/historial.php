<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <header>
        <nav class="navbar">
            <h1><?php echo SITE_NAME; ?></h1>
            <ul>
                <li><a href="<?php echo BASE_URL; ?>?ruta=home">Inicio</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=nosotros">Nosotros</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=cartelera">Cartelera</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=contacto">Contacto</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=reservar">Reservar entradas</a></li>
                <li><a href="<?php echo BASE_URL; ?>?ruta=perfil">Perfil</a></li>
                <?php if (esAdmin()): ?>
                    <li><a href="<?php echo BASE_URL; ?>?ruta=admin">Panel Admin</a></li>
                <?php endif; ?>
            </ul>
            <div class="button">
                <a href="<?php echo BASE_URL; ?>?ruta=logout">
                    <p>Cerrar Sesión</p>
                </a>
            </div>
        </nav>
    </header>

    <div class="historial-container">
        <h1> Mi Historial de Compras</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($historial)): ?>
            <p class="no-historial">No tienes compras registradas.</p>
            <a href="<?php echo BASE_URL; ?>?ruta=cartelera" class="btn-reservar">Explorar Cartelera</a>
        <?php else: ?>
            <div class="historial-table">
                <table>
                    <thead>
                        <tr>
                            <th>Película</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Butaca</th>
                            <th>Precio</th>
                            <th>Compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $compra): ?>
                            <tr>
                                <td><?php echo $compra['pelicula_titulo']; ?></td>
                                <td><?php echo formatearFecha($compra['fecha'], 'd/m/Y'); ?></td>
                                <td><?php echo substr($compra['horario'], 0, 5); ?></td>
                                <td><?php echo $compra['numero_butaca']; ?></td>
                                <td>$<?php echo number_format($compra['precio_unitario'], 2); ?></td>
                                <td><?php echo formatearFecha($compra['fechacompra']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>