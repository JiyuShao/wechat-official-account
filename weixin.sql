-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2016 at 12:39 PM
-- Server version: 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weixin`
--

-- --------------------------------------------------------

--
-- Table structure for table `bi_rooms`
--

DROP TABLE IF EXISTS `bi_rooms`;
CREATE TABLE IF NOT EXISTS `bi_rooms` (
  `roomid` smallint(4) NOT NULL AUTO_INCREMENT,
  `userid` text NOT NULL,
  `allcount` tinyint(2) NOT NULL,
  `nowcount` tinyint(2) NOT NULL,
  `numbers` text NOT NULL,
  `eachs` tinyint(2) NOT NULL,
  PRIMARY KEY (`roomid`)
) ENGINE=InnoDB AUTO_INCREMENT=1005 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uc_punish`
--

DROP TABLE IF EXISTS `uc_punish`;
CREATE TABLE IF NOT EXISTS `uc_punish` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `item` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_punish`
--

INSERT INTO `uc_punish` (`id`, `item`) VALUES
(1, 'punish1'),
(2, 'punish2'),
(3, 'punish3'),
(4, 'punish4'),
(5, 'punish5'),
(6, 'punish6');

-- --------------------------------------------------------

--
-- Table structure for table `uc_rooms`
--

DROP TABLE IF EXISTS `uc_rooms`;
CREATE TABLE IF NOT EXISTS `uc_rooms` (
  `roomid` smallint(4) NOT NULL AUTO_INCREMENT,
  `userid` text NOT NULL,
  `allcount` tinyint(2) NOT NULL,
  `nowcount` tinyint(2) NOT NULL,
  `undercoverid1` tinyint(2) NOT NULL,
  `undercoverid2` tinyint(2) DEFAULT NULL,
  `whiteboardid` tinyint(2) DEFAULT NULL,
  `word1` text NOT NULL,
  `word2` text NOT NULL,
  PRIMARY KEY (`roomid`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uc_words`
--

DROP TABLE IF EXISTS `uc_words`;
CREATE TABLE IF NOT EXISTS `uc_words` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `word1` text NOT NULL,
  `word2` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_words`
--

INSERT INTO `uc_words` (`id`, `word1`, `word2`) VALUES
(1, 'cat', 'dog');

-- --------------------------------------------------------

--
-- Table structure for table `user_games`
--

DROP TABLE IF EXISTS `user_games`;
CREATE TABLE IF NOT EXISTS `user_games` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `userid` text NOT NULL,
  `currentgame` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_games`
--

INSERT INTO `user_games` (`id`, `userid`, `currentgame`, `status`) VALUES
(5, 'shao', 'UnderCover', 0),
(7, 'shao2', 'UnderCover', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
