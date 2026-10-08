<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros - <?php echo SITE_NAME; ?></title>
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
                <?php if (estaLogueado()): ?>
                    <li><a href="<?php echo BASE_URL; ?>?ruta=reservar">Reservar entradas</a></li>
                <?php endif; ?>
            </ul>
            <div class="button">
                <?php if (estaLogueado()): ?>
                    <a href="<?php echo BASE_URL; ?>?ruta=perfil">
                        <p><?php echo $_SESSION['usuario_nombre']; ?></p>
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>?ruta=login">
                        <p>Iniciar Sesión</p>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="contenedor-nosotros">
        <h1> ¿Quiénes Somos?</h1>
        <h2>
            En un mundo de streaming y pantallas pequeñas, Retro Rewind nació de un deseo simple: 
            recuperar la magia de las luces apagándose en una sala llena.<br><br>
            Somos más que un cine; somos una cápsula del tiempo.<br><br>
            Nos dedicamos a proyectar esos clásicos que definieron generaciones, 
            desde el grano del cine negro hasta la explosión de color de los años 90.<br><br>
            Aquí, el cine no se ve, se vive. Rebobinamos el reloj para que 
            puedas disfrutar de tus películas favoritas tal como fueron concebidas: 
            en pantalla grande y en comunidad.
        </h2>
    </div>
</body>
</html>