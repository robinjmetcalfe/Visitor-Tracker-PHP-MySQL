-- phpMyAdmin SQL Dump
-- version 2.11.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 08, 2008 at 12:56 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `visitor_tracking`
--

DROP TABLE IF EXISTS `visitor_tracking`;
CREATE TABLE IF NOT EXISTS `visitor_tracking` (
  `entry_id` int(11) NOT NULL auto_increment,
  `visitor_id` int(11) default NULL,
  `ip_address` varchar(15) NOT NULL,
  `page_name` text,
  `query_string` text,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`entry_id`),
  KEY `visitor_id` (`visitor_id`,`timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;
