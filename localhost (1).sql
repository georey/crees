-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 07-08-2016 a las 21:50:21
-- Versión del servidor: 5.6.30
-- Versión de PHP: 5.6.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_credito`
--
CREATE DATABASE IF NOT EXISTS `db_credito` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_credito`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asesores`
--

CREATE TABLE `asesores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` binary(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `asesores`
--

INSERT INTO `asesores` (`id`, `nombre`, `apellido`, `telefono`, `created_at`, `updated_at`, `active`) VALUES
(3, 'DANIEL ALFREDO', 'CRUZ LOPEZ ', '72127764', '2016-08-05 23:31:52', '2016-08-05 23:31:52', 0x31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `dui` varchar(10) NOT NULL,
  `nit` varchar(18) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` varchar(1) NOT NULL,
  `direccion` varchar(300) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `zona_id` int(11) NOT NULL,
  `profesion_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL DEFAULT '1',
  `estado_civil_id` int(11) NOT NULL,
  `conyuge` varchar(100) DEFAULT NULL,
  `observaciones` varchar(300) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `conyuge_telefono` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `dui`, `nit`, `codigo`, `nombre`, `apellido`, `fecha_nacimiento`, `sexo`, `direccion`, `telefono`, `zona_id`, `profesion_id`, `estado_id`, `estado_civil_id`, `conyuge`, `observaciones`, `created_at`, `updated_at`, `conyuge_telefono`) VALUES
(3, '01702301-3', '0312-190884-101-5', '00003', 'DAISI NOEMI', 'ORANTES CAMPOS', '1984-08-19', '-', 'COL NAZARENO\r\n    ', '7504-5645', 1, 3, 1, 1, 'fulano', '    \r\n    \r\n    ', '2016-07-02 21:35:04', '2016-08-06 22:02:59', '4545-1515'),
(4, '00490044-', '02101811841046', '00004', 'Giovanni', 'Reinoza', '1984-11-18', '1', 'Barcelona', '77294185', 1, 1, 1, 2, 'Rosa Quiñonez', '---', '2016-08-03 02:39:26', '2016-08-03 02:39:26', NULL),
(5, '92928829-8', '9809-809809-809-8', '00005', 'Erick', 'Reinoza', '2012-02-28', '1', 'ahi mismo    ', '9098-0980', 1, 1, 1, 1, '---', '---    ', '2016-08-03 08:22:36', '2016-08-03 08:22:36', NULL),
(6, '04402868-4', '0210-251290-103-9', '00006', 'KARLA MARIA ', 'CASTRO GONZALEZ ', '2016-12-25', '2', 'COLONIA LOS PINOS AV BRASIL CASA 12 A    \r\n    ', '7926-7139', 1, 3, 1, 1, 'N/A', '    \r\n    ', '2016-08-05 22:34:37', '2016-08-05 22:39:12', NULL),
(7, '01186284-7', '0210-010666-110-5', '00007', 'ANGELICA DEL CARMEN', 'PINZON DE ZETINO', '1966-06-01', '2', 'CIUDAD REAL RESIDENCIAL  VALENCIA POL 44 CASA 22 SAN SEBASTIAN SALITRIO    ', '7638-2110', 1, 3, 1, 2, 'FIDEL ANTONIO ZETINO ', '    ', '2016-08-05 22:50:25', '2016-08-05 22:50:25', NULL),
(8, '03626084-0', '0210-100986-108-2', '00008', 'JORGE ALBERTO ', 'GARCIA QUIJADA', '1986-09-10', '1', 'ALTOS DEL MOLINO POLG D CASA 20 SANTA ANA     ', '7246-9273', 1, 10, 1, 2, 'ROSA MARITZA RAMOS', '    ', '2016-08-05 23:54:29', '2016-08-05 23:54:29', NULL),
(9, '03818852-3', '0210-280877-116-5', '00009', 'DAISY  ELIZABETH ', 'ARCE MONTERROSA', '1977-08-28', '2', '33 CLLE PTE ENTRE 2DA Y 4TA AV SUR MESON COSTA RICA PIEZA 2 SANTA ANA     ', '7582-5613', 1, 3, 1, 1, 'N/A', '    ', '2016-08-06 01:34:11', '2016-08-06 01:34:11', NULL),
(10, '03598801-3', '0210-190174-104-0', '00010', 'SONIA NOEMI ', 'HERNANDEZ MEJIA ', '1974-01-19', '2', 'RES TENERIFE II POLG  1B CASA 35 CIUDAD REAL SAN SEBASTIAN SALITRILLO SANTA ANA', '7665-1626', 1, 6, 1, 1, 'FERNANDO ENRIQUE VELASQUEZ ', '    ', '2016-08-06 01:53:10', '2016-08-06 01:53:10', NULL),
(11, '03703934-9', '0210-150387-106-6', '00011', 'MAIRA MARISELA ', 'RODRIGUEZ JACOBO', '1987-03-15', '2', 'COL CEL BLOCK H CASA 1 SANTA ANA SANTA ANA    ', '7498-1477', 1, 8, 1, 1, 'JOSE MIGUEL CRISTALES GOMEZ ', '    ', '2016-08-06 02:07:11', '2016-08-06 02:07:11', NULL),
(12, '01781312-1', '0210-251075-110-2', '00012', 'CLARA VICTORIA ', 'CASTANEDA DE SANTILLANA', '1975-10-25', '2', 'RES VILLA REAL II    POLG 3 CASA 32 SANTA ANA', '7736-9132', 1, 12, 1, 2, 'MARLON ALFREDO SANTILLANA CALIDONIO', '    ', '2016-08-06 02:38:36', '2016-08-06 02:38:36', NULL),
(14, '02122202-1', '2121-651651-651-6', '00014', 'Elizabeth', 'Reinoza', '2013-10-26', '2', 'Barcelona    ', '7722-5844', 1, 3, 1, 4, '---', '---    ', '2016-08-06 21:58:35', '2016-08-06 21:58:35', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobradores`
--

CREATE TABLE `cobradores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` binary(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cobradores`
--

INSERT INTO `cobradores` (`id`, `nombre`, `apellido`, `telefono`, `created_at`, `updated_at`, `active`) VALUES
(3, 'DANIEL ALFREDO', 'CRUZ LOPEZ ', '72127764', '2016-08-05 23:33:37', '2016-08-05 23:33:37', 0x31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`) VALUES
(1, 'Ahuachapan'),
(2, 'Cabañas'),
(3, 'Chalatenango'),
(4, 'Cuscatlan'),
(5, 'La Libertad'),
(6, 'La Paz'),
(7, 'La Union'),
(8, 'Morazan'),
(9, 'San Miguel'),
(10, 'San Salvador'),
(11, 'San Vicente'),
(12, 'Santa Ana'),
(13, 'Sonsonate'),
(14, 'Usulutan');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `nombre`) VALUES
(1, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_civiles`
--

CREATE TABLE `estados_civiles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estados_civiles`
--

INSERT INTO `estados_civiles` (`id`, `nombre`) VALUES
(1, 'Soltero'),
(2, 'Casado'),
(3, 'Acompañado'),
(4, 'Divorciado'),
(5, 'Viudo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_prestamo`
--

CREATE TABLE `estados_prestamo` (
  `id` int(11) NOT NULL,
  `estado` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estados_prestamo`
--

INSERT INTO `estados_prestamo` (`id`, `estado`) VALUES
(1, 'Activo'),
(2, 'Liquidado'),
(3, 'Cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fiadores`
--

CREATE TABLE `fiadores` (
  `cliente_id` int(11) DEFAULT NULL,
  `prestamo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `fiadores`
--

INSERT INTO `fiadores` (`cliente_id`, `prestamo_id`) VALUES
(4, 1),
(5, 2),
(4, 3),
(4, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `prestamo_id` int(11) DEFAULT NULL,
  `tipo_gasto_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`prestamo_id`, `tipo_gasto_id`) VALUES
(1, 1),
(1, 4),
(2, 2),
(2, 5),
(3, 3),
(3, 6),
(5, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas`
--

CREATE TABLE `lineas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `tasa_anual` decimal(19,2) DEFAULT NULL,
  `indice_conversion` int(11) DEFAULT NULL,
  `tasa_mora` decimal(19,2) DEFAULT NULL,
  `multa` decimal(19,2) DEFAULT NULL,
  `periodo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `lineas`
--

INSERT INTO `lineas` (`id`, `nombre`, `tasa_anual`, `indice_conversion`, `tasa_mora`, `multa`, `periodo`) VALUES
(1, '10', '200.00', 365, '48.00', '0.10', 'Diaria'),
(2, '20', '180.00', 52, '48.00', '2.00', 'Semanal'),
(4, '30', '180.00', 26, '48.00', '2.00', 'Quincenal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `departamento_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id`, `nombre`, `departamento_id`) VALUES
(1, 'Ahuachapán', 1),
(2, 'Apaneca', 1),
(3, 'Atiquizaya', 1),
(4, 'Concepción de Ataco', 1),
(5, 'El Refugio', 1),
(6, 'Guaymango', 1),
(7, 'Jujutla', 1),
(8, 'San Francisco Menéndez', 1),
(9, 'San Lorenzo', 1),
(10, 'San Pedro Puxtla', 1),
(11, 'Tacuba', 1),
(12, 'Turín', 1),
(13, 'Cinquera', 2),
(14, 'Dolores', 2),
(15, 'Guacotecti', 2),
(16, 'Ilobasco', 2),
(17, 'Jutiapa', 2),
(18, 'San Isidro', 2),
(19, 'Sensuntepeque', 2),
(20, 'Tejutepeque', 2),
(21, 'Victoria', 2),
(22, 'Agua Caliente', 3),
(23, 'Arcatao', 3),
(24, 'Azacualpa', 3),
(25, 'Chalatenango', 3),
(26, 'Comalapa', 3),
(27, 'Citalá', 3),
(28, 'Concepción Quezaltepeque', 3),
(29, 'Dulce Nombre de María', 3),
(30, 'El Carrizal', 3),
(31, 'El Paraíso', 3),
(32, 'La Laguna', 3),
(33, 'La Palma', 3),
(34, 'La Reina', 3),
(35, 'Las Vueltas', 3),
(36, 'Nueva Concepción', 3),
(37, 'Nueva Trinidad', 3),
(38, 'Nombre de Jesús', 3),
(39, 'Ojos de Agua', 3),
(40, 'Potonico', 3),
(41, 'San Antonio de la Cruz', 3),
(42, 'San Antonio Los Ranchos', 3),
(43, 'San Fernando', 3),
(44, 'San Francisco Lempa', 3),
(45, 'San Francisco Morazán', 3),
(46, 'San Ignacio', 3),
(47, 'San Isidro Labrador', 3),
(48, 'San José Cancasque', 3),
(49, 'San José Las Flores', 3),
(50, 'San Luis del Carmen', 3),
(51, 'San Miguel de Mercedes', 3),
(52, 'San Rafael', 3),
(53, 'Santa Rita', 3),
(54, 'Tejutla', 3),
(55, 'Candelaria', 4),
(56, 'Cojutepeque', 4),
(57, 'El Carmen', 4),
(58, 'El Rosario', 4),
(59, 'Monte San Juan', 4),
(60, 'Oratorio de Concepción', 4),
(61, 'San Bartolomé Perulapía', 4),
(62, 'San Cristóbal', 4),
(63, 'San José Guayabal', 4),
(64, 'San Pedro Perulapán', 4),
(65, 'San Rafael Cedros', 4),
(66, 'San Ramón', 4),
(67, 'Santa Cruz Analquito', 4),
(68, 'Santa Cruz Michapa', 4),
(69, 'Suchitoto', 4),
(70, 'Tenancingo', 4),
(71, 'Antiguo Cuscatlán', 5),
(72, 'Chiltiupán', 5),
(73, 'Ciudad Arce', 5),
(74, 'Colón', 5),
(75, 'Comasagua', 5),
(76, 'Huizúcar', 5),
(77, 'Jayaque', 5),
(78, 'Jicalapa', 5),
(79, 'La Libertad', 5),
(80, 'Santa Tecla', 5),
(81, 'Nuevo Cuscatlán', 5),
(82, 'San Juan Opico', 5),
(83, 'Quezaltepeque', 5),
(84, 'Sacacoyo', 5),
(85, 'San José Villanueva', 5),
(86, 'San Matías', 5),
(87, 'San Pablo Tacachico', 5),
(88, 'Talnique', 5),
(89, 'Tamanique', 5),
(90, 'Teotepeque', 5),
(91, 'Tepecoyo', 5),
(92, 'Zaragoza', 5),
(93, 'Cuyultitán', 6),
(94, 'El Rosario', 6),
(95, 'Jerusalén', 6),
(96, 'Mercedes La Ceiba', 6),
(97, 'Olocuilta', 6),
(98, 'Paraíso de Osorio', 6),
(99, 'San Antonio Masahuat', 6),
(100, 'San Emigdio', 6),
(101, 'San Francisco Chinameca', 6),
(102, 'San Juan Nonualco', 6),
(103, 'San Juan Talpa', 6),
(104, 'San Juan Tepezontes', 6),
(105, 'San Luis Talpa', 6),
(106, 'San Luis La Herradura', 6),
(107, 'San Miguel Tepezontes', 6),
(108, 'San Pedro Masahuat', 6),
(109, 'San Pedro Nonualco', 6),
(110, 'San Rafael Obrajuelo', 6),
(111, 'Santa María Ostuma', 6),
(112, 'Santiago Nonualco', 6),
(113, 'Tapalhuaca', 6),
(114, 'Zacatecoluca', 6),
(115, 'Anamorós', 7),
(116, 'Bolívar', 7),
(117, 'Concepción de Oriente', 7),
(118, 'Conchagua', 7),
(119, 'El Carmen', 7),
(120, 'El Sauce', 7),
(121, 'Intipucá', 7),
(122, 'La Unión', 7),
(123, 'Lislique', 7),
(124, 'Meanguera del Golfo', 7),
(125, 'Nueva Esparta', 7),
(126, 'Pasaquina', 7),
(127, 'Polorós', 7),
(128, 'San Alejo', 7),
(129, 'San José', 7),
(130, 'Santa Rosa de Lima', 7),
(131, 'Yayantique', 7),
(132, 'Yucuaiquín', 7),
(133, 'Arambala', 8),
(134, 'Cacaopera', 8),
(135, 'Chilanga', 8),
(136, 'Corinto', 8),
(137, 'Delicias de Concepción', 8),
(138, 'El Divisadero', 8),
(139, 'El Rosario', 8),
(140, 'Gualococti', 8),
(141, 'Guatajiagua', 8),
(142, 'Joateca', 8),
(143, 'Jocoaitique', 8),
(144, 'Jocoro', 8),
(145, 'Lolotiquillo', 8),
(146, 'Meanguera', 8),
(147, 'Osicala', 8),
(148, 'Perquín', 8),
(149, 'San Carlos', 8),
(150, 'San Fernando', 8),
(151, 'San Francisco Gotera', 8),
(152, 'San Isidro', 8),
(153, 'San Simón', 8),
(154, 'Sensembra', 8),
(155, 'Sociedad', 8),
(156, 'Torola', 8),
(157, 'Yamabal', 8),
(158, 'Yoloaiquín', 8),
(159, 'Carolina', 9),
(160, 'Chapeltique', 9),
(161, 'Chinameca', 9),
(162, 'Chirilagua', 9),
(163, 'Ciudad Barrios', 9),
(164, 'Comacarán', 9),
(165, 'El Tránsito', 9),
(166, 'Lolotique', 9),
(167, 'Moncagua', 9),
(168, 'Nueva Guadalupe', 9),
(169, 'Nuevo Edén de San Juan', 9),
(170, 'Quelepa', 9),
(171, 'San Antonio del Mosco', 9),
(172, 'San Gerardo', 9),
(173, 'San Jorge', 9),
(174, 'San Luis de la Reina', 9),
(175, 'San Miguel', 9),
(176, 'San Rafael Oriente', 9),
(177, 'Sesori', 9),
(178, 'Uluazapa', 9),
(179, 'Aguilares', 10),
(180, 'Apopa', 10),
(181, 'Ayutuxtepeque', 10),
(182, 'Cuscatancingo', 10),
(183, 'Ciudad Delgado', 10),
(184, 'El Paisnal', 10),
(185, 'Guazapa', 10),
(186, 'Ilopango', 10),
(187, 'Mejicanos', 10),
(188, 'Nejapa', 10),
(189, 'Panchimalco', 10),
(190, 'Rosario de Mora', 10),
(191, 'San Marcos', 10),
(192, 'San Martín', 10),
(193, 'San Salvador', 10),
(194, 'Santiago Texacuangos', 10),
(195, 'Santo Tomás', 10),
(196, 'Soyapango', 10),
(197, 'Tonacatepeque', 10),
(198, 'Apastepeque', 11),
(199, 'Guadalupe', 11),
(200, 'San Cayetano Istepeque', 11),
(201, 'San Esteban Catarina', 11),
(202, 'San Ildefonso', 11),
(203, 'San Lorenzo', 11),
(204, 'San Sebastián', 11),
(205, 'San Vicente', 11),
(206, 'Santa Clara', 11),
(207, 'Santo Domingo', 11),
(208, 'Tecoluca', 11),
(209, 'Tepetitán', 11),
(210, 'Verapaz', 11),
(211, 'Candelaria de la Frontera', 12),
(212, 'Chalchuapa', 12),
(213, 'Coatepeque', 12),
(214, 'El Congo', 12),
(215, 'El Porvenir', 12),
(216, 'Masahuat', 12),
(217, 'Metapán', 12),
(218, 'San Antonio Pajonal', 12),
(219, 'San Sebastián Salitrillo', 12),
(220, 'Santa Ana', 12),
(221, 'Santa Rosa Guachipilín', 12),
(222, 'Santiago de la Frontera', 12),
(223, 'Texistepeque', 12),
(224, 'Acajutla', 13),
(225, 'Armenia', 13),
(226, 'Caluco', 13),
(227, 'Cuisnahuat', 13),
(228, 'Izalco', 13),
(229, 'Juayúa', 13),
(230, 'Nahuizalco', 13),
(231, 'Nahulingo', 13),
(232, 'Salcoatitán', 13),
(233, 'San Antonio del Monte', 13),
(234, 'San Julián', 13),
(235, 'Santa Catarina Masahuat', 13),
(236, 'Santa Isabel Ishuatán', 13),
(237, 'Santo Domingo Guzmán', 13),
(238, 'Sonsonate', 13),
(239, 'Sonzacate', 13),
(240, 'Alegría', 14),
(241, 'Berlín', 14),
(242, 'California', 14),
(243, 'Concepción Batres', 14),
(244, 'El Triunfo', 14),
(245, 'Ereguayquín', 14),
(246, 'Estanzuelas', 14),
(247, 'Jiquilisco', 14),
(248, 'Jucuapa', 14),
(249, 'Jucuarán', 14),
(250, 'Mercedes Umaña', 14),
(251, 'Nueva Granada', 14),
(252, 'Ozatlán', 14),
(253, 'Puerto El Triunfo', 14),
(254, 'San Agustín', 14),
(255, 'San Buenaventura', 14),
(256, 'San Dionisio', 14),
(257, 'San Francisco Javier', 14),
(258, 'Santa Elena', 14),
(259, 'Santa María', 14),
(260, 'Santiago de María', 14),
(261, 'Tecapán', 14),
(262, 'Usulután', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `negocios`
--

CREATE TABLE `negocios` (
  `id` int(11) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `empleados` int(11) DEFAULT NULL,
  `dias_trabajo` int(11) DEFAULT NULL,
  `horario` varchar(45) DEFAULT NULL,
  `tipo_negocio_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  `municipio_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `negocios`
--

INSERT INTO `negocios` (`id`, `telefono`, `direccion`, `edad`, `empleados`, `dias_trabajo`, `horario`, `tipo_negocio_id`, `cliente_id`, `municipio_id`) VALUES
(1, '7777777', '77777', 77, 4, 6, '8 a 6', 1, 2, 220),
(2, '77294185', 'barcelona', 5, 1, 7, '7 a 7', 1, 2, 220),
(3, '77294185', 'barcelona', 5, 1, 7, '7 a 7', 1, 2, 220),
(4, '77294185', 'barcelona', 5, 1, 7, '7 a 7', 1, 2, 220),
(5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 220),
(6, '7729-9090', 'centro', 3, 5, 6, '8 a 5 sin cerrar a medio dia', 1, 3, 220),
(7, '79267139', 'BUFALO WINS    ', 5, NULL, 7, '8:00 AM A 10:00 PM', 1, 6, 220),
(8, '72557826', 'COMERCIAL SAN ANTONIO     ', 14, NULL, 6, '7:00 AM A 5:30 PM ', 1, 7, 20),
(9, NULL, '    ', NULL, NULL, 0, NULL, 0, 7, 221),
(10, '7246-9273', '   DE CASA', 4, NULL, 0, '6:00 AM - 12:00 PM', 2, 8, 230),
(11, NULL, '    ', NULL, NULL, NULL, NULL, 0, 8, 10),
(12, NULL, '8° AV SUR ENTRE 15 Y 17 CLLE PTE SALIDA DEL PARQUEO/BODEGA    ', 10, NULL, 0, '6:00 AM - 12:00 MD', 9, 9, 11),
(13, '76651626', 'MERCADITO EL TRIANGULO PUESTO 15 MERCADO COLON SANTA ANA     ', 15, NULL, 0, '9.00 AM - 5:00 PM', 3, 10, 156),
(14, NULL, '    ', NULL, NULL, NULL, NULL, 13, 10, 147),
(15, '74981477', '15 CLLE PTE FRENTE AGENCIAS, 27 MERCADO COLON SANTA ANA \r\n', 10, NULL, 0, '8:00 AM - 5:00 PM', 10, 11, 210),
(16, NULL, '    ', NULL, NULL, NULL, NULL, 0, 11, 206);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `capital` decimal(19,2) DEFAULT '0.00',
  `interes` decimal(19,2) DEFAULT '0.00',
  `mora` decimal(19,2) DEFAULT '0.00',
  `multa` decimal(19,2) DEFAULT '0.00',
  `prestamo_id` int(11) DEFAULT NULL,
  `saldo` decimal(19,2) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `cobrador_id` int(11) DEFAULT NULL,
  `pago_sucursal` tinyint(4) DEFAULT NULL,
  `sucursal_id` int(11) DEFAULT NULL,
  `interes_pendiente` decimal(19,2) DEFAULT '0.00',
  `interes_mora_pendiente` decimal(19,2) DEFAULT '0.00',
  `multa_pendiente` decimal(19,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `capital`, `interes`, `mora`, `multa`, `prestamo_id`, `saldo`, `fecha`, `cobrador_id`, `pago_sucursal`, `sucursal_id`, `interes_pendiente`, `interes_mora_pendiente`, `multa_pendiente`, `created_at`, `updated_at`) VALUES
(1, '24.81', '3.94', '0.00', '0.00', 1, NULL, '2016-08-04 21:45:54', 0, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL),
(2, '32.23', '11.47', '0.30', '6.00', 1, NULL, '2016-08-07 23:21:51', 3, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL),
(3, '0.00', '80.36', '9.64', '10.00', 2, NULL, '2016-08-07 23:26:59', 0, NULL, NULL, '154.38', '0.00', '0.00', NULL, NULL),
(4, '0.00', '100.00', '0.00', '0.00', 2, NULL, '2016-08-07 03:50:42', 0, NULL, NULL, '54.38', '0.00', '0.00', '2016-08-08 09:50:42', '2016-08-08 09:50:42'),
(5, '45.62', '54.38', '0.00', '0.00', 2, NULL, '2016-08-07 03:52:15', 0, NULL, NULL, '0.00', '0.00', '0.00', '2016-08-08 09:52:15', '2016-08-08 09:52:15'),
(6, '12.48', '2.52', '0.00', '0.00', 3, NULL, '2016-08-07 03:53:22', 0, NULL, NULL, '0.00', '0.00', '0.00', '2016-08-08 09:53:22', '2016-08-08 09:53:22'),
(10, '17.10', '6.90', '0.00', '0.00', 5, NULL, '2016-07-27 04:35:46', 0, NULL, NULL, '0.00', '0.00', '0.00', '2016-08-08 10:35:46', '2016-08-08 10:35:46'),
(11, '17.69', '6.31', '0.00', '0.00', 5, NULL, '2016-08-03 04:36:10', 0, NULL, NULL, '0.00', '0.00', '0.00', '2016-08-08 10:36:10', '2016-08-08 10:36:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `monto` decimal(19,2) NOT NULL,
  `linea_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `cuotas` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estado_prestamo_id` int(11) DEFAULT '1',
  `tasa` decimal(19,2) DEFAULT NULL,
  `observaciones` varchar(300) DEFAULT NULL,
  `tasa_mora` decimal(19,2) DEFAULT NULL,
  `multa` decimal(19,2) DEFAULT NULL,
  `garantia` varchar(500) DEFAULT NULL,
  `descuento` decimal(19,2) DEFAULT NULL,
  `liquido` decimal(19,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `cuota` decimal(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `codigo`, `monto`, `linea_id`, `cliente_id`, `cuotas`, `created_at`, `updated_at`, `estado_prestamo_id`, `tasa`, `observaciones`, `tasa_mora`, `multa`, `garantia`, `descuento`, `liquido`, `fecha`, `cuota`) VALUES
(1, '00003011001', '800.00', 1, 3, 30, '2016-07-05 03:01:22', '2016-08-05 03:01:22', 1, '180.00', '    ---', '190.00', '2.00', '    ---', '7.00', '793.00', '2016-07-04', '28.99'),
(2, '00004012001', '1200.00', 2, 4, 40, '2016-07-05 03:07:14', '2016-08-05 03:07:14', 1, '210.00', '    ---', '225.00', '2.00', '    ---', '6.75', '1193.25', '2016-07-04', '55.86'),
(3, '00005013001', '100.00', 4, 5, 10, '2016-07-05 03:08:57', '2016-08-05 03:08:57', 1, '230.00', '    ---', '245.00', '1.50', '    ---', '8.00', '92.00', '2016-08-04', '14.19'),
(5, '00003012002', '200.00', 2, 3, 10, '2016-08-08 04:24:58', '2016-08-08 04:24:58', 1, '180.00', '---    ', '48.00', '2.00', '    ---', '2.00', '198.00', '2016-07-20', '24.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesiones`
--

CREATE TABLE `profesiones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profesiones`
--

INSERT INTO `profesiones` (`id`, `nombre`) VALUES
(3, 'Ama de casa'),
(5, 'DOMESTICA'),
(6, 'COMERCIANTE'),
(7, 'OFICIOS VARIOS'),
(8, 'ESTUDIANTE'),
(9, 'MECANICO'),
(10, 'PANIFICADOR'),
(11, 'SECRETARIA'),
(12, 'DOCTOR (A)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_gastos`
--

CREATE TABLE `tipo_gastos` (
  `id` int(11) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `monto` decimal(19,2) DEFAULT NULL,
  `linea_id` int(11) DEFAULT NULL,
  `monto_min` decimal(19,2) DEFAULT NULL,
  `monto_max` decimal(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_gastos`
--

INSERT INTO `tipo_gastos` (`id`, `tipo`, `monto`, `linea_id`, `monto_min`, `monto_max`) VALUES
(1, 'AUTENTICA', '2.50', 2, NULL, NULL),
(2, 'Autentica', '1.50', 2, '0.00', '100.00'),
(3, 'Gasto juridico', '1.75', 4, NULL, NULL),
(4, 'Autentica', '2.00', 2, '101.00', '200.00'),
(5, 'Autentica', '2.50', 2, '201.00', '300.00'),
(6, 'Gasto Z', '6.25', 4, NULL, NULL),
(7, 'Descuento por buro de credito', '1.50', 2, '0.00', '100.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_negocios`
--

CREATE TABLE `tipo_negocios` (
  `id` int(11) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_negocios`
--

INSERT INTO `tipo_negocios` (`id`, `tipo`) VALUES
(1, 'Venta'),
(2, 'PANADERIA'),
(3, 'TIENDA '),
(4, 'COMEDOR'),
(5, 'LIBRERIA'),
(6, 'ZAPATERIA'),
(7, 'ARTESANIA'),
(8, 'VENTA DE ROPA NUEVA'),
(9, 'VENTA DE ROPA USADA'),
(10, 'VENTA DE ROPA INTIMA'),
(11, 'VENTA DE TORTAS'),
(12, 'TORTILLERIA'),
(13, 'VENTA DE CEREALES '),
(14, 'VENTA DE PRODUCTO DE CONSUMO DIARIO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` binary(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellido`, `username`, `password`, `remember_token`, `created_at`, `updated_at`, `active`) VALUES
(1, 'Giovanni', 'Reinoza', 'giovanni.reinoza', '$2y$10$kK7hb1i.nbQVo1IRDd02xe0Q5bwTyEGD3iTT4EkMBkX0paHJeNZnK', 'Hc2Xs9yhVtgAbg4TdzFG5wLrryWyRRclRDs2jIiXZlkFAVpzMOc189dufapK', NULL, '2016-07-16 11:09:35', NULL),
(2, 'Daisi', 'Orantes', 'daisi.orantes', '$2y$10$zIIK8J6O6ZDpYz21uEup0.eYS0Bs9ckx.E11.alYw8s9bZv0zxUwy', 'jbwadQ4xqZjNnAJmBpcIsFIyyPm7ym8gHHEFblODe401VrDtjZnuJ2b9na3W', NULL, '2016-07-16 11:09:53', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas`
--

CREATE TABLE `zonas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `zonas`
--

INSERT INTO `zonas` (`id`, `nombre`) VALUES
(1, 'SANTA ANA CENTRO'),
(2, 'Zona 2'),
(3, 'Zona 3'),
(4, 'Zona 4'),
(5, 'Zona 5');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asesores`
--
ALTER TABLE `asesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dui_UNIQUE` (`dui`),
  ADD UNIQUE KEY `nit_UNIQUE` (`nit`),
  ADD UNIQUE KEY `codigo_UNIQUE` (`codigo`);

--
-- Indices de la tabla `cobradores`
--
ALTER TABLE `cobradores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados_civiles`
--
ALTER TABLE `estados_civiles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados_prestamo`
--
ALTER TABLE `estados_prestamo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lineas`
--
ALTER TABLE `lineas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `negocios`
--
ALTER TABLE `negocios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `profesiones`
--
ALTER TABLE `profesiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_gastos`
--
ALTER TABLE `tipo_gastos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_negocios`
--
ALTER TABLE `tipo_negocios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `zonas`
--
ALTER TABLE `zonas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asesores`
--
ALTER TABLE `asesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `cobradores`
--
ALTER TABLE `cobradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `estados_civiles`
--
ALTER TABLE `estados_civiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `estados_prestamo`
--
ALTER TABLE `estados_prestamo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `lineas`
--
ALTER TABLE `lineas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;
--
-- AUTO_INCREMENT de la tabla `negocios`
--
ALTER TABLE `negocios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `profesiones`
--
ALTER TABLE `profesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `tipo_gastos`
--
ALTER TABLE `tipo_gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `tipo_negocios`
--
ALTER TABLE `tipo_negocios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `zonas`
--
ALTER TABLE `zonas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;--
-- Base de datos: `db_obra`
--
CREATE DATABASE IF NOT EXISTS `db_obra` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_obra`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_departamento`
--

CREATE TABLE `tbl_departamento` (
  `id_departamento` int(11) NOT NULL,
  `departamento` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_departamento`
--

INSERT INTO `tbl_departamento` (`id_departamento`, `departamento`) VALUES
(1, 'Santa Ana'),
(2, 'Ahuachapan');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_municipio`
--

CREATE TABLE `tbl_municipio` (
  `id_municipio` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `municipio` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_obra`
--

CREATE TABLE `tbl_obra` (
  `id_obra` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `obra` varchar(50) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuario`
--

CREATE TABLE `tbl_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_departamento`
--
ALTER TABLE `tbl_departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `tbl_municipio`
--
ALTER TABLE `tbl_municipio`
  ADD PRIMARY KEY (`id_municipio`);

--
-- Indices de la tabla `tbl_obra`
--
ALTER TABLE `tbl_obra`
  ADD PRIMARY KEY (`id_obra`);

--
-- Indices de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_departamento`
--
ALTER TABLE `tbl_departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tbl_municipio`
--
ALTER TABLE `tbl_municipio`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tbl_obra`
--
ALTER TABLE `tbl_obra`
  MODIFY `id_obra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;--
-- Base de datos: `jsfhibercrud`
--
CREATE DATABASE IF NOT EXISTS `jsfhibercrud` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `jsfhibercrud`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer`
--

CREATE TABLE `customer` (
  `cust_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `customer`
--

INSERT INTO `customer` (`cust_id`, `first_name`, `last_name`, `email`, `dob`) VALUES
(1, 'Giovanni', 'Reinoza', 'greinoza@gmail.com', NULL),
(2, 'Elizabeth', 'Reinoza', 'ely@correo.com', NULL),
(3, 'Erick', 'Reinoza', 'erick@correo.com', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cust_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `customer`
--
ALTER TABLE `customer`
  MODIFY `cust_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;--
-- Base de datos: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
