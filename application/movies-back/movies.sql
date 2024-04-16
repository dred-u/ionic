-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-02-2024 a las 01:20:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `movies`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actores`
--

CREATE TABLE `actores` (
  `id_actor` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `nacionalidad` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_actor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actores`
--

INSERT INTO `actores` (`id_actor`, `nombre`, `fecha_nacimiento`, `nacionalidad`) VALUES
(1, 'Emma Stone', '1988-11-06', 'Estados Unidos'),
(2, 'Heath Ledger', '1979-04-04', 'Australia'),
(3, 'Marlon Brando', '1924-04-03', 'Estados Unidos'),
(4, 'Jeff Goldblum', '1952-10-22', 'Estados Unidos'),
(5, 'Tom Hanks', '1956-07-09', 'Estados Unidos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `directores`
--

CREATE TABLE `directores` (
  `id_director` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `nacionalidad` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_director`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `directores`
--

INSERT INTO `directores` (`id_director`, `nombre`, `fecha_nacimiento`, `nacionalidad`) VALUES
(1, 'Damien Chazelle', '1985-01-19', 'Estados Unidos'),
(2, 'Christopher Nolan', '1970-07-30', 'Reino Unido'),
(3, 'Francis Ford Coppola', '1939-04-07', 'Estados Unidos'),
(4, 'Steven Spielberg', '1946-12-18', 'Estados Unidos'),
(5, 'Robert Zemeckis', '1952-05-14', 'Estados Unidos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_genero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `nombre`) VALUES
(1, 'Acción'),
(2, 'Aventura'),
(3, 'Drama'),
(4, 'Fantasía'),
(5, 'Musical'),
(6, 'Comedia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `id_pelicula` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `anno_estreno` int(11) DEFAULT NULL,
  `duracion_minutos` int(11) DEFAULT NULL,
  `genero_id` int(11) NOT NULL,
  PRIMARY KEY (`id_pelicula`),
  KEY `fk_genero_id` (`genero_id`),
  CONSTRAINT `fk_genero_id` FOREIGN KEY (`genero_id`) REFERENCES `genero` (`id_genero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`id_pelicula`, `titulo`, `anno_estreno`, `duracion_minutos`, `genero_id`) VALUES
(1, 'La La Land', 2016, 128, 5),
(2, 'The Dark Knight', 2008, 152, 1),
(3, 'The Godfather', 1972, 175, 3),
(6, 'Oppenheimer', 2023, 181, 3),
(7, 'La princesa Mononoke', 1997, 134, 1),
(8, 'El niño y la garza', 2023, 124, 4),
(9, 'Poor things', 2023, 141, 4),
(10, 'Nimona', 2023, 98, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productora`
--

CREATE TABLE `productora` (
  `id_productora` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `pais` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_productora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productora`
--

INSERT INTO `productora` (`id_productora`, `nombre`, `pais`) VALUES
(1, 'Warner Bros. Pictures', 'Estados Unidos'),
(2, 'Pixar Animation Studios', 'Estados Unidos'),
(3, 'Studio Ghibli', 'Japón'),
(4, 'Columbia Pictures', 'Estados Unidos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritas`
--

CREATE TABLE `favoritas` (
  `id_favorita` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_pelicula` int(11) NOT NULL,
  `calificacion` int(11) DEFAULT NULL,
  `resena` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_favorita`),
  KEY `fk_usuario_id` (`id_usuario`),
  KEY `fk_pelicula_id` (`id_pelicula`),
  CONSTRAINT `fk_pelicula_id` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`),
  CONSTRAINT `fk_usuario_id` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

