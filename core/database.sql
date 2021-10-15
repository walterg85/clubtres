-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-10-2021 a las 23:36:50
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clubtres`
--
DROP DATABASE IF EXISTS `clubtres`;
CREATE DATABASE IF NOT EXISTS `clubtres` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `clubtres`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invitation`
--

DROP TABLE IF EXISTS `invitation`;
CREATE TABLE `invitation` (
  `id` int(11) NOT NULL,
  `udestiny_id` int(11) DEFAULT NULL COMMENT 'invitado',
  `uorigin_id` int(11) DEFAULT NULL COMMENT 'anfitrion',
  `event` varchar(1500) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `event_type` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `league`
--

DROP TABLE IF EXISTS `league`;
CREATE TABLE `league` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `sport` int(11) DEFAULT NULL,
  `image` varchar(250) DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `season_start` date DEFAULT NULL,
  `season_end` date DEFAULT NULL,
  `subscription_limit` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `image` varchar(250) DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team_league`
--

DROP TABLE IF EXISTS `team_league`;
CREATE TABLE `team_league` (
  `id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `league_id` int(11) DEFAULT NULL,
  `register_date` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `oauth_provider` varchar(20) DEFAULT NULL,
  `token` varchar(500) DEFAULT NULL,
  `current_session` varchar(500) DEFAULT NULL,
  `active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_team`
--

DROP TABLE IF EXISTS `user_team`;
CREATE TABLE `user_team` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `role` varchar(250) DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `league`
--
ALTER TABLE `league`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `user_team`
--
ALTER TABLE `user_team`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `invitation`
--
ALTER TABLE `invitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `league`
--
ALTER TABLE `league`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user_team`
--
ALTER TABLE `user_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
