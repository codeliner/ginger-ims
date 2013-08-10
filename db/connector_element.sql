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
(22, 'Digits', 'Ginger\\Model\\Feature\\ValidatorFeature', '#', 'Ginger.Application', 'feature'),
(23, 'StringTrim', 'Ginger\\Model\\Feature\\FilterFeature', '#', 'Ginger.Application', 'feature'),
(24, 'AttributeMap', 'Ginger\\Model\\Feature\\AttributeMapFeature', '#', 'Ginger.Application', 'feature'),
(28, 'DocumentStructureMapper', 'Ginger\\Model\\Mapper\\DocumentStructureMapper', '#', 'Ginger.Application', 'mapper'),
(29, 'File', 'Ginger\\Model\\File\\SourceFile', '#', 'Ginger.Application', 'source'),
(31, 'vonWerth::categories', 'SqlConnect\\Model\\Db\\TableTarget', 'sqlconnect/targets/vonWerth/show/categories', 'SqlConnect', 'target'),
(32, 'Null', 'Ginger\\Model\\Feature\\FilterFeature', '#', 'Ginger.Application', 'feature'),
(33, 'Categories', 'JtlWawi\\Model\\Source\\Categories', '#', 'JtlWawi', 'source'),
(34, 'Directory', 'Ginger\\Model\\Directory\\SourceDirectory', '#', 'Ginger.Application', 'source'),
(35, 'vonWerth::articles', 'SqlConnect\\Model\\Db\\TableTarget', 'sqlconnect/targets/vonWerth/show/articles', 'SqlConnect', 'target'),
(36, 'Manipulator::StaticValue', 'Ginger\\Model\\Feature\\StaticValueFeature', '#', 'Ginger.Application', 'feature'),
(37, 'Url', 'Ginger\\Model\\Feature\\FilterFeature', '#', 'Ginger.Application', 'feature'),
(38, 'PHP Script', 'Ginger\\Model\\Script\\SourceScript', '#', 'Ginger.Application', 'source'),
(39, 'Dev/Null', 'Ginger\\Model\\Script\\DevNullTarget', '#', 'Ginger.Application', 'target'),
(40, 'Directory', 'Ginger\\Model\\Directory\\TargetDirectory', '#', 'Ginger.Application', 'target'),
(42, 'vonWerth::articles', 'SqlConnect\\Model\\Db\\TableSource', 'sqlconnect/sources/vonWerth/show/articles', 'SqlConnect', 'source'),
(43, 'TestSource::tartikel', 'SqlConnect\\Model\\Db\\TableSource', 'sqlconnect/sources/TestSource/show/tartikel', 'SqlConnect', 'source'),
(44, 'TestTarget::articles', 'SqlConnect\\Model\\Db\\TableTarget', 'sqlconnect/targets/TestTarget/show/articles', 'SqlConnect', 'target');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
