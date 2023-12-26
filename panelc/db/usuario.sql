-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-02-2022 a las 03:49:00
-- Versión del servidor: 10.5.12-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u690371019_pibg`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(240) NOT NULL,
  `apellido_p` varchar(240) DEFAULT NULL,
  `apellido_m` varchar(240) DEFAULT NULL,
  `puesto` varchar(240) DEFAULT NULL,
  `login` varchar(240) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `lugar` varchar(100) DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `area` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `apellido_p`, `apellido_m`, `puesto`, `login`, `clave`, `imagen`, `lugar`, `estatus`, `area`) VALUES
(1, 'Orel', 'Vilchis', 'Martinez', 'Administrador del sistema', 'Admin', 'pgadmin2019', NULL, 'Fabrica', 1, NULL),
(2, 'Ana', 'Guillén', 'Torres', 'Ejecutivo de ventas', 'AnaGV', 'gpez.30112020', NULL, 'Zuno', 1, NULL),
(3, 'Maria Adriana', 'Ponce', 'Rios', 'Ejecutivo de ventas', 'AdrianaV', 'mpga2020', NULL, 'Zuno', 1, NULL),
(4, 'Claudia', 'Elizondo', 'Flores', 'Embarques', 'PGEmbarques', 'cfpg.2020', NULL, 'Fabrica', 1, NULL),
(5, 'Angelina', 'Durán', 'Garibay', 'Calidad', 'AngelinaDC', 'adpg.030620', NULL, 'Fabrica', 1, NULL),
(10, 'Lupita', '', 'Balcárcel', 'Recepción', 'PGRecep', 'gnpg.2020', NULL, 'Fabrica', 1, NULL),
(6, 'AJM', NULL, NULL, 'Distribuidor', 'Userajm', 'ajm.2020', NULL, 'AJM', 1, NULL),
(7, 'El Patrón', NULL, NULL, 'Dirección', 'pgdir1', 'dir1.2020', NULL, 'Fabrica', 1, NULL),
(8, 'Arturo', 'Hernández', NULL, 'Supervisor de producción', 'produccion1', 'pgprod2020', NULL, 'Fabrica', 1, NULL),
(9, 'Juan Carlos', 'Figueroa', NULL, 'Accionista', 'JCFigueroa', 'jcpg.2020', NULL, 'Fabrica', 1, NULL),
(11, 'Leonardo Emmanuel', 'Reyes', 'Garcia', 'Consultor', 'CPro', 'LEpg.2021', NULL, 'Fabrica', 1, NULL),
(12, 'UserAdmin', 'App', 'APp', 'NA', 'UserAdmin', 'User.F.2020', NULL, 'Fabrica', 1, NULL),
(13, 'Ensamble', NULL, NULL, 'NA', 'Ensamble', 'ensamblepg', NULL, 'Fabrica', 1, NULL),
(14, 'Martha', 'Figueroa', NULL, 'Accionista', 'MFigueroa', 'mfpg.2020', NULL, 'Fabrica', 1, NULL),
(15, 'Ensamble', '(Porcelanizado)', NULL, 'Produccion', 'Ensamble1', 'EPpg.20', NULL, 'Fabrica', 1, 5),
(16, 'Ensamble', '(Comercial)', NULL, 'Produccion', 'Ensamble2', 'ECpg.20', NULL, 'Fabrica', 1, 6),
(17, 'Ensamble', '(Muebles)', NULL, 'Produccion', 'Ensamble3', 'EMpg.20', NULL, 'Fabrica', 1, 7),
(18, 'Horno', NULL, NULL, 'Produccion', 'Horno', 'HOpg.20', NULL, 'Fabrica', 1, 8),
(19, 'Herrería', NULL, NULL, 'Produccion', 'Herreria', 'HEpg.20', NULL, 'Fabrica', 1, 1),
(20, 'Plásticos', NULL, NULL, 'Produccion', 'Plasticos', 'PLpg.20', NULL, 'Fabrica', 1, 3),
(21, 'Pintura', NULL, NULL, 'Produccion', 'Pintura', 'PTpg.20', NULL, 'Fabrica', 1, 2),
(22, 'Almacén PT', NULL, NULL, 'Almacenista', 'Almacenpt', 'pgalmacen1510', NULL, 'Fabrica', 1, 0),
(23, 'Josafat', 'Vilchis', 'Martinez', 'Ejecutivo de ventas', 'JsVM', 'vmjs.pg2021', NULL, 'Zuno', 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
