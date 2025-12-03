-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 04:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `filey_cs`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `idCategoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`idCategoria`, `nombre`, `descripcion`) VALUES
(2, 'Familiar', 'Evento abierto a todo público'),
(3, 'Educativo', 'Evento adecuado para estudiantes'),
(4, 'Horror', 'Presentación de libros / conferencias de terror'),
(5, 'Infantil', 'Evento para niños');

-- --------------------------------------------------------

--
-- Table structure for table `conferencias`
--

DROP TABLE IF EXISTS `conferencias`;
CREATE TABLE `conferencias` (
  `idEvento` int(11) NOT NULL,
  `tipoConferencia` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conferencias`
--

INSERT INTO `conferencias` (`idEvento`, `tipoConferencia`) VALUES
(1, 'Presentación de libro');

-- --------------------------------------------------------

--
-- Table structure for table `constancias`
--

DROP TABLE IF EXISTS `constancias`;
CREATE TABLE `constancias` (
  `idConstancia` int(11) NOT NULL,
  `fechaEmision` datetime DEFAULT current_timestamp(),
  `codigoVerificacion` varchar(64) NOT NULL,
  `idRegistro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `editoriales`
--

DROP TABLE IF EXISTS `editoriales`;
CREATE TABLE `editoriales` (
  `idEditorial` int(11) NOT NULL,
  `nombreEditorial` varchar(100) NOT NULL,
  `numPuestoEditorial` int(11) DEFAULT NULL,
  `ubicacionPuestoEditorial` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `editoriales`
--

INSERT INTO `editoriales` (`idEditorial`, `nombreEditorial`, `numPuestoEditorial`, `ubicacionPuestoEditorial`) VALUES
(1, 'Planeta', 1, 'Entrada');

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `idEvento` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ponente` varchar(100) DEFAULT NULL,
  `numParticipantes` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFinal` time NOT NULL,
  `tipoCupo` varchar(20) DEFAULT NULL,
  `tipoEvento` enum('conferencia','taller','premiacion') NOT NULL,
  `idCategoria` int(11) DEFAULT NULL,
  `idSalon` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`idEvento`, `titulo`, `descripcion`, `ponente`, `numParticipantes`, `fecha`, `horaInicio`, `horaFinal`, `tipoCupo`, `tipoEvento`, `idCategoria`, `idSalon`, `imagen`) VALUES
(1, 'Relatos de la Noche', 'Presentación del libro \"Relatos de la noche\" por el autor. ', 'Uriel Reyes, Raúl Lara', 70, '2025-12-10', '12:00:00', '13:00:00', 'Limitado', 'conferencia', 4, 3, 'evento_691d5a5cbebdf.jpg'),
(2, 'Gigante', 'Taller para niños de 6 a 12 años', 'Nancy Mendoza, Oliver Charles', 30, '2025-12-14', '17:00:00', '17:50:00', 'Limitado', 'taller', 5, 1, 'evento_691d604362397.jpg'),
(4, 'Entrega del Premio Excelencia en las Letras José Emilio Pacheco', 'Premiación a David Toscana por la FILEY', 'UC-Mexicanistas, FILEY-UADY', 100, '2025-12-13', '10:00:00', '13:00:00', 'Abierto', 'premiacion', 2, 1, 'evento_691d622664ed5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `premiaciones`
--

DROP TABLE IF EXISTS `premiaciones`;
CREATE TABLE `premiaciones` (
  `idEvento` int(11) NOT NULL,
  `ganadorPremiacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `premiaciones`
--

INSERT INTO `premiaciones` (`idEvento`, `ganadorPremiacion`) VALUES
(4, 'David Toscana');

-- --------------------------------------------------------

--
-- Table structure for table `registros`
--

DROP TABLE IF EXISTS `registros`;
CREATE TABLE `registros` (
  `idRegistro` int(11) NOT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `asistio` tinyint(1) DEFAULT 0,
  `idUsuario` int(11) NOT NULL,
  `idEvento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registros`
--

INSERT INTO `registros` (`idRegistro`, `fechaRegistro`, `asistio`, `idUsuario`, `idEvento`) VALUES
(1, '2025-11-19 08:58:47', 0, 2, 1),
(2, '2025-11-19 12:21:03', 1, 2, 2),
(3, '2025-11-19 16:05:24', 0, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `salones`
--

DROP TABLE IF EXISTS `salones`;
CREATE TABLE `salones` (
  `idSalon` int(11) NOT NULL,
  `nombreSalon` varchar(50) NOT NULL,
  `maxCapacidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salones`
--

INSERT INTO `salones` (`idSalon`, `nombreSalon`, `maxCapacidad`) VALUES
(1, 'Uxmal', 100),
(2, 'Izamal', 50),
(3, 'Dzibilchaltún', 100);

-- --------------------------------------------------------

--
-- Table structure for table `talleres`
--

DROP TABLE IF EXISTS `talleres`;
CREATE TABLE `talleres` (
  `idEvento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talleres`
--

INSERT INTO `talleres` (`idEvento`) VALUES
(2);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rolUsuario` enum('admin','usuario') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `email`, `password`, `rolUsuario`) VALUES
(2, 'Karina Puch', 'a19200896@alumnos.uady.mx', '$2y$10$unjHLcKTGoFS0lfmB6rEXeqiwbkeCz8hkdf8RLcGNlcE.8ozT.E8a', 'usuario'),
(3, 'Gabriela Puch', 'gaby171402@gmail.com', '$2y$10$r99lcAgUiGMxlzZKrBE7Nu6yb8x7olvHM46pcPo0rUvI/4eeWdCWC', 'admin'),
(4, 'Jesus Antonio Tec Bonilla', 'jesustecas@outlook.com', '$2y$10$nVhorBn4j5cuJsQA.44ZZeFX7.pcnkaE2GRD9MjayxVOpOG5ldG6q', 'usuario');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indexes for table `conferencias`
--
ALTER TABLE `conferencias`
  ADD PRIMARY KEY (`idEvento`);

--
-- Indexes for table `constancias`
--
ALTER TABLE `constancias`
  ADD PRIMARY KEY (`idConstancia`),
  ADD UNIQUE KEY `codigoVerificacion` (`codigoVerificacion`),
  ADD UNIQUE KEY `idRegistro` (`idRegistro`);

--
-- Indexes for table `editoriales`
--
ALTER TABLE `editoriales`
  ADD PRIMARY KEY (`idEditorial`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`idEvento`),
  ADD KEY `idCategoria` (`idCategoria`),
  ADD KEY `idSalon` (`idSalon`);

--
-- Indexes for table `premiaciones`
--
ALTER TABLE `premiaciones`
  ADD PRIMARY KEY (`idEvento`);

--
-- Indexes for table `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`idRegistro`),
  ADD UNIQUE KEY `idUsuario` (`idUsuario`,`idEvento`),
  ADD KEY `idEvento` (`idEvento`);

--
-- Indexes for table `salones`
--
ALTER TABLE `salones`
  ADD PRIMARY KEY (`idSalon`);

--
-- Indexes for table `talleres`
--
ALTER TABLE `talleres`
  ADD PRIMARY KEY (`idEvento`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `constancias`
--
ALTER TABLE `constancias`
  MODIFY `idConstancia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `editoriales`
--
ALTER TABLE `editoriales`
  MODIFY `idEditorial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `idEvento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `registros`
--
ALTER TABLE `registros`
  MODIFY `idRegistro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `salones`
--
ALTER TABLE `salones`
  MODIFY `idSalon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conferencias`
--
ALTER TABLE `conferencias`
  ADD CONSTRAINT `conferencias_ibfk_1` FOREIGN KEY (`idEvento`) REFERENCES `eventos` (`idEvento`) ON DELETE CASCADE;

--
-- Constraints for table `constancias`
--
ALTER TABLE `constancias`
  ADD CONSTRAINT `constancias_ibfk_1` FOREIGN KEY (`idRegistro`) REFERENCES `registros` (`idRegistro`) ON DELETE CASCADE;

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`idCategoria`) REFERENCES `categorias` (`idCategoria`),
  ADD CONSTRAINT `eventos_ibfk_2` FOREIGN KEY (`idSalon`) REFERENCES `salones` (`idSalon`);

--
-- Constraints for table `premiaciones`
--
ALTER TABLE `premiaciones`
  ADD CONSTRAINT `premiaciones_ibfk_1` FOREIGN KEY (`idEvento`) REFERENCES `eventos` (`idEvento`) ON DELETE CASCADE;

--
-- Constraints for table `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `registros_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`),
  ADD CONSTRAINT `registros_ibfk_2` FOREIGN KEY (`idEvento`) REFERENCES `eventos` (`idEvento`);

--
-- Constraints for table `talleres`
--
ALTER TABLE `talleres`
  ADD CONSTRAINT `talleres_ibfk_1` FOREIGN KEY (`idEvento`) REFERENCES `eventos` (`idEvento`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
