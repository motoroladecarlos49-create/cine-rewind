<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar - <?php echo SITE_NAME; ?></title>
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

    <div class="reservar-container">
        <h1> Reservar Entradas</h1>
        <h2><?php echo $pelicula['titulo']; ?></h2>
        <p><?php echo $pelicula['descripcion']; ?></p>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($funciones)): ?>
            <p class="no-funciones">No hay funciones disponibles para esta película.</p>
        <?php else: ?>
            <div class="funciones-container">
                <h3>Selecciona una función:</h3>
                <div class="funciones-grid">
                    <?php foreach ($funciones as $funcion): ?>
                        <div class="funcion-card">
                            <p><strong> Fecha:</strong> <?php echo formatearFecha($funcion['fecha'], 'd/m/Y'); ?></p>
                            <p><strong> Hora:</strong> <?php echo substr($funcion['horario'], 0, 5); ?></p>
                            <p><strong> Sala:</strong> <?php echo $funcion['sala_nombre']; ?></p>
                            <p><strong> Precio:</strong> $<?php echo number_format($funcion['precio'], 2); ?></p>
                            <a href="<?php echo BASE_URL; ?>?ruta=entrada&funcion=<?php echo $funcion['id_funcion']; ?>" class="btn-reservar">Seleccionar butaca</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>