-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 29 2016 г., 16:20
-- Версия сервера: 5.5.50
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `rssdb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `feeds`
--

CREATE TABLE IF NOT EXISTS `feeds` (
  `feed_id` bigint(20) unsigned NOT NULL,
  `feed_url` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` bigint(20) unsigned NOT NULL,
  `feed_id` bigint(20) unsigned NOT NULL,
  `news_title` text NOT NULL,
  `news_description` text NOT NULL,
  `news_link` varchar(255) NOT NULL,
  `publication_date` int(10) unsigned NOT NULL,
  `read_news` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `news_archive`
--

CREATE TABLE IF NOT EXISTS `news_archive` (
  `news_archive_id` bigint(20) unsigned NOT NULL,
  `feed_id` bigint(20) unsigned NOT NULL,
  `news_title` text NOT NULL,
  `news_description` text NOT NULL,
  `news_link` varchar(255) NOT NULL,
  `publication_date` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`feed_id`),
  ADD UNIQUE KEY `unique_feed>url` (`feed_url`(191)) USING BTREE;

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`),
  ADD UNIQUE KEY `news_link` (`news_link`(191));

--
-- Индексы таблицы `news_archive`
--
ALTER TABLE `news_archive`
  ADD PRIMARY KEY (`news_archive_id`),
  ADD UNIQUE KEY `news_link` (`news_link`(191));

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `feeds`
--
ALTER TABLE `feeds`
  MODIFY `feed_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `news_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT для таблицы `news_archive`
--
ALTER TABLE `news_archive`
  MODIFY `news_archive_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
