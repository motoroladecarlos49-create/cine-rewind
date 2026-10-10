<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Películas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
	<?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <div class="buscar-container">
        <h1> Buscar Películas</h1>

        <form method="GET" action="<?php echo BASE_URL; ?>">
            <input type="hidden" name="ruta" value="buscar">
            <div class="filtros-grid">
                <div class="input-group">
                    <label>Nombre o descripción</label>
                    <input type="text" name="q" 
                           value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
                           placeholder="Ej: Godfather...">
                </div>
                <div class="input-group">
                    <label>Género</label>
                    <select name="genero">
                        <option value="">Todos</option>
                        <?php foreach ($generos as $g): ?>
                            <option value="<?php echo htmlspecialchars($g); ?>"
                                <?php echo ($_GET['genero'] ?? '') === $g ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($g); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Año</label>
                    <input type="number" name="anio" min="1900" max="2030"
                           value="<?php echo intval($_GET['anio'] ?? 0) ?: ''; ?>">
                </div>
                <div class="input-group">
                    <label>Década</label>
                    <select name="decada">
                        <option value="">Todas</option>
                        <?php foreach ([1960,1970,1980,1990,2000,2010] as $d): ?>
                            <option value="<?php echo $d; ?>"
                                <?php echo intval($_GET['decada'] ?? 0) === $d ? 'selected' : ''; ?>>
                                <?php echo $d; ?>'s
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-login">Buscar</button>
            <a href="<?php echo BASE_URL; ?>?ruta=buscar" class="btn-cancel">Limpiar</a>
        </form>

        <h2 style="margin-top:30px;">Resultados (<?php echo count($peliculas); ?>)</h2>

        <?php if (empty($peliculas)): ?>
            <p class="no-peliculas">No se encontraron películas con esos filtros.</p>
        <?php else: ?>
            <div class="conteiner-peliculas">
                <?php foreach ($peliculas as $p): ?>
                    <div class="pelicula">
                        <img src="<?php echo BASE_URL; ?>imagenes/<?php echo htmlspecialchars($p['posterurl']); ?>" 
                             alt="<?php echo htmlspecialchars($p['titulo']); ?>">
                        <div class="info-pelicula">
                            <h3><?php echo htmlspecialchars($p['titulo']); ?></h3>
                            <span class="anio"><?php echo $p['anio_estreno']; ?></span>
                            <a href="<?php echo BASE_URL; ?>?ruta=detalle&id=<?php echo $p['id_pelicula']; ?>" 
                               class="btn-reservar">Ver detalle</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>