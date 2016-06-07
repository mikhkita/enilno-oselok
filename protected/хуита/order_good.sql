-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 06 2016 г., 22:28
-- Версия сервера: 5.5.45
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `koleso`
--

-- --------------------------------------------------------

--
-- Структура таблицы `order_good`
--

CREATE TABLE IF NOT EXISTS `order_good` (
  `order_id` int(10) unsigned NOT NULL,
  `good_id` int(10) unsigned NOT NULL,
  `waybill` varchar(25) DEFAULT NULL,
  `waybill_photo` varchar(100) DEFAULT NULL,
  `tk_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`order_id`,`good_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
