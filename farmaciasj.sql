-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-05-2026 a las 07:49:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

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
(9, 1, '2026-04-27 19:27:31', 7.50),
(10, 5, '2026-05-03 02:41:41', 6.45),
(11, 5, '2026-05-03 02:48:00', 48.10),
(12, 4, '2026-05-03 02:48:51', 58.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallecompras`
--

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
(7, 9, 9, 1, 2.00),
(9, 10, 29, 2, 1.75),
(10, 10, 28, 1, 2.95),
(11, 11, 23, 5, 8.90),
(12, 11, 20, 2, 1.80),
(13, 12, 13, 9, 6.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientosinventario`
--

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
(3, 9, 4, 'ENTRADA', 5, '2026-04-27 19:39:13'),
(5, 29, 9, 'SALIDA', 2, '2026-05-03 02:41:41'),
(6, 28, 9, 'SALIDA', 1, '2026-05-03 02:41:41'),
(7, 10, 9, 'ENTRADA', 5, '2026-05-03 02:42:29'),
(8, 23, 10, 'SALIDA', 5, '2026-05-03 02:48:00'),
(9, 20, 10, 'SALIDA', 2, '2026-05-03 02:48:00'),
(10, 13, 10, 'SALIDA', 9, '2026-05-03 02:48:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

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
(9, 'ACETAMINOFEN BAYER', 'PARA DOLOR', 2.00, '2030-12-25', 45, 10, 1, 1),
(10, 'Aspirina 100mg', 'Alivio del dolor leve', 1.50, '2027-05-10', 55, 10, 1, 4),
(11, 'Diclofenaco 50mg', 'Antiinflamatorio', 2.75, '2027-03-15', 40, 10, 1, 5),
(12, 'Jarabe para la tos', 'Alivio de tos seca', 3.25, '2026-11-20', 30, 5, 1, 6),
(13, 'Azitromicina 500mg', 'Antibiótico de amplio espectro', 6.50, '2027-01-01', 16, 5, 2, 4),
(14, 'Ciprofloxacino 500mg', 'Tratamiento infecciones', 5.80, '2027-06-30', 20, 5, 2, 5),
(15, 'Vitamina C 500mg', 'Refuerza el sistema inmune', 4.00, '2028-02-15', 60, 10, 3, 6),
(16, 'Multivitaminas', 'Suplemento completo', 7.50, '2028-08-01', 35, 10, 3, 4),
(17, 'Alcohol 70%', 'Desinfección', 1.25, '2029-01-01', 100, 20, 4, 5),
(18, 'Gel antibacterial', 'Elimina bacterias', 2.20, '2027-09-10', 80, 15, 4, 6),
(19, 'Shampoo anticaspa', 'Cuidado capilar', 5.00, '2028-04-22', 45, 10, 4, 4),
(20, 'Gasas estériles', 'Uso médico', 1.80, '2030-01-01', 68, 15, 5, 5),
(21, 'Vendas adhesivas', 'Curaciones rápidas', 2.10, '2030-01-01', 65, 15, 5, 6),
(22, 'Agua oxigenada', 'Desinfectante', 1.40, '2029-06-01', 50, 10, 5, 4),
(23, 'Termómetro digital', 'Medición de temperatura', 8.90, '2032-01-01', 10, 3, 5, 5),
(24, 'Omeprazol 20mg', 'Protector gástrico', 3.90, '2027-07-07', 40, 10, 1, 6),
(25, 'Loratadina', 'Antialérgico', 2.60, '2027-10-10', 55, 10, 1, 4),
(26, 'Ibuprofeno infantil', 'Para niños', 3.10, '2026-12-30', 30, 5, 1, 5),
(27, 'Enjuague bucal', 'Higiene oral', 4.50, '2028-03-03', 35, 10, 4, 6),
(28, 'Crema antibacterial', 'Uso tópico', 2.95, '2027-05-05', 24, 5, 4, 4),
(29, 'Suero oral', 'Hidratación', 1.75, '2026-09-09', 58, 15, 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

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
(3, 'Cesar Hernandez', 'cesaredu@gmail.com', '$2y$10$3Awz1HXdFRNrj4jqP8hJMOu.G0Mzn1z7f1KSTPjL4Y4knagjxVjxW', 'usuario', 1),
(4, 'Héctor Rodríguez', 'hectorvaquerano30@gmail.com', '$2y$10$KAgCyRPb6JEwkEZa8cOCvOEBxibr/qIpJvfT/18fM1w4jmISV601m', 'admin', 1),
(5, 'Vladimir Sebastian', 'vladimir23@gmail.com', '$2y$10$YQhSuhuHAs38/Enxqdr3UOq.QNupRdU9X8AssaRKVsRUd9Wxyp98e', 'usuario', 1),
(6, 'Julissa Delgado', 'julissa@gmail.com', '$2y$10$HmMcO7clK69bN/sGaijFauCHsAuC4VRbvqbWMZ1hemBZbgScGuThe', 'usuario', 1),
(8, 'Jose Fernandez', 'jose@gmail.com', '$2y$10$BkUofwgaMqHAaT4OHo5X8eEThU1ryF.fBp89SSkMe45j6XvWQ/Ph2', 'usuario', 1),
(9, 'Juan Bernal', 'bernal@gmail.com', '$2y$10$4bSrFm01FZDZjknxuhfB5OeuVv7V3rTuZLhVwdUvNq0UL01AOwrCm', 'usuario', 1),
(10, 'Marcos Vaquerano', 'vaquerano@gmail.com', '$2y$10$hMrPTh8V1YKiSDvVjumkd.8B7AayGOGZmRCd0/RmdQ1UvCZQ6Mr.G', 'usuario', 1);

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
  MODIFY `IdCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `detallecompras`
--
ALTER TABLE `detallecompras`
  MODIFY `IdDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `movimientosinventario`
--
ALTER TABLE `movimientosinventario`
  MODIFY `IdMovimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `IdProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `IdProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
