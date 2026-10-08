<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - <?php echo SITE_NAME; ?></title>
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

    <div class="contacto-container">
        <h1> ¡Contactate con nosotros!</h1>
        <br><br>
        <h2>¿Queres saber más sobre nuestro cine?<br>
            Buscanos por las redes!</h2>
        <br><br>
        <div class="redes-sociales">
            <p> IG: <a href="#" target="_blank">@retro_rewindcine</a></p>
            <p> FB: <a href="#" target="_blank">/retro_rewindcine</a></p>
            <p> Email: <a href="mailto:info@retrorewind.com">info@retrorewind.com</a></p>
            <p> Dirección: Calle Ficticia 123, Tandil</p>
        </div>
    </div>
</body>
</html>