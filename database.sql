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
                                                                                                                                                                                                                                                       (5, '¿Qué instrumento tiene teclas, cuerdas y martillos?', 'Piano', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (6, '¿Qué gas necesitan las plantas para hacer la fotosíntesis?', 'Dióxido de carbono', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (7, '¿Cuál es el río más largo del mundo?', 'Nilo', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (8, '¿Quién pintó la Mona Lisa?', 'Leonardo da Vinci', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (9, '¿Qué deporte se practica en Wimbledon?', 'Tenis', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (10, '¿En qué continente se encuentra Egipto?', 'África', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (11, '¿Qué animal es conocido como el rey de la selva?', 'León', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (12, '¿Cuál es la capital de Japón?', 'Tokio', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (13, '¿Cuántos lados tiene un triángulo?', 'Tres', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (14, '¿Cuál es el metal más utilizado en la construcción?', 'Acero', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (15, '¿Qué lengua se habla en Brasil?', 'Portugués', '2025-10-22 13:11:37', 0, 0, 1, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (16, '¿Cuál es la capital de Australia?', 'Canberra', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (17, '¿Qué cordillera atraviesa América del Sur de norte a sur?', 'Los Andes', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (18, '¿En qué país se encuentra el desierto del Sahara?', 'Mayormente en Argelia', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (19, '¿Cuál es el país más grande del mundo?', 'Rusia', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (20, '¿Qué país tiene más islas en el mundo?', 'Suecia', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (21, '¿Cuál es el continente con más países?', 'África', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (22, '¿Qué país europeo tiene forma de bota?', 'Italia', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (23, '¿Dónde se encuentra el monte Everest?', 'Nepal', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (24, '¿Cuál es el mar que baña las costas del norte de África?', 'Mar Mediterráneo', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (25, '¿Qué país tiene como capital a Oslo?', 'Noruega', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (26, '¿Cuál es el lago más grande de América del Sur?', 'Lago Titicaca', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (27, '¿Qué línea imaginaria divide la Tierra en hemisferio norte y sur?', 'Ecuador', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (28, '¿Cuál es el país más pequeño del mundo?', 'El Vaticano', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (29, '¿En qué continente está Mongolia?', 'Asia', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (30, '¿Qué dos países están separados por el Canal de la Mancha?', 'Francia y Reino Unido', '2025-10-22 13:14:46', 0, 0, 2, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (31, '¿Quién interpreta a Iron Man en el Universo Cinematográfico de Marvel?', 'Robert Downey Jr.', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (32, '¿Cómo se llama la serie protagonizada por Eleven y ambientada en Hawkins?', 'Stranger Things', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (33, '¿Qué saga de películas tiene como protagonista a Katniss Everdeen?', 'Los Juegos del Hambre', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (34, '¿Cuál es el apellido del mago protagonista de una famosa saga de libros y películas?', 'Potter', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (35, '¿En qué serie aparece el personaje Walter White?', 'Breaking Bad', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (36, '¿Cuál es el nombre del parque en “Jurassic Park”?', 'Jurassic Park', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (37, '¿Qué famoso detective vive en el 221B de Baker Street?', 'Sherlock Holmes', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (38, '¿Cómo se llama la escuela de magia en “Harry Potter”?', 'Hogwarts', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (39, '¿Qué película animada tiene una canción titulada “Let It Go”?', 'Frozen', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (40, '¿Qué película ganó el Oscar a Mejor Película en 2020?', 'Parásitos', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (41, '¿Qué actor protagonizó “Piratas del Caribe” como Jack Sparrow?', 'Johnny Depp', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (42, '¿Cómo se llama el universo de superhéroes de DC Comics?', 'DC Universe', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (43, '¿Qué personaje de videojuegos colecciona monedas y salta sobre enemigos?', 'Mario', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (44, '¿Qué saga de ciencia ficción presenta a los Jedi y Sith?', 'Star Wars', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (45, '¿Qué plataforma produjo la serie “The Crown”?', 'Netflix', '2025-10-22 13:17:32', 0, 0, 3, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (46, '¿De qué país es originario el sushi?', 'Japón', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (47, '¿Qué ingrediente principal tiene la paella tradicional?', 'Arroz', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (48, '¿Con qué tipo de leche se elabora tradicionalmente el queso mozzarella?', 'Leche de búfala', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (49, '¿Cuál es la bebida alcohólica principal del tequila?', 'Agave', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (50, '¿Qué fruta tiene las semillas en el exterior?', 'Frutilla', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (51, '¿Qué tipo de pasta tiene forma de mariposa?', 'Farfalle', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (52, '¿Qué ingrediente da color al curry?', 'Cúrcuma', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (53, '¿Qué plato italiano consiste en capas de pasta, carne, salsa y queso?', 'Lasaña', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (54, '¿Qué vegetal es conocido por hacer llorar al ser cortado?', 'Cebolla', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (55, '¿Qué postre argentino se prepara con dulce de leche entre dos tapas?', 'Alfajor', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (56, '¿Cuál es el ingrediente principal del guacamole?', 'Palta', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (57, '¿Qué cereal se utiliza para hacer cerveza?', 'Cebada', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (58, '¿Qué tipo de carne se utiliza tradicionalmente en el gyros griego?', 'Cordero', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (59, '¿Qué condimento se obtiene del grano de mostaza?', 'Mostaza', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (60, '¿Qué bebida se prepara infusionando hojas de yerba?', 'Mate', '2025-10-22 13:20:55', 0, 0, 4, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (61, '¿Qué banda británica compuso la canción \"Bohemian Rhapsody\"?', 'Queen', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (62, '¿Quién es conocida como la \"Reina del Pop\"?', 'Madonna', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (63, '¿Qué instrumento musical tiene teclas blancas y negras?', 'Piano', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (64, '¿Qué famoso músico fue parte de los Beatles?', 'John Lennon', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (65, '¿Qué cantante argentino es autor de \"Crimen\"?', 'Gustavo Cerati', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (66, '¿Qué grupo creó el álbum \"The Dark Side of the Moon\"?', 'Pink Floyd', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (67, '¿Cuál de estos artistas es un exponente del reggaetón?', 'Bad Bunny', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (68, '¿Qué banda es famosa por la canción \"Smells Like Teen Spirit\"?', 'Nirvana', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (69, '¿Qué cantante lanzó el álbum \"1989\"?', 'Taylor Swift', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (70, '¿Qué estilo musical se asocia con el tango?', 'Argentina', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (71, '¿Quién cantó \"Imagine\"?', 'John Lennon', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (72, '¿Qué banda argentina canta \"Persiana Americana\"?', 'Soda Stereo', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (73, '¿Qué instrumento de viento es común en el jazz?', 'Saxofón', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (74, '¿Cuál de estos es un género de música electrónica?', 'House', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (75, '¿Qué compositor es famoso por su novena sinfonía?', 'Beethoven', '2025-10-22 13:22:55', 0, 0, 5, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (76, '¿Quién pintó \"La Mona Lisa\"?', 'Leonardo da Vinci', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (77, '¿Cuál es el nombre del famoso mural de Miguel Ángel en el Vaticano?', 'La Capilla Sixtina', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (78, '¿Qué movimiento artístico lideró Pablo Picasso?', 'Cubismo', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (79, '¿Qué escultor renacentista hizo el \"David\"?', 'Miguel Ángel', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (80, '¿En qué país se encuentra el museo del Louvre?', 'Francia', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (81, '¿Qué artista es conocido por sus latas de sopa Campbell?', 'Andy Warhol', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (82, '¿Quién pintó \"La noche estrellada\"?', 'Vincent van Gogh', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (83, '¿Cuál es el nombre de la famosa escultura con una figura humana pensativa?', 'El Pensador', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (84, '¿Qué artista español pintó \"Guernica\"?', 'Pablo Picasso', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (85, '¿Qué arte se practica con arcilla?', 'Cerámica', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (86, '¿Cuál de estos es un estilo arquitectónico gótico?', 'Arco apuntado', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (87, '¿Qué famoso pintor fue conocido por cortar parte de su oreja?', 'Van Gogh', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (88, '¿Qué artista argentino creó obras con formas geométricas y coloridas?', 'Rogelio Polesello', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (89, '¿Cómo se llama el arte de doblar papel?', 'Origami', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (90, '¿Qué famosa obra representa una escena con relojes derretidos?', 'La persistencia de la memoria', '2025-10-22 13:24:54', 0, 0, 6, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (91, '¿Qué película ganó el Oscar a Mejor Película en 1994?', 'Forrest Gump', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (92, '¿Quién dirigió la trilogía de El Señor de los Anillos?', 'Peter Jackson', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (93, '¿Cuál es el nombre del personaje principal en \"Matrix\"?', 'Neo', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (94, '¿Qué actor interpretó al Joker en \"The Dark Knight\"?', 'Heath Ledger', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (95, '¿Qué película animada presenta a un león llamado Simba?', 'El Rey León', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (96, '¿Qué película populariza la frase \"Yo soy tu padre\"?', 'Star Wars: Episodio V', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (97, '¿Quién dirigió \"Titanic\"?', 'James Cameron', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (98, '¿Qué actor protagonizó \"Misión Imposible\"?', 'Tom Cruise', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (99, '¿Cuál es la película con más premios Oscar en la historia?', 'Ben-Hur', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (100, '¿Qué película argentina ganó el Oscar a Mejor Película Extranjera en 1985?', 'La historia oficial', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (101, '¿Qué actor interpretó a Jack en \"Titanic\"?', 'Leonardo DiCaprio', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (102, '¿Qué película tiene como personaje a Gollum?', 'El Señor de los Anillos', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (103, '¿Quién interpreta a Iron Man en el Universo Marvel?', 'Robert Downey Jr.', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (104, '¿Cuál es el nombre de la película donde un niño ve fantasmas?', 'Sexto Sentido', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (105, '¿Qué película de Tarantino tiene una escena icónica de baile?', 'Pulp Fiction', '2025-10-22 13:26:16', 0, 0, 7, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (106, '¿Quién escribió \"Cien años de soledad\"?', 'Gabriel García Márquez', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (107, '¿Cuál es el apellido del detective creado por Arthur Conan Doyle?', 'Holmes', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (108, '¿Qué obra comienza con \"En un lugar de la Mancha...\"?', 'Don Quijote de la Mancha', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (109, '¿Quién escribió \"La Odisea\"?', 'Homero', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (110, '¿Qué escritor argentino escribió \"El Aleph\"?', 'Jorge Luis Borges', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (111, '¿Qué poeta chilena ganó el Nobel en 1945?', 'Gabriela Mistral', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (112, '¿Cuál es el nombre del protagonista de \"1984\"?', 'Winston Smith', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (113, '¿Quién es el autor de \"Crimen y Castigo\"?', 'Fiódor Dostoievski', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (114, '¿Qué autor escribió \"La metamorfosis\"?', 'Franz Kafka', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (115, '¿En qué novela aparece el personaje Jay Gatsby?', 'El Gran Gatsby', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (116, '¿Quién escribió \"Rayuela\"?', 'Julio Cortázar', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (117, '¿Qué obra relata la historia de Raskólnikov?', 'Crimen y Castigo', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (118, '¿Qué novela tiene un personaje llamado Ishmael?', 'Moby Dick', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (119, '¿Quién escribió \"Orgullo y Prejuicio\"?', 'Jane Austen', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (120, '¿Qué autor escribió \"Ficciones\"?', 'Jorge Luis Borges', '2025-10-22 13:28:03', 0, 0, 8, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (121, '¿En qué año comenzó la Segunda Guerra Mundial?', '1939', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (122, '¿Quién fue el primer presidente de los Estados Unidos?', 'George Washington', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (123, '¿Qué civilización construyó las pirámides de Egipto?', 'Los egipcios', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (124, '¿Cuál fue el barco que se hundió en 1912 en su viaje inaugural?', 'Titanic', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (125, '¿Qué país fue liderado por Napoleón Bonaparte?', 'Francia', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (126, '¿En qué año cayó el Muro de Berlín?', '1989', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (127, '¿Qué imperio estaba gobernado por Julio César?', 'Imperio Romano', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (128, '¿Quién descubrió América en 1492?', 'Cristóbal Colón', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (129, '¿Qué evento marcó el inicio de la Revolución Francesa?', 'La toma de la Bastilla', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (130, '¿Qué guerra enfrentó al norte y al sur de Estados Unidos?', 'La Guerra de Secesión', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (131, '¿Qué país lanzó la primera bomba atómica?', 'Estados Unidos', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (132, '¿Qué civilización americana creó el calendario solar de 365 días?', 'Los mayas', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (133, '¿Quién fue el líder del movimiento de independencia en India?', 'Mahatma Gandhi', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (134, '¿Qué país inició la Primera Revolución Industrial?', 'Inglaterra', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (135, '¿Qué tratado puso fin a la Primera Guerra Mundial?', 'Tratado de Versalles', '2025-10-22 13:30:51', 0, 0, 9, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (136, '¿Cuál es la fórmula química del agua?', 'H2O', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (137, '¿Qué planeta es conocido como el planeta rojo?', 'Marte', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (138, '¿Qué órgano del cuerpo humano bombea sangre?', 'El corazón', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (139, '¿Cuál es el gas más abundante en la atmósfera terrestre?', 'Nitrógeno', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (140, '¿Cómo se llama el proceso mediante el cual las plantas hacen su alimento?', 'Fotosíntesis', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (141, '¿Qué científico formuló la ley de la gravedad?', 'Isaac Newton', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (142, '¿Cuál es la velocidad de la luz en el vacío?', '299,792 km/s', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (143, '¿Qué célula del cuerpo humano transporta oxígeno?', 'Glóbulo rojo', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (144, '¿Qué animal es el más grande del planeta?', 'La ballena azul', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (145, '¿Qué parte del átomo tiene carga positiva?', 'El protón', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (146, '¿Cuál es el metal líquido a temperatura ambiente?', 'Mercurio', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (147, '¿Qué unidad se usa para medir la intensidad de la corriente eléctrica?', 'Amperio', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (148, '¿Qué instrumento se usa para medir terremotos?', 'Sismógrafo', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (149, '¿Qué planeta tiene anillos prominentes?', 'Saturno', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (150, '¿Qué vitamina se obtiene al exponerse al sol?', 'Vitamina D', '2025-10-22 13:32:02', 0, 0, 10, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (151, '¿Cuál es el árbol más alto del mundo?', 'Secuoya', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (152, '¿Qué animal pone los huevos más grandes?', 'Avestruz', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (153, '¿Qué tipo de animal es la ballena?', 'Mamífero', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (154, '¿Qué planta es conocida por cerrar sus hojas al ser tocada?', 'Mimosa púdica', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (155, '¿Cómo se llama el ecosistema de agua salada?', 'Océano', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (156, '¿Cuál es el desierto más grande del mundo?', 'Antártico', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (157, '¿Qué gas exhalan las plantas durante la fotosíntesis?', 'Oxígeno', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (158, '¿Qué parte de la flor produce el polen?', 'Estambre', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (159, '¿Qué animal cambia de color para camuflarse?', 'Camaleón', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (160, '¿Qué árbol produce bellotas?', 'Roble', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (161, '¿Cuál es el mamífero más pequeño del mundo?', 'Murciélago abejorro', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (162, '¿Qué animal tiene la mordida más fuerte del reino animal?', 'Cocodrilo', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (163, '¿Cuál es el río más largo del mundo?', 'Amazonas', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (164, '¿Qué insecto produce miel?', 'Abeja', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (165, '¿Cuál es el único mamífero capaz de volar?', 'Murciélago', '2025-10-22 13:33:17', 0, 0, 11, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (166, '¿Cómo se llama el laboratorio donde trabaja Walter White en Breaking Bad?', 'Los Pollos Hermanos', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (167, '¿Qué personaje de Friends se casa con Monica?', 'Chandler', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (168, '¿Cuál es el apellido de Jon en Game of Thrones?', 'Snow', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (169, '¿En qué ciudad ocurre la serie Stranger Things?', 'Hawkins', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (170, '¿Qué actor interpreta a Sherlock Holmes en la serie de la BBC?', 'Benedict Cumberbatch', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (171, '¿Cuál es la profesión de Ted Mosby en How I Met Your Mother?', 'Arquitecto', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (172, '¿Qué serie popular comienza con una carta escrita desde prisión?', 'Prison Break', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (173, '¿Quién es el asesino en la primera temporada de \"You\"?', 'Joe Goldberg', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (174, '¿Qué serie presenta una empresa de papel llamada Dunder Mifflin?', 'The Office', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (175, '¿Cómo se llama el trono por el que luchan en Game of Thrones?', 'Trono de Hierro', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (176, '¿En qué serie aparece el personaje Eleven?', 'Stranger Things', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (177, '¿Cuál es el nombre del profesor en La Casa de Papel?', 'Sergio', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (178, '¿Qué serie tiene como protagonista a un científico loco y su nieto?', 'Rick y Morty', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (179, '¿En qué serie aparece el personaje llamado Tyrion Lannister?', 'Game of Thrones', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (180, '¿Qué serie se basa en las aventuras de un grupo de nerds?', 'The Big Bang Theory', '2025-10-22 13:34:30', 0, 0, 12, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (181, '¿Cuántos jugadores hay en un equipo de fútbol en el campo?', '11', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (182, '¿Quién ha ganado más títulos de Grand Slam en tenis masculino?', 'Novak Djokovic', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (183, '¿Qué país ha ganado más Copas del Mundo de fútbol?', 'Brasil', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (184, '¿En qué deporte se utiliza una pelota ovalada?', 'Rugby', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (185, '¿Cómo se llama la competencia de ciclismo más famosa del mundo?', 'Tour de France', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (186, '¿Cuál es el récord de Usain Bolt en los 100 metros?', '9.58 segundos', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (187, '¿Qué deporte se practica en Wimbledon?', 'Tenis', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (188, '¿Cuál es el nombre del trofeo que se entrega en la NBA?', 'Larry O’Brien Trophy', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (189, '¿Qué selección ganó la Copa América 2021?', 'Argentina', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (190, '¿En qué año fueron los primeros Juegos Olímpicos modernos?', '1896', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (191, '¿En qué deporte se usa el término \"strike\"?', 'Béisbol', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (192, '¿Cuántos puntos vale un triple en básquet?', '3', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (193, '¿Qué país organizó el Mundial de Fútbol 2018?', 'Rusia', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (194, '¿Qué boxeador es conocido como \"El más grande\" y también como \"The Greatest\"?', 'Muhammad Ali', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (195, '¿Cuántos sets debe ganar un jugador para ganar un partido de Grand Slam masculino?', '3 de 5', '2025-10-22 13:35:56', 0, 0, 13, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (196, '¿Quién es conocido como el padre de la computación?', 'Alan Turing', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (197, '¿Qué significa la sigla \"HTML\"?', 'HyperText Markup Language', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (198, '¿Cuál es el lenguaje de programación principal para desarrollo web frontend?', 'JavaScript', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (199, '¿Qué empresa desarrolló el sistema operativo Windows?', 'Microsoft', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (200, '¿Qué dispositivo se utiliza para almacenar datos de manera permanente en una computadora?', 'Disco duro', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (201, '¿Qué significa \"RAM\" en informática?', 'Memoria de acceso aleatorio', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (202, '¿Qué protocolo se utiliza principalmente para la transferencia de páginas web?', 'HTTP', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (203, '¿Qué es un \"CPU\"?', 'Unidad Central de Procesamiento', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (204, '¿Qué tipo de archivo tiene la extensión \".exe\"?', 'Archivo ejecutable', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (205, '¿Cuál fue la primera red informática que dio origen a Internet?', 'ARPANET', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (206, '¿Qué es un \"firewall\"?', 'Un sistema de seguridad para redes', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (207, '¿Qué significa \"URL\"?', 'Localizador Uniforme de Recursos', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (208, '¿Qué es un \"bug\" en programación?', 'Un error en el código', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (209, '¿Qué lenguaje de programación es conocido por su uso en inteligencia artificial?', 'Python', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL),
                                                                                                                                                                                                                                                       (210, '¿Qué tecnología se usa para la conexión inalámbrica de dispositivos a corta distancia?', 'Bluetooth', '2025-10-22 13:39:27', 0, 0, 14, NULL, 3, 2, NULL, NULL, NULL);

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
                                                                                                      (15, 'Flauta', 5),
                                                                                                      (16, 'Oxígeno', 6),
                                                                                                      (17, 'Hidrógeno', 6),
                                                                                                      (18, 'Nitrógeno', 6),
                                                                                                      (19, 'Amazonas', 7),
                                                                                                      (20, 'Yangtsé', 7),
                                                                                                      (21, 'Misisipi', 7),
                                                                                                      (22, 'Miguel Ángel', 8),
                                                                                                      (23, 'Vincent van Gogh', 8),
                                                                                                      (24, 'Pablo Picasso', 8),
                                                                                                      (25, 'Fútbol', 9),
                                                                                                      (26, 'Golf', 9),
                                                                                                      (27, 'Bádminton', 9),
                                                                                                      (28, 'Asia', 10),
                                                                                                      (29, 'Europa', 10),
                                                                                                      (30, 'América', 10),
                                                                                                      (31, 'Tigre', 11),
                                                                                                      (32, 'Elefante', 11),
                                                                                                      (33, 'Cocodrilo', 11),
                                                                                                      (34, 'Beijing', 12),
                                                                                                      (35, 'Seúl', 12),
                                                                                                      (36, 'Bangkok', 12),
                                                                                                      (37, 'Cuatro', 13),
                                                                                                      (38, 'Cinco', 13),
                                                                                                      (39, 'Seis', 13),
                                                                                                      (40, 'Plomo', 14),
                                                                                                      (41, 'Oro', 14),
                                                                                                      (42, 'Aluminio', 14),
                                                                                                      (43, 'Español', 15),
                                                                                                      (44, 'Francés', 15),
                                                                                                      (45, 'Italiano', 15),
                                                                                                      (46, 'Sídney', 16),
                                                                                                      (47, 'Melbourne', 16),
                                                                                                      (48, 'Brisbane', 16),
                                                                                                      (49, 'Los Alpes', 17),
                                                                                                      (50, 'Montes Urales', 17),
                                                                                                      (51, 'Cordillera del Himalaya', 17),
                                                                                                      (52, 'Egipto', 18),
                                                                                                      (53, 'Sudán', 18),
                                                                                                      (54, 'Marruecos', 18),
                                                                                                      (55, 'Canadá', 19),
                                                                                                      (56, 'China', 19),
                                                                                                      (57, 'Estados Unidos', 19),
                                                                                                      (58, 'Filipinas', 20),
                                                                                                      (59, 'Indonesia', 20),
                                                                                                      (60, 'Noruega', 20),
                                                                                                      (61, 'Europa', 21),
                                                                                                      (62, 'Asia', 21),
                                                                                                      (63, 'América', 21),
                                                                                                      (64, 'España', 22),
                                                                                                      (65, 'Grecia', 22),
                                                                                                      (66, 'Francia', 22),
                                                                                                      (67, 'China', 23),
                                                                                                      (68, 'India', 23),
                                                                                                      (69, 'Bután', 23),
                                                                                                      (70, 'Mar Rojo', 24),
                                                                                                      (71, 'Océano Atlántico', 24),
                                                                                                      (72, 'Mar Caspio', 24),
                                                                                                      (73, 'Suecia', 25),
                                                                                                      (74, 'Dinamarca', 25),
                                                                                                      (75, 'Finlandia', 25),
                                                                                                      (76, 'Lago Maracaibo', 26),
                                                                                                      (77, 'Lago Poopó', 26),
                                                                                                      (78, 'Lago Buenos Aires', 26),
                                                                                                      (79, 'Trópico de Cáncer', 27),
                                                                                                      (80, 'Meridiano de Greenwich', 27),
                                                                                                      (81, 'Trópico de Capricornio', 27),
                                                                                                      (82, 'Mónaco', 28),
                                                                                                      (83, 'Nauru', 28),
                                                                                                      (84, 'San Marino', 28),
                                                                                                      (85, 'Europa', 29),
                                                                                                      (86, 'África', 29),
                                                                                                      (87, 'Oceanía', 29),
                                                                                                      (88, 'España e Italia', 30),
                                                                                                      (89, 'Francia y Alemania', 30),
                                                                                                      (90, 'Irlanda y Escocia', 30),
                                                                                                      (91, 'Chris Evans', 31),
                                                                                                      (92, 'Mark Ruffalo', 31),
                                                                                                      (93, 'Chris Hemsworth', 31),
                                                                                                      (94, 'The OA', 32),
                                                                                                      (95, 'Dark', 32),
                                                                                                      (96, 'The Umbrella Academy', 32),
                                                                                                      (97, 'Divergente', 33),
                                                                                                      (98, 'Maze Runner', 33),
                                                                                                      (99, 'Crepúsculo', 33),
                                                                                                      (100, 'Granger', 34),
                                                                                                      (101, 'Malfoy', 34),
                                                                                                      (102, 'Dumbledore', 34),
                                                                                                      (103, 'The Walking Dead', 35),
                                                                                                      (104, 'Ozark', 35),
                                                                                                      (105, 'Better Call Saul', 35),
                                                                                                      (106, 'Dinosaur Park', 36),
                                                                                                      (107, 'Cretaceous World', 36),
                                                                                                      (108, 'Prehistoric Land', 36),
                                                                                                      (109, 'Hercule Poirot', 37),
                                                                                                      (110, 'Batman', 37),
                                                                                                      (111, 'Dr. House', 37),
                                                                                                      (112, 'Durmstrang', 38),
                                                                                                      (113, 'Beauxbatons', 38),
                                                                                                      (114, 'Ilvermorny', 38),
                                                                                                      (115, 'Moana', 39),
                                                                                                      (116, 'Encanto', 39),
                                                                                                      (117, 'Enredados', 39),
                                                                                                      (118, '1917', 40),
                                                                                                      (119, 'Joker', 40),
                                                                                                      (120, 'El Irlandés', 40),
                                                                                                      (121, 'Orlando Bloom', 41),
                                                                                                      (122, 'Brad Pitt', 41),
                                                                                                      (123, 'Tom Hiddleston', 41),
                                                                                                      (124, 'Marvel Cinematic Universe', 42),
                                                                                                      (125, 'Dark Universe', 42),
                                                                                                      (126, 'HeroVerse', 42),
                                                                                                      (127, 'Sonic', 43),
                                                                                                      (128, 'Crash Bandicoot', 43),
                                                                                                      (129, 'Donkey Kong', 43),
                                                                                                      (130, 'Star Trek', 44),
                                                                                                      (131, 'Guardians of the Galaxy', 44),
                                                                                                      (132, 'The Mandalorian', 44),
                                                                                                      (133, 'HBO', 45),
                                                                                                      (134, 'Amazon Prime Video', 45),
                                                                                                      (135, 'Disney+', 45),
                                                                                                      (136, 'China', 46),
                                                                                                      (137, 'Corea del Sur', 46),
                                                                                                      (138, 'Tailandia', 46),
                                                                                                      (139, 'Papas', 47),
                                                                                                      (140, 'Pollo', 47),
                                                                                                      (141, 'Pescado', 47),
                                                                                                      (142, 'Leche de vaca', 48),
                                                                                                      (143, 'Leche de cabra', 48),
                                                                                                      (144, 'Leche de oveja', 48),
                                                                                                      (145, 'Maíz', 49),
                                                                                                      (146, 'Caña de azúcar', 49),
                                                                                                      (147, 'Uva', 49),
                                                                                                      (148, 'Kiwi', 50),
                                                                                                      (149, 'Granada', 50),
                                                                                                      (150, 'Sandía', 50),
                                                                                                      (151, 'Penne', 51),
                                                                                                      (152, 'Spaghetti', 51),
                                                                                                      (153, 'Ravioles', 51),
                                                                                                      (154, 'Pimentón', 52),
                                                                                                      (155, 'Canela', 52),
                                                                                                      (156, 'Curry', 52),
                                                                                                      (157, 'Pizza', 53),
                                                                                                      (158, 'Ravioles', 53),
                                                                                                      (159, 'Ñoquis', 53),
                                                                                                      (160, 'Ajo', 54),
                                                                                                      (161, 'Puerro', 54),
                                                                                                      (162, 'Zanahoria', 54),
                                                                                                      (163, 'Empanada', 55),
                                                                                                      (164, 'Churro', 55),
                                                                                                      (165, 'Panqueque', 55),
                                                                                                      (166, 'Tomate', 56),
                                                                                                      (167, 'Pepino', 56),
                                                                                                      (168, 'Zanahoria', 56),
                                                                                                      (169, 'Trigo', 57),
                                                                                                      (170, 'Maíz', 57),
                                                                                                      (171, 'Avena', 57),
                                                                                                      (172, 'Pollo', 58),
                                                                                                      (173, 'Carne vacuna', 58),
                                                                                                      (174, 'Cerdo', 58),
                                                                                                      (175, 'Cúrcuma', 59),
                                                                                                      (176, 'Pimienta', 59),
                                                                                                      (177, 'Comino', 59),
                                                                                                      (178, 'Té verde', 60),
                                                                                                      (179, 'Café', 60),
                                                                                                      (180, 'Infusión de manzanilla', 60),
                                                                                                      (181, 'The Beatles', 61),
                                                                                                      (182, 'Rolling Stones', 61),
                                                                                                      (183, 'Led Zeppelin', 61),
                                                                                                      (184, 'Britney Spears', 62),
                                                                                                      (185, 'Lady Gaga', 62),
                                                                                                      (186, 'Shakira', 62),
                                                                                                      (187, 'Guitarra', 63),
                                                                                                      (188, 'Violín', 63),
                                                                                                      (189, 'Batería', 63),
                                                                                                      (190, 'Mick Jagger', 64),
                                                                                                      (191, 'Paul McCartney', 64),
                                                                                                      (192, 'Freddie Mercury', 64),
                                                                                                      (193, 'Charly García', 65),
                                                                                                      (194, 'Luis Alberto Spinetta', 65),
                                                                                                      (195, 'Fito Páez', 65),
                                                                                                      (196, 'The Who', 66),
                                                                                                      (197, 'Radiohead', 66),
                                                                                                      (198, 'U2', 66),
                                                                                                      (199, 'Drake', 67),
                                                                                                      (200, 'Ed Sheeran', 67),
                                                                                                      (201, 'Post Malone', 67),
                                                                                                      (202, 'Pearl Jam', 68),
                                                                                                      (203, 'Metallica', 68),
                                                                                                      (204, 'Red Hot Chili Peppers', 68),
                                                                                                      (205, 'Katy Perry', 69),
                                                                                                      (206, 'Selena Gomez', 69),
                                                                                                      (207, 'Billie Eilish', 69),
                                                                                                      (208, 'España', 70),
                                                                                                      (209, 'Colombia', 70),
                                                                                                      (210, 'Cuba', 70),
                                                                                                      (211, 'Paul McCartney', 71),
                                                                                                      (212, 'Bob Dylan', 71),
                                                                                                      (213, 'David Bowie', 71),
                                                                                                      (214, 'Los Pericos', 72),
                                                                                                      (215, 'La Renga', 72),
                                                                                                      (216, 'Los Auténticos Decadentes', 72),
                                                                                                      (217, 'Clarinete', 73),
                                                                                                      (218, 'Trompeta', 73),
                                                                                                      (219, 'Flauta traversa', 73),
                                                                                                      (220, 'Rock', 74),
                                                                                                      (221, 'Trap', 74),
                                                                                                      (222, 'Reggae', 74),
                                                                                                      (223, 'Mozart', 75),
                                                                                                      (224, 'Bach', 75),
                                                                                                      (225, 'Chopin', 75),
                                                                                                      (226, 'Vincent van Gogh', 76),
                                                                                                      (227, 'Pablo Picasso', 76),
                                                                                                      (228, 'Miguel Ángel', 76),
                                                                                                      (229, 'La Última Cena', 77),
                                                                                                      (230, 'El Nacimiento de Venus', 77),
                                                                                                      (231, 'El Jardín de las Delicias', 77),
                                                                                                      (232, 'Impresionismo', 78),
                                                                                                      (233, 'Surrealismo', 78),
                                                                                                      (234, 'Realismo', 78),
                                                                                                      (235, 'Donatello', 79),
                                                                                                      (236, 'Rodin', 79),
                                                                                                      (237, 'Bernini', 79),
                                                                                                      (238, 'Italia', 80),
                                                                                                      (239, 'España', 80),
                                                                                                      (240, 'Alemania', 80),
                                                                                                      (241, 'Jackson Pollock', 81),
                                                                                                      (242, 'Roy Lichtenstein', 81),
                                                                                                      (243, 'Frida Kahlo', 81),
                                                                                                      (244, 'Claude Monet', 82),
                                                                                                      (245, 'Paul Cézanne', 82),
                                                                                                      (246, 'Salvador Dalí', 82),
                                                                                                      (247, 'El Grito', 83),
                                                                                                      (248, 'El David', 83),
                                                                                                      (249, 'El Pensador de Rodin', 83),
                                                                                                      (250, 'Joan Miró', 84),
                                                                                                      (251, 'Salvador Dalí', 84),
                                                                                                      (252, 'Goya', 84),
                                                                                                      (253, 'Pintura al óleo', 85),
                                                                                                      (254, 'Escultura', 85),
                                                                                                      (255, 'Acuarela', 85),
                                                                                                      (256, 'Cúpula romana', 86),
                                                                                                      (257, 'Arco redondo', 86),
                                                                                                      (258, 'Frontón clásico', 86),
                                                                                                      (259, 'Claude Monet', 87),
                                                                                                      (260, 'Paul Gauguin', 87),
                                                                                                      (261, 'Rembrandt', 87),
                                                                                                      (262, 'Antonio Berni', 88),
                                                                                                      (263, 'Benito Quinquela Martín', 88),
                                                                                                      (264, 'Xul Solar', 88),
                                                                                                      (265, 'Ikebana', 89),
                                                                                                      (266, 'Haiku', 89),
                                                                                                      (267, 'Calligrafía', 89),
                                                                                                      (268, 'El Grito', 90),
                                                                                                      (269, 'La Gioconda', 90),
                                                                                                      (270, 'La Noche Estrellada', 90),
                                                                                                      (271, 'Pulp Fiction', 91),
                                                                                                      (272, 'Shawshank Redemption', 91),
                                                                                                      (273, 'El Rey León', 91),
                                                                                                      (274, 'Christopher Nolan', 92),
                                                                                                      (275, 'James Cameron', 92),
                                                                                                      (276, 'Steven Spielberg', 92),
                                                                                                      (277, 'Morpheus', 93),
                                                                                                      (278, 'Trinity', 93),
                                                                                                      (279, 'Smith', 93),
                                                                                                      (280, 'Joaquin Phoenix', 94),
                                                                                                      (281, 'Jack Nicholson', 94),
                                                                                                      (282, 'Jared Leto', 94),
                                                                                                      (283, 'Madagascar', 95),
                                                                                                      (284, 'Buscando a Nemo', 95),
                                                                                                      (285, 'Zootopia', 95),
                                                                                                      (286, 'Star Wars: Episodio IV', 96),
                                                                                                      (287, 'Star Wars: Episodio VI', 96),
                                                                                                      (288, 'Star Wars: Episodio I', 96),
                                                                                                      (289, 'Steven Spielberg', 97),
                                                                                                      (290, 'Christopher Nolan', 97),
                                                                                                      (291, 'Ridley Scott', 97),
                                                                                                      (292, 'Brad Pitt', 98),
                                                                                                      (293, 'Matt Damon', 98),
                                                                                                      (294, 'Keanu Reeves', 98),
                                                                                                      (295, 'Titanic', 99),
                                                                                                      (296, 'El Señor de los Anillos', 99),
                                                                                                      (297, 'Avatar', 99),
                                                                                                      (298, 'Relatos Salvajes', 100),
                                                                                                      (299, 'El secreto de sus ojos', 100),
                                                                                                      (300, 'Nueve Reinas', 100),
                                                                                                      (301, 'Brad Pitt', 101),
                                                                                                      (302, 'Matt Damon', 101),
                                                                                                      (303, 'Orlando Bloom', 101),
                                                                                                      (304, 'Harry Potter', 102),
                                                                                                      (305, 'Avatar', 102),
                                                                                                      (306, 'El Hobbit', 102),
                                                                                                      (307, 'Chris Evans', 103),
                                                                                                      (308, 'Mark Ruffalo', 103),
                                                                                                      (309, 'Chris Hemsworth', 103),
                                                                                                      (310, 'Actividad Paranormal', 104),
                                                                                                      (311, 'El Conjuro', 104),
                                                                                                      (312, 'El Resplandor', 104),
                                                                                                      (313, 'Django Unchained', 105),
                                                                                                      (314, 'Kill Bill', 105),
                                                                                                      (315, 'Bastardos sin gloria', 105),
                                                                                                      (316, 'Mario Vargas Llosa', 106),
                                                                                                      (317, 'Isabel Allende', 106),
                                                                                                      (318, 'Pablo Neruda', 106),
                                                                                                      (319, 'Watson', 107),
                                                                                                      (320, 'Poirot', 107),
                                                                                                      (321, 'Marlowe', 107),
                                                                                                      (322, 'La Celestina', 108),
                                                                                                      (323, 'La Regenta', 108),
                                                                                                      (324, 'La Ilíada', 108),
                                                                                                      (325, 'Virgilio', 109),
                                                                                                      (326, 'Platón', 109),
                                                                                                      (327, 'Sófocles', 109),
                                                                                                      (328, 'Ricardo Piglia', 110),
                                                                                                      (329, 'César Aira', 110),
                                                                                                      (330, 'Roberto Bolaño', 110),
                                                                                                      (331, 'Violeta Parra', 111),
                                                                                                      (332, 'Isabel Allende', 111),
                                                                                                      (333, 'Pablo Neruda', 111),
                                                                                                      (334, 'Winston Churchill', 112),
                                                                                                      (335, 'O\'Brien', 112),
                                                                                                      (336, 'Big Brother', 112),
                                                                                                      (337, 'Lev Tolstói', 113),
                                                                                                      (338, 'Nikolái Gógol', 113),
                                                                                                      (339, 'Antón Chéjov', 113),
                                                                                                      (340, 'Albert Camus', 114),
                                                                                                      (341, 'Jean-Paul Sartre', 114),
                                                                                                      (342, 'J. D. Salinger', 114),
                                                                                                      (343, 'Rebelión en la granja', 115),
                                                                                                      (344, 'Los miserables', 115),
                                                                                                      (345, 'La naranja mecánica', 115),
                                                                                                      (346, 'Mario Benedetti', 116),
                                                                                                      (347, 'Gabriel García Márquez', 116),
                                                                                                      (348, 'Ernesto Sábato', 116),
                                                                                                      (349, 'Los Hermanos Karamazov', 117),
                                                                                                      (350, 'El jugador', 117),
                                                                                                      (351, 'Notas del subsuelo', 117),
                                                                                                      (352, 'La isla del tesoro', 118),
                                                                                                      (353, 'Veinte mil leguas de viaje submarino', 118),
                                                                                                      (354, 'Corazón de las tinieblas', 118),
                                                                                                      (355, 'Charlotte Brontë', 119),
                                                                                                      (356, 'Emily Dickinson', 119),
                                                                                                      (357, 'Virginia Woolf', 119),
                                                                                                      (358, 'Ricardo Güiraldes', 120),
                                                                                                      (359, 'José Hernández', 120),
                                                                                                      (360, 'Mario Vargas Llosa', 120),
                                                                                                      (361, '1945', 121),
                                                                                                      (362, '1914', 121),
                                                                                                      (363, '1929', 121),
                                                                                                      (364, 'Abraham Lincoln', 122),
                                                                                                      (365, 'Thomas Jefferson', 122),
                                                                                                      (366, 'John Adams', 122),
                                                                                                      (367, 'Los romanos', 123),
                                                                                                      (368, 'Los griegos', 123),
                                                                                                      (369, 'Los mayas', 123),
                                                                                                      (370, 'Lusitania', 124),
                                                                                                      (371, 'Queen Mary', 124),
                                                                                                      (372, 'Bismarck', 124),
                                                                                                      (373, 'Italia', 125),
                                                                                                      (374, 'España', 125),
                                                                                                      (375, 'Alemania', 125),
                                                                                                      (376, '1991', 126),
                                                                                                      (377, '1985', 126),
                                                                                                      (378, '1979', 126),
                                                                                                      (379, 'Imperio Griego', 127),
                                                                                                      (380, 'Imperio Persa', 127),
                                                                                                      (381, 'Imperio Otomano', 127),
                                                                                                      (382, 'Américo Vespucio', 128),
                                                                                                      (383, 'Hernán Cortés', 128),
                                                                                                      (384, 'Marco Polo', 128),
                                                                                                      (385, 'La ejecución de Luis XVI', 129),
                                                                                                      (386, 'La Declaración de los Derechos del Hombre', 129),
                                                                                                      (387, 'La Revolución Rusa', 129),
                                                                                                      (388, 'La Guerra Fría', 130),
                                                                                                      (389, 'La Guerra del Golfo', 130),
                                                                                                      (390, 'La Guerra de los Cien Años', 130),
                                                                                                      (391, 'Alemania', 131),
                                                                                                      (392, 'Rusia', 131),
                                                                                                      (393, 'Japón', 131),
                                                                                                      (394, 'Los mohicanos', 132),
                                                                                                      (395, 'Los incas', 132),
                                                                                                      (396, 'Los aztecas', 132),
                                                                                                      (397, 'Jawaharlal Nehru', 133),
                                                                                                      (398, 'Indira Gandhi', 133),
                                                                                                      (399, 'Subhas Chandra Bose', 133),
                                                                                                      (400, 'Francia', 134),
                                                                                                      (401, 'Estados Unidos', 134),
                                                                                                      (402, 'Alemania', 134),
                                                                                                      (403, 'Tratado de París', 135),
                                                                                                      (404, 'Tratado de Tordesillas', 135),
                                                                                                      (405, 'Tratado de Utrecht', 135),
                                                                                                      (406, 'CO2', 136),
                                                                                                      (407, 'O2', 136),
                                                                                                      (408, 'NaCl', 136),
                                                                                                      (409, 'Venus', 137),
                                                                                                      (410, 'Júpiter', 137),
                                                                                                      (411, 'Mercurio', 137),
                                                                                                      (412, 'El pulmón', 138),
                                                                                                      (413, 'El hígado', 138),
                                                                                                      (414, 'El estómago', 138),
                                                                                                      (415, 'Oxígeno', 139),
                                                                                                      (416, 'Dióxido de carbono', 139),
                                                                                                      (417, 'Hidrógeno', 139),
                                                                                                      (418, 'Respiración', 140),
                                                                                                      (419, 'Digestión', 140),
                                                                                                      (420, 'Fermentación', 140),
                                                                                                      (421, 'Albert Einstein', 141),
                                                                                                      (422, 'Galileo Galilei', 141),
                                                                                                      (423, 'Stephen Hawking', 141),
                                                                                                      (424, '150,000 km/s', 142),
                                                                                                      (425, '3,000 km/s', 142),
                                                                                                      (426, '1,000,000 km/s', 142),
                                                                                                      (427, 'Glóbulo blanco', 143),
                                                                                                      (428, 'Plaqueta', 143),
                                                                                                      (429, 'Neurona', 143),
                                                                                                      (430, 'El elefante africano', 144),
                                                                                                      (431, 'El tiburón blanco', 144),
                                                                                                      (432, 'El calamar gigante', 144),
                                                                                                      (433, 'El neutrón', 145),
                                                                                                      (434, 'El electrón', 145),
                                                                                                      (435, 'El fotón', 145),
                                                                                                      (436, 'Plomo', 146),
                                                                                                      (437, 'Hierro', 146),
                                                                                                      (438, 'Zinc', 146),
                                                                                                      (439, 'Voltio', 147),
                                                                                                      (440, 'Ohmio', 147),
                                                                                                      (441, 'Hertz', 147),
                                                                                                      (442, 'Termómetro', 148),
                                                                                                      (443, 'Barómetro', 148),
                                                                                                      (444, 'Altímetro', 148),
                                                                                                      (445, 'Urano', 149),
                                                                                                      (446, 'Neptuno', 149),
                                                                                                      (447, 'Marte', 149),
                                                                                                      (448, 'Vitamina C', 150),
                                                                                                      (449, 'Vitamina B12', 150),
                                                                                                      (450, 'Vitamina A', 150),
                                                                                                      (451, 'Eucalipto', 151),
                                                                                                      (452, 'Baobab', 151),
                                                                                                      (453, 'Pino', 151),
                                                                                                      (454, 'Pingüino', 152),
                                                                                                      (455, 'Águila', 152),
                                                                                                      (456, 'Tucán', 152),
                                                                                                      (457, 'Pez', 153),
                                                                                                      (458, 'Reptil', 153),
                                                                                                      (459, 'Anfibio', 153),
                                                                                                      (460, 'Orquídea', 154),
                                                                                                      (461, 'Rosa', 154),
                                                                                                      (462, 'Girasol', 154),
                                                                                                      (463, 'Río', 155),
                                                                                                      (464, 'Lago', 155),
                                                                                                      (465, 'Pantano', 155),
                                                                                                      (466, 'Sáhara', 156),
                                                                                                      (467, 'Gobi', 156),
                                                                                                      (468, 'Kalahari', 156),
                                                                                                      (469, 'Dióxido de carbono', 157),
                                                                                                      (470, 'Nitrógeno', 157),
                                                                                                      (471, 'Helio', 157),
                                                                                                      (472, 'Pétalo', 158),
                                                                                                      (473, 'Raíz', 158),
                                                                                                      (474, 'Sépalo', 158),
                                                                                                      (475, 'Iguana', 159),
                                                                                                      (476, 'Serpiente', 159),
                                                                                                      (477, 'Tortuga', 159),
                                                                                                      (478, 'Abeto', 160),
                                                                                                      (479, 'Sauce', 160),
                                                                                                      (480, 'Olivo', 160),
                                                                                                      (481, 'Ratón', 161),
                                                                                                      (482, 'Topo', 161),
                                                                                                      (483, 'Musaraña', 161),
                                                                                                      (484, 'León', 162),
                                                                                                      (485, 'Tiburón blanco', 162),
                                                                                                      (486, 'Hiena', 162),
                                                                                                      (487, 'Nilo', 163),
                                                                                                      (488, 'Yangtsé', 163),
                                                                                                      (489, 'Misisipi', 163),
                                                                                                      (490, 'Hormiga', 164),
                                                                                                      (491, 'Escarabajo', 164),
                                                                                                      (492, 'Mariposa', 164),
                                                                                                      (493, 'Colibrí', 165),
                                                                                                      (494, 'Murciélago vampiro', 165),
                                                                                                      (495, 'Periquito', 165),
                                                                                                      (496, 'Gus Fring Laboratories', 166),
                                                                                                      (497, 'Heisenberg Inc.', 166),
                                                                                                      (498, 'Albuquerque Chem Co.', 166),
                                                                                                      (499, 'Ross', 167),
                                                                                                      (500, 'Joey', 167),
                                                                                                      (501, 'Gunther', 167),
                                                                                                      (502, 'Stark', 168),
                                                                                                      (503, 'Lannister', 168),
                                                                                                      (504, 'Baratheon', 168),
                                                                                                      (505, 'Springfield', 169),
                                                                                                      (506, 'Riverdale', 169),
                                                                                                      (507, 'Greendale', 169),
                                                                                                      (508, 'Martin Freeman', 170),
                                                                                                      (509, 'Tom Hiddleston', 170),
                                                                                                      (510, 'David Tennant', 170),
                                                                                                      (511, 'Ingeniero', 171),
                                                                                                      (512, 'Abogado', 171),
                                                                                                      (513, 'Profesor', 171),
                                                                                                      (514, 'Breaking Bad', 172),
                                                                                                      (515, 'Dexter', 172),
                                                                                                      (516, 'Sons of Anarchy', 172),
                                                                                                      (517, 'Beck', 173),
                                                                                                      (518, 'Candace', 173),
                                                                                                      (519, 'Benji', 173),
                                                                                                      (520, 'Parks and Recreation', 174),
                                                                                                      (521, 'Brooklyn Nine-Nine', 174),
                                                                                                      (522, 'Modern Family', 174),
                                                                                                      (523, 'Trono Dorado', 175),
                                                                                                      (524, 'Trono de Acero', 175),
                                                                                                      (525, 'Trono del Norte', 175),
                                                                                                      (526, 'The Umbrella Academy', 176),
                                                                                                      (527, 'Dark', 176),
                                                                                                      (528, 'Locke & Key', 176),
                                                                                                      (529, 'Andrés', 177),
                                                                                                      (530, 'Pedro', 177),
                                                                                                      (531, 'Ramiro', 177),
                                                                                                      (532, 'BoJack Horseman', 178),
                                                                                                      (533, 'Futurama', 178),
                                                                                                      (534, 'Big Mouth', 178),
                                                                                                      (535, 'Varys', 179),
                                                                                                      (536, 'Joffrey', 179),
                                                                                                      (537, 'Ramsay', 179),
                                                                                                      (538, 'Silicon Valley', 180),
                                                                                                      (539, 'Mr. Robot', 180),
                                                                                                      (540, 'How I Met Your Mother', 180),
                                                                                                      (541, '10', 181),
                                                                                                      (542, '12', 181),
                                                                                                      (543, '9', 181),
                                                                                                      (544, 'Roger Federer', 182),
                                                                                                      (545, 'Rafael Nadal', 182),
                                                                                                      (546, 'Pete Sampras', 182),
                                                                                                      (547, 'Alemania', 183),
                                                                                                      (548, 'Italia', 183),
                                                                                                      (549, 'Francia', 183),
                                                                                                      (550, 'Fútbol Americano', 184),
                                                                                                      (551, 'Críquet', 184),
                                                                                                      (552, 'Balonmano', 184),
                                                                                                      (553, 'Vuelta a España', 185),
                                                                                                      (554, 'Giro de Italia', 185),
                                                                                                      (555, 'Ironman', 185),
                                                                                                      (556, '9.69 segundos', 186),
                                                                                                      (557, '9.63 segundos', 186),
                                                                                                      (558, '9.50 segundos', 186),
                                                                                                      (559, 'Golf', 187),
                                                                                                      (560, 'Squash', 187),
                                                                                                      (561, 'Bádminton', 187),
                                                                                                      (562, 'NBA Trophy', 188),
                                                                                                      (563, 'Champions Cup', 188),
                                                                                                      (564, 'World Cup Trophy', 188),
                                                                                                      (565, 'Brasil', 189),
                                                                                                      (566, 'Colombia', 189),
                                                                                                      (567, 'Uruguay', 189),
                                                                                                      (568, '1900', 190),
                                                                                                      (569, '1888', 190),
                                                                                                      (570, '1924', 190),
                                                                                                      (571, 'Críquet', 191),
                                                                                                      (572, 'Tenis de mesa', 191),
                                                                                                      (573, 'Fútbol', 191),
                                                                                                      (574, '2', 192),
                                                                                                      (575, '4', 192),
                                                                                                      (576, '1', 192),
                                                                                                      (577, 'Alemania', 193),
                                                                                                      (578, 'Qatar', 193),
                                                                                                      (579, 'Brasil', 193),
                                                                                                      (580, 'Mike Tyson', 194),
                                                                                                      (581, 'Floyd Mayweather', 194),
                                                                                                      (582, 'Rocky Marciano', 194),
                                                                                                      (583, '2 de 3', 195),
                                                                                                      (584, '4 de 7', 195),
                                                                                                      (585, '5 de 9', 195),
                                                                                                      (586, 'Bill Gates', 196),
                                                                                                      (587, 'Steve Jobs', 196),
                                                                                                      (588, 'Charles Babbage', 196),
                                                                                                      (589, 'HighText Machine Language', 197),
                                                                                                      (590, 'Hyperlink Text Markup Language', 197),
                                                                                                      (591, 'Hyper Transfer Markup Language', 197),
                                                                                                      (592, 'Python', 198),
                                                                                                      (593, 'Java', 198),
                                                                                                      (594, 'C++', 198),
                                                                                                      (595, 'Apple', 199),
                                                                                                      (596, 'Google', 199),
                                                                                                      (597, 'IBM', 199),
                                                                                                      (598, 'Memoria RAM', 200),
                                                                                                      (599, 'Procesador', 200),
                                                                                                      (600, 'Tarjeta madre', 200),
                                                                                                      (601, 'Registro de acceso aleatorio', 201),
                                                                                                      (602, 'Memoria de solo lectura', 201),
                                                                                                      (603, 'Memoria virtual', 201),
                                                                                                      (604, 'FTP', 202),
                                                                                                      (605, 'SMTP', 202),
                                                                                                      (606, 'TCP', 202),
                                                                                                      (607, 'Unidad de procesamiento gráfico', 203),
                                                                                                      (608, 'Unidad de almacenamiento', 203),
                                                                                                      (609, 'Unidad de control', 203),
                                                                                                      (610, 'Documento de texto', 204),
                                                                                                      (611, 'Archivo comprimido', 204),
                                                                                                      (612, 'Archivo de imagen', 204),
                                                                                                      (613, 'World Wide Web', 205),
                                                                                                      (614, 'LAN', 205),
                                                                                                      (615, 'Ethernet', 205),
                                                                                                      (616, 'Un programa antivirus', 206),
                                                                                                      (617, 'Un sistema operativo', 206),
                                                                                                      (618, 'Un lenguaje de programación', 206),
                                                                                                      (619, 'Registro Universal de Localización', 207),
                                                                                                      (620, 'Localizador Unificado de Recursos', 207),
                                                                                                      (621, 'Unidad de Recursos Locales', 207),
                                                                                                      (622, 'Una función en el código', 208),
                                                                                                      (623, 'Una librería', 208),
                                                                                                      (624, 'Una variable', 208),
                                                                                                      (625, 'Java', 209),
                                                                                                      (626, 'C#', 209),
                                                                                                      (627, 'Ruby', 209),
                                                                                                      (628, 'Wi-Fi', 210),
                                                                                                      (629, 'NFC', 210),
                                                                                                      (630, 'Zigbee', 210);

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
