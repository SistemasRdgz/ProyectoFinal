-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-04-2026 a las 04:29:20
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
-- Base de datos: `farmaciasj`
--
CREATE DATABASE IF NOT EXISTS `farmaciasj` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `farmaciasj`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `IdCategoria` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`IdCategoria`, `Nombre`, `Descripcion`) VALUES
(1, 'Analgésicos', 'Medicamentos para aliviar dolor y fiebre'),
(2, 'Antibióticos', 'Medicamentos para tratar infecciones bacterianas'),
(3, 'Vitaminas', 'Suplementos vitamínicos y minerales'),
(4, 'Cuidado personal', 'Productos de higiene y cuidado personal'),
(5, 'Primeros auxilios', 'Productos para curaciones y atención básica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

DROP TABLE IF EXISTS `compras`;
CREATE TABLE `compras` (
  `IdCompra` int(11) NOT NULL,
  `IdProveedor` int(11) DEFAULT NULL,
  `Fecha` datetime DEFAULT current_timestamp(),
  `Total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`IdCompra`, `IdProveedor`, `Fecha`, `Total`) VALUES
(3, NULL, '2026-04-27 03:05:45', NULL),
(5, 1, '2026-04-27 03:13:59', 93.50),
(6, 1, '2026-04-27 11:12:22', 45.00),
(7, 1, '2026-04-27 19:24:50', 0.00),
(8, 1, '2026-04-27 19:25:00', 0.00),
(9, 1, '2026-04-27 19:27:31', 7.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallecompras`
--

DROP TABLE IF EXISTS `detallecompras`;
CREATE TABLE `detallecompras` (
  `IdDetalle` int(11) NOT NULL,
  `IdCompra` int(11) DEFAULT NULL,
  `IdProducto` int(11) DEFAULT NULL,
  `Cantidad` int(11) NOT NULL,
  `Precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detallecompras`
--

INSERT INTO `detallecompras` (`IdDetalle`, `IdCompra`, `IdProducto`, `Cantidad`, `Precio`) VALUES
(2, 5, 3, 1, 2.00),
(3, 5, 4, 1, 5.50),
(6, 6, 3, 1, 2.00),
(7, 9, 9, 1, 2.00),
(8, 9, 4, 1, 5.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientosinventario`
--

DROP TABLE IF EXISTS `movimientosinventario`;
CREATE TABLE `movimientosinventario` (
  `IdMovimiento` int(11) NOT NULL,
  `IdProducto` int(11) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `TipoMovimiento` varchar(10) DEFAULT NULL,
  `Cantidad` int(11) NOT NULL,
  `Fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientosinventario`
--

INSERT INTO `movimientosinventario` (`IdMovimiento`, `IdProducto`, `IdUsuario`, `TipoMovimiento`, `Cantidad`, `Fecha`) VALUES
(1, 9, 6, 'SALIDA', 1, '2026-04-27 19:27:31'),
(2, 4, 6, 'SALIDA', 1, '2026-04-27 19:27:31'),
(3, 9, 4, 'ENTRADA', 5, '2026-04-27 19:39:13'),
(4, 4, 4, 'ENTRADA', 10, '2026-04-27 19:39:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `IdProducto` int(11) NOT NULL,
  `Nombre` varchar(150) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `StockActual` int(11) NOT NULL DEFAULT 0,
  `StockMinimo` int(11) NOT NULL DEFAULT 5,
  `IdCategoria` int(11) DEFAULT NULL,
  `IdProveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`IdProducto`, `Nombre`, `Descripcion`, `Precio`, `FechaVencimiento`, `StockActual`, `StockMinimo`, `IdCategoria`, `IdProveedor`) VALUES
(3, 'Ibuprofeno 400mg', NULL, 2.00, '0000-00-00', 80, 5, NULL, NULL),
(4, 'Amoxicilina 500mg', NULL, 5.50, '0000-00-00', 59, 5, NULL, NULL),
(9, 'ACETAMINOFEN BAYER', 'PARA DOLOR', 2.00, '2030-12-25', 45, 10, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE `proveedores` (
  `IdProveedor` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`IdProveedor`, `Nombre`, `Telefono`, `Direccion`) VALUES
(1, 'Proveedor General', NULL, NULL),
(2, 'Proveedor General', '75131016', 'sanvi'),
(3, 'Proveedor General', '2222-0000', 'San Salvador'),
(4, 'Distribuidora Farmacéutica San José', '2233-4455', 'San Salvador'),
(5, 'Laboratorios Centroamericanos', '2244-5566', 'Santa Tecla'),
(6, 'Medicamentos y Suministros S.A.', '2255-6677', 'San Miguel');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `IdUsuario` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Correo` varchar(150) NOT NULL,
  `Pass` varchar(100) NOT NULL,
  `Rol` varchar(50) NOT NULL,
  `Estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IdUsuario`, `Nombre`, `Correo`, `Pass`, `Rol`, `Estado`) VALUES
(1, 'Hector', 'hectorvicenzo34@gmail.com', '12399', 'admin', 1),
(2, 'Adal', 'adalcarcamo@gmail.com', '123456', 'usuario', 1),
(3, 'cesar', 'cesaredu@gmail.com', '$2y$10$3Awz1HXdFRNrj4jqP8hJMOu.G0Mzn1z7f1KSTPjL4Y4knagjxVjxW', 'usuario', 1),
(4, 'Camavaro', 'hectorvaquerano30@gmail.com', '$2y$10$KAgCyRPb6JEwkEZa8cOCvOEBxibr/qIpJvfT/18fM1w4jmISV601m', 'admin', 1),
(5, 'vladimir sebastian', 'vladimir23@gmail.com', '$2y$10$YQhSuhuHAs38/Enxqdr3UOq.QNupRdU9X8AssaRKVsRUd9Wxyp98e', 'usuario', 1),
(6, 'Isaac', 'isaacarcamo@gmail.com', '$2y$10$HmMcO7clK69bN/sGaijFauCHsAuC4VRbvqbWMZ1hemBZbgScGuThe', 'usuario', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`IdCategoria`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`IdCompra`),
  ADD KEY `IdProveedor` (`IdProveedor`);

--
-- Indices de la tabla `detallecompras`
--
ALTER TABLE `detallecompras`
  ADD PRIMARY KEY (`IdDetalle`),
  ADD KEY `IdCompra` (`IdCompra`),
  ADD KEY `IdProducto` (`IdProducto`);

--
-- Indices de la tabla `movimientosinventario`
--
ALTER TABLE `movimientosinventario`
  ADD PRIMARY KEY (`IdMovimiento`),
  ADD KEY `IdProducto` (`IdProducto`),
  ADD KEY `IdUsuario` (`IdUsuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`IdProducto`),
  ADD KEY `IdCategoria` (`IdCategoria`),
  ADD KEY `IdProveedor` (`IdProveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`IdProveedor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUsuario`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `IdCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `IdCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `detallecompras`
--
ALTER TABLE `detallecompras`
  MODIFY `IdDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `movimientosinventario`
--
ALTER TABLE `movimientosinventario`
  MODIFY `IdMovimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `IdProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `IdProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`IdProveedor`) REFERENCES `proveedores` (`IdProveedor`);

--
-- Filtros para la tabla `detallecompras`
--
ALTER TABLE `detallecompras`
  ADD CONSTRAINT `detallecompras_ibfk_1` FOREIGN KEY (`IdCompra`) REFERENCES `compras` (`IdCompra`),
  ADD CONSTRAINT `detallecompras_ibfk_2` FOREIGN KEY (`IdProducto`) REFERENCES `productos` (`IdProducto`);

--
-- Filtros para la tabla `movimientosinventario`
--
ALTER TABLE `movimientosinventario`
  ADD CONSTRAINT `movimientosinventario_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `productos` (`IdProducto`),
  ADD CONSTRAINT `movimientosinventario_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`IdCategoria`) REFERENCES `categorias` (`IdCategoria`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`IdProveedor`) REFERENCES `proveedores` (`IdProveedor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;