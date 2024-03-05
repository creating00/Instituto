-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-02-2024 a las 18:29:13
-- Versión del servidor: 10.1.16-MariaDB
-- Versión de PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `eduser`
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
(4, 78945612, 'PEPE', 'ALVARADO', 'las colinas', '388845121', 'pepe@gmail.com', 'pedro', '38887451', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
(1, 'Creating', '3888604355', 'creatingint@gmail.com', 'san pedro de jujuy');

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
(224, 40, '2022-03-09', 1, 'Marzo', 2022, 2500, 0, 2500, 'PAGADO', 'Efectivo', 31, 0),
(225, 40, '2024-02-29', 2, 'Abril', 2022, 2500, 0, 2500, 'PAGADO', 'Efectivo', 31, 1),
(226, 40, '2024-02-29', 3, 'Mayo', 2022, 2500, 0, 2500, 'PAGADO', 'Efectivo', 31, 0),
(227, 40, '0000-00-00', 4, 'Junio', 2022, 2500, 0, 0, 'IMPAGA', '', 31, 1),
(228, 41, '2022-03-10', 1, 'Marzo', 2022, 4000, 0, 4000, 'PAGADO', 'Efectivo', 31, 0),
(229, 41, '0000-00-00', 2, 'Abril', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 2),
(230, 41, '0000-00-00', 3, 'Mayo', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(231, 41, '0000-00-00', 4, 'Junio', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(232, 41, '0000-00-00', 5, 'Julio', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(233, 41, '0000-00-00', 6, 'Agosto', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(234, 41, '0000-00-00', 7, 'Septiembre', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(235, 41, '0000-00-00', 8, 'Octubre', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(236, 41, '0000-00-00', 9, 'Noviembre', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(237, 41, '0000-00-00', 10, 'Diciembre', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(238, 41, '0000-00-00', 11, 'Diciembre', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0),
(239, 41, '0000-00-00', 12, 'Diciembre', 2022, 4000, 0, 0, 'IMPAGA', '', 31, 0);

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
(6, 'INGLES BASICO', 2500, 4, 1, '4 meses', 7),
(7, 'INFORMATICA', 4000, 12, 1, '12 meses', 7);

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
(534, 2, 32),
(535, 3, 32),
(536, 4, 32),
(537, 5, 32),
(538, 7, 32),
(539, 8, 32),
(540, 9, 32),
(541, 10, 32),
(542, 13, 32),
(543, 14, 32),
(544, 3, 33),
(545, 4, 33),
(546, 5, 33),
(547, 7, 33),
(548, 8, 33),
(549, 9, 33),
(550, 10, 33),
(551, 13, 33),
(564, 1, 38),
(565, 2, 38),
(566, 1, 31),
(567, 2, 31),
(568, 3, 31),
(569, 4, 31),
(570, 5, 31),
(571, 7, 31),
(572, 8, 31),
(573, 9, 31),
(574, 10, 31),
(575, 11, 31),
(576, 12, 31),
(577, 13, 31),
(578, 14, 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `idgastos` int(11) NOT NULL,
  `idservicioproducto` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `total` float NOT NULL,
  `mes` int(11) NOT NULL,
  `año` int(11) NOT NULL,
  `idsede` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`idgastos`, `idservicioproducto`, `fecha`, `total`, `mes`, `año`, `idsede`, `idusuario`) VALUES
(2, 3, '2022-02-04', 4000, 2, 2022, 7, 32),
(3, 4, '2022-02-04', 6000, 2, 2022, 8, 31),
(4, 5, '2022-02-05', 10000, 2, 2022, 7, 31),
(5, 3, '2022-03-09', 4000, 3, 2022, 7, 31);

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
(40, 31, 3, 6, 11, 3, '2022-02-22 02:33:58', '2022-03-01', 2000, 'efectivo', 6, 0, 'Febrero', 2022),
(41, 31, 4, 7, 11, 3, '2022-03-09 21:23:33', '2022-03-09', 3000, 'efectivo', 7, 0, 'Marzo', 2022);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagosprofesores`
--

CREATE TABLE `pagosprofesores` (
  `idpagosprofesores` int(11) NOT NULL,
  `idprofesor` int(11) NOT NULL,
  `idsede` int(11) NOT NULL,
  `importe` float NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mes` int(11) NOT NULL,
  `año` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pagosprofesores`
--

INSERT INTO `pagosprofesores` (`idpagosprofesores`, `idprofesor`, `idsede`, `importe`, `fecha`, `mes`, `año`, `descripcion`) VALUES
(1, 1, 7, 2250, '2022-02-05 18:40:49', 2, 2022, ''),
(2, 2, 8, 35000, '2022-02-05 19:51:26', 2, 2022, 'pago del mes febrero'),
(3, 3, 7, 15000, '2022-03-09 21:27:55', 3, 2022, 'pago del mes de marzo');

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
(7, 'alumnos'),
(8, 'profesor'),
(9, 'curso'),
(10, 'inscripcion'),
(11, 'sedes'),
(12, 'salas'),
(13, 'cobrar'),
(14, 'CobranzasUsuarios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `codigo` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
(3, 39587462, 'barbara', 'lopez', 'presidente peron', '32644165', 'baby@gmail.com', 7, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `idproveedor` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `direccion` varchar(300) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `idsede` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idproveedor`, `nombre`, `direccion`, `telefono`, `correo`, `idsede`, `idusuario`) VALUES
(2, 'creating internet', 'san pedro de jujuy', '3888451461', 'creatingInt@gmail.com', 7, 31),
(3, 'EJESA', 'san pedro de jujuy', '3888451461', 'ejesa@gmail.com', 7, 31),
(4, 'juan giron', 'los claves 319', '38884513269', 'alquileresjuan@gmail.com', 7, 31);

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
-- Estructura de tabla para la tabla `servicioproducto`
--

CREATE TABLE `servicioproducto` (
  `idservicioproducto` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `idproveedor` int(11) NOT NULL,
  `importe` int(11) NOT NULL,
  `idsede` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `servicioproducto`
--

INSERT INTO `servicioproducto` (`idservicioproducto`, `descripcion`, `tipo`, `idproveedor`, `importe`, `idsede`, `idusuario`) VALUES
(3, '50MB internet', 'Producto', 2, 4000, 7, 31),
(4, 'Luz electrica', 'Servicio', 3, 6000, 7, 31),
(5, 'Alquiler oficina jujuy', 'Alquiler', 4, 10000, 7, 31);

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
(31, 'fliasoft', 'fliasoft@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`);

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
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `examen`
--
ALTER TABLE `examen`
  ADD PRIMARY KEY (`idexamen`),
  ADD KEY `idalumno` (`idinscripcion`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`idgastos`),
  ADD KEY `idservicioproducto` (`idservicioproducto`),
  ADD KEY `idsede` (`idsede`),
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
-- Indices de la tabla `pagosprofesores`
--
ALTER TABLE `pagosprofesores`
  ADD PRIMARY KEY (`idpagosprofesores`),
  ADD KEY `idprofesor` (`idprofesor`),
  ADD KEY `idsede` (`idsede`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`idprofesor`),
  ADD KEY `idsede` (`idsede`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idproveedor`),
  ADD KEY `idsede` (`idsede`),
  ADD KEY `idusuario` (`idusuario`);

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
-- Indices de la tabla `servicioproducto`
--
ALTER TABLE `servicioproducto`
  ADD PRIMARY KEY (`idservicioproducto`),
  ADD KEY `idproveedor` (`idproveedor`),
  ADD KEY `idsede` (`idsede`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `idsede` (`idsede`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `idalumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  MODIFY `idcuotas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;
--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `idcurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=579;
--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `examen`
--
ALTER TABLE `examen`
  MODIFY `idexamen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `idgastos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `idinscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT de la tabla `pagosprofesores`
--
ALTER TABLE `pagosprofesores`
  MODIFY `idpagosprofesores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `idprofesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
-- AUTO_INCREMENT de la tabla `servicioproducto`
--
ALTER TABLE `servicioproducto`
  MODIFY `idservicioproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
-- Filtros para la tabla `examen`
--
ALTER TABLE `examen`
  ADD CONSTRAINT `examen_ibfk_1` FOREIGN KEY (`idinscripcion`) REFERENCES `inscripcion` (`idinscripcion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `examen_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`idservicioproducto`) REFERENCES `servicioproducto` (`idservicioproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`idprofesor`) REFERENCES `profesor` (`idprofesor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_3` FOREIGN KEY (`idalumno`) REFERENCES `alumno` (`idalumno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_4` FOREIGN KEY (`idcurso`) REFERENCES `curso` (`idcurso`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_5` FOREIGN KEY (`idsala`) REFERENCES `sala` (`idsala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`idsede`) REFERENCES `sedes` (`idsede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `servicioproducto`
--
ALTER TABLE `servicioproducto`
  ADD CONSTRAINT `servicioproducto_ibfk_1` FOREIGN KEY (`idproveedor`) REFERENCES `proveedores` (`idproveedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idsede`) REFERENCES `sedes` (`idsede`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
