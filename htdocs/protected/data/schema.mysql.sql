-- phpMyAdmin SQL Dump
-- version 4.0.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 25, 2013 at 02:31 PM
-- Server version: 5.5.31-1
-- PHP Version: 5.5.1-2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bloglooks`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Attachment ID',
  `post_id` int(11) NOT NULL COMMENT 'Post ID',
  `post_language` varchar(6) CHARACTER SET ascii NOT NULL COMMENT 'Post language',
  `filename` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Filename',
  `mime` varchar(100) CHARACTER SET ascii DEFAULT NULL COMMENT 'MIME type',
  `path` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Full path',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Posts attachments';

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Comment ID',
  `post_id` int(11) NOT NULL COMMENT 'Post ID',
  `post_language` varchar(6) CHARACTER SET ascii NOT NULL COMMENT 'Post language',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Content',
  `status` enum('P','A') CHARACTER SET ascii NOT NULL DEFAULT 'P' COMMENT 'Approval status',
  `timestamp` datetime NOT NULL COMMENT 'Timestamp',
  `author_id` int(11) DEFAULT NULL COMMENT 'Author ID',
  `anon_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Unregistered user name',
  `anon_email` varchar(100) CHARACTER SET ascii DEFAULT NULL COMMENT 'Unregistered user email',
  PRIMARY KEY (`id`),
  KEY `post` (`post_id`,`post_language`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Comments';

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE `configuration` (
  `name` varchar(100) CHARACTER SET ascii NOT NULL COMMENT 'Variable name',
  `language` varchar(6) CHARACTER SET ascii NOT NULL DEFAULT 'en' COMMENT 'Language',
  `value` longtext COLLATE utf8_unicode_ci COMMENT 'Variable value',
  `help` text COLLATE utf8_unicode_ci COMMENT 'Help message',
  PRIMARY KEY (`name`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Configuration';

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `name` varchar(100) CHARACTER SET ascii NOT NULL COMMENT 'Page name',
  `language` varchar(6) CHARACTER SET ascii NOT NULL DEFAULT 'en' COMMENT 'Language',
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Page title',
  `author_id` int(11) NOT NULL COMMENT 'Author ID',
  `created` datetime NOT NULL COMMENT 'Creation timestamp',
  `modified` datetime NOT NULL COMMENT 'Last modified timestamp',
  `published` datetime DEFAULT NULL COMMENT 'Publish timestamp',
  `content` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Content',
  `order` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Page order in list',
  PRIMARY KEY (`name`,`language`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Pages';

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Post ID',
  `language` varchar(6) CHARACTER SET ascii NOT NULL DEFAULT 'en' COMMENT 'Language',
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Post title',
  `author_id` int(11) NOT NULL COMMENT 'Author ID',
  `tags` text COLLATE utf8_unicode_ci COMMENT 'Tags (comma-separated)',
  `created` datetime NOT NULL COMMENT 'Creation timestamp',
  `modified` datetime NOT NULL COMMENT 'Last modified timestamp',
  `published` datetime DEFAULT NULL COMMENT 'Publish timestamp',
  `content` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Content',
  `comments_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Comments enabled flag',
  PRIMARY KEY (`id`,`language`),
  KEY `author_id` (`author_id`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Posts';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `login` varchar(140) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'Login name',
  `password` char(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'Password',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Display name',
  `role` varchar(20) CHARACTER SET ascii NOT NULL DEFAULT 'user' COMMENT 'Role',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
