-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2025 at 06:01 AM
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
-- Database: `votaciones`
--

-- --------------------------------------------------------

--
-- Table structure for table `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL,
  `proyecto_id` int(11) NOT NULL,
  `fecha_calificacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calificaciones`
--

INSERT INTO `calificaciones` (`id`, `user_id`, `pregunta_id`, `puntuacion`, `proyecto_id`, `fecha_calificacion`, `comentario`) VALUES
(82, 37, 45, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(83, 37, 46, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(84, 37, 47, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(85, 37, 48, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(86, 37, 49, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(87, 37, 50, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(88, 37, 51, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(89, 37, 52, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(90, 37, 53, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(91, 37, 54, 5, 11, '2025-02-27 03:29:20', 'Excelente proyecto'),
(92, 37, 55, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(93, 37, 56, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(94, 37, 57, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(95, 37, 58, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(96, 37, 59, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(97, 37, 60, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(98, 37, 61, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(99, 37, 62, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(100, 37, 63, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(101, 37, 64, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(102, 37, 65, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(103, 37, 66, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(104, 37, 67, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(105, 37, 68, 5, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(106, 37, 69, 4, 12, '2025-02-27 03:30:25', 'Buen proyecto pero falto bla bla bla'),
(107, 37, 70, 5, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(108, 37, 71, 5, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(109, 37, 72, 5, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(110, 37, 73, 4, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(111, 37, 74, 4, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(112, 37, 75, 4, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(113, 37, 76, 3, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(114, 37, 77, 3, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(115, 37, 78, 3, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(116, 37, 79, 2, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(117, 37, 80, 2, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(118, 37, 81, 2, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(119, 37, 82, 1, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(120, 37, 83, 1, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(121, 37, 84, 1, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(122, 37, 85, 2, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(123, 37, 86, 2, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(124, 37, 87, 2, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(125, 37, 88, 3, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(126, 37, 89, 3, 13, '2025-02-27 03:30:56', 'Excelente proyecto'),
(127, 38, 45, 3, 11, '2025-02-27 04:54:19', 'Personalmente no me agrada el proyecto'),
(128, 38, 46, 2, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(129, 38, 47, 2, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(130, 38, 48, 3, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(131, 38, 49, 2, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(132, 38, 50, 2, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(133, 38, 51, 3, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(134, 38, 52, 3, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(135, 38, 53, 3, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(136, 38, 54, 2, 11, '2025-02-27 04:54:20', 'Personalmente no me agrada el proyecto'),
(137, 38, 55, 3, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(138, 38, 56, 2, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(139, 38, 57, 5, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(140, 38, 58, 5, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(141, 38, 59, 4, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(142, 38, 60, 2, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(143, 38, 61, 1, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(144, 38, 62, 2, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(145, 38, 63, 4, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(146, 38, 64, 1, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(147, 38, 65, 4, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(148, 38, 66, 3, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(149, 38, 67, 2, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(150, 38, 68, 1, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(151, 38, 69, 3, 12, '2025-02-27 04:56:05', 'la verdad le faltan cosas'),
(152, 38, 70, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(153, 38, 71, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(154, 38, 72, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(155, 38, 73, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(156, 38, 74, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(157, 38, 75, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(158, 38, 76, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(159, 38, 77, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(160, 38, 78, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(161, 38, 79, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(162, 38, 80, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(163, 38, 81, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(164, 38, 82, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(165, 38, 83, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(166, 38, 84, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(167, 38, 85, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(168, 38, 86, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(169, 38, 87, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(170, 38, 88, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto'),
(171, 38, 89, 5, 13, '2025-02-27 04:56:40', 'Excelente proyecto');

-- --------------------------------------------------------

--
-- Table structure for table `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `pregunta` text NOT NULL,
  `proyecto_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `preguntas`
--

INSERT INTO `preguntas` (`id`, `pregunta`, `proyecto_id`, `fecha_creacion`) VALUES
(45, 'pregunta 1', 11, '2025-02-27 03:26:02'),
(46, 'pregunta 2', 11, '2025-02-27 03:26:04'),
(47, 'pregunta 3', 11, '2025-02-27 03:26:06'),
(48, 'pregunta 4', 11, '2025-02-27 03:26:09'),
(49, 'pregunta 5', 11, '2025-02-27 03:26:11'),
(50, 'pregunta 6', 11, '2025-02-27 03:26:14'),
(51, 'pregunta 7', 11, '2025-02-27 03:26:18'),
(52, 'pregunta 8', 11, '2025-02-27 03:26:21'),
(53, 'pregunta 9', 11, '2025-02-27 03:26:24'),
(54, 'pregunta 10', 11, '2025-02-27 03:26:26'),
(55, 'pregunta 1', 12, '2025-02-27 03:26:34'),
(56, 'pregunta 2', 12, '2025-02-27 03:26:38'),
(57, 'pregunta 3', 12, '2025-02-27 03:26:40'),
(58, 'pregunta 4', 12, '2025-02-27 03:26:42'),
(59, 'pregunta 5', 12, '2025-02-27 03:26:44'),
(60, 'pregunta 6', 12, '2025-02-27 03:26:47'),
(61, 'pregunta 7', 12, '2025-02-27 03:26:49'),
(62, 'pregunta 8', 12, '2025-02-27 03:26:53'),
(63, 'pregunta 9', 12, '2025-02-27 03:26:55'),
(64, 'pregunta 10', 12, '2025-02-27 03:26:58'),
(65, 'pregunta 11', 12, '2025-02-27 03:27:00'),
(66, 'pregunta 12', 12, '2025-02-27 03:27:02'),
(67, 'pregunta 13', 12, '2025-02-27 03:27:05'),
(68, 'pregunta 14', 12, '2025-02-27 03:27:10'),
(69, 'pregunta 15', 12, '2025-02-27 03:27:14'),
(70, 'pregunta 1', 13, '2025-02-27 03:27:44'),
(71, 'pregunta 2', 13, '2025-02-27 03:27:46'),
(72, 'preguta 3', 13, '2025-02-27 03:27:49'),
(73, 'pregunta 4', 13, '2025-02-27 03:27:51'),
(74, 'pregunta 5', 13, '2025-02-27 03:27:53'),
(75, 'pregunta 6', 13, '2025-02-27 03:27:58'),
(76, 'pregunta 7', 13, '2025-02-27 03:28:01'),
(77, 'pregunta 8', 13, '2025-02-27 03:28:04'),
(78, 'pregunta 9', 13, '2025-02-27 03:28:06'),
(79, 'pregunta 10', 13, '2025-02-27 03:28:08'),
(80, 'pregunta 11', 13, '2025-02-27 03:28:10'),
(81, 'pregunta 12', 13, '2025-02-27 03:28:12'),
(82, 'pregunta 13', 13, '2025-02-27 03:28:15'),
(83, 'pregunta 14', 13, '2025-02-27 03:28:17'),
(84, 'pregunta 15', 13, '2025-02-27 03:28:20'),
(85, 'pregunta 16', 13, '2025-02-27 03:28:22'),
(86, 'pregunta 17', 13, '2025-02-27 03:28:29'),
(87, 'pregunta 18', 13, '2025-02-27 03:28:32'),
(88, 'pregunta 19', 13, '2025-02-27 03:28:34'),
(89, 'pregunta 20', 13, '2025-02-27 03:28:40');

-- --------------------------------------------------------

--
-- Table structure for table `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `descripcion`, `fecha_creacion`, `estado`) VALUES
(11, 'Gestion de proyectos TI', 'El kjasncijad vkwejndiouvnwoelkfn ojsdfnwojk \r\n', '2025-02-27 03:00:09', 1),
(12, 'Contabilidad', 'asdasdasdasd', '2025-02-27 03:13:08', 1),
(13, 'Proyecto X', 'aqui va la descripcion del proyecto\r\n', '2025-02-27 03:27:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `session_id`, `created_at`) VALUES
(1, 21, 'keb6if5a62l2dbb4f7f9bb8h6n', '2025-02-20 04:18:55');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `Id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','cliente') NOT NULL,
  `nombre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`Id`, `email`, `password`, `rol`, `nombre`) VALUES
(35, 'carlos.petersen.calderon@covao.ed.cr', '$2y$10$f2LYFaidduRCbgoqt8.ZgOuBSBGqpExDtbJusRPCUpEwsr4nkiQfO', 'admin', 'Carlos Petersen Calderon'),
(37, 'angel.vargas.montero@covao.ed.cr', '$2y$10$1yuEsdYyT/83U6qdHKU49eW1jqAUYiuH4lKAPjZndT8wdB3bMAPz.', 'cliente', 'Angel Gabriel Vargas Montero'),
(38, 'manuel.araya.redondo@covao.ed.cr', '$2y$10$XCYBIlDIX6KgShdzPMzq5eG9pE4d1vdfGG.8i0xBhRH87Z6/7O7ju', 'cliente', 'Manuel Araya Redondo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pregunta_id` (`pregunta_id`),
  ADD KEY `proyecto_id` (`proyecto_id`);

--
-- Indexes for table `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proyecto_id` (`proyecto_id`);

--
-- Indexes for table `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
