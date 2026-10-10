<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
   	<?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <div class="historial-container">
        <h1>Mi Historial de Compras</h1>

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
                            <th>Código</th>
                            <th>Película</th>
                            <th>Sala</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Butacas</th>
                            <th>Entradas</th>
                            <th>Total</th>
                            <th>Compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $compra): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($compra['codigo_reserva']); ?></strong></td>
                                <td><?php echo htmlspecialchars($compra['pelicula_titulo']); ?></td>
                                <td><?php echo htmlspecialchars($compra['sala_nombre']); ?></td>
                                <td><?php echo formatearFecha($compra['fecha'], 'd/m/Y'); ?></td>
                                <td><?php echo substr($compra['horario'], 0, 5); ?></td>
                                <td><?php echo htmlspecialchars($compra['butacas']); ?></td>
                                <td><?php echo $compra['cantidad_entradas']; ?></td>
                                <td>$<?php echo number_format($compra['monto_total'], 2); ?></td>
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