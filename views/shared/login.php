<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?php echo SITE_NAME; ?></title>
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
            </ul>
            <div class="button">
                <a href="<?php echo BASE_URL; ?>?ruta=login">
                    <p>Iniciar Sesión</p>
                </a>
            </div>
        </nav>
    </header>

    <div class="login-container">
        <h2> Iniciar Sesión</h2>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>?ruta=login_post" method="POST">
            <div class="input-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required placeholder="tú@ejemplo.com" autofocus>
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="register-link">
            ¿No tienes cuenta? <a href="<?php echo BASE_URL; ?>?ruta=registro">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>