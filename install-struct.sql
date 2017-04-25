-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Erstellungszeit: 30. Nov 2016 um 09:50
-- Server-Version: 5.5.50-0ubuntu0.14.04.1
-- PHP-Version: 5.6.14
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
--
-- Datenbank: `wb-dw283_db1`
--
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `{TABLE_PREFIX}mod_procalendar_actions`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}mod_procalendar_actions`;
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}mod_procalendar_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `owner` int(11) NOT NULL DEFAULT '0',
  `date_start` date NOT NULL,
  `time_start` time DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  `acttype` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(255){FIELD_COLLATION} NOT NULL DEFAULT '',
  `description` text{FIELD_COLLATION} NOT NULL,
  `custom1` text{FIELD_COLLATION} NOT NULL,
  `custom2` text{FIELD_COLLATION} NOT NULL,
  `custom3` text{FIELD_COLLATION} NOT NULL,
  `custom4` text{FIELD_COLLATION} NOT NULL,
  `custom5` text{FIELD_COLLATION} NOT NULL,
  `custom6` text{FIELD_COLLATION} NOT NULL,
  `custom7` text{FIELD_COLLATION} NOT NULL,
  `custom8` text{FIELD_COLLATION} NOT NULL,
  `custom9` text{FIELD_COLLATION} NOT NULL,
  `public_stat` tinyint(4) NOT NULL DEFAULT '0',
  `rec_id` int(11) NOT NULL DEFAULT '0',
  `rec_day` varchar(255){FIELD_COLLATION} NOT NULL DEFAULT '',
  `rec_week` varchar(255){FIELD_COLLATION} NOT NULL DEFAULT '',
  `rec_month` varchar(255){FIELD_COLLATION} NOT NULL DEFAULT '',
  `rec_year` varchar(255){FIELD_COLLATION} NOT NULL DEFAULT '',
  `rec_count` smallint(6) NOT NULL DEFAULT '0',
  `rec_exclude` varchar(255){FIELD_COLLATION} NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `{TABLE_PREFIX}mod_procalendar_eventgroups`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}mod_procalendar_eventgroups`;
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}mod_procalendar_eventgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255){FIELD_COLLATION} NOT NULL DEFAULT '',
  `format` varchar(255){FIELD_COLLATION} NOT NULL DEFAULT '',
  `format_days` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `{TABLE_PREFIX}mod_procalendar_settings`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}mod_procalendar_settings`;
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}mod_procalendar_settings` (
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `settings` text{FIELD_COLLATION} NOT NULL,
  `startday` int(11) DEFAULT '0',
  `onedate` int(11) DEFAULT '0',
  `usetime` int(11) DEFAULT '0',
  `useformat` varchar(15){FIELD_COLLATION} NOT NULL DEFAULT '',
  `useifformat` varchar(15){FIELD_COLLATION} NOT NULL DEFAULT '',
  `usecustom1` int(11) DEFAULT '0',
  `custom1` text{FIELD_COLLATION} NOT NULL,
  `customtemplate1` text{FIELD_COLLATION} NOT NULL ,
  `usecustom2` int(11) DEFAULT '0',
  `custom2` text{FIELD_COLLATION} NOT NULL,
  `customtemplate2` text NOT NULL ,
  `usecustom3` int(11) DEFAULT '0',
  `custom3` text{FIELD_COLLATION} NOT NULL,
  `customtemplate3` text NOT NULL ,
  `usecustom4` int(11) DEFAULT '0',
  `custom4` text{FIELD_COLLATION} NOT NULL,
  `customtemplate4` text NOT NULL ,
  `usecustom5` int(11) DEFAULT '0',
  `custom5` text{FIELD_COLLATION} NOT NULL,
  `customtemplate5` text{FIELD_COLLATION} NOT NULL ,
  `usecustom6` int(11) DEFAULT '0',
  `custom6` text{FIELD_COLLATION} NOT NULL,
  `customtemplate6` text NOT NULL ,
  `usecustom7` int(11) DEFAULT '0',
  `custom7` text{FIELD_COLLATION} NOT NULL,
  `customtemplate7` text{FIELD_COLLATION} NOT NULL,
  `usecustom8` int(11) DEFAULT '0',
  `custom8` text{FIELD_COLLATION} NOT NULL,
  `customtemplate8` text{FIELD_COLLATION} NOT NULL ,
  `usecustom9` int(11) DEFAULT '0',
  `custom9` text{FIELD_COLLATION} NOT NULL,
  `customtemplate9` text{FIELD_COLLATION} NOT NULL,
  `resize` int(11) DEFAULT '0',
  `header` text{FIELD_COLLATION} NOT NULL,
  `footer` text{FIELD_COLLATION} NOT NULL,
  `posttempl` text{FIELD_COLLATION} NOT NULL,
  PRIMARY KEY (`section_id`)
){TABLE_ENGINE};

