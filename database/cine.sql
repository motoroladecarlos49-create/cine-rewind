-- phpMyAdmin SQL Dump
-- Base de datos: `cine_rewind`
-- Versión: 1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Base de datos: `cine_rewind`
--
CREATE DATABASE IF NOT EXISTS `cine_rewind` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cine_rewind`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario',
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `id_pelicula` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `duracion` int(11) NOT NULL,
  `genero` varchar(50) NOT NULL,
  `clasificacion` varchar(10) NOT NULL,
  `anio_estreno` int(11) NOT NULL,
  `posterurl` varchar(255) NOT NULL,
  `trailerurl` varchar(255) DEFAULT NULL,
  `puntaje` decimal(3,1) DEFAULT NULL,
  `destacada` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_pelicula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id_sala` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_sala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones`
--

CREATE TABLE `funciones` (
  `id_funcion` int(11) NOT NULL AUTO_INCREMENT,
  `id_pelicula` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `horario` time NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_funcion`),
  FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas`(`id_pelicula`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_sala`) REFERENCES `salas`(`id_sala`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `butacas`
--

CREATE TABLE `butacas` (
  `id_butaca` int(11) NOT NULL AUTO_INCREMENT,
  `id_funcion` int(11) NOT NULL,
  `numero_butaca` varchar(10) NOT NULL,
  `estado` enum('disponible','reservada','ocupada') NOT NULL DEFAULT 'disponible',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_butaca`),
  FOREIGN KEY (`id_funcion`) REFERENCES `funciones`(`id_funcion`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `fechacompra` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `monto_total` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_venta`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuario`(`id_usuario`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `id_detalleventa` int(11) NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) NOT NULL,
  `id_funcion` int(11) NOT NULL,
  `id_butaca` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `estado` enum('confirmada','cancelada') NOT NULL DEFAULT 'confirmada',
  PRIMARY KEY (`id_detalleventa`),
  FOREIGN KEY (`id_venta`) REFERENCES `ventas`(`id_venta`) ON DELETE CASCADE,
  FOREIGN KEY (`id_funcion`) REFERENCES `funciones`(`id_funcion`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_butaca`) REFERENCES `butacas`(`id_butaca`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_pelicula` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `puntaje` tinyint(1) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_comentario`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuario`(`id_usuario`) ON DELETE CASCADE,
  FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas`(`id_pelicula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Actualización de la base de datos para Retro Rewind
USE `cine_rewind`;

-- Código único de reserva en ventas
ALTER TABLE `ventas`
  ADD COLUMN `codigo_reserva` VARCHAR(20) NOT NULL UNIQUE AFTER `id_usuario`;

-- Cantidad de entradas por venta
ALTER TABLE `ventas`
  ADD COLUMN `cantidad_entradas` INT(11) NOT NULL DEFAULT 1 AFTER `codigo_reserva`;

-- Estado de la venta
ALTER TABLE `ventas`
  ADD COLUMN `estado` ENUM('pendiente','confirmada','cancelada') NOT NULL DEFAULT 'pendiente' AFTER `monto_total`;

-- Índices para búsquedas
ALTER TABLE `peliculas` ADD INDEX `idx_titulo` (`titulo`);
ALTER TABLE `peliculas` ADD INDEX `idx_genero` (`genero`);
ALTER TABLE `peliculas` ADD INDEX `idx_anio` (`anio_estreno`);

-- --------------------------------------------------------

--
-- DATOS DE PRUEBA
--

-- Usuario administrador
INSERT INTO `usuario` (`nombre`, `email`, `password`, `rol`, `activo`) VALUES
('Administrador', 'admin@cine.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);
-- Contraseña: admin123

-- Usuarios de prueba
INSERT INTO `usuario` (`nombre`, `email`, `password`, `rol`, `activo`) VALUES
('Juan Pérez', 'juan@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 1),
('María García', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 1),
('Carlos López', 'carlos@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 1);
-- Contraseña para todos: admin123

-- Películas
INSERT INTO `peliculas` (`titulo`, `descripcion`, `duracion`, `genero`, `clasificacion`, `anio_estreno`, `posterurl`, `puntaje`, `destacada`) VALUES
('The Breakfast Club', 'Cinco estudiantes muy diferentes son castigados un sábado en la biblioteca. Descubren que tienen más en común de lo que pensaban, rompiendo estereotipos sociales.', 97, 'Comedia Dramática', 'R', 1985, 'breakfastclub.jpg', 4.5, 1),
('Breakfast at Tiffany\'s', 'Basada en la novela de Truman Capote, es una comedia romántica icónica sobre Holly Golightly, una excéntrica y encantadora joven neoyorquina que busca casarse con un millonario.', 115, 'Comedia Romántica', 'PG', 1961, 'breakfast_at_tifannys.webp', 4.2, 1),
('Girl, Interrupted', 'Susanna Kaysen, una joven de 18 años, es ingresada en 1967 a un hospital psiquiátrico tras un intento de suicidio. Allí, conviviendo con un grupo de jóvenes problemáticas, busca entender su trastorno límite de la personalidad.', 127, 'Drama', 'R', 1999, 'GirlInterrupted.jpg', 4.0, 1),
('The Godfather', 'Una saga familiar sobre la mafia italiana en Nueva York. Don Vito Corleone, el jefe de la familia, debe enfrentar amenazas mientras su hijo Michael se involucra en los negocios familiares.', 175, 'Crimen', 'R', 1972, 'thegodfather.jpg', 4.9, 1),
('A Clockwork Orange', 'En un futuro distópico, Alex y su pandilla cometen actos de violencia extrema. Tras ser arrestado, se somete a un experimento de condicionamiento para curar su agresividad.', 136, 'Ciencia Ficción', 'R', 1971, 'orangemecanique.jpg', 4.3, 1);

-- Salas
INSERT INTO `salas` (`nombre`, `capacidad`) VALUES
('Sala 1 - Retro', 80),
('Sala 2 - Clásicos', 60),
('Sala 3 - Premiere', 100);

-- Funciones (para los próximos días)
INSERT INTO `funciones` (`id_pelicula`, `id_sala`, `fecha`, `horario`, `precio`) VALUES
(1, 1, CURDATE() + INTERVAL 1 DAY, '18:00:00', 500.00),
(1, 1, CURDATE() + INTERVAL 1 DAY, '21:00:00', 600.00),
(2, 2, CURDATE() + INTERVAL 2 DAY, '18:00:00', 450.00),
(2, 2, CURDATE() + INTERVAL 2 DAY, '20:30:00', 550.00),
(3, 3, CURDATE() + INTERVAL 3 DAY, '19:00:00', 500.00),
(4, 1, CURDATE() + INTERVAL 4 DAY, '20:00:00', 650.00),
(5, 2, CURDATE() + INTERVAL 5 DAY, '21:00:00', 550.00);

-- Generar butacas para cada función
INSERT INTO `butacas` (`id_funcion`, `numero_butaca`, `estado`)
SELECT 
    f.id_funcion,
    CONCAT('Fila ', CHAR(64 + ((t.n - 1) DIV 10) + 1), ' - Asiento ', LPAD(((t.n - 1) % 10) + 1, 2, '0')),
    'disponible'
FROM funciones f
CROSS JOIN (
    SELECT 1 AS n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION
    SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
) t
WHERE t.n <= (SELECT capacidad FROM salas WHERE id_sala = f.id_sala);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

