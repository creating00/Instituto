-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2024 a las 21:17:38
-- Versión del servidor: 10.1.16-MariaDB
-- Versión de PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `instituto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `idalumno` int(11) NOT NULL,
  `dni` int(8) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido` varchar(150) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `celular` varchar(50) NOT NULL,
  `email` varchar(250) NOT NULL,
  `tutor` varchar(200) NOT NULL,
  `contacto` varchar(50) NOT NULL,
  `idsede` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`idalumno`, `dni`, `nombre`, `apellido`, `direccion`, `celular`, `email`, `tutor`, `contacto`, `idsede`, `estado`) VALUES
(3, 12345678, 'JONATHAN', 'GONZALEZ', 'la merced', '32644165', 'joni8del11@gmail.com', 'mabel', '38413131', 6, 1),
(4, 78945612, 'PEPE', 'ALVARADO', 'las colinas', '388845121', 'pepe@gmail.com', 'pedro', '38887451', 6, 1),
(5, 44445698, 'TOMAS', 'LOPEZ', 'la merced', '4546111', 'tomas@gmail.com', 'jorge', '161314646', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'CAMBRIDGE', '3888604355', 'creatingint@gmail.com', 'san pedro de jujuy');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotas`
--

CREATE TABLE `cuotas` (
  `idcuotas` int(11) NOT NULL,
  `idinscripcion` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `cuota` int(11) NOT NULL,
  `mes` varchar(20) NOT NULL,
  `año` int(11) NOT NULL,
  `importe` float NOT NULL,
  `interes` float NOT NULL,
  `total` float NOT NULL,
  `condicion` varchar(100) NOT NULL,
  `mediodepago` varchar(250) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idexamen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cuotas`
--

INSERT INTO `cuotas` (`idcuotas`, `idinscripcion`, `fecha`, `cuota`, `mes`, `año`, `importe`, `interes`, `total`, `condicion`, `mediodepago`, `idusuario`, `idexamen`) VALUES
(1, 1, '2024-05-29', 1, 'Mayo', 2024, 8000, 0, 8000, 'PAGADO', 'Efectivo', 1, 0),
(2, 1, '0000-00-00', 2, 'Junio', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(3, 1, '0000-00-00', 3, 'Julio', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(4, 1, '0000-00-00', 4, 'Agosto', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(5, 1, '0000-00-00', 5, 'Septiembre', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(6, 2, '0000-00-00', 1, 'Mayo', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(7, 2, '0000-00-00', 2, 'Junio', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(8, 2, '0000-00-00', 3, 'Julio', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(9, 2, '0000-00-00', 4, 'Agosto', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(10, 2, '0000-00-00', 5, 'Septiembre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(11, 2, '0000-00-00', 6, 'Octubre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(12, 3, '0000-00-00', 1, 'Mayo', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(13, 3, '0000-00-00', 2, 'Junio', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(14, 3, '0000-00-00', 3, 'Julio', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(15, 3, '0000-00-00', 4, 'Agosto', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(16, 3, '0000-00-00', 5, 'Septiembre', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(17, 4, '0000-00-00', 1, 'Junio', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(18, 4, '0000-00-00', 2, 'Julio', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(19, 4, '0000-00-00', 3, 'Agosto', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(20, 4, '0000-00-00', 4, 'Septiembre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(21, 4, '0000-00-00', 5, 'Octubre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(22, 4, '0000-00-00', 6, 'Noviembre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(23, 4, '0000-00-00', 1, 'Junio', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(24, 4, '0000-00-00', 2, 'Julio', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(25, 4, '0000-00-00', 3, 'Agosto', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(26, 4, '0000-00-00', 4, 'Septiembre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(27, 4, '0000-00-00', 5, 'Octubre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(28, 4, '0000-00-00', 6, 'Noviembre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(29, 5, '0000-00-00', 1, 'Junio', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(30, 5, '0000-00-00', 2, 'Julio', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(31, 5, '0000-00-00', 3, 'Agosto', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(32, 5, '0000-00-00', 4, 'Septiembre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(33, 5, '0000-00-00', 5, 'Octubre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(34, 5, '0000-00-00', 6, 'Noviembre', 2024, 2500, 0, 0, 'IMPAGA', '', 1, 0),
(40, 7, '0000-00-00', 1, 'Junio', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(41, 7, '0000-00-00', 2, 'Julio', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(42, 7, '0000-00-00', 3, 'Agosto', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(43, 7, '0000-00-00', 4, 'Septiembre', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0),
(44, 7, '0000-00-00', 5, 'Octubre', 2024, 8000, 0, 0, 'IMPAGA', '', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `idcurso` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `precio` float NOT NULL,
  `duracion` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `idsede` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`idcurso`, `nombre`, `precio`, `duracion`, `estado`, `tipo`, `idsede`) VALUES
(6, 'INGLES BASICO', 2500, 6, 1, '6 meses', 6),
(7, 'INFORMATICA', 4000, 12, 0, '12 meses', 7),
(8, 'INGLES I', 8000, 5, 1, '5 meses', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_permisos`
--

CREATE TABLE `detalle_permisos` (
  `id` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_permisos`
--

INSERT INTO `detalle_permisos` (`id`, `id_permiso`, `id_usuario`) VALUES
(14, 1, 1),
(15, 2, 1),
(16, 3, 1),
(17, 4, 1),
(18, 5, 1),
(19, 7, 1),
(20, 8, 1),
(21, 9, 1),
(22, 10, 1),
(23, 11, 1),
(24, 12, 1),
(25, 13, 1),
(26, 14, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen`
--

CREATE TABLE `examen` (
  `idexamen` int(11) NOT NULL,
  `idinscripcion` int(11) NOT NULL,
  `interes` float NOT NULL,
  `total` float NOT NULL,
  `fecha` date NOT NULL,
  `mediodepago` varchar(200) NOT NULL,
  `sede` varchar(100) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idcuota` int(11) NOT NULL,
  `mes` varchar(50) NOT NULL,
  `año` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `examen`
--

INSERT INTO `examen` (`idexamen`, `idinscripcion`, `interes`, `total`, `fecha`, `mediodepago`, `sede`, `idusuario`, `idcuota`, `mes`, `año`) VALUES
(1, 40, 0, 2500, '2022-03-09', 'Efectivo', 'GENERAL', 31, 227, 'Junio', 2022),
(2, 41, 0, 2500, '2022-03-09', 'Efectivo', 'SEDE CENTRAL SALTA', 31, 229, 'Abril', 2022);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `idinscripcion` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idalumno` int(11) NOT NULL,
  `idcurso` int(11) NOT NULL,
  `idsala` int(11) NOT NULL,
  `idprofesor` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fechacomienzo` date NOT NULL,
  `importe` float NOT NULL,
  `mediodepago` varchar(100) NOT NULL,
  `idsede` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `mes` varchar(20) NOT NULL,
  `año` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`idinscripcion`, `idusuario`, `idalumno`, `idcurso`, `idsala`, `idprofesor`, `fecha`, `fechacomienzo`, `importe`, `mediodepago`, `idsede`, `estado`, `mes`, `año`) VALUES
(1, 1, 3, 8, 11, 3, '2024-05-29 23:31:20', '2024-05-29', 25000, 'efectivo', 6, 0, 'Mayo', 2024),
(2, 1, 4, 6, 11, 3, '2024-05-29 23:46:40', '2024-05-29', 35000, 'efectivo', 6, 0, 'Mayo', 2024),
(3, 1, 4, 8, 11, 3, '2024-05-30 00:02:53', '2024-05-29', 40000, 'efectivo', 6, 0, 'Mayo', 2024),
(4, 1, 3, 6, 11, 3, '2024-06-19 19:04:57', '2024-06-19', 4500, 'efectivo', 6, 0, 'Junio', 2024),
(5, 1, 5, 6, 11, 3, '2024-06-19 19:08:17', '2024-06-19', 8500, 'efectivo', 6, 0, 'Junio', 2024),
(7, 1, 5, 8, 11, 3, '2024-06-19 19:10:17', '2024-06-19', 6844, 'efectivo', 6, 0, 'Junio', 2024);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`) VALUES
(1, 'configuración'),
(2, 'usuarios'),
(3, 'Ganancias'),
(4, 'gastos'),
(5, 'estadisticas'),
(7, 'alumno'),
(8, 'profesor'),
(9, 'curso'),
(10, 'inscripcion'),
(11, 'sedes'),
(12, 'salas'),
(13, 'cobrar'),
(14, 'CobranzasUsuarios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `idprofesor` int(11) NOT NULL,
  `dni` int(8) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `celular` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `idsede` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`idprofesor`, `dni`, `nombre`, `apellido`, `direccion`, `celular`, `email`, `idsede`, `estado`) VALUES
(3, 39587462, 'BARBARA', 'PEREZ', 'LO ANGELES', '32644165', 'baby@gmail.com', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala`
--

CREATE TABLE `sala` (
  `idsala` int(11) NOT NULL,
  `sala` varchar(20) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sala`
--

INSERT INTO `sala` (`idsala`, `sala`, `descripcion`, `estado`) VALUES
(11, 'SALA "A"', 'AULA 1', 1),
(12, 'SALA "B"', 'AULA 2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `idsede` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `provincia` varchar(200) NOT NULL,
  `ciudad` varchar(200) NOT NULL,
  `direccion` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL,
  `telefono` varchar(60) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`idsede`, `nombre`, `provincia`, `ciudad`, `direccion`, `email`, `telefono`, `estado`) VALUES
(6, 'GENERAL', 'JUJUY', 'general', 'general', 'fliasoft@gmail.com', '604355', 1),
(7, 'SEDE CENTRAL SALTA', 'SALTA', 'los nogales', 'las colinas', 'salta@gmail.com', '388845136203', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '1',
  `idsede` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `estado`, `idsede`) VALUES
(1, 'fliasoft', 'fliasoft@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 6);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`idalumno`),
  ADD KEY `idsede` (`idsede`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD PRIMARY KEY (`idcuotas`),
  ADD KEY `idinscripcion` (`idinscripcion`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`idcurso`),
  ADD KEY `idsede` (`idsede`);

--
-- Indices de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `examen`
--
ALTER TABLE `examen`
  ADD PRIMARY KEY (`idexamen`),
  ADD KEY `idalumno` (`idinscripcion`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`idinscripcion`),
  ADD KEY `idusuario` (`idusuario`),
  ADD KEY `idalumno` (`idalumno`),
  ADD KEY `idcurso` (`idcurso`),
  ADD KEY `idsala` (`idsala`),
  ADD KEY `idprofesor` (`idprofesor`),
  ADD KEY `idsede` (`idsede`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`idprofesor`),
  ADD KEY `idsede` (`idsede`);

--
-- Indices de la tabla `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`idsala`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`idsede`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `idsede` (`idsede`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `idalumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  MODIFY `idcuotas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `idcurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de la tabla `examen`
--
ALTER TABLE `examen`
  MODIFY `idexamen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `idinscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `idprofesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `sala`
--
ALTER TABLE `sala`
  MODIFY `idsala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `idsede` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`idsede`) REFERENCES `sedes` (`idsede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD CONSTRAINT `cuotas_ibfk_1` FOREIGN KEY (`idinscripcion`) REFERENCES `inscripcion` (`idinscripcion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`idsede`) REFERENCES `sedes` (`idsede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idsede`) REFERENCES `sedes` (`idsede`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
