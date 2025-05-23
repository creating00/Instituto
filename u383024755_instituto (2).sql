-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-01-2025 a las 15:42:57
-- Versión del servidor: 10.11.10-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u383024755_instituto`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`idalumno`, `dni`, `nombre`, `apellido`, `direccion`, `celular`, `email`, `tutor`, `contacto`, `idsede`, `estado`) VALUES
(3, 12345678, 'JONATHAN', 'GONZALEZ', 'la merced', '32644165', 'joni8del11@gmail.com', 'mabel', '38413131', 6, 1),
(4, 78945612, 'PEPE', 'ALVARADO', 'las colinas', '388845121', 'pepe@gmail.com', 'pedro', '38887451', 6, 1),
(5, 433453355, 'JAVIER', 'GONZALES', 'la merced', '130303030', 'joni8del11@gmail.com', 'juliete', '20225588', 6, 1),
(6, 123666, 'Eduardo ', 'PRUEBA', '1212313', '123132163', '456465@sds.com', '564564', '6465', 6, 1),
(7, 13570223, 'JOAQUíN ', 'GUZMAN LOERA', 'La tuna, Badiraguato Sinaloa', '1234567891', 'elchapo@gmail.com', 'El Mayo Zambada', '3216549873', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'Academia Yolanda', '8095958699', 'Academiayolanda27@gmail.com', 'Av. Charles de Gaulle #163 ');

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
  `idexamen` int(11) NOT NULL,
  `isDetalle` tinyint(1) DEFAULT NULL,
  `detalle` varchar(100) DEFAULT NULL,
  `tieneMora` tinyint(1) NOT NULL DEFAULT 0,
  `nroFactura` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `cuotas`
--

INSERT INTO `cuotas` (`idcuotas`, `idinscripcion`, `fecha`, `cuota`, `mes`, `año`, `importe`, `interes`, `total`, `condicion`, `mediodepago`, `idusuario`, `idexamen`, `isDetalle`, `detalle`, `tieneMora`, `nroFactura`) VALUES
(1, 2, '2025-01-29', 1, 'Diciembre', 2024, 10000, 0, 10000, 'PAGADO', 'Efectivo', 38, 0, NULL, '', 1, '2025-00000000000006'),
(2, 3, '0000-00-00', 1, 'Diciembre', 2024, 5000, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(3, 4, '0000-00-00', 1, 'Enero', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(4, 4, '0000-00-00', 2, 'Febrero', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(5, 4, '0000-00-00', 3, 'Marzo', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(6, 5, '2024-12-24', 1, 'Enero', 2025, 1500, 0, 1500, 'PAGADO', 'Efectivo', 31, 0, NULL, '', 0, NULL),
(7, 5, '0000-00-00', 2, 'Febrero', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(8, 5, '0000-00-00', 3, 'Marzo', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(9, 5, '0000-00-00', 4, 'Abril', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(10, 6, '0000-00-00', 1, 'Enero', 2025, 10000, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(11, 6, '0000-00-00', 2, 'Febrero', 2025, 10000, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(12, 6, '0000-00-00', 3, 'Marzo', 2025, 10000, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(13, 6, '0000-00-00', 4, 'Abril', 2025, 10000, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(14, 7, '2025-01-15', 1, 'Enero', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 0, NULL),
(15, 7, '2025-01-15', 2, 'Febrero', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 0, NULL),
(16, 7, '2025-01-15', 3, 'Marzo', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 0, NULL),
(17, 7, '2025-01-15', 4, 'Abril', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 0, NULL),
(18, 8, '2025-01-16', 1, 'Enero', 2025, 1500, 0, 1500, 'PAGADO', 'Efectivo', 31, 0, NULL, '', 0, '2025-00000000000005'),
(19, 8, '2025-01-15', 2, 'Febrero', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(20, 8, '2025-01-15', 3, 'Marzo', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL),
(21, 8, '2025-01-15', 4, 'Abril', 2025, 1500, 0, 0, 'PENDIENTE', '', 31, 0, NULL, NULL, 1, NULL);

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
  `idsede` int(11) NOT NULL,
  `dias` int(11) DEFAULT NULL,
  `inscripcion` double DEFAULT NULL,
  `idprofesor` int(11) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `fechaComienzo` date DEFAULT NULL,
  `fechaTermino` date DEFAULT NULL,
  `mora` float DEFAULT NULL,
  `diasRecordatorio` int(11) DEFAULT NULL,
  `horarioDesde` time DEFAULT NULL,
  `horarioHasta` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`idcurso`, `nombre`, `precio`, `duracion`, `estado`, `tipo`, `idsede`, `dias`, `inscripcion`, `idprofesor`, `monto`, `fechaComienzo`, `fechaTermino`, `mora`, `diasRecordatorio`, `horarioDesde`, `horarioHasta`) VALUES
(8, 'PELUQUERIA', 10000, 4, 1, '4 meses', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'PRUEBA', 5000, 4, 1, '4 meses', 6, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'INFORMATICA (PRUEBA)', 1500, 3, 1, '3 meses', 6, 1, 1200, 0, 0, '2025-01-07', '2025-04-08', NULL, NULL, '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_permisos`
--

CREATE TABLE `detalle_permisos` (
  `id` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_permisos`
--

INSERT INTO `detalle_permisos` (`id`, `id_permiso`, `id_usuario`) VALUES
(1, 2, 31),
(2, 3, 31),
(3, 4, 31),
(4, 5, 31),
(5, 6, 31),
(6, 7, 31),
(7, 8, 31),
(8, 9, 31),
(9, 10, 31),
(10, 10, 32),
(11, 13, 32),
(12, 11, 31),
(13, 12, 31),
(14, 13, 31),
(15, 3, 31),
(16, 10, 33),
(17, 13, 33),
(18, 13, 36),
(19, 13, 38);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(50) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion_secuencia`
--

CREATE TABLE `facturacion_secuencia` (
  `anio` int(11) NOT NULL,
  `secuencia` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `facturacion_secuencia`
--

INSERT INTO `facturacion_secuencia` (`anio`, `secuencia`, `id`) VALUES
(2025, 7, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`idgastos`, `idservicioproducto`, `fecha`, `total`, `mes`, `año`, `idsede`, `idusuario`) VALUES
(2, 3, '2022-02-04', 4000, 2, 2022, 7, 32),
(3, 4, '2022-02-04', 6000, 2, 2022, 8, 31),
(4, 5, '2022-02-05', 10000, 2, 2022, 7, 31),
(5, 3, '2022-03-09', 4000, 3, 2022, 7, 31),
(0, 0, '2025-01-15', 400, 1, 2025, 6, 31),
(0, 0, '2025-01-15', 10000, 1, 2025, 6, 31);

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
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fechacomienzo` date NOT NULL,
  `importe` float NOT NULL,
  `mediodepago` varchar(100) NOT NULL,
  `idsede` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `mes` varchar(20) NOT NULL,
  `anio` int(11) NOT NULL,
  `fechaTermino` date NOT NULL,
  `isDetalle` tinyint(1) DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `nroFactura` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`idinscripcion`, `idusuario`, `idalumno`, `idcurso`, `idsala`, `idprofesor`, `fecha`, `fechacomienzo`, `importe`, `mediodepago`, `idsede`, `estado`, `mes`, `anio`, `fechaTermino`, `isDetalle`, `detalle`, `nroFactura`) VALUES
(2, 31, 5, 8, 11, 3, '2024-12-12 18:43:50', '2024-12-12', 10000, 'efectivo', 6, 0, 'Diciembre', 2024, '2024-12-31', 1, 'Nuevo detalle', NULL),
(3, 31, 4, 9, 11, 3, '2024-12-12 18:55:34', '2024-12-01', 5000, 'efectivo', 6, 0, 'Diciembre', 2024, '2024-12-31', 0, '', NULL),
(4, 31, 6, 10, 11, 0, '2024-12-21 00:30:48', '2025-01-15', 1500, 'efectivo', 6, 0, 'Diciembre', 2024, '2025-03-22', 0, '', NULL),
(5, 31, 3, 10, 11, 0, '2024-12-23 17:56:34', '2025-01-10', 1500, 'efectivo', 6, 0, 'Diciembre', 2024, '2025-04-10', 0, '', NULL),
(6, 31, 6, 8, 11, 0, '2024-12-23 23:13:06', '2025-01-01', 10000, 'efectivo', 6, 0, 'Diciembre', 2024, '2025-04-01', 0, '', NULL),
(8, 31, 7, 10, 11, 0, '2025-01-15 17:47:32', '2025-01-07', 1200, 'efectivo', 6, 0, 'Enero', 2025, '2025-04-08', 1, 'SE VOLVIO A REINSCRIBIR ', '2025-00000000000002');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagosprofesores`
--

CREATE TABLE `pagosprofesores` (
  `idpagosprofesores` int(11) NOT NULL,
  `idprofesor` int(11) NOT NULL,
  `idsede` int(11) NOT NULL,
  `importe` float NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `mes` int(11) NOT NULL,
  `año` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `pagosprofesores`
--

INSERT INTO `pagosprofesores` (`idpagosprofesores`, `idprofesor`, `idsede`, `importe`, `fecha`, `mes`, `año`, `descripcion`) VALUES
(1, 1, 7, 2250, '2022-02-05 18:40:49', 2, 2022, ''),
(2, 2, 8, 35000, '2022-02-05 19:51:26', 2, 2022, 'pago del mes febrero'),
(3, 3, 7, 15000, '2022-03-09 21:27:55', 3, 2022, 'pago del mes de marzo'),
(0, 5, 6, 1500, '2025-01-23 00:16:10', 1, 2025, 'pago de enero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `codigo` varchar(20) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`idprofesor`, `dni`, `nombre`, `apellido`, `direccion`, `celular`, `email`, `idsede`, `estado`) VALUES
(3, 39587462, 'barbara', 'lopez', 'presidente peron', '32644165', 'baby@gmail.com', 7, 1),
(4, 12345678, 'Andres', 'Prueba', '', '', '', 6, 1),
(5, 12345, 'Mayo ', 'Zambada', 'Badiraguato Sinaloa ', '4564569875', 'mayo_zambada@gmail.com', 6, 1),
(6, 65113, 'Carol', 'M', 'ddd', '2222', 'h@gmail.com', 6, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idproveedor`, `nombre`, `direccion`, `telefono`, `correo`, `idsede`, `idusuario`) VALUES
(2, 'creating internet', 'san pedro de jujuy', '3888451461', 'creatingInt@gmail.com', 7, 31),
(3, 'EJESA', 'san pedro de jujuy', '3888451461', 'ejesa@gmail.com', 7, 31),
(4, 'juan giron', 'los claves 319', '38884513269', 'alquileresjuan@gmail.com', 7, 31),
(5, 'Telmex', 'Su oficina CDMX', '4567894568', 'telmex@gmail.com', 6, 31),
(6, 'Doña Juana', 'CDMX', '4563216874', 'donajuana@gmail.com', 6, 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recordatorios`
--

CREATE TABLE `recordatorios` (
  `id_recordatorio` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `fecha_recordatorio` date NOT NULL,
  `estado` enum('pendiente','enviado','fallido') DEFAULT 'pendiente',
  `id_alumno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recordatorios`
--

INSERT INTO `recordatorios` (`id_recordatorio`, `id_curso`, `fecha_recordatorio`, `estado`, `id_alumno`) VALUES
(38, 8, '2024-12-03', '', 3),
(39, 8, '2024-12-10', 'pendiente', 3),
(40, 8, '2025-01-10', 'pendiente', 3),
(41, 8, '2025-02-10', 'pendiente', 3),
(42, 8, '2024-11-10', 'pendiente', 4),
(43, 8, '2024-12-10', 'pendiente', 4),
(44, 8, '2025-01-10', 'pendiente', 4),
(45, 8, '2025-02-10', 'pendiente', 4),
(46, 8, '2024-11-10', 'pendiente', 3),
(47, 8, '2025-03-10', 'pendiente', 3),
(48, 8, '2025-03-10', 'pendiente', 4),
(49, 8, '2024-12-10', 'pendiente', 5),
(50, 8, '2025-01-10', 'pendiente', 5),
(51, 8, '2025-02-10', 'pendiente', 5),
(52, 8, '2025-03-10', 'pendiente', 5),
(53, 9, '2024-12-10', 'pendiente', 4),
(54, 9, '2025-01-10', 'pendiente', 4),
(55, 9, '2025-02-10', 'pendiente', 4),
(56, 9, '2025-03-10', 'pendiente', 4),
(57, 10, '2025-01-27', 'pendiente', 6),
(58, 10, '2025-02-24', 'pendiente', 6),
(59, 10, '2025-03-27', 'pendiente', 6),
(60, 10, '2025-01-27', 'pendiente', 3),
(61, 10, '2025-02-24', 'pendiente', 3),
(62, 10, '2025-03-27', 'pendiente', 3),
(63, 8, '2024-12-10', 'pendiente', 6),
(64, 8, '2025-01-10', 'pendiente', 6),
(65, 8, '2025-02-10', 'pendiente', 6),
(66, 8, '2025-03-10', 'pendiente', 6),
(67, 8, '2025-04-10', 'pendiente', 6),
(68, 8, '2025-05-10', 'pendiente', 6),
(69, 9, '2025-04-10', 'pendiente', 4),
(70, 8, '2025-04-10', 'pendiente', 5),
(71, 10, '2025-01-27', 'pendiente', 7),
(72, 10, '2025-02-24', 'pendiente', 7),
(73, 10, '2025-03-27', 'pendiente', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idrol` int(11) NOT NULL,
  `nombrerol` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idrol`, `nombrerol`) VALUES
(1, 'admin'),
(2, 'secretaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala`
--

CREATE TABLE `sala` (
  `idsala` int(11) NOT NULL,
  `sala` varchar(20) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `sala`
--

INSERT INTO `sala` (`idsala`, `sala`, `descripcion`, `estado`) VALUES
(11, 'SALA \"A\"', 'AULA 1', 1),
(12, 'SALA \"B\"', 'AULA 2', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `servicioproducto`
--

INSERT INTO `servicioproducto` (`idservicioproducto`, `descripcion`, `tipo`, `idproveedor`, `importe`, `idsede`, `idusuario`) VALUES
(3, '50MB internet', 'Producto', 2, 4000, 7, 31),
(4, 'Luz electrica', 'Servicio', 3, 6000, 7, 31),
(5, 'Alquiler oficina jujuy', 'Alquiler', 4, 10000, 7, 31),
(0, 'Internet', 'Servicio', 5, 400, 6, 31),
(0, 'Renta oficina CDMX', 'Alquiler', 6, 10000, 6, 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `idsede` int(11) NOT NULL,
  `idrol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `estado`, `idsede`, `idrol`) VALUES
(31, 'creating', 'creatingrs@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 6, 1),
(32, 'caja san luis', 'caja1@gmail.com', 'cajasn', 'e10adc3949ba59abbe56e057f20f883e', 1, 6, 0),
(33, 'demo', 'demo@gmail.com', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 6, 0),
(36, 'testeo', 'admin@h.com', 'permiso', '202cb962ac59075b964b07152d234b70', 1, 6, 0),
(37, 'Prueba2', 'cualqueria@g.com', 'test1', '289dff07669d7a23de0ef88d2f7129e7', 1, 6, 1),
(38, 'prueba3', 'adminl@h.com', 'test3', 'd81f9c1be2e08964bf9f24b15f0e4900', 1, 6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indices de la tabla `facturacion_secuencia`
--
ALTER TABLE `facturacion_secuencia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`idinscripcion`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`idprofesor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idproveedor`),
  ADD KEY `idsede` (`idsede`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `recordatorios`
--
ALTER TABLE `recordatorios`
  ADD PRIMARY KEY (`id_recordatorio`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_alumno` (`id_alumno`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`idsede`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `idalumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  MODIFY `idcuotas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `idcurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `facturacion_secuencia`
--
ALTER TABLE `facturacion_secuencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `idinscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `idprofesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `recordatorios`
--
ALTER TABLE `recordatorios`
  MODIFY `id_recordatorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `idsede` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`idsede`) REFERENCES `sedes` (`idsede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`idsede`) REFERENCES `sedes` (`idsede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recordatorios`
--
ALTER TABLE `recordatorios`
  ADD CONSTRAINT `recordatorios_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`idcurso`),
  ADD CONSTRAINT `recordatorios_ibfk_2` FOREIGN KEY (`id_alumno`) REFERENCES `alumno` (`idalumno`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
