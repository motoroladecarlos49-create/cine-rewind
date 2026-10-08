<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera - <?php echo SITE_NAME; ?></title>
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

    <h1 class="page-title"> Cartelera Completa</h1>

    <?php if (isset($mensaje)): ?>
        <div class="mensaje <?php echo $mensaje['tipo']; ?>">
            <?php echo $mensaje['texto']; ?>
        </div>
    <?php endif; ?>

    <section class="conteiner-peliculas">
        <?php foreach ($peliculas as $pelicula): ?>
            <div class="pelicula">
                <img src="<?php echo BASE_URL; ?>imagenes/<?php echo $pelicula['posterurl']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                <div class="info-pelicula">
                    <h3><?php echo $pelicula['titulo']; ?></h3>
                    <p><?php echo truncarTexto($pelicula['descripcion'], 100); ?></p>
                    <span class="anio"><?php echo $pelicula['anio_estreno']; ?></span>
                    <a href="<?php echo BASE_URL; ?>?ruta=reservar&id=<?php echo $pelicula['id_pelicula']; ?>" class="btn-reservar">Reservar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</body>
</html>