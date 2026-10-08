<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Butaca - <?php echo SITE_NAME; ?></title>
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
                <?php if (esAdmin()): ?>
                    <li><a href="<?php echo BASE_URL; ?>?ruta=admin">Panel Admin</a></li>
                <?php endif; ?>
            </ul>
            <div class="button">
                <a href="<?php echo BASE_URL; ?>?ruta=perfil">
                    <p><?php echo $_SESSION['usuario_nombre']; ?></p>
                </a>
            </div>
        </nav>
    </header>

    <div class="entrada-container">
        <h1> Seleccionar Butaca</h1>
        <h2><?php echo $funcion['pelicula_titulo']; ?></h2>
        <p><strong> Fecha:</strong> <?php echo formatearFecha($funcion['fecha'], 'd/m/Y'); ?></p>
        <p><strong> Hora:</strong> <?php echo substr($funcion['horario'], 0, 5); ?></p>
        <p><strong> Sala:</strong> <?php echo $funcion['sala_nombre']; ?></p>
        <p><strong> Precio:</strong> $<?php echo number_format($funcion['precio'], 2); ?></p>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($butacas_disponibles)): ?>
            <p class="no-butacas">No hay butacas disponibles para esta función.</p>
        <?php else: ?>
            <div class="butacas-grid">
                <?php foreach ($butacas_disponibles as $butaca): ?>
                    <form action="<?php echo BASE_URL; ?>?ruta=procesar_compra" method="POST" class="butaca-form">
                        <input type="hidden" name="id_funcion" value="<?php echo $funcion['id_funcion']; ?>">
                        <input type="hidden" name="id_butaca" value="<?php echo $butaca['id_butaca']; ?>">
                        <button type="submit" class="butaca-btn">
                            <?php echo $butaca['numero_butaca']; ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>