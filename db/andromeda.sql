-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-04-2020 a las 22:40:08
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `andromeda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspection_daily`
--

CREATE TABLE `inspection_daily` (
  `id_inspection_daily` int(10) NOT NULL,
  `fk_id_user` int(10) NOT NULL,
  `fk_id_vehicle` int(10) NOT NULL,
  `date_issue` datetime NOT NULL,
  `belt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `power_steering` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `oil_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `coolant_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `water_leaks` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `nuts` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `head_lamps` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `hazard_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `clearance_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `bake_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `work_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `glass` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `clean_exterior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `proper_decals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `brake_pedal` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `emergency_brake` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `backup_beeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `beacon_light` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `gauges` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `horn` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `hoist` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `passenger_door` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `seatbelts` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `fire_extinguisher` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `emergency_reflectors` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `first_aid` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `wipers` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `drives_axle` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `grease_front` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `grease_end` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `spill_kit` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `grease` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `steering_axle` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `turn_signals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `clean_interior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `insurance` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `driver_seat` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `registration` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `other` varchar(150) NOT NULL,
  `other_response` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `comments` text NOT NULL,
  `signature` varchar(100) NOT NULL,
  `with_trailer` tinyint(4) DEFAULT NULL COMMENT '1: With trailer; 2: NO trailer',
  `fk_id_trailer` int(11) DEFAULT NULL,
  `trailer_lights` int(1) DEFAULT 99 COMMENT '2: Fail; 1: Pass; 99:N/A',
  `trailer_tires` int(1) DEFAULT 99 COMMENT '2: Fail; 1: Pass; 99:N/A',
  `trailer_slings` int(1) DEFAULT 99 COMMENT '2: Fail; 1: Pass; 99:N/A',
  `trailer_clean` int(1) DEFAULT 99 COMMENT '2: Fail; 1: Pass; 99:N/A',
  `trailer_chains` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `trailer_ratchet` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `trailer_comments` text DEFAULT NULL,
  `heater` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `steering_wheel` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `suspension_system` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `air_brake` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `fuel_system` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspection_generator`
--

CREATE TABLE `inspection_generator` (
  `id_inspection_generator` int(11) NOT NULL,
  `fk_id_user` int(10) NOT NULL,
  `fk_id_vehicle` int(10) NOT NULL,
  `date_issue` datetime NOT NULL,
  `belt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `fuel_filter` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `oil_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_leaks` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `turn_signal` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `hazard_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tail_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `flood_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `boom` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `gears` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `gauges` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `pulley` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `electrical` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `brackers` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tires` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clean_exterior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `decals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `comments` text NOT NULL,
  `signature` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspection_heavy`
--

CREATE TABLE `inspection_heavy` (
  `id_inspection_heavy` int(10) NOT NULL,
  `fk_id_user` int(10) NOT NULL,
  `fk_id_vehicle` int(10) NOT NULL,
  `date_issue` datetime NOT NULL,
  `belt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `hydrolic` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `oil_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `coolant_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `coolant_leaks` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `working_lamps` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `beacon_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `horn` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `windows` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `clean_exterior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `clean_interior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `boom_grease` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `bucket` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `blades` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `cutting_edges` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `tracks` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `heater` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `fire_extinguisher` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `first_aid` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `spill_kit` int(1) NOT NULL,
  `tire_presurre` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `turn_signals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `rims` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `emergency_brake` int(1) NOT NULL COMMENT '0: Fail; 1: Pass',
  `operator_seat` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `gauges` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `seatbelt` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `wipers` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `backup_beeper` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `door` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `decals` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `table_excavator` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `bucket_pins` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `blade_pins` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `front_axle` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `rear_axle` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `table_dozer` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `pivin_points` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `bucket_pins_skit` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `side_arms` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `rubber_trucks` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `rollers` int(1) NOT NULL DEFAULT 99,
  `thamper` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `drill` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `transmission` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `ripper` int(1) NOT NULL DEFAULT 99 COMMENT '0: Fail; 1: Pass; 99: N/A',
  `comments` text DEFAULT NULL,
  `signature` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspection_hydrovac`
--

CREATE TABLE `inspection_hydrovac` (
  `id_inspection_hydrovac` int(11) NOT NULL,
  `fk_id_user` int(10) NOT NULL,
  `fk_id_vehicle` int(10) NOT NULL,
  `date_issue` datetime NOT NULL,
  `belt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `power_steering` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `oil_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_leaks` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `head_lamps` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `hazard_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clearance_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tail_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `work_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `turn_signals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `beacon_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tires` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `windows` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clean_exterior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `wipers` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `backup_beeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `door` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `decals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `stering_wheels` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `drives` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `front_drive` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `middle_drive` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `back_drive` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `transfer` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tail_gate` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `boom` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `lock_bar` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `brake` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `emergency_brake` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `gauges` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `horn` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `seatbelt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `seat` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `insurance` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `registration` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clean_interior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `fire_extinguisher` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `first_aid` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `emergency_kit` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `spill_kit` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `cartige` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `pump` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `wash_hose` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `pressure_hose` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `pump_oil` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `hydraulic_oil` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `gear_case` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `hydraulic` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `control` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `panel` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `foam` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `heater` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `steering_wheel` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `suspension_system` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `air_brake` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `fuel_system` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `comments` text NOT NULL,
  `signature` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspection_sweeper`
--

CREATE TABLE `inspection_sweeper` (
  `id_inspection_sweeper` int(11) NOT NULL,
  `fk_id_user` int(10) NOT NULL,
  `fk_id_vehicle` int(10) NOT NULL,
  `date_issue` datetime NOT NULL,
  `belt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `power_steering` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `oil_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_leaks` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `hydraulic` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `belt_sweeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `oil_level_sweeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_level_sweeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_leaks_sweeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `head_lamps` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `hazard_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clearance_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tail_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `work_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `turn_signals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `beacon_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tires` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `windows` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clean_exterior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `wipers` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `backup_beeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `door` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `decals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `stering_wheels` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `drives` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `front_drive` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `elevator` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `rotor` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `mixture_box` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `lf_rotor` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `elevator_sweeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `mixture_container` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `broom` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `right_broom` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `left_broom` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `sprinkerls` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `water_tank` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `hose` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `cam` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `brake` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `emergency_brake` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `gauges` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `horn` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `seatbelt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `seat` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `insurance` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `registration` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clean_interior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `fire_extinguisher` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `first_aid` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `emergency_kit` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `spill_kit` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `comments` text NOT NULL,
  `signature` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspection_total`
--

CREATE TABLE `inspection_total` (
  `id_inspection_total` int(10) NOT NULL,
  `fk_id_vehicle` int(10) NOT NULL,
  `fk_id_inspection` int(10) NOT NULL,
  `fk_id_user` int(10) NOT NULL,
  `current_hours` int(11) NOT NULL,
  `date_issue` datetime NOT NULL,
  `current_hours_2` int(11) DEFAULT NULL COMMENT 'para sweeper y hydrovac',
  `current_hours_3` int(11) DEFAULT NULL COMMENT 'Para hydrovac'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspection_watertruck`
--

CREATE TABLE `inspection_watertruck` (
  `id_inspection_watertruck` int(11) NOT NULL,
  `fk_id_user` int(10) NOT NULL,
  `fk_id_vehicle` int(10) NOT NULL,
  `date_issue` datetime NOT NULL,
  `belt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `power_steering` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `oil_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_level` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `coolant_leaks` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `head_lamps` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `hazard_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clearance_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tail_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `work_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `turn_signals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `beacon_lights` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `tires` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `mirrors` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clean_exterior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `wipers` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `backup_beeper` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `door` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `decals` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `sprinkelrs` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `stering_axle` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `drives_axles` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `front_drive` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `back_drive` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `water_pump` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `brake` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `emergency_brake` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `gauges` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `horn` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `seatbelt` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `seat` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `insurance` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `registration` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `clean_interior` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `fire_extinguisher` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `first_aid` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `emergency_kit` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `spill_kit` int(1) NOT NULL COMMENT '0: Fail; 1: Pass; 99: N/A',
  `heater` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `steering_wheel` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `suspension_system` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `air_brake` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `fuel_system` int(1) NOT NULL DEFAULT 99 COMMENT '0:Fail; 1:Pass',
  `comments` text NOT NULL,
  `signature` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maintenance`
--

CREATE TABLE `maintenance` (
  `id_maintenance` int(10) NOT NULL,
  `fk_id_vehicle` int(10) NOT NULL,
  `date_maintenance` date NOT NULL,
  `amount` float NOT NULL,
  `fk_id_maintenance_type` int(1) NOT NULL,
  `maintenance_description` text NOT NULL,
  `done_by` varchar(150) NOT NULL,
  `fk_revised_by_user` int(10) NOT NULL,
  `next_hours_maintenance` int(11) NOT NULL,
  `next_date_maintenance` varchar(10) NOT NULL,
  `maintenance_state` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1: Active; 2: Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maintenance_check`
--

CREATE TABLE `maintenance_check` (
  `id_maintenance_check` int(10) NOT NULL,
  `fk_id_maintenance` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maintenance_type`
--

CREATE TABLE `maintenance_type` (
  `id_maintenance_type` int(1) NOT NULL,
  `maintenance_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametric`
--

CREATE TABLE `parametric` (
  `id_parametric` int(1) NOT NULL,
  `data` varchar(100) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_company`
--

CREATE TABLE `param_company` (
  `id_company` int(3) NOT NULL,
  `company_name` varchar(120) NOT NULL,
  `company_type` int(1) NOT NULL DEFAULT 2 COMMENT '1: VCI; 2: subcontractor',
  `contact` varchar(100) NOT NULL,
  `movil_number` varchar(12) NOT NULL,
  `email` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_hazard`
--

CREATE TABLE `param_hazard` (
  `id_hazard` int(4) NOT NULL,
  `fk_id_hazard_activity` int(1) NOT NULL,
  `hazard_description` varchar(250) NOT NULL,
  `solution` text NOT NULL,
  `fk_id_priority` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_hazard_activity`
--

CREATE TABLE `param_hazard_activity` (
  `id_hazard_activity` int(1) NOT NULL,
  `hazard_activity` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_hazard_priority`
--

CREATE TABLE `param_hazard_priority` (
  `id_priority` int(1) NOT NULL,
  `priority_description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_jobs`
--

CREATE TABLE `param_jobs` (
  `id_job` int(10) NOT NULL,
  `job_description` varchar(250) NOT NULL,
  `state` int(1) NOT NULL DEFAULT 1 COMMENT '1: Active; 2: Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_material_type`
--

CREATE TABLE `param_material_type` (
  `id_material` int(1) NOT NULL,
  `material` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_menu`
--

CREATE TABLE `param_menu` (
  `id_menu` int(3) NOT NULL,
  `menu_name` varchar(50) NOT NULL,
  `menu_url` varchar(200) NOT NULL DEFAULT '0',
  `menu_icon` varchar(50) NOT NULL,
  `menu_order` int(1) NOT NULL,
  `menu_type` tinyint(1) NOT NULL COMMENT '1:Left; 2:Top'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_menu_access`
--

CREATE TABLE `param_menu_access` (
  `id_access` int(3) NOT NULL,
  `fk_id_menu` int(3) NOT NULL,
  `fk_id_link` int(3) NOT NULL,
  `fk_id_role` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_menu_links`
--

CREATE TABLE `param_menu_links` (
  `id_link` int(3) NOT NULL,
  `fk_id_menu` int(3) NOT NULL,
  `link_name` varchar(100) NOT NULL,
  `link_url` varchar(200) NOT NULL,
  `link_icon` varchar(50) NOT NULL,
  `order` int(1) NOT NULL,
  `date_issue` datetime NOT NULL,
  `link_state` tinyint(1) NOT NULL COMMENT '1:Active;2:Inactive',
  `link_type` tinyint(1) NOT NULL COMMENT '1:System URL;2:Complete URL; 3:Divider; 4:Complete URL, Videos; 5:Complete URL, Manuals'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_role`
--

CREATE TABLE `param_role` (
  `id_role` int(1) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `style` varchar(50) NOT NULL,
  `dashboard_url` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_vehicle`
--

CREATE TABLE `param_vehicle` (
  `id_vehicle` int(10) NOT NULL,
  `fk_id_company` int(3) NOT NULL,
  `type_level_1` int(1) NOT NULL COMMENT '1:Fleet; 2:Rental',
  `type_level_2` int(1) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `manufacturer_date` date NOT NULL,
  `description` varchar(250) NOT NULL,
  `unit_number` varchar(50) NOT NULL,
  `hours` int(100) NOT NULL,
  `oil_change` int(100) NOT NULL,
  `photo` varchar(250) NOT NULL,
  `qr_code` varchar(250) NOT NULL,
  `encryption` varchar(60) NOT NULL,
  `state` int(1) NOT NULL DEFAULT 1 COMMENT '1:active; 2:inactive',
  `hours_2` int(20) DEFAULT NULL COMMENT 'para sweeper y hydrovac',
  `oil_change_2` int(20) DEFAULT NULL COMMENT 'para sweeper y hydrovac',
  `hours_3` int(20) DEFAULT NULL COMMENT 'Para Hydrovac - Blower',
  `oil_change_3` int(20) DEFAULT NULL COMMENT 'Para Hydrovac - Blower',
  `heater_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `brakes_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `lights_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `steering_wheel_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `suspension_system_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `tires_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `wipers_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `air_brake_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `driver_seat_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA',
  `fuel_system_check` int(1) DEFAULT 99 COMMENT '0:Fail; 1:Pass; 99:NA'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `param_vehicle_type_2`
--

CREATE TABLE `param_vehicle_type_2` (
  `id_type_2` int(1) NOT NULL,
  `type_2` varchar(100) NOT NULL,
  `inspection_type` int(1) NOT NULL COMMENT '1: Pickup; 2: Heavy; 3: Trucks; 4: Special; 99: N/A',
  `show_vehicle` int(1) NOT NULL COMMENT '1: Yes; 2:No',
  `show_workorder` int(1) NOT NULL COMMENT '1: Yes; 2:No',
  `link_inspection` varchar(100) NOT NULL DEFAULT 'NA',
  `form` varchar(100) NOT NULL DEFAULT 'NA',
  `table_inspection` varchar(100) NOT NULL,
  `id_table_inspection` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id_user` int(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `log_user` varchar(50) NOT NULL,
  `movil` varchar(12) NOT NULL,
  `email` varchar(70) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `state` int(1) NOT NULL DEFAULT 0 COMMENT '0: newUser; 1:active; 2:inactive',
  `fk_id_user_role` int(1) NOT NULL DEFAULT 7 COMMENT '99: Super Admin;',
  `photo` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `inspection_daily`
--
ALTER TABLE `inspection_daily`
  ADD PRIMARY KEY (`id_inspection_daily`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_vehicle` (`fk_id_vehicle`),
  ADD KEY `fk_id_trailer` (`fk_id_trailer`);

--
-- Indices de la tabla `inspection_generator`
--
ALTER TABLE `inspection_generator`
  ADD PRIMARY KEY (`id_inspection_generator`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_vehicle` (`fk_id_vehicle`);

--
-- Indices de la tabla `inspection_heavy`
--
ALTER TABLE `inspection_heavy`
  ADD PRIMARY KEY (`id_inspection_heavy`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_vehicle` (`fk_id_vehicle`);

--
-- Indices de la tabla `inspection_hydrovac`
--
ALTER TABLE `inspection_hydrovac`
  ADD PRIMARY KEY (`id_inspection_hydrovac`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_vehicle` (`fk_id_vehicle`);

--
-- Indices de la tabla `inspection_sweeper`
--
ALTER TABLE `inspection_sweeper`
  ADD PRIMARY KEY (`id_inspection_sweeper`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_vehicle` (`fk_id_vehicle`);

--
-- Indices de la tabla `inspection_total`
--
ALTER TABLE `inspection_total`
  ADD PRIMARY KEY (`id_inspection_total`),
  ADD KEY `fk_id_vehicle` (`fk_id_vehicle`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_inspection` (`fk_id_inspection`);

--
-- Indices de la tabla `inspection_watertruck`
--
ALTER TABLE `inspection_watertruck`
  ADD PRIMARY KEY (`id_inspection_watertruck`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_vehicle` (`fk_id_vehicle`);

--
-- Indices de la tabla `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id_maintenance`),
  ADD KEY `fk_id_vehicle` (`fk_id_vehicle`),
  ADD KEY `fk_id_maintenance_type` (`fk_id_maintenance_type`),
  ADD KEY `fk_revised_by_user` (`fk_revised_by_user`),
  ADD KEY `maintenance_state` (`maintenance_state`);

--
-- Indices de la tabla `maintenance_check`
--
ALTER TABLE `maintenance_check`
  ADD PRIMARY KEY (`id_maintenance_check`),
  ADD KEY `fk_id_maintenance` (`fk_id_maintenance`);

--
-- Indices de la tabla `maintenance_type`
--
ALTER TABLE `maintenance_type`
  ADD PRIMARY KEY (`id_maintenance_type`);

--
-- Indices de la tabla `parametric`
--
ALTER TABLE `parametric`
  ADD PRIMARY KEY (`id_parametric`);

--
-- Indices de la tabla `param_company`
--
ALTER TABLE `param_company`
  ADD PRIMARY KEY (`id_company`),
  ADD KEY `company_type` (`company_type`);

--
-- Indices de la tabla `param_hazard`
--
ALTER TABLE `param_hazard`
  ADD PRIMARY KEY (`id_hazard`),
  ADD KEY `fk_id_hazard_activity` (`fk_id_hazard_activity`),
  ADD KEY `fk_id_priority` (`fk_id_priority`);

--
-- Indices de la tabla `param_hazard_activity`
--
ALTER TABLE `param_hazard_activity`
  ADD PRIMARY KEY (`id_hazard_activity`);

--
-- Indices de la tabla `param_hazard_priority`
--
ALTER TABLE `param_hazard_priority`
  ADD PRIMARY KEY (`id_priority`);

--
-- Indices de la tabla `param_jobs`
--
ALTER TABLE `param_jobs`
  ADD PRIMARY KEY (`id_job`);

--
-- Indices de la tabla `param_material_type`
--
ALTER TABLE `param_material_type`
  ADD PRIMARY KEY (`id_material`);

--
-- Indices de la tabla `param_menu`
--
ALTER TABLE `param_menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `menu_type` (`menu_type`);

--
-- Indices de la tabla `param_menu_access`
--
ALTER TABLE `param_menu_access`
  ADD PRIMARY KEY (`id_access`),
  ADD UNIQUE KEY `indice_principal` (`fk_id_menu`,`fk_id_link`,`fk_id_role`),
  ADD KEY `fk_id_menu` (`fk_id_menu`),
  ADD KEY `fk_id_role` (`fk_id_role`),
  ADD KEY `fk_id_link` (`fk_id_link`);

--
-- Indices de la tabla `param_menu_links`
--
ALTER TABLE `param_menu_links`
  ADD PRIMARY KEY (`id_link`),
  ADD KEY `fk_id_menu` (`fk_id_menu`),
  ADD KEY `link_type` (`link_type`);

--
-- Indices de la tabla `param_role`
--
ALTER TABLE `param_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indices de la tabla `param_vehicle`
--
ALTER TABLE `param_vehicle`
  ADD PRIMARY KEY (`id_vehicle`),
  ADD UNIQUE KEY `encryption` (`encryption`),
  ADD KEY `ref_company` (`fk_id_company`),
  ADD KEY `type_level_1` (`type_level_1`),
  ADD KEY `type_level_2` (`type_level_2`);

--
-- Indices de la tabla `param_vehicle_type_2`
--
ALTER TABLE `param_vehicle_type_2`
  ADD PRIMARY KEY (`id_type_2`),
  ADD KEY `inspection_type` (`inspection_type`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `log_user` (`log_user`),
  ADD KEY `perfil` (`fk_id_user_role`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `inspection_daily`
--
ALTER TABLE `inspection_daily`
  MODIFY `id_inspection_daily` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inspection_generator`
--
ALTER TABLE `inspection_generator`
  MODIFY `id_inspection_generator` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inspection_heavy`
--
ALTER TABLE `inspection_heavy`
  MODIFY `id_inspection_heavy` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inspection_hydrovac`
--
ALTER TABLE `inspection_hydrovac`
  MODIFY `id_inspection_hydrovac` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inspection_sweeper`
--
ALTER TABLE `inspection_sweeper`
  MODIFY `id_inspection_sweeper` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inspection_total`
--
ALTER TABLE `inspection_total`
  MODIFY `id_inspection_total` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inspection_watertruck`
--
ALTER TABLE `inspection_watertruck`
  MODIFY `id_inspection_watertruck` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id_maintenance` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maintenance_check`
--
ALTER TABLE `maintenance_check`
  MODIFY `id_maintenance_check` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maintenance_type`
--
ALTER TABLE `maintenance_type`
  MODIFY `id_maintenance_type` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parametric`
--
ALTER TABLE `parametric`
  MODIFY `id_parametric` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_company`
--
ALTER TABLE `param_company`
  MODIFY `id_company` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_hazard`
--
ALTER TABLE `param_hazard`
  MODIFY `id_hazard` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_hazard_activity`
--
ALTER TABLE `param_hazard_activity`
  MODIFY `id_hazard_activity` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_hazard_priority`
--
ALTER TABLE `param_hazard_priority`
  MODIFY `id_priority` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_jobs`
--
ALTER TABLE `param_jobs`
  MODIFY `id_job` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_material_type`
--
ALTER TABLE `param_material_type`
  MODIFY `id_material` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_menu`
--
ALTER TABLE `param_menu`
  MODIFY `id_menu` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_menu_access`
--
ALTER TABLE `param_menu_access`
  MODIFY `id_access` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_menu_links`
--
ALTER TABLE `param_menu_links`
  MODIFY `id_link` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_role`
--
ALTER TABLE `param_role`
  MODIFY `id_role` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_vehicle`
--
ALTER TABLE `param_vehicle`
  MODIFY `id_vehicle` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `param_vehicle_type_2`
--
ALTER TABLE `param_vehicle_type_2`
  MODIFY `id_type_2` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inspection_daily`
--
ALTER TABLE `inspection_daily`
  ADD CONSTRAINT `inspection_daily_ibfk_1` FOREIGN KEY (`fk_id_vehicle`) REFERENCES `param_vehicle` (`id_vehicle`);

--
-- Filtros para la tabla `inspection_generator`
--
ALTER TABLE `inspection_generator`
  ADD CONSTRAINT `inspection_generator_ibfk_1` FOREIGN KEY (`fk_id_vehicle`) REFERENCES `param_vehicle` (`id_vehicle`);

--
-- Filtros para la tabla `inspection_heavy`
--
ALTER TABLE `inspection_heavy`
  ADD CONSTRAINT `inspection_heavy_ibfk_1` FOREIGN KEY (`fk_id_vehicle`) REFERENCES `param_vehicle` (`id_vehicle`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inspection_hydrovac`
--
ALTER TABLE `inspection_hydrovac`
  ADD CONSTRAINT `inspection_hydrovac_ibfk_1` FOREIGN KEY (`fk_id_vehicle`) REFERENCES `param_vehicle` (`id_vehicle`);

--
-- Filtros para la tabla `inspection_sweeper`
--
ALTER TABLE `inspection_sweeper`
  ADD CONSTRAINT `inspection_sweeper_ibfk_1` FOREIGN KEY (`fk_id_vehicle`) REFERENCES `param_vehicle` (`id_vehicle`);

--
-- Filtros para la tabla `inspection_total`
--
ALTER TABLE `inspection_total`
  ADD CONSTRAINT `inspection_total_ibfk_1` FOREIGN KEY (`fk_id_vehicle`) REFERENCES `param_vehicle` (`id_vehicle`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inspection_watertruck`
--
ALTER TABLE `inspection_watertruck`
  ADD CONSTRAINT `inspection_watertruck_ibfk_1` FOREIGN KEY (`fk_id_vehicle`) REFERENCES `param_vehicle` (`id_vehicle`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `param_hazard`
--
ALTER TABLE `param_hazard`
  ADD CONSTRAINT `param_hazard_ibfk_1` FOREIGN KEY (`fk_id_hazard_activity`) REFERENCES `param_hazard_activity` (`id_hazard_activity`),
  ADD CONSTRAINT `param_hazard_ibfk_2` FOREIGN KEY (`fk_id_priority`) REFERENCES `param_hazard_priority` (`id_priority`);

--
-- Filtros para la tabla `param_menu_access`
--
ALTER TABLE `param_menu_access`
  ADD CONSTRAINT `param_menu_access_ibfk_1` FOREIGN KEY (`fk_id_role`) REFERENCES `param_role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `param_menu_access_ibfk_2` FOREIGN KEY (`fk_id_menu`) REFERENCES `param_menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `param_menu_links`
--
ALTER TABLE `param_menu_links`
  ADD CONSTRAINT `param_menu_links_ibfk_1` FOREIGN KEY (`fk_id_menu`) REFERENCES `param_menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `param_vehicle`
--
ALTER TABLE `param_vehicle`
  ADD CONSTRAINT `ref_company` FOREIGN KEY (`fk_id_company`) REFERENCES `param_company` (`id_company`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
