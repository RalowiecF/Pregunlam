-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 03:45 AM
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
-- Database: `pregunlam`
--
CREATE DATABASE IF NOT EXISTS `pregunlam` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pregunlam`;

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
                             `idCategoria` int(11) NOT NULL,
                             `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `descripcion`) VALUES
                                                           (6, 'Arte'),
                                                           (10, 'Ciencia'),
                                                           (7, 'Cine'),
                                                           (4, 'Comida'),
                                                           (1, 'Cultura General'),
                                                           (13, 'Deporte'),
                                                           (3, 'Entretenimiento'),
                                                           (2, 'Geografía'),
                                                           (9, 'Historia'),
                                                           (8, 'Literatura'),
                                                           (5, 'Musica'),
                                                           (11, 'Naturaleza'),
                                                           (12, 'Series'),
                                                           (14, 'Tecnología');

-- --------------------------------------------------------

--
-- Table structure for table `entorno`
--

CREATE TABLE `entorno` (
                           `idEntorno` int(11) NOT NULL,
                           `descripcion` varchar(50) NOT NULL,
                           `idUsuarioCreador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estado`
--

CREATE TABLE `estado` (
                          `idEstado` int(11) NOT NULL,
                          `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estado`
--

INSERT INTO `estado` (`idEstado`, `descripcion`) VALUES
                                                     (1, 'Sugerida'),
                                                     (2, 'Vigente'),
                                                     (3, 'Reportada');

-- --------------------------------------------------------

--
-- Table structure for table `nivel`
--

CREATE TABLE `nivel` (
                         `idNivel` int(11) NOT NULL,
                         `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nivel`
--

INSERT INTO `nivel` (`idNivel`, `descripcion`) VALUES
                                                   (1, 'Muy facil'),
                                                   (2, 'Facil'),
                                                   (3, 'Normal'),
                                                   (4, 'Dificil'),
                                                   (5, 'Muy dificil');

-- --------------------------------------------------------

--
-- Table structure for table `partida`
--

CREATE TABLE `partida` (
                           `idPartida` int(11) NOT NULL,
                           `fechaPartida` datetime NOT NULL DEFAULT current_timestamp(),
                           `duracionPartida` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partida_tiene_pregunta`
--

CREATE TABLE `partida_tiene_pregunta` (
                                          `idPartida` int(11) NOT NULL,
                                          `idPregunta` int(11) NOT NULL,
                                          `idResultado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pregunta`
--

CREATE TABLE `pregunta` (
                            `idPregunta` int(11) NOT NULL,
                            `enunciado` text NOT NULL,
                            `respuestaCorrecta` text NOT NULL,
                            `fechaCreacion` datetime NOT NULL DEFAULT current_timestamp(),
                            `cantidadApariciones` int(11) DEFAULT 0,
                            `cantidadAciertos` int(11) DEFAULT 0,
                            `idCategoria` int(11) NOT NULL,
                            `idEntorno` int(11) DEFAULT NULL,
                            `idNivel` int(11) NOT NULL DEFAULT 3,
                            `idEstado` int(11) NOT NULL DEFAULT 1,
                            `motivoReporte` text DEFAULT NULL,
                            `idUsuarioCreador` int(11) DEFAULT NULL,
                            `idUsuarioQueReporto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pregunta`
--

INSERT INTO `pregunta` (`idPregunta`, `enunciado`, `respuestaCorrecta`, `fechaCreacion`, `cantidadApariciones`, `cantidadAciertos`, `idCategoria`, `idEntorno`, `idNivel`, `idEstado`, `motivoReporte`, `idUsuarioCreador`, `idUsuarioQueReporto`) VALUES
 (1, '¿Cuál es el océano más grande del mundo?', 'Océano Pacífico', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
 (2, '¿En qué país se encuentra la Torre Eiffel?', 'Francia', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
 (3, '¿Quién escribió \"Cien años de soledad\"?', 'Gabriel García Márquez', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
 (4, '¿Cuál es el planeta más cercano al Sol?', 'Mercurio', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
 (5, '¿Qué instrumento tiene teclas, cuerdas y martillos?', 'Piano', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL);


-- --------------------------------------------------------

--
-- Table structure for table `respuesta_incorrecta`
--

CREATE TABLE `respuesta_incorrecta` (
                                        `idRespuestaIncorrecta` int(11) NOT NULL,
                                        `respuestaIncorrecta` text NOT NULL,
                                        `idPregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `respuesta_incorrecta`
--

INSERT INTO `respuesta_incorrecta` (`idRespuestaIncorrecta`, `respuestaIncorrecta`, `idPregunta`) VALUES
                                                                                                      (1, 'Océano Atlántico', 1),
                                                                                                      (2, 'Océano Índico', 1),
                                                                                                      (3, 'Océano Ártico', 1),
                                                                                                      (4, 'Italia', 2),
                                                                                                      (5, 'España', 2),
                                                                                                      (6, 'Alemania', 2),
                                                                                                      (7, 'Pablo Neruda', 3),
                                                                                                      (8, 'Julio Cortázar', 3),
                                                                                                      (9, 'Mario Vargas Llosa', 3),
                                                                                                      (10, 'Venus', 4),
                                                                                                      (11, 'Tierra', 4),
                                                                                                      (12, 'Marte', 4),
                                                                                                      (13, 'Violín', 5),
                                                                                                      (14, 'Arpa', 5),
                                                                                                      (15, 'Flauta', 5);


-- --------------------------------------------------------

--
-- Table structure for table `resultado`
--

CREATE TABLE `resultado` (
                             `idResultado` int(11) NOT NULL,
                             `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resultado`
--

INSERT INTO `resultado` (`idResultado`, `descripcion`) VALUES
                                                           (1, 'Pendiente'),
                                                           (2, 'Correcta'),
                                                           (3, 'Incorrecta'),
                                                           (4, 'Abandonada'),
                                                           (5, 'Salteada con trampa');

-- --------------------------------------------------------

--
-- Table structure for table `sexo`
--

CREATE TABLE `sexo` (
                        `idSexo` int(11) NOT NULL,
                        `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sexo`
--

INSERT INTO `sexo` (`idSexo`, `descripcion`) VALUES
                                                 (1, 'Femenino'),
                                                 (2, 'Masculino'),
                                                 (3, 'Indefinido');

-- --------------------------------------------------------

--
-- Table structure for table `tipousuario`
--

CREATE TABLE `tipousuario` (
                               `idTipoUsuario` int(11) NOT NULL,
                               `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipousuario`
--

INSERT INTO `tipousuario` (`idTipoUsuario`, `descripcion`) VALUES
                                                               (1, 'Administrador'),
                                                               (2, 'Editor'),
                                                               (3, 'Jugador');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
                           `idUsuario` int(11) NOT NULL,
                           `nombreUsuario` varchar(50) NOT NULL,
                           `mail` varchar(50) NOT NULL,
                           `nombre` varchar(50) NOT NULL,
                           `apellido` varchar(50) NOT NULL,
                           `anioNacimiento` int(11) DEFAULT NULL,
                           `idSexo` int(11) DEFAULT NULL,
                           `contrasenia` varchar(50) NOT NULL,
                           `fotoPerfil` varchar(50) DEFAULT NULL,
                           `fechaRegistro` datetime NOT NULL DEFAULT current_timestamp(),
                           `latitud` decimal(10,8) DEFAULT NULL,
                           `longitud` decimal(11,8) DEFAULT NULL,
                           `ciudad` varchar(50) DEFAULT NULL,
                           `pais` varchar(50) DEFAULT NULL,
                           `cantidadTrampas` int(11) DEFAULT 0,
                           `cantidadPreguntas` int(11) DEFAULT 0,
                           `cantidadAciertos` int(11) DEFAULT 0,
                           `idTipoUsuario` int(11) NOT NULL DEFAULT 3,
                           `idNivel` int(11) NOT NULL DEFAULT 3,
                           `idEntorno` int(11) DEFAULT NULL,
                           `mailVerificado` tinyint(1) NOT NULL DEFAULT 0,
                           `tokenVerificacion` varchar(64) DEFAULT NULL,
                           `puntaje` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nombreUsuario`, `mail`, `nombre`, `apellido`, `anioNacimiento`, `idSexo`, `contrasenia`, `fotoPerfil`, `fechaRegistro`, `latitud`, `longitud`, `ciudad`, `pais`, `cantidadTrampas`, `cantidadPreguntas`, `cantidadAciertos`, `idTipoUsuario`, `idNivel`, `idEntorno`, `mailVerificado`, `tokenVerificacion`, `puntaje`) VALUES
                                                                                                                                                                                                                                                                                                                                                                 (1, 'Fede', 'fralowiec115@alumno.unlam.edu.ar', 'Federico', 'Ralowiec', 1987, 2, '1234', 'Anonimo.png', '2025-10-17 13:39:50', -34.67034000, -58.56425000, 'San Justo', 'Argentina', 10000, 0, 0, 1, 3, NULL, 1, NULL, 0),
                                                                                                                                                                                                                                                                                                                                                                 (2, 'Fran', 'francisco@gmail.com', 'Fracisco', 'Larralde', 2000, 2, '1234', 'Anonimo.png', '2025-10-17 13:56:24', -34.67034000, -58.56425000, 'San Justo', 'Argentina', 10000, 0, 0, 1, 3, NULL, 1, NULL, 0),
                                                                                                                                                                                                                                                                                                                                                                 (3, 'Dario', 'Dario@gmail.com', 'Dario', 'Miguel', 2000, 2, '1234', 'Anonimo.png', '2025-10-17 13:56:24', -34.67034000, -58.56425000, 'San Justo', 'Argentina', 10000, 0, 0, 1, 3, NULL, 1, NULL, 0),
                                                                                                                                                                                                                                                                                                                                                                 (4, 'Facu', 'Facu@gmail.com', 'Facundo', 'D Aranno', 2000, 2, '1234', 'Anonimo.png', '2025-10-17 13:56:24', -34.67034000, -58.56425000, 'San Justo', 'Argentina', 10000, 0, 0, 1, 3, NULL, 1, NULL, 0),
                                                                                                                                                                                                                                                                                                                                                                 (5, 'Ale', 'Ale@gmail.com', 'Alejandro', 'Rusticcini', 2000, 2, '1234', 'Anonimo.png', '2025-10-17 13:56:24', -34.67034000, -58.56425000, 'San Justo', 'Argentina', 10000, 0, 0, 1, 3, NULL, 1, NULL, 0),
                                                                                                                                                                                                                                                                                                                                                                 (6, 'Omar', 'Omar@gmail.com', 'Omar', 'Sosa', 2000, 2, '1234', 'Anonimo.png', '2025-10-17 13:56:24', -34.67034000, -58.56425000, 'San Justo', 'Argentina', 10000, 0, 0, 1, 3, NULL, 1, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usuario_contacto`
--

CREATE TABLE `usuario_contacto` (
                                    `idUsuario` int(11) NOT NULL,
                                    `idContacto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuario_juega_partida`
--

CREATE TABLE `usuario_juega_partida` (
                                         `idUsuario` int(11) NOT NULL,
                                         `idPartida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
    ADD PRIMARY KEY (`idCategoria`),
    ADD UNIQUE KEY `uq_Categoria_Descripcion` (`descripcion`);

--
-- Indexes for table `entorno`
--
ALTER TABLE `entorno`
    ADD PRIMARY KEY (`idEntorno`),
    ADD UNIQUE KEY `descripcion` (`descripcion`),
    ADD KEY `pk_Entorno_Usuario` (`idUsuarioCreador`);

--
-- Indexes for table `estado`
--
ALTER TABLE `estado`
    ADD PRIMARY KEY (`idEstado`);

--
-- Indexes for table `nivel`
--
ALTER TABLE `nivel`
    ADD PRIMARY KEY (`idNivel`);

--
-- Indexes for table `partida`
--
ALTER TABLE `partida`
    ADD PRIMARY KEY (`idPartida`);

--
-- Indexes for table `partida_tiene_pregunta`
--
ALTER TABLE `partida_tiene_pregunta`
    ADD PRIMARY KEY (`idPartida`,`idPregunta`),
    ADD KEY `fk_PTP_Pregunta_idx` (`idPregunta`),
    ADD KEY `fk_PTP_Partida_idx` (`idPartida`),
    ADD KEY `fk_PTP_Resultado` (`idResultado`);

--
-- Indexes for table `pregunta`
--
ALTER TABLE `pregunta`
    ADD PRIMARY KEY (`idPregunta`),
    ADD KEY `fk_Pregunta_Categoria_idx` (`idCategoria`),
    ADD KEY `fk_Pregunta_Nivel_idx` (`idNivel`),
    ADD KEY `fk_Pregunta_Estado_idx` (`idEstado`),
    ADD KEY `fk_Pregunta_Entorno` (`idEntorno`),
    ADD KEY `fk_Pregunta_UsuarioCreador` (`idUsuarioCreador`),
    ADD KEY `fk_Pregunta_UsuarioQueReporto` (`idUsuarioQueReporto`);

--
-- Indexes for table `respuesta_incorrecta`
--
ALTER TABLE `respuesta_incorrecta`
    ADD PRIMARY KEY (`idRespuestaIncorrecta`),
    ADD KEY `fk_Respuesta_Incorrecta_Pregunta_idx` (`idPregunta`);

--
-- Indexes for table `resultado`
--
ALTER TABLE `resultado`
    ADD PRIMARY KEY (`idResultado`);

--
-- Indexes for table `sexo`
--
ALTER TABLE `sexo`
    ADD PRIMARY KEY (`idSexo`);

--
-- Indexes for table `tipousuario`
--
ALTER TABLE `tipousuario`
    ADD PRIMARY KEY (`idTipoUsuario`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
    ADD PRIMARY KEY (`idUsuario`),
    ADD UNIQUE KEY `nombreUsuario` (`nombreUsuario`),
    ADD UNIQUE KEY `mail` (`mail`),
    ADD KEY `fk_Usuario_Nivel_idx` (`idNivel`),
    ADD KEY `fk_Usuario_TipoUsuario` (`idTipoUsuario`),
    ADD KEY `fk_Usuario_Sexo` (`idSexo`),
    ADD KEY `fk_Usuario_Entorno` (`idEntorno`);

--
-- Indexes for table `usuario_contacto`
--
ALTER TABLE `usuario_contacto`
    ADD PRIMARY KEY (`idUsuario`,`idContacto`),
    ADD KEY `idContacto` (`idContacto`);

--
-- Indexes for table `usuario_juega_partida`
--
ALTER TABLE `usuario_juega_partida`
    ADD PRIMARY KEY (`idUsuario`,`idPartida`),
    ADD KEY `fk_UJP_Usuario_idx` (`idUsuario`),
    ADD KEY `fk_UJP_Partida` (`idPartida`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
    MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `entorno`
--
ALTER TABLE `entorno`
    MODIFY `idEntorno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estado`
--
ALTER TABLE `estado`
    MODIFY `idEstado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nivel`
--
ALTER TABLE `nivel`
    MODIFY `idNivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `partida`
--
ALTER TABLE `partida`
    MODIFY `idPartida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pregunta`
--
ALTER TABLE `pregunta`
    MODIFY `idPregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `respuesta_incorrecta`
--
ALTER TABLE `respuesta_incorrecta`
    MODIFY `idRespuestaIncorrecta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=631;

--
-- AUTO_INCREMENT for table `resultado`
--
ALTER TABLE `resultado`
    MODIFY `idResultado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sexo`
--
ALTER TABLE `sexo`
    MODIFY `idSexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tipousuario`
--
ALTER TABLE `tipousuario`
    MODIFY `idTipoUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
    MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `entorno`
--
ALTER TABLE `entorno`
    ADD CONSTRAINT `pk_Entorno_Usuario` FOREIGN KEY (`idUsuarioCreador`) REFERENCES `usuario` (`idUsuario`);

--
-- Constraints for table `partida_tiene_pregunta`
--
ALTER TABLE `partida_tiene_pregunta`
    ADD CONSTRAINT `fk_PTP_Partida` FOREIGN KEY (`idPartida`) REFERENCES `partida` (`idPartida`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_PTP_Pregunta` FOREIGN KEY (`idPregunta`) REFERENCES `pregunta` (`idPregunta`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_PTP_Resultado` FOREIGN KEY (`idResultado`) REFERENCES `resultado` (`idResultado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pregunta`
--
ALTER TABLE `pregunta`
    ADD CONSTRAINT `fk_Pregunta_Categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`) ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Pregunta_Entorno` FOREIGN KEY (`idEntorno`) REFERENCES `entorno` (`idEntorno`) ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Pregunta_Estado` FOREIGN KEY (`idEstado`) REFERENCES `estado` (`idEstado`) ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Pregunta_Nivel` FOREIGN KEY (`idNivel`) REFERENCES `nivel` (`idNivel`) ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Pregunta_UsuarioCreador` FOREIGN KEY (`idUsuarioCreador`) REFERENCES `usuario` (`idUsuario`),
    ADD CONSTRAINT `fk_Pregunta_UsuarioQueReporto` FOREIGN KEY (`idUsuarioQueReporto`) REFERENCES `usuario` (`idUsuario`);

--
-- Constraints for table `respuesta_incorrecta`
--
ALTER TABLE `respuesta_incorrecta`
    ADD CONSTRAINT `fk_Respuesta_Incorrecta_Pregunta` FOREIGN KEY (`idPregunta`) REFERENCES `pregunta` (`idPregunta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
    ADD CONSTRAINT `fk_Usuario_Entorno` FOREIGN KEY (`idEntorno`) REFERENCES `entorno` (`idEntorno`) ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Usuario_Nivel` FOREIGN KEY (`idNivel`) REFERENCES `nivel` (`idNivel`) ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Usuario_Sexo` FOREIGN KEY (`idSexo`) REFERENCES `sexo` (`idSexo`) ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_Usuario_TipoUsuario` FOREIGN KEY (`idTipoUsuario`) REFERENCES `tipousuario` (`idTipoUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `usuario_contacto`
--
ALTER TABLE `usuario_contacto`
    ADD CONSTRAINT `usuario_contacto_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE,
    ADD CONSTRAINT `usuario_contacto_ibfk_2` FOREIGN KEY (`idContacto`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE;

--
-- Constraints for table `usuario_juega_partida`
--
ALTER TABLE `usuario_juega_partida`
    ADD CONSTRAINT `fk_UJP_Partida` FOREIGN KEY (`idPartida`) REFERENCES `partida` (`idPartida`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_UJP_Usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
