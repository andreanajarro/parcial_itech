-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-06-2026 a las 15:03:22
-- Versión del servidor: 8.4.7
-- Versión de PHP: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parcial_itech`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas_interes`
--

DROP TABLE IF EXISTS `areas_interes`;
CREATE TABLE IF NOT EXISTS `areas_interes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `areas_interes`
--

INSERT INTO `areas_interes` (`id`, `nombre`) VALUES
(6, 'Big Data'),
(8, 'Blockchain'),
(3, 'Ciberseguridad'),
(5, 'Cloud Computing'),
(4, 'Desarrollo Movil'),
(1, 'Desarrollo Web'),
(9, 'DevOps'),
(2, 'Inteligencia Artificial'),
(7, 'IoT (Internet de las Cosas)'),
(10, 'Machine Learning');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscriptores`
--

DROP TABLE IF EXISTS `inscriptores`;
CREATE TABLE IF NOT EXISTS `inscriptores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `edad` int NOT NULL,
  `sexo` enum('Masculino','Femenino','Otro') NOT NULL,
  `pais_residencia_id` int NOT NULL,
  `nacionalidad_id` int NOT NULL,
  `correo` varchar(150) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `observaciones` text,
  `hash_verificacion` varchar(255) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_inscriptores_pais` (`pais_residencia_id`),
  KEY `fk_inscriptores_nacionalidad` (`nacionalidad_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `inscriptores`
--

INSERT INTO `inscriptores` (`id`, `nombre`, `apellido`, `edad`, `sexo`, `pais_residencia_id`, `nacionalidad_id`, `correo`, `celular`, `observaciones`, `hash_verificacion`, `fecha_registro`) VALUES
(3, 'Andrea', 'Najarro', 22, 'Femenino', 1, 11, 'andynaja2004@gmail.com', '64032519', '', '5bc0a5244a85eef3ee299e54b60746a7b703397c88fac9501b6952911f094e4b', '2026-06-30 14:36:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscriptor_temas`
--

DROP TABLE IF EXISTS `inscriptor_temas`;
CREATE TABLE IF NOT EXISTS `inscriptor_temas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inscriptor_id` int NOT NULL,
  `area_interes_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_temas_inscriptor` (`inscriptor_id`),
  KEY `fk_temas_area` (`area_interes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `inscriptor_temas`
--

INSERT INTO `inscriptor_temas` (`id`, `inscriptor_id`, `area_interes_id`) VALUES
(9, 3, 1),
(10, 3, 2),
(11, 3, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

DROP TABLE IF EXISTS `paises`;
CREATE TABLE IF NOT EXISTS `paises` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `paises`
--

INSERT INTO `paises` (`id`, `nombre`) VALUES
(7, 'Argentina'),
(8, 'Chile'),
(2, 'Colombia'),
(3, 'Costa Rica'),
(11, 'El Salvador'),
(6, 'Espana'),
(5, 'Estados Unidos'),
(4, 'Mexico'),
(1, 'Panama'),
(9, 'Peru'),
(10, 'Venezuela');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inscriptores`
--
ALTER TABLE `inscriptores`
  ADD CONSTRAINT `fk_inscriptores_nacionalidad` FOREIGN KEY (`nacionalidad_id`) REFERENCES `paises` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscriptores_pais` FOREIGN KEY (`pais_residencia_id`) REFERENCES `paises` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscriptor_temas`
--
ALTER TABLE `inscriptor_temas`
  ADD CONSTRAINT `fk_temas_area` FOREIGN KEY (`area_interes_id`) REFERENCES `areas_interes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_temas_inscriptor` FOREIGN KEY (`inscriptor_id`) REFERENCES `inscriptores` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
