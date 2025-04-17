-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-04-2025 a las 06:46:22
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
-- Base de datos: `proyecto_ambiental`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `proyecto_id` int(11) DEFAULT NULL,
  `nombre_evento` varchar(100) NOT NULL,
  `fecha_evento` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `direccion_reunion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id_evento`, `proyecto_id`, `nombre_evento`, `fecha_evento`, `descripcion`, `direccion_reunion`) VALUES
(3, 4, 'Primer dia de limpieza - reciclaje de residuos en el rio', '2025-03-28', 'Teniendo en cueanta la iniciativa de este proyecto, comenzamos la labor como parte de fortalecer el medio ambiente de Barranquilla', NULL),
(4, 4, 'Segundo dia de limpieza - Recoleccion de material toxico', '2025-03-29', 'Iniciamos segundo dia de limpieza para incentivar a la comunidad a unirse a la csausa', NULL),
(5, 4, 'Tercer dia de limpieza - Jornada de educacion ambinental en el Malecon', '2025-03-29', 'Daremos charlas sobre practicas ecologicas que mitiguen el cambio medioambiental', NULL),
(7, 6, 'Preparación del terreno', '2025-04-15', 'Acondicionamiento de los parques y zonas verdes para la siembra.', NULL),
(8, 6, 'Siembra de árboles nativos', '2025-04-22', 'Plantación de especies nativas con la participación de voluntarios.', NULL),
(9, 7, 'Recolección de residuos en la playa', '2025-05-01', 'Jornada de limpieza para recoger plásticos y otros desechos.', NULL),
(10, 7, 'Clasificación de residuos reciclables', '2025-05-02', 'Separación de materiales para su posterior reciclaje.', NULL),
(11, 8, 'Taller de sensibilización sobre reciclaje', '2025-04-22', 'Charla educativa para estudiantes sobre la importancia del reciclaje.', NULL),
(12, 8, 'Implementación de contenedores de reciclaje', '2025-04-29', 'Colocación de contenedores diferenciados en las aulas.', NULL),
(13, 9, 'Construcción de bancales para huertos', '2025-05-10', 'Preparación de las estructuras para el cultivo de hortalizas.', NULL),
(14, 9, 'Siembra de hortalizas y verduras', '2025-05-17', 'Plantación de diferentes especies para el consumo comunitario.', NULL),
(15, 10, 'Jornada de Siembra', '2025-06-05', 'Primera jornada de siembra de árboles nativos', NULL),
(16, 10, 'Mantenimiento de Áreas Reforestadas', '2025-06-20', 'Revisión y cuidado de las zonas reforestadas', NULL),
(17, 11, 'Taller de Reciclaje Creativo', '2025-05-20', 'Actividades prácticas sobre reutilización de materiales', NULL),
(18, 11, 'Feria Ambiental Escolar', '2025-06-15', 'Exposición de proyectos ambientales estudiantiles', NULL),
(19, 12, 'Capacitación en Separación de Residuos', '2025-05-25', 'Taller práctico sobre clasificación de residuos', NULL),
(20, 13, 'Preparación de Compost', '2025-06-15', 'Taller sobre elaboración de compost orgánico', NULL),
(21, 13, 'Siembra de Hortalizas', '2025-06-25', 'Jornada de siembra en huertos escolares', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_puntos`
--

CREATE TABLE `log_puntos` (
  `id_log` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `puntos_ganados` int(11) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_log` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_puntos`
--

INSERT INTO `log_puntos` (`id_log`, `usuario_id`, `puntos_ganados`, `descripcion`, `fecha_log`) VALUES
(63, 52, 10, 'Inscripción al proyecto con ID: 4', '2025-04-09 21:37:05'),
(64, 52, 0, 'Obtención de la recompensa: Nuevo Voluntario', '2025-04-09 21:37:05'),
(65, 52, 0, 'Obtención de la recompensa: Voluntario Básico', '2025-04-09 21:37:05'),
(66, 52, 0, 'Obtención de la recompensa: Voluntario Intermedio', '2025-04-09 21:37:05'),
(67, 52, 1, 'Asistencia confirmada al evento ID: 4', '2025-04-09 21:45:30'),
(68, 52, 1, 'Asistencia confirmada al evento ID: 5', '2025-04-09 21:50:24'),
(69, 52, 1, 'Asistencia confirmada al evento ID: 3', '2025-04-09 21:55:36'),
(70, 53, 10, 'Inscripción al proyecto con ID: 10', '2025-04-09 21:57:45'),
(71, 53, 10, 'Inscripción al proyecto con ID: 12', '2025-04-09 21:57:45'),
(72, 53, 1, 'Asistencia confirmada al evento ID: 15', '2025-04-09 21:57:45'),
(73, 53, 1, 'Asistencia confirmada al evento ID: 17', '2025-04-09 21:57:45'),
(74, 54, 10, 'Inscripción al proyecto con ID: 11', '2025-04-09 21:57:45'),
(75, 54, 10, 'Inscripción al proyecto con ID: 13', '2025-04-09 21:57:45'),
(76, 54, 1, 'Asistencia confirmada al evento ID: 18', '2025-04-09 21:57:45'),
(77, 54, 1, 'Asistencia confirmada al evento ID: 19', '2025-04-09 21:57:45'),
(78, 55, 10, 'Inscripción al proyecto con ID: 12', '2025-04-09 21:57:45'),
(79, 55, 10, 'Inscripción al proyecto con ID: 13', '2025-04-09 21:57:45'),
(80, 55, 1, 'Asistencia confirmada al evento ID: 20', '2025-04-09 21:57:45'),
(81, 52, 1, 'Asistencia confirmada al evento ID: 18', '2025-04-09 21:58:08'),
(82, 53, 1, 'Asistencia confirmada al evento ID: 16', '2025-04-09 21:58:38'),
(83, 52, 10, 'Inscripción al proyecto con ID: 6', '2025-04-10 04:34:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participacion_eventos`
--

CREATE TABLE `participacion_eventos` (
  `id_participacion` int(11) NOT NULL,
  `voluntario_id` int(11) DEFAULT NULL,
  `evento_id` int(11) DEFAULT NULL,
  `puntos_ganados` int(11) DEFAULT 0,
  `fecha_participacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmado` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_confirmacion` timestamp NULL DEFAULT NULL,
  `organizador_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participacion_eventos`
--

INSERT INTO `participacion_eventos` (`id_participacion`, `voluntario_id`, `evento_id`, `puntos_ganados`, `fecha_participacion`, `confirmado`, `fecha_confirmacion`, `organizador_id`) VALUES
(43, 52, 4, 1, '2025-04-09 21:45:30', 1, '2025-04-09 21:45:30', 30),
(44, 52, 5, 1, '2025-04-09 21:50:24', 1, '2025-04-09 21:50:24', 30),
(45, 52, 3, 1, '2025-04-09 21:55:36', 1, '2025-04-09 21:55:36', 30),
(46, 52, 15, 1, '2025-04-09 21:57:45', 1, '2025-06-05 15:00:00', NULL),
(47, 52, 16, 1, '2025-04-09 21:57:45', 1, '2025-06-20 14:30:00', NULL),
(48, 53, 15, 1, '2025-04-09 21:57:45', 1, '2025-06-05 15:00:00', NULL),
(49, 53, 17, 1, '2025-04-09 21:57:45', 1, '2025-05-20 19:00:00', NULL),
(50, 54, 18, 1, '2025-04-09 21:57:45', 1, '2025-06-15 20:00:00', NULL),
(51, 54, 19, 1, '2025-04-09 21:57:45', 1, '2025-05-25 15:00:00', NULL),
(52, 55, 20, 1, '2025-04-09 21:57:45', 1, '2025-06-15 14:00:00', NULL),
(53, 52, 18, 1, '2025-04-09 21:58:08', 1, '2025-04-09 21:58:08', 30),
(54, 53, 16, 1, '2025-04-09 21:58:38', 1, '2025-04-09 21:58:38', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participacion_proyectos`
--

CREATE TABLE `participacion_proyectos` (
  `usuario_id` int(11) NOT NULL,
  `proyecto_id` int(11) NOT NULL,
  `fecha_union` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participacion_proyectos`
--

INSERT INTO `participacion_proyectos` (`usuario_id`, `proyecto_id`, `fecha_union`) VALUES
(52, 4, '2025-04-09 21:37:05'),
(52, 6, '2025-04-10 04:34:57'),
(52, 10, '2025-04-09 21:57:45'),
(52, 11, '2025-04-09 21:57:45'),
(53, 10, '2025-04-09 21:57:45'),
(53, 12, '2025-04-09 21:57:45'),
(54, 11, '2025-04-09 21:57:45'),
(54, 13, '2025-04-09 21:57:45'),
(55, 12, '2025-04-09 21:57:45'),
(55, 13, '2025-04-09 21:57:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id_proyecto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cupo` int(30) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('activo','completado','cancelado') DEFAULT 'activo',
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id_proyecto`, `nombre`, `descripcion`, `cupo`, `fecha_inicio`, `fecha_fin`, `estado`, `usuario_id`) VALUES
(4, 'Limpieza Río Magdalena', 'Jornada de limpieza de orillas del Río Magdalena en el sector de Las Flores.', 25, '2025-03-25', '2025-03-30', 'activo', 30),
(6, 'Siembra de árboles en la ciudad', 'Plantación de árboles nativos en parques y zonas urbanas para mejorar la calidad del aire.', 50, '2025-04-15', '2025-05-15', 'activo', 30),
(7, 'Limpieza de playas', 'Recolección de residuos y limpieza de playas para proteger la vida marina y el turismo.', 30, '2025-05-01', '2025-05-07', 'activo', 30),
(8, 'Campaña de reciclaje en escuelas', 'Implementación de programas de reciclaje en escuelas para educar a los niños sobre la importancia de la sostenibilidad.', 40, '2025-04-22', '2025-06-22', 'activo', 50),
(9, 'Creación de huertos urbanos', 'Construcción de huertos comunitarios en espacios públicos para promover la alimentación saludable y la agricultura urbana.', 20, '2025-05-10', '2025-06-10', 'activo', 50),
(10, 'Reforestación Parque Nacional', 'Proyecto de reforestación y conservación de especies nativas en el Parque Nacional.', 40, '2025-06-01', '2025-07-30', 'activo', 30),
(11, 'Educación Ambiental en Colegios', 'Programa de concientización ambiental para estudiantes de primaria y secundaria.', 25, '2025-05-15', '2025-08-15', 'activo', 30),
(12, 'Reciclaje Comunitario', 'Implementación de sistema de reciclaje en comunidades vulnerables.', 35, '2025-05-20', '2025-06-20', 'activo', 50),
(13, 'Huertos Escolares', 'Creación de huertos educativos en escuelas públicas.', 30, '2025-06-10', '2025-09-10', 'activo', 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recompensas`
--

CREATE TABLE `recompensas` (
  `recompensa_id` int(11) NOT NULL,
  `tipo_recompensa` varchar(255) NOT NULL,
  `puntos_requeridos` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recompensas`
--

INSERT INTO `recompensas` (`recompensa_id`, `tipo_recompensa`, `puntos_requeridos`, `descripcion`) VALUES
(1, 'Nuevo Voluntario', 1, '1 punto por iniciar sesión como usuario nuevo'),
(2, 'Voluntario Básico', 10, '10 puntos por completar ciertas acciones básicas como reciclaje o participación'),
(3, 'Voluntario Intermedio', 50, '50 puntos por lograr un nivel intermedio de participación en actividades'),
(4, 'Voluntario Avanzado', 100, '100 puntos por un nivel avanzado de participación'),
(5, 'Voluntario Experimentado', 200, '200 puntos por ser un voluntario activo y frecuente'),
(6, 'Voluntario Profesional', 500, '500 puntos por demostrar un compromiso excepcional y una participación continua'),
(7, 'Voluntario Elite', 1000, '1000 puntos por ser un líder destacado en proyectos y tener un gran impacto en la comunidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_usuarios`
--

CREATE TABLE `tipos_usuarios` (
  `id_tipo_usuario` int(11) NOT NULL,
  `descripcion` enum('voluntario','organizador','administrador') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_usuarios`
--

INSERT INTO `tipos_usuarios` (`id_tipo_usuario`, `descripcion`) VALUES
(1, 'voluntario'),
(2, 'organizador'),
(3, 'administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `tipo_usuario_id` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `puntos` int(11) NOT NULL DEFAULT 0,
  `nivel` varchar(50) NOT NULL DEFAULT 'Voluntario Bronce'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombres`, `apellidos`, `correo`, `contraseña`, `tipo_usuario_id`, `fecha_registro`, `puntos`, `nivel`) VALUES
(30, 'Anders Enrique', 'Muñoz Pua', 'andersmp6@gmail.com', '$2y$10$DFIXrYjb0XCV9./tN1xstO6sdqUXdwkYYtI4ofew1riHz8aFc8NxW', 2, '2025-03-25 00:20:15', 0, 'Voluntario Bronce'),
(50, 'Andres Felipe', 'De la Hoz Cera', 'adelahoz@gmail.com', '$2y$10$TCiN1FZ2i4ylzrhghtHj9OfO1x79NvFIdi0E9YZnkcF82ajGRgJSy', 1, '2025-03-26 06:40:55', 0, 'Voluntario Bronce'),
(52, 'Carolay Gineth', 'Muñoz Pua', 'Cmunoz@gmail.com', '$2y$10$CTJ2kQ71ceAgcyBLkISIb.qaeJ4Ap4.KVdSVdc.khLEQtypp7Aju6', 1, '2025-04-07 06:42:27', 85, 'Voluntario Oro'),
(53, 'Maria José', 'García López', 'mariajose@gmail.com', '$2y$10$TCiN1FZ2i4ylzrhghtHj9OfO1x79NvFIdi0E9YZnkcF82ajGRgJSy', 1, '2025-04-09 21:57:45', 76, 'Voluntario Plata'),
(54, 'Juan Carlos', 'Martínez Ruiz', 'juancarlos@gmail.com', '$2y$10$TCiN1FZ2i4ylzrhghtHj9OfO1x79NvFIdi0E9YZnkcF82ajGRgJSy', 1, '2025-04-09 21:57:45', 120, 'Voluntario Oro'),
(55, 'Ana Isabel', 'Pérez Castro', 'anaisabel@gmail.com', '$2y$10$TCiN1FZ2i4ylzrhghtHj9OfO1x79NvFIdi0E9YZnkcF82ajGRgJSy', 2, '2025-04-09 21:57:45', 0, 'Voluntario Bronce'),
(56, 'Luis Miguel', 'Torres Vega', 'luismiguel@gmail.com', '$2y$10$TCiN1FZ2i4ylzrhghtHj9OfO1x79NvFIdi0E9YZnkcF82ajGRgJSy', 1, '2025-04-09 21:57:45', 45, 'Voluntario Plata');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voluntarios_recompensas`
--

CREATE TABLE `voluntarios_recompensas` (
  `voluntario_id` int(11) NOT NULL,
  `recompensa_id` int(11) NOT NULL,
  `fecha_asignacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `voluntarios_recompensas`
--

INSERT INTO `voluntarios_recompensas` (`voluntario_id`, `recompensa_id`, `fecha_asignacion`) VALUES
(52, 1, '2025-04-09'),
(52, 2, '2025-04-09'),
(52, 3, '2025-04-09'),
(53, 1, '2025-05-01'),
(53, 2, '2025-05-15'),
(54, 1, '2025-05-01'),
(54, 2, '2025-05-20'),
(55, 1, '2025-05-01'),
(55, 2, '2025-06-01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `proyecto_id` (`proyecto_id`);

--
-- Indices de la tabla `log_puntos`
--
ALTER TABLE `log_puntos`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `participacion_eventos`
--
ALTER TABLE `participacion_eventos`
  ADD PRIMARY KEY (`id_participacion`),
  ADD KEY `voluntario_id` (`voluntario_id`),
  ADD KEY `evento_id` (`evento_id`),
  ADD KEY `FK_participacion_eventos_usuarios` (`organizador_id`);

--
-- Indices de la tabla `participacion_proyectos`
--
ALTER TABLE `participacion_proyectos`
  ADD PRIMARY KEY (`usuario_id`,`proyecto_id`),
  ADD KEY `proyecto_id` (`proyecto_id`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id_proyecto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `recompensas`
--
ALTER TABLE `recompensas`
  ADD PRIMARY KEY (`recompensa_id`);

--
-- Indices de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  ADD PRIMARY KEY (`id_tipo_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `tipo_usuario_id` (`tipo_usuario_id`);

--
-- Indices de la tabla `voluntarios_recompensas`
--
ALTER TABLE `voluntarios_recompensas`
  ADD PRIMARY KEY (`voluntario_id`,`recompensa_id`),
  ADD KEY `recompensa_id` (`recompensa_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `log_puntos`
--
ALTER TABLE `log_puntos`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de la tabla `participacion_eventos`
--
ALTER TABLE `participacion_eventos`
  MODIFY `id_participacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id_proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `recompensas`
--
ALTER TABLE `recompensas`
  MODIFY `recompensa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  MODIFY `id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `log_puntos`
--
ALTER TABLE `log_puntos`
  ADD CONSTRAINT `log_puntos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `participacion_eventos`
--
ALTER TABLE `participacion_eventos`
  ADD CONSTRAINT `FK_participacion_eventos_usuarios` FOREIGN KEY (`organizador_id`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `participacion_eventos_ibfk_1` FOREIGN KEY (`voluntario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `participacion_eventos_ibfk_2` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id_evento`) ON DELETE CASCADE;

--
-- Filtros para la tabla `participacion_proyectos`
--
ALTER TABLE `participacion_proyectos`
  ADD CONSTRAINT `participacion_proyectos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `participacion_proyectos_ibfk_2` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_usuario_id`) REFERENCES `tipos_usuarios` (`id_tipo_usuario`);

--
-- Filtros para la tabla `voluntarios_recompensas`
--
ALTER TABLE `voluntarios_recompensas`
  ADD CONSTRAINT `voluntarios_recompensas_ibfk_1` FOREIGN KEY (`voluntario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `voluntarios_recompensas_ibfk_2` FOREIGN KEY (`recompensa_id`) REFERENCES `recompensas` (`recompensa_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
