-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 10. Aug 2013 um 18:30
-- Server Version: 5.5.32
-- PHP-Version: 5.5.1-1~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `ginger`
--

--
-- Daten für Tabelle `connector_element`
--

INSERT INTO `connector_element` (`id`, `name`, `class`, `link`, `module_name`, `type`) VALUES
(1, 'TableStructureMapper', 'Ginger\\Model\\Mapper\\TableStructureMapper', '#', 'Ginger.Application', 'mapper'),
(2, 'Digits', 'Ginger\\Model\\Feature\\ValidatorFeature', '#', 'Ginger.Application', 'feature'),
(3, 'StringTrim', 'Ginger\\Model\\Feature\\FilterFeature', '#', 'Ginger.Application', 'feature'),
(4, 'AttributeMap', 'Ginger\\Model\\Feature\\AttributeMapFeature', '#', 'Ginger.Application', 'feature'),
(5, 'DocumentStructureMapper', 'Ginger\\Model\\Mapper\\DocumentStructureMapper', '#', 'Ginger.Application', 'mapper'),
(6, 'File', 'Ginger\\Model\\File\\SourceFile', '#', 'Ginger.Application', 'source'),
(7, 'Null', 'Ginger\\Model\\Feature\\FilterFeature', '#', 'Ginger.Application', 'feature'),
(8, 'Directory', 'Ginger\\Model\\Directory\\SourceDirectory', '#', 'Ginger.Application', 'source'),
(9, 'Manipulator::StaticValue', 'Ginger\\Model\\Feature\\StaticValueFeature', '#', 'Ginger.Application', 'feature'),
(10, 'Url', 'Ginger\\Model\\Feature\\FilterFeature', '#', 'Ginger.Application', 'feature'),
(11, 'PHP Script', 'Ginger\\Model\\Script\\SourceScript', '#', 'Ginger.Application', 'source'),
(12, 'Dev/Null', 'Ginger\\Model\\Script\\DevNullTarget', '#', 'Ginger.Application', 'target'),
(13, 'Directory', 'Ginger\\Model\\Directory\\TargetDirectory', '#', 'Ginger.Application', 'target'),

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
