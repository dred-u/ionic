-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-01-2024 a las 20:20:33
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
  `nacionalidad` varchar(50) DEFAULT NULL
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
  `nacionalidad` varchar(50) DEFAULT NULL
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
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `id_pelicula` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `anno_estreno` int(11) DEFAULT NULL,
  `duracion_minutos` int(11) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`id_pelicula`, `titulo`, `anno_estreno`, `duracion_minutos`, `genero`) VALUES
(1, 'La La Land', 2016, 128, 'Musical'),
(2, 'The Dark Knight', 2008, 152, 'Acción'),
(3, 'The Godfather', 1972, 175, 'Drama'),
(4, 'Jurassic Park', 1993, 127, 'Aventura'),
(5, 'Forrest Gump', 1994, 142, 'Drama'),
(6, 'Oppenheimer', 2023, 181, 'Drama'),
(7, 'La princesa Mononoke', 1997, 134, 'Accion'),
(8, 'El niño y la garza', 2023, 124, 'Fantasia/Aventura'),
(9, 'Poor things', 2023, 141, 'Fantasia/Drama');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actores`
--
ALTER TABLE `actores`
  ADD PRIMARY KEY (`id_actor`);

--
-- Indices de la tabla `directores`
--
ALTER TABLE `directores`
  ADD PRIMARY KEY (`id_director`);

--
-- Indices de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD PRIMARY KEY (`id_pelicula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  MODIFY `id_pelicula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
