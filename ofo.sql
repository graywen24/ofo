-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016-12-11 19:08:33
-- 服务器版本: 5.5.50-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `ofo`
--

-- --------------------------------------------------------

--
-- 表的结构 `ofo_log`
--

CREATE TABLE IF NOT EXISTS `ofo_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL COMMENT '类型',
  `bike_id` varchar(10) NOT NULL COMMENT '车牌号',
  `bike_pass` varchar(10) NOT NULL COMMENT '密码',
  `time` int(10) NOT NULL,
  `status` int(1) NOT NULL,
  `ipaddr` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=139 ;

-- --------------------------------------------------------

--
-- 表的结构 `ofo_pass`
--

CREATE TABLE IF NOT EXISTS `ofo_pass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bike_id` int(10) NOT NULL,
  `bike_pass` int(10) NOT NULL,
  `offer` varchar(32) NOT NULL,
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
