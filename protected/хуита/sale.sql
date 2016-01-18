-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 18 2016 г., 17:15
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
-- Структура таблицы `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
  `good_id` int(11) unsigned NOT NULL,
  `summ` int(6) NOT NULL,
  `extra` int(6) NOT NULL DEFAULT '0',
  `date` timestamp NULL DEFAULT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `comment` text,
  `customer_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`good_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sale`
--

INSERT INTO `sale` (`good_id`, `summ`, `extra`, `date`, `channel_id`, `city`, `comment`, `customer_id`) VALUES
(1, 123, 0, '2016-01-17 15:25:24', NULL, 'asdasd', 'asdasdas', NULL),
(2, 213213, 0, '2016-01-18 09:36:41', NULL, 'zxcszdc', 'sdfcdsf', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
