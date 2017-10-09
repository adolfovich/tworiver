-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 09 2017 г., 09:34
-- Версия сервера: 5.5.49-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `snt_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `acts`
--

CREATE TABLE IF NOT EXISTS `acts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `date` date NOT NULL,
  `comment` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `post` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `contracts_type`
--

CREATE TABLE IF NOT EXISTS `contracts_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `contribution_type`
--

CREATE TABLE IF NOT EXISTS `contribution_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Indications`
--

CREATE TABLE IF NOT EXISTS `Indications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user` int(11) NOT NULL,
  `tarif` int(11) NOT NULL,
  `Indications` decimal(10,2) NOT NULL,
  `additional` decimal(10,2) NOT NULL,
  `additional_sum` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_crate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `header` varchar(255) NOT NULL,
  `text` varchar(10000) NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `important` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `sum` decimal(10,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `base` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=146 ;

-- --------------------------------------------------------

--
-- Структура таблицы `reports_fhd`
--

CREATE TABLE IF NOT EXISTS `reports_fhd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Структура таблицы `requisites`
--

CREATE TABLE IF NOT EXISTS `requisites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_short` varchar(255) NOT NULL,
  `addres` varchar(1000) NOT NULL,
  `addres_post` varchar(1000) NOT NULL,
  `inn` varchar(255) NOT NULL,
  `kpp` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_bik` varchar(255) NOT NULL,
  `bank_ks` varchar(255) NOT NULL,
  `bank_rs` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tarifs`
--

CREATE TABLE IF NOT EXISTS `tarifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `unit` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `units`
--

CREATE TABLE IF NOT EXISTS `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `session` varchar(255) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  `is_admin` int(11) NOT NULL DEFAULT '0',
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uchastok` varchar(255) NOT NULL,
  `sch_model` varchar(255) NOT NULL,
  `sch_num` varchar(255) NOT NULL,
  `sch_plomb_num` varchar(255) NOT NULL,
  `sch_step` int(11) NOT NULL DEFAULT '0',
  `balans` decimal(10,2) NOT NULL DEFAULT '0.00',
  `membership_balans` decimal(10,2) NOT NULL,
  `target_balans` decimal(10,2) NOT NULL,
  `total_balance` decimal(10,2) NOT NULL,
  `start_indications` decimal(10,2) NOT NULL DEFAULT '0.00',
  `start_balans` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sms_notice` int(11) NOT NULL DEFAULT '1',
  `email_notice` int(11) NOT NULL DEFAULT '1',
  `user_agreement` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_contracts`
--

CREATE TABLE IF NOT EXISTS `users_contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `num` varchar(255) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_contributions`
--

CREATE TABLE IF NOT EXISTS `users_contributions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contribution_type` int(11) NOT NULL,
  `year` year(4) DEFAULT NULL,
  `quarter` int(11) DEFAULT NULL,
  `sum` decimal(10,2) NOT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `paid_date` date DEFAULT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_tarifs`
--

CREATE TABLE IF NOT EXISTS `users_tarifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `tarif` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
