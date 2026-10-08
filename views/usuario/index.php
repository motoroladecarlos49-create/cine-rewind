<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - <?php echo SITE_NAME; ?></title>
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

    <!-- CARRUSEL -->
    <section class="carrusel">
        <div class="carrusel-container">
            <?php foreach ($destacadas as $index => $pelicula): ?>
                <div class="carrusel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo BASE_URL; ?>imagenes/<?php echo $pelicula['posterurl']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                    <div class="carrusel-info">
                        <h2><?php echo $pelicula['titulo']; ?></h2>
                        <p><?php echo truncarTexto($pelicula['descripcion'], 150); ?></p>
                        <span class="anio"><?php echo $pelicula['anio_estreno']; ?></span>
                        <a href="<?php echo BASE_URL; ?>?ruta=reservar&id=<?php echo $pelicula['id_pelicula']; ?>" class="btn-reservar">Reservar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carrusel-btn prev">❮</button>
        <button class="carrusel-btn next">❯</button>
        <div class="carrusel-indicadores">
            <?php for ($i = 0; $i < count($destacadas); $i++): ?>
                <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>"></span>
            <?php endfor; ?>
        </div>
    </section>

    <!-- BOTONES POR DÉCADA -->
    <nav class="barra-superior">
        <h1> Explora por décadas</h1>
        <a href="<?php echo BASE_URL; ?>?ruta=decada&decada=60"><button class="btn-g">60's y 70's</button></a>
        <a href="<?php echo BASE_URL; ?>?ruta=decada&decada=80"><button class="btn-g">80's</button></a>
        <a href="<?php echo BASE_URL; ?>?ruta=decada&decada=90"><button class="btn-g">90's</button></a>
        <a href="<?php echo BASE_URL; ?>?ruta=decada&decada=2000"><button class="btn-g">2000's</button></a>
        <a href="<?php echo BASE_URL; ?>?ruta=decada&decada=2010"><button class="btn-g">2010's</button></a>
    </nav>

    <!-- PELÍCULAS -->
    <section class="conteiner-peliculas">
        <?php foreach ($peliculas as $pelicula): ?>
            <div class="pelicula">
                <img src="<?php echo BASE_URL; ?>imagenes/<?php echo $pelicula['posterurl']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                <div class="info-pelicula">
                    <h3><?php echo $pelicula['titulo']; ?></h3>
                    <p><?php echo truncarTexto($pelicula['descripcion'], 100); ?></p>
                    <a href="<?php echo BASE_URL; ?>?ruta=reservar&id=<?php echo $pelicula['id_pelicula']; ?>" class="btn-reservar">Reservar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <script src="<?php echo BASE_URL; ?>js/validaciones.js"></script>
</body>
</html>