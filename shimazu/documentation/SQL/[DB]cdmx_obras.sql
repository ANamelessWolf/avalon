-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-09-2017 a las 16:59:23
-- Versión del servidor: 10.1.19-MariaDB
-- Versión de PHP: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cdmx_obras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avances`
--

CREATE TABLE `avances` (
  `clv_avance` int(11) NOT NULL,
  `id_captura` int(11) NOT NULL,
  `clv_partida` int(11) NOT NULL,
  `avance_programado` double NOT NULL DEFAULT '-1',
  `avance_fisico` double NOT NULL DEFAULT '-1',
  `avance_financiero` double NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `avances_onac`
--
CREATE TABLE `avances_onac` (
`clv_programa` int(11)
,`clv_proyecto` int(11)
,`id_captura` int(11)
,`fecha_captura` datetime
,`nom_proyecto` varchar(300)
,`num_contrato` varchar(20)
,`monto_contrato` double
,`monto_convenio` double
,`fecha_inicio_contrato` datetime
,`fecha_termino_contrato` datetime
,`fecha_convenio` datetime
,`empleos_generados` int(8)
,`empleos_temporales` int(8)
,`empleos_indirectos` int(8)
,`observaciones` varchar(600)
,`avance_programado_total` double
,`avance_fisico_total` double
,`avance_financiero_total` double
,`avance_programado_percent` varchar(20)
,`avance_fisico_percent` varchar(20)
,`avance_financiero_percent` varchar(20)
,`HHTSA` int(8)
,`contractual` double
,`escalatorio` double
,`adicional` double
,`extraordinaria` double
,`reclamos` double
,`nombre` varchar(200)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `avances_programas`
--
CREATE TABLE `avances_programas` (
`id_captura` int(11)
,`clv_partida` int(11)
,`clv_programa` int(11)
,`fecha_captura` datetime
,`avance_fisico` double
,`avance_financiero` double
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `datos_avances_historicos`
--
CREATE TABLE `datos_avances_historicos` (
`clv_partida` int(11)
,`id_captura` int(11)
,`clv_programa` int(11)
,`nom_partida` varchar(200)
,`fecha_captura` datetime
,`monto_total_programado` double
,`avance_programado` double
,`avance_fisico` double
,`avance_financiero` double
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_geograficos`
--

CREATE TABLE `datos_geograficos` (
  `clv_dg` int(11) NOT NULL,
  `clv_proyecto` int(11) NOT NULL,
  `tipo_geometria` tinyint(3) NOT NULL,
  `latitud` double NOT NULL,
  `longitud` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `datos_historicos_programados`
--
CREATE TABLE `datos_historicos_programados` (
`clv_programa` int(11)
,`id` bigint(12)
,`fecha_captura` datetime
,`avance_programado_total` double
,`avance_fisico_total` double
,`avance_financiero_total` double
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `fechas_avances`
--
CREATE TABLE `fechas_avances` (
`id_captura` int(11)
,`clv_programa` int(11)
,`fecha_captura` datetime
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotografias`
--

CREATE TABLE `fotografias` (
  `clv_fotografia` int(11) NOT NULL,
  `clv_proyecto` int(11) NOT NULL,
  `path_fotografia` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `desc_fotografia` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `mapa` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historicos`
--

CREATE TABLE `historicos` (
  `id_captura` int(11) NOT NULL,
  `clv_programa` int(11) NOT NULL,
  `fecha_captura` datetime NOT NULL,
  `avance_programado_total` double NOT NULL,
  `avance_fisico_total` double NOT NULL,
  `avance_financiero_total` double NOT NULL,
  `contractual` double DEFAULT '0',
  `escalatorio` double DEFAULT '0',
  `adicional` double DEFAULT '0',
  `extraordinaria` double DEFAULT '0',
  `reclamos` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `knights`
--

CREATE TABLE `knights` (
  `knight_id` int(11) NOT NULL,
  `user_name` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(45) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `knights`
--

INSERT INTO `knights` (`knight_id`, `user_name`, `password`) VALUES
(1, 'sobse', '1QYvdDw5d+6YJ0XmrPTd0A=='),
(2, 'Nameless', 'Z5XL6D6XZXNxOQTMvGLnDg=='),
(3, 'Administrador', 'qBH3IaYubLuYJ0XmrPTd0A=='),
(4, 'Recycler', 'l2Qgr+YNZCdxOQTMvGLnDg=='),
(5, 'usr1', 'lC5y18IUj38=');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `knights_groups`
--

CREATE TABLE `knights_groups` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(70) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `knights_groups`
--

INSERT INTO `knights_groups` (`group_id`, `group_name`) VALUES
(4, 'dgop'),
(3, 'grp_admin'),
(5, 'recycle_bin'),
(2, 'super'),
(1, 'vigilante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `knights_ranking`
--

CREATE TABLE `knights_ranking` (
  `rank_id` int(11) NOT NULL,
  `knight_id` int(11) NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `knights_ranking`
--

INSERT INTO `knights_ranking` (`rank_id`, `knight_id`, `group_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 3, 4),
(5, 5, 4),
(6, 4, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
  `clv_partida` int(11) NOT NULL,
  `clv_programa` int(11) NOT NULL,
  `nom_partida` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_termino` datetime NOT NULL,
  `monto_total_programado` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `clv_programa` int(11) NOT NULL,
  `clv_proyecto` int(11) NOT NULL,
  `id_convenio` varchar(45) CHARACTER SET big5 DEFAULT NULL,
  `fecha_convenio` datetime NOT NULL,
  `monto_convenio` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `clv_proyecto` int(11) NOT NULL,
  `clv_usuario` int(11) NOT NULL,
  `num_contrato` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `nom_proyecto` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `monto_contrato` double NOT NULL,
  `fecha_inicio_contrato` datetime NOT NULL,
  `fecha_termino_contrato` datetime NOT NULL,
  `estatus` varchar(3) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo_proyecto` tinyint(4) DEFAULT NULL,
  `empleos_generados` int(8) DEFAULT NULL,
  `empleos_temporales` int(8) DEFAULT NULL,
  `empleos_indirectos` int(8) DEFAULT NULL,
  `HHTSA` int(8) DEFAULT NULL,
  `observaciones` varchar(600) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `proyecto_datos_geograficos`
--
CREATE TABLE `proyecto_datos_geograficos` (
`clv_proyecto` int(11)
,`clv_usuario` int(11)
,`num_contrato` varchar(20)
,`nom_proyecto` varchar(300)
,`monto_contrato` double
,`fecha_inicio_contrato` datetime
,`fecha_termino_contrato` datetime
,`estatus` varchar(3)
,`tipo_proyecto` tinyint(4)
,`empleos_generados` int(8)
,`empleos_temporales` int(8)
,`empleos_indirectos` int(8)
,`HHTSA` int(8)
,`observaciones` varchar(600)
,`clv_dg` int(11)
,`latitud` double
,`longitud` double
,`tipo_geometria` tinyint(3)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `user_groups`
--
CREATE TABLE `user_groups` (
`user_name` varchar(45)
,`nombre` varchar(200)
,`group_name` varchar(70)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `clv_usuario` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `knight_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`clv_usuario`, `nombre`, `knight_id`) VALUES
(1, 'Secretario', 1),
(2, 'Necron-Sama', 2),
(3, 'Admin - Dirección de Construcción de Obras públicas', 3),
(4, 'Dirección de Construcción de Obras públicas A', 5),
(5, 'Papelera de Reciclaje', 4);

-- --------------------------------------------------------

--
-- Estructura para la vista `avances_onac`
--
DROP TABLE IF EXISTS `avances_onac`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `avances_onac`  AS  (select `p`.`clv_programa` AS `clv_programa`,`p`.`clv_proyecto` AS `clv_proyecto`,`h`.`id_captura` AS `id_captura`,`h`.`fecha_captura` AS `fecha_captura`,`j`.`nom_proyecto` AS `nom_proyecto`,`j`.`num_contrato` AS `num_contrato`,`j`.`monto_contrato` AS `monto_contrato`,`p`.`monto_convenio` AS `monto_convenio`,`j`.`fecha_inicio_contrato` AS `fecha_inicio_contrato`,`j`.`fecha_termino_contrato` AS `fecha_termino_contrato`,`p`.`fecha_convenio` AS `fecha_convenio`,`j`.`empleos_generados` AS `empleos_generados`,`j`.`empleos_temporales` AS `empleos_temporales`,`j`.`empleos_indirectos` AS `empleos_indirectos`,`j`.`observaciones` AS `observaciones`,`d`.`avance_programado_total` AS `avance_programado_total`,`d`.`avance_fisico_total` AS `avance_fisico_total`,`d`.`avance_financiero_total` AS `avance_financiero_total`,concat(round(((`d`.`avance_programado_total` / `j`.`monto_contrato`) * 100),2),'%') AS `avance_programado_percent`,concat(round(((`d`.`avance_fisico_total` / `j`.`monto_contrato`) * 100),2),'%') AS `avance_fisico_percent`,concat(round(((`d`.`avance_financiero_total` / `j`.`monto_contrato`) * 100),2),'%') AS `avance_financiero_percent`,`j`.`HHTSA` AS `HHTSA`,`h`.`contractual` AS `contractual`,`h`.`escalatorio` AS `escalatorio`,`h`.`adicional` AS `adicional`,`h`.`extraordinaria` AS `extraordinaria`,`h`.`reclamos` AS `reclamos`,`u`.`nombre` AS `nombre` from ((((`datos_historicos_programados` `d` join `historicos` `h`) join `programas` `p`) join `proyectos` `j`) join `usuarios` `u`) where ((`d`.`id` = `h`.`id_captura`) and (`p`.`clv_programa` = `h`.`clv_programa`) and (`j`.`clv_proyecto` = `p`.`clv_proyecto`) and (`u`.`clv_usuario` = `j`.`clv_usuario`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `avances_programas`
--
DROP TABLE IF EXISTS `avances_programas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `avances_programas`  AS  (select `a`.`id_captura` AS `id_captura`,`a`.`clv_partida` AS `clv_partida`,`p`.`clv_programa` AS `clv_programa`,`h`.`fecha_captura` AS `fecha_captura`,`a`.`avance_fisico` AS `avance_fisico`,`a`.`avance_financiero` AS `avance_financiero` from ((`avances` `a` join `partidas` `p`) join `historicos` `h`) where ((`a`.`clv_partida` = `p`.`clv_partida`) and (`h`.`id_captura` = `a`.`id_captura`)) order by `h`.`fecha_captura`,`a`.`clv_partida`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `datos_avances_historicos`
--
DROP TABLE IF EXISTS `datos_avances_historicos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `datos_avances_historicos`  AS  (select `p`.`clv_partida` AS `clv_partida`,`a`.`id_captura` AS `id_captura`,`p`.`clv_programa` AS `clv_programa`,`p`.`nom_partida` AS `nom_partida`,`h`.`fecha_captura` AS `fecha_captura`,`p`.`monto_total_programado` AS `monto_total_programado`,`a`.`avance_programado` AS `avance_programado`,`a`.`avance_fisico` AS `avance_fisico`,`a`.`avance_financiero` AS `avance_financiero` from ((`partidas` `p` join `avances` `a`) join `historicos` `h`) where ((`p`.`clv_partida` = `a`.`clv_partida`) and (`a`.`id_captura` = `h`.`id_captura`)) order by `h`.`fecha_captura`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `datos_historicos_programados`
--
DROP TABLE IF EXISTS `datos_historicos_programados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `datos_historicos_programados`  AS  (select `p`.`clv_programa` AS `clv_programa`,if((`h`.`fecha_captura` >= `p`.`fecha_inicio`),`h`.`id_captura`,(`h`.`id_captura` * -(1))) AS `id`,`h`.`fecha_captura` AS `fecha_captura`,`h`.`avance_programado_total` AS `avance_programado_total`,`h`.`avance_fisico_total` AS `avance_fisico_total`,`h`.`avance_financiero_total` AS `avance_financiero_total` from (`partidas` `p` join `historicos` `h`) where (`p`.`clv_programa` = `h`.`clv_programa`) group by if((`h`.`fecha_captura` >= `p`.`fecha_inicio`),`h`.`id_captura`,(`h`.`id_captura` * -(1))) having (`id` > 0)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `fechas_avances`
--
DROP TABLE IF EXISTS `fechas_avances`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `fechas_avances`  AS  (select `a`.`id_captura` AS `id_captura`,`p`.`clv_programa` AS `clv_programa`,`h`.`fecha_captura` AS `fecha_captura` from ((`partidas` `p` join `avances` `a`) join `historicos` `h`) where ((`p`.`clv_partida` = `a`.`clv_partida`) and (`a`.`id_captura` = `h`.`id_captura`) and (`h`.`avance_financiero_total` > 0) and (`h`.`avance_fisico_total` > 0)) group by `p`.`clv_programa`,`a`.`id_captura` order by `h`.`fecha_captura` desc) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `proyecto_datos_geograficos`
--
DROP TABLE IF EXISTS `proyecto_datos_geograficos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `proyecto_datos_geograficos`  AS  (select `p`.`clv_proyecto` AS `clv_proyecto`,`p`.`clv_usuario` AS `clv_usuario`,`p`.`num_contrato` AS `num_contrato`,`p`.`nom_proyecto` AS `nom_proyecto`,`p`.`monto_contrato` AS `monto_contrato`,`p`.`fecha_inicio_contrato` AS `fecha_inicio_contrato`,`p`.`fecha_termino_contrato` AS `fecha_termino_contrato`,`p`.`estatus` AS `estatus`,`p`.`tipo_proyecto` AS `tipo_proyecto`,`p`.`empleos_generados` AS `empleos_generados`,`p`.`empleos_temporales` AS `empleos_temporales`,`p`.`empleos_indirectos` AS `empleos_indirectos`,`p`.`HHTSA` AS `HHTSA`,`p`.`observaciones` AS `observaciones`,`l`.`clv_dg` AS `clv_dg`,`l`.`latitud` AS `latitud`,`l`.`longitud` AS `longitud`,`l`.`tipo_geometria` AS `tipo_geometria` from (`proyectos` `p` join `datos_geograficos` `l`) where (`p`.`clv_proyecto` = `l`.`clv_proyecto`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `user_groups`
--
DROP TABLE IF EXISTS `user_groups`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_groups`  AS  (select `knights`.`user_name` AS `user_name`,`usuarios`.`nombre` AS `nombre`,`knights_groups`.`group_name` AS `group_name` from (((`usuarios` join `knights`) join `knights_groups`) join `knights_ranking`) where ((`usuarios`.`knight_id` = `knights`.`knight_id`) and (`knights_groups`.`group_id` = `knights_ranking`.`group_id`) and (`knights`.`knight_id` = `knights_ranking`.`knight_id`))) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `avances`
--
ALTER TABLE `avances`
  ADD PRIMARY KEY (`clv_avance`),
  ADD UNIQUE KEY `clv_avance_UNIQUE` (`clv_avance`),
  ADD KEY `fk_avances_historicos_idx` (`id_captura`),
  ADD KEY `fk_avances_partidas_idx` (`clv_partida`);

--
-- Indices de la tabla `datos_geograficos`
--
ALTER TABLE `datos_geograficos`
  ADD PRIMARY KEY (`clv_dg`),
  ADD UNIQUE KEY `clv_dg_UNIQUE` (`clv_dg`),
  ADD KEY `fk_datos_geograficos_proyectos_idx` (`clv_proyecto`);

--
-- Indices de la tabla `fotografias`
--
ALTER TABLE `fotografias`
  ADD PRIMARY KEY (`clv_fotografia`),
  ADD UNIQUE KEY `clv_fotografia_UNIQUE` (`clv_fotografia`),
  ADD KEY `fk_fotografias_proyectos_idx` (`clv_proyecto`);

--
-- Indices de la tabla `historicos`
--
ALTER TABLE `historicos`
  ADD PRIMARY KEY (`id_captura`),
  ADD UNIQUE KEY `id_captura_UNIQUE` (`id_captura`),
  ADD KEY `fk_partidas_programas_idx` (`clv_programa`);

--
-- Indices de la tabla `knights`
--
ALTER TABLE `knights`
  ADD PRIMARY KEY (`knight_id`),
  ADD UNIQUE KEY `knight_id_UNIQUE` (`knight_id`),
  ADD UNIQUE KEY `user_name_UNIQUE` (`user_name`);

--
-- Indices de la tabla `knights_groups`
--
ALTER TABLE `knights_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `group_name_UNIQUE` (`group_name`);

--
-- Indices de la tabla `knights_ranking`
--
ALTER TABLE `knights_ranking`
  ADD PRIMARY KEY (`rank_id`),
  ADD UNIQUE KEY `rank_id_UNIQUE` (`rank_id`),
  ADD KEY `fk_knights_ranking_knights_idx` (`knight_id`),
  ADD KEY `fk_knights_ranking_groups_idx` (`group_id`);

--
-- Indices de la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`clv_partida`),
  ADD UNIQUE KEY `clv_partida_UNIQUE` (`clv_partida`),
  ADD KEY `fk_partidas_programas_idx` (`clv_programa`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`clv_programa`),
  ADD UNIQUE KEY `clv_programa_UNIQUE` (`clv_programa`),
  ADD KEY `fk_programas_proyectos_idx` (`clv_proyecto`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`clv_proyecto`),
  ADD UNIQUE KEY `clv_proyecto_UNIQUE` (`clv_proyecto`),
  ADD KEY `fk_proyectos_usuarios_idx` (`clv_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`clv_usuario`),
  ADD UNIQUE KEY `clv_usuario_UNIQUE` (`clv_usuario`),
  ADD KEY `fk_usuarios_knights_idx` (`knight_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `avances`
--
ALTER TABLE `avances`
  MODIFY `clv_avance` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `datos_geograficos`
--
ALTER TABLE `datos_geograficos`
  MODIFY `clv_dg` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `fotografias`
--
ALTER TABLE `fotografias`
  MODIFY `clv_fotografia` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `historicos`
--
ALTER TABLE `historicos`
  MODIFY `id_captura` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `knights`
--
ALTER TABLE `knights`
  MODIFY `knight_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `knights_groups`
--
ALTER TABLE `knights_groups`
  MODIFY `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `knights_ranking`
--
ALTER TABLE `knights_ranking`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
  MODIFY `clv_partida` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `clv_programa` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `clv_proyecto` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `clv_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `avances`
--
ALTER TABLE `avances`
  ADD CONSTRAINT `fk_avances_historicos` FOREIGN KEY (`id_captura`) REFERENCES `historicos` (`id_captura`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_avances_partidas` FOREIGN KEY (`clv_partida`) REFERENCES `partidas` (`clv_partida`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `datos_geograficos`
--
ALTER TABLE `datos_geograficos`
  ADD CONSTRAINT `fk_datos_geograficos_proyectos` FOREIGN KEY (`clv_proyecto`) REFERENCES `proyectos` (`clv_proyecto`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fotografias`
--
ALTER TABLE `fotografias`
  ADD CONSTRAINT `fk_fotografias_proyectos` FOREIGN KEY (`clv_proyecto`) REFERENCES `proyectos` (`clv_proyecto`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `historicos`
--
ALTER TABLE `historicos`
  ADD CONSTRAINT `fk_historicos_programas` FOREIGN KEY (`clv_programa`) REFERENCES `programas` (`clv_programa`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `knights_ranking`
--
ALTER TABLE `knights_ranking`
  ADD CONSTRAINT `fk_knights_ranking_groups` FOREIGN KEY (`group_id`) REFERENCES `knights_groups` (`group_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_knights_ranking_knights` FOREIGN KEY (`knight_id`) REFERENCES `knights` (`knight_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `fk_partidas_programas` FOREIGN KEY (`clv_programa`) REFERENCES `programas` (`clv_programa`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `programas`
--
ALTER TABLE `programas`
  ADD CONSTRAINT `fk_programas_proyectos` FOREIGN KEY (`clv_proyecto`) REFERENCES `proyectos` (`clv_proyecto`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `fk_proyectos_usuarios` FOREIGN KEY (`clv_usuario`) REFERENCES `usuarios` (`clv_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_knights` FOREIGN KEY (`knight_id`) REFERENCES `knights` (`knight_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
