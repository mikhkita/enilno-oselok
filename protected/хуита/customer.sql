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
-- Структура таблицы `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(25) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `tk_id` int(11) DEFAULT NULL,
  `tk_pay_id` int(11) DEFAULT NULL,
  `tk_price` int(11) DEFAULT NULL,
  `order_number` varchar(25) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `referrer_id` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `good_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `customer`
--

INSERT INTO `customer` (`id`, `name`, `phone`, `city`, `tk_id`, `tk_pay_id`, `tk_price`, `order_number`, `price`, `referrer_id`, `photo`, `state_id`, `good_id`) VALUES
(1, 'VLad', '+7 (111) 111-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
