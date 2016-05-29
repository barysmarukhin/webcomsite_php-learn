-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 29 2016 г., 20:06
-- Версия сервера: 5.5.45
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `webcom_media`
--

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `filename`, `email`, `status`, `author`, `body`) VALUES
(66, 'photoPerson.png', '', '', 'name', 'review'),
(69, 'photoPerson.png', '', '', '5', '5'),
(73, 'photoPerson.png', '', '', '1', '1'),
(74, 'photoPerson.png', '', '', '1', '1'),
(75, 'photoPerson.png', '', '', '', ''),
(76, 'photoPerson.png', '', '', '5', '5'),
(77, '51bbcfbd31a9b15bd7dba3514c7370ca-1464529082.jpg', '', '', '5', '5'),
(78, 'fd9bc44d6546cca56eafa8ab763ff790-1464529107.jpg', '', '', '2', '2'),
(79, 'photoPerson.png', '', '', '', ''),
(80, 'aa7402062266eca66329b3edf8bcb4a3-1464530277.jpg', '', '', '4', '4'),
(81, '422d8213289f8612f7c5ffd1751064c9-1464530312.jpg', '', '', '5', '5'),
(82, 'photoPerson.png', '', '', '', ''),
(83, 'photoPerson.png', '', '', '', ''),
(84, '036151438a14597d2342987663c3b36f-1464531081.jpg', '', '', 'namename', 'review'),
(85, 'fbc57935841ff55b9b86e5b13666643e-1464531745.jpg', '', '', '1', '1'),
(86, 'photoPerson.png', '', '', '', ''),
(87, 'photoPerson.png', '', '', '', ''),
(88, 'photoPerson.png', '', '', '', ''),
(89, 'photoPerson.png', '', '', '', ''),
(90, '5bb8c3b669f459c45cf09409bb0cb5b1-1464532222.jpg', '', '', 'ddddddddddddddddddddddddd', 'ddddddddddddddddddddddddddddddddddddddddd'),
(91, '9e7b70a2753494b261c3d2d4e93a41d4-1464532237.jpg', '', '', 'ddddddddddddddddddddddddd', 'ddddddddddddddddddddddddddddddddddddddddd'),
(92, 'photoPerson.png', '', '', 'SSSSSSSSSSSSSSS', 'SSSSSSSSSSSSSSSSS'),
(93, 'photoPerson.png', '', '', '', ''),
(94, 'photoPerson.png', '', '', '1', '123'),
(95, 'photoPerson.png', '', '', 'wwwwww', 'wwwwwwwwww'),
(96, 'photoPerson.png', '', '', '', ''),
(97, 'photoPerson.png', '', '', '', ''),
(98, 'photoPerson.png', '', '', '1', '1'),
(99, 'photoPerson.png', '', '', '', ''),
(100, 'photoPerson.png', '', '', '123123123', 'ccccccccccccccc'),
(101, '16705ddfafc45d3e5cf70392ff24fe62-1464533587.jpg', '', '', 'kkkkkkkkkkkk', 'kkkkkkkkkkkkk');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(40) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`) VALUES
(1, 'admin', 'marusya', 'Barys', 'Marukhin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
