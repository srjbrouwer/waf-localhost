# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.20)
# Database: waf
# Generation Time: 2014-09-05 09:53:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table a_concepts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `a_concepts`;

CREATE TABLE `a_concepts` (
  `concept_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `concept_parent_id` int(11) NOT NULL DEFAULT '0',
  `concept_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`concept_id`),
  UNIQUE KEY `concept_name` (`concept_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `a_concepts` WRITE;
/*!40000 ALTER TABLE `a_concepts` DISABLE KEYS */;

INSERT INTO `a_concepts` (`concept_id`, `concept_parent_id`, `concept_name`)
VALUES
	(1,0,'site'),
	(2,1,'page'),
	(3,2,'page/home'),
	(4,0,'file/css'),
	(5,2,'page/overview'),
	(6,2,'page/contact'),
	(7,2,'page/disclamer'),
	(8,2,'page/about'),
	(11,2,'page/help'),
	(10,0,'page/test'),
	(12,0,'adaptationlayer'),
	(35,12,'done'),
	(15,12,'hook'),
	(16,12,'intro'),
	(17,12,'cms'),
	(18,0,'contact'),
	(29,2,'page/hook'),
	(30,0,'apage/ahooknext'),
	(31,2,'page/cms'),
	(25,0,'apage/ahookread'),
	(26,0,'apage/ahookread2');

/*!40000 ALTER TABLE `a_concepts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table a_globals
# ------------------------------------------------------------

DROP TABLE IF EXISTS `a_globals`;

CREATE TABLE `a_globals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `startup` text NOT NULL,
  `clean_auto` int(1) NOT NULL DEFAULT '1',
  `clean_expire` int(11) NOT NULL DEFAULT '0',
  `clean_last` timestamp NULL DEFAULT NULL,
  `clean_toglobal` int(1) NOT NULL DEFAULT '1',
  `log` int(1) NOT NULL DEFAULT '0',
  `benchmark` int(1) NOT NULL DEFAULT '0',
  `concept_create_auto` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `a_globals` WRITE;
/*!40000 ALTER TABLE `a_globals` DISABLE KEYS */;

INSERT INTO `a_globals` (`id`, `startup`, `clean_auto`, `clean_expire`, `clean_last`, `clean_toglobal`, `log`, `benchmark`, `concept_create_auto`)
VALUES
	(1,'{done$order;init;1;/}\n{hook$order;init;0.1;/}\n{cms$order;init;0.2;/}\n{$visited;init;1;+1/}\n{$visit;set;1/}\n{intro$knowledge;init;0;/}\n{hook$knowledge;init;0;/}\n{cms$knowledge;init;0;/}',1,86400,'2014-09-05 11:50:22',0,0,0,0);

/*!40000 ALTER TABLE `a_globals` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table a_relationships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `a_relationships`;

CREATE TABLE `a_relationships` (
  `relationship_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_concept_id` int(11) NOT NULL,
  `parent_var_name` varchar(255) NOT NULL DEFAULT '',
  `child_concept_id` int(11) NOT NULL,
  `child_var_name` varchar(255) NOT NULL DEFAULT '',
  `relationship_weight` float(3,2) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`relationship_id`),
  UNIQUE KEY `relationship` (`parent_concept_id`,`parent_var_name`,`child_concept_id`,`child_var_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `a_relationships` WRITE;
/*!40000 ALTER TABLE `a_relationships` DISABLE KEYS */;

INSERT INTO `a_relationships` (`relationship_id`, `parent_concept_id`, `parent_var_name`, `child_concept_id`, `child_var_name`, `relationship_weight`)
VALUES
	(11,16,'knowledge',3,'visit',1.00),
	(13,17,'order',17,'knowledge',1.00),
	(14,15,'order',15,'knowledge',1.00);

/*!40000 ALTER TABLE `a_relationships` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table a_vars
# ------------------------------------------------------------

DROP TABLE IF EXISTS `a_vars`;

CREATE TABLE `a_vars` (
  `var_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `var_name` varchar(255) NOT NULL,
  `var_inheritance` int(1) NOT NULL DEFAULT '0',
  `var_parent_value` int(1) NOT NULL DEFAULT '0',
  `concept_id` int(11) NOT NULL,
  `var_weight` float(3,2) NOT NULL DEFAULT '1.00',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`var_id`),
  UNIQUE KEY `variable` (`var_name`,`concept_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `a_vars` WRITE;
/*!40000 ALTER TABLE `a_vars` DISABLE KEYS */;

INSERT INTO `a_vars` (`var_id`, `var_name`, `var_inheritance`, `var_parent_value`, `concept_id`, `var_weight`, `time`)
VALUES
	(1,'visited',1,0,1,1.00,'2014-07-25 11:52:32'),
	(2,'visit',1,0,1,1.00,'2014-07-25 11:52:44'),
	(3,'visited',1,0,2,1.00,'2014-07-25 11:52:50'),
	(4,'visit',1,0,2,1.00,'2014-07-25 11:52:56'),
	(5,'visited',0,0,3,1.00,'2014-07-25 11:52:57'),
	(6,'visit',0,0,3,1.00,'2014-07-25 11:52:57'),
	(7,'visit',0,0,5,1.00,'2014-07-25 20:51:49'),
	(8,'visited',1,1,5,1.00,'2014-07-25 20:52:56'),
	(9,'visited',0,0,6,1.00,'2014-07-25 20:54:21'),
	(10,'visit',0,0,6,1.00,'2014-07-25 20:54:21'),
	(11,'visited',0,0,7,1.00,'2014-07-28 10:58:00'),
	(12,'visit',0,0,7,1.00,'2014-07-28 10:58:00'),
	(13,'visited',0,0,8,1.00,'2014-07-28 12:29:40'),
	(14,'visit',0,0,8,1.00,'2014-07-28 12:29:40'),
	(15,'visited',0,0,10,1.00,'2014-07-28 19:30:25'),
	(16,'visit',0,0,10,1.00,'2014-07-28 19:30:25'),
	(17,'visited',0,0,11,1.00,'2014-07-28 20:16:22'),
	(18,'visit',0,0,11,1.00,'2014-07-28 20:16:22'),
	(19,'link',0,0,3,1.00,'2014-07-28 20:43:25'),
	(20,'linkName',0,0,3,1.00,'2014-07-28 21:05:20'),
	(21,'knowledge',1,0,12,1.00,NULL),
	(23,'knowledge',1,0,14,1.00,NULL),
	(24,'knowledge',1,0,16,1.00,'2014-07-31 08:24:19'),
	(25,'visited',0,0,19,1.00,'2014-07-29 15:25:25'),
	(26,'visit',0,0,19,1.00,'2014-07-29 15:25:25'),
	(27,'visited',0,0,20,1.00,'2014-07-29 15:26:16'),
	(28,'visit',0,0,20,1.00,'2014-07-29 15:26:16'),
	(29,'knowledge',1,1,15,1.00,'2014-07-29 15:39:25'),
	(30,'next',0,0,3,1.00,'2014-07-30 11:48:33'),
	(31,'nextLink',0,0,3,1.00,'2014-07-30 11:48:33'),
	(32,'next',0,0,29,1.00,'2014-07-30 11:50:49'),
	(33,'nextLink',0,0,29,1.00,'2014-07-30 11:50:49'),
	(34,'visited',0,0,29,1.00,'2014-07-30 11:50:49'),
	(35,'visit',0,0,29,1.00,'2014-07-30 11:50:49'),
	(36,'next',0,0,30,1.00,'2014-07-30 12:19:07'),
	(37,'nextLink',0,0,30,1.00,'2014-07-30 12:19:07'),
	(38,'next',0,0,31,1.00,'2014-07-30 12:22:36'),
	(39,'nextLink',0,0,31,1.00,'2014-07-30 12:22:36'),
	(40,'visited',0,0,31,1.00,'2014-07-30 12:22:36'),
	(41,'visit',0,0,31,1.00,'2014-07-30 12:22:36'),
	(42,'next',0,0,1,1.00,'2014-07-30 12:28:38'),
	(43,'nextLink',0,0,1,1.00,'2014-07-30 12:28:38'),
	(44,'knowledge',0,0,17,1.00,'2014-07-30 16:08:25'),
	(46,'next',0,0,17,1.00,'2014-07-30 21:07:21'),
	(47,'nextLink',0,0,17,1.00,'2014-07-30 21:07:28'),
	(48,'next',0,0,15,1.00,'2014-07-30 21:30:44'),
	(49,'nextLink',0,0,15,1.00,'2014-07-30 21:30:52'),
	(53,'order',1,1,17,1.00,'2014-07-30 23:30:59'),
	(52,'order',1,1,15,1.00,'2014-07-30 23:32:05'),
	(54,'order',1,0,12,1.00,'2014-07-30 21:59:09'),
	(55,'knowledge',0,0,35,1.00,'2014-07-31 08:38:22'),
	(56,'order',0,0,35,1.00,'2014-07-31 08:40:35'),
	(57,'next',0,0,35,1.00,'2014-07-31 08:41:23'),
	(58,'nextLink',0,0,35,1.00,'2014-07-31 08:41:28'),
	(59,'link',0,0,29,1.00,'2014-07-31 10:56:13'),
	(61,'linkName',0,0,29,1.00,'2014-07-31 13:31:26'),
	(62,'lya',0,0,10,1.00,'2014-09-05 10:50:57');

/*!40000 ALTER TABLE `a_vars` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table a_vars_values
# ------------------------------------------------------------

DROP TABLE IF EXISTS `a_vars_values`;

CREATE TABLE `a_vars_values` (
  `var_value_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `var_id` int(11) NOT NULL,
  `session_id` varchar(40) NOT NULL DEFAULT '',
  `var_value` varchar(1000) NOT NULL,
  `global_weight` int(11) NOT NULL DEFAULT '1',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`var_value_id`),
  UNIQUE KEY `variable_value` (`var_id`,`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `a_vars_values` WRITE;
/*!40000 ALTER TABLE `a_vars_values` DISABLE KEYS */;

INSERT INTO `a_vars_values` (`var_value_id`, `var_id`, `session_id`, `var_value`, `global_weight`, `time`)
VALUES
	(1936,19,'0','page/home',1,'2014-09-05 11:06:32'),
	(1939,20,'0','15',1,'2014-09-05 10:58:35'),
	(2089,1,'0','2.5384615384615',13,'2014-07-30 17:54:20'),
	(2090,2,'0','0.76923076923077',13,'2014-07-30 17:54:21'),
	(2091,5,'0','3.4545454545454',11,'2014-07-30 17:54:21'),
	(2092,6,'0','1',11,'2014-07-30 17:54:21'),
	(2093,29,'0','0.55555555555556',9,'2014-07-30 17:54:21'),
	(2094,34,'0','1.3333333333333',6,'2014-07-30 17:40:40'),
	(2095,35,'0','1',6,'2014-07-30 17:40:40'),
	(2096,40,'0','1.4',5,'2014-07-30 17:40:40'),
	(2097,41,'0','1',5,'2014-07-30 17:40:40'),
	(2173,7,'0','1',2,'2014-07-30 16:02:57'),
	(2174,8,'0','2',2,'2014-07-30 16:02:57'),
	(2198,24,'0','6.4',5,'2014-07-30 17:54:21'),
	(2199,44,'0','0.4',5,'2014-07-30 17:54:21'),
	(2223,22,'0','0',1,'2014-07-30 16:38:29'),
	(2273,15,'0','6',1,'2014-07-30 17:54:21'),
	(2274,16,'0','1',1,'2014-07-30 17:54:21'),
	(2314,46,'0','CMS',1,'2014-07-30 21:07:46'),
	(2315,47,'0','page/cms',1,'2014-09-05 11:06:23'),
	(2326,49,'0','page/hook',1,'2014-09-05 11:06:26'),
	(2327,48,'0','Hook',1,'2014-07-30 21:31:41'),
	(2356,57,'0','Overview',1,'2014-07-31 08:42:50'),
	(2357,58,'0','page/overview',1,'2014-09-05 11:06:27'),
	(2516,61,'0','Hook Page',1,'2014-07-31 13:31:26'),
	(2871,56,'eh69vrq4t2hjrpa2pa13qo8r83','1',1,'2014-09-05 09:34:33'),
	(2872,52,'eh69vrq4t2hjrpa2pa13qo8r83','0.1',1,'2014-09-05 09:34:33'),
	(2873,53,'eh69vrq4t2hjrpa2pa13qo8r83','0.2',1,'2014-09-05 09:34:33'),
	(2874,5,'eh69vrq4t2hjrpa2pa13qo8r83','1',1,'2014-09-05 09:34:33'),
	(2875,6,'eh69vrq4t2hjrpa2pa13qo8r83','1',1,'2014-09-05 09:34:33'),
	(2876,29,'eh69vrq4t2hjrpa2pa13qo8r83','0',1,'2014-09-05 09:34:33'),
	(2877,44,'eh69vrq4t2hjrpa2pa13qo8r83','0',1,'2014-09-05 09:34:33'),
	(2878,56,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 10:25:55'),
	(2879,52,'61ta4g5bhbp9qsne61u9fnne97','0.1',1,'2014-09-05 10:25:55'),
	(2880,53,'61ta4g5bhbp9qsne61u9fnne97','0.2',1,'2014-09-05 10:25:55'),
	(2881,5,'61ta4g5bhbp9qsne61u9fnne97','44',1,'2014-09-05 11:42:04'),
	(2882,6,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 10:25:55'),
	(2883,29,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 11:42:12'),
	(2884,44,'61ta4g5bhbp9qsne61u9fnne97','0',1,'2014-09-05 11:34:13'),
	(2885,15,'61ta4g5bhbp9qsne61u9fnne97','2',1,'2014-09-05 10:58:35'),
	(2886,16,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 10:50:57'),
	(2887,62,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 10:58:35'),
	(2888,34,'61ta4g5bhbp9qsne61u9fnne97','8',1,'2014-09-05 11:42:06'),
	(2889,35,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 11:02:34'),
	(2890,13,'61ta4g5bhbp9qsne61u9fnne97','2',1,'2014-09-05 11:08:16'),
	(2891,14,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 11:07:02'),
	(2892,11,'61ta4g5bhbp9qsne61u9fnne97','2',1,'2014-09-05 11:09:30'),
	(2893,12,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 11:09:07'),
	(2894,40,'61ta4g5bhbp9qsne61u9fnne97','3',1,'2014-09-05 11:34:00'),
	(2895,41,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 11:32:21'),
	(2896,55,'61ta4g5bhbp9qsne61u9fnne97','0',1,'2014-09-05 11:34:13'),
	(2897,24,'61ta4g5bhbp9qsne61u9fnne97','0',1,'2014-09-05 11:32:32'),
	(2898,8,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 11:34:07'),
	(2899,7,'61ta4g5bhbp9qsne61u9fnne97','1',1,'2014-09-05 11:34:07'),
	(2900,56,'or8mfsthh4vtuhf7drrkcj9ls6','1',1,'2014-09-05 11:47:34'),
	(2901,52,'or8mfsthh4vtuhf7drrkcj9ls6','0.1',1,'2014-09-05 11:47:34'),
	(2902,53,'or8mfsthh4vtuhf7drrkcj9ls6','0.2',1,'2014-09-05 11:47:34'),
	(2903,5,'or8mfsthh4vtuhf7drrkcj9ls6','1',1,'2014-09-05 11:47:34'),
	(2904,6,'or8mfsthh4vtuhf7drrkcj9ls6','1',1,'2014-09-05 11:47:34'),
	(2905,29,'or8mfsthh4vtuhf7drrkcj9ls6','0',1,'2014-09-05 11:47:34'),
	(2906,44,'or8mfsthh4vtuhf7drrkcj9ls6','0',1,'2014-09-05 11:47:34'),
	(2907,56,'7ahk86so4dnj1td48fen8foc70','1',1,'2014-09-05 11:48:30'),
	(2908,52,'7ahk86so4dnj1td48fen8foc70','0.1',1,'2014-09-05 11:48:30'),
	(2909,53,'7ahk86so4dnj1td48fen8foc70','0.2',1,'2014-09-05 11:48:30'),
	(2910,5,'7ahk86so4dnj1td48fen8foc70','1',1,'2014-09-05 11:48:30'),
	(2911,6,'7ahk86so4dnj1td48fen8foc70','1',1,'2014-09-05 11:48:30'),
	(2912,29,'7ahk86so4dnj1td48fen8foc70','0',1,'2014-09-05 11:48:30'),
	(2913,44,'7ahk86so4dnj1td48fen8foc70','0',1,'2014-09-05 11:48:30');

/*!40000 ALTER TABLE `a_vars_values` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table acls
# ------------------------------------------------------------

DROP TABLE IF EXISTS `acls`;

CREATE TABLE `acls` (
  `id` int(11) NOT NULL,
  `acl_label` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `acls` WRITE;
/*!40000 ALTER TABLE `acls` DISABLE KEYS */;

INSERT INTO `acls` (`id`, `acl_label`)
VALUES
	(0,'Public'),
	(1,'User'),
	(2,'Admins');

/*!40000 ALTER TABLE `acls` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ci_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`)
VALUES
	('064a9fed6dd1c9a5a3b26f8700e99e27','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910418,''),
	('394802a72e3aa5e5462336d526355568','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910418,''),
	('e9d4d21484afc96b9b360b26fe9635f4','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910419,''),
	('47c0437baf7bed24ef96be0534d66e15','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910419,''),
	('6d417f5791775cdfe29fed0a5b44b998','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910419,''),
	('e93579e46a85b7b14255bba88bbf6850','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910419,''),
	('6737860c3808ca32569f24909cb17e27','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910421,'a:2:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:6:{s:7:\"user_id\";s:1:\"1\";s:11:\"user_active\";s:1:\"1\";s:10:\"user_email\";s:5:\"admin\";s:8:\"user_acl\";s:1:\"2\";s:10:\"user_fname\";s:5:\"Admin\";s:10:\"user_lname\";s:0:\"\";}}'),
	('8f419f616f88cc03c1f04d955a767155','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910421,''),
	('9f9ef40aa426d23dcd7d6a43947038d5','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910422,''),
	('51b064da06cacffcd83d614adf66e183','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910422,''),
	('b90120526a940c5f8866ebcce8576440','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910422,''),
	('1eb3599b5c6afd96a9e63f96d964d1b3','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910422,''),
	('fba8d78275f59e51c5a186967c4e45d3','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910422,''),
	('5f2956a03575eca9c555a9f78bbfdeea','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910422,''),
	('37fc6b498b35e7f6d62c25fb9e8bcb15','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910454,''),
	('94417576841594281ed483f42341d887','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910454,''),
	('ae3e67d8ce06103bcfce024c9bb66722','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910454,''),
	('3314a4c36b23847ea020662a9b405fbf','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910454,''),
	('2f76ea1a23a1dd95bd733bfb32c8ceb3','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910454,''),
	('f1e2eb85a68f78aea7a2663a4f66273d','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910454,''),
	('3ec37c18b490ab464377eaa5a9f02d46','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910456,''),
	('8a2a0fc992f17bb96ab4bd55ccac9079','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910456,''),
	('a9c1b2242b265be0656e579b79e0dfb5','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910456,''),
	('711fdfadc3e876996f4bde14c8bd8a6c','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910456,''),
	('2fe481da919c0a8db1108b861e36f6c0','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910456,''),
	('543976972403468d292f5203b0815c7d','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910456,''),
	('d4339527b283629c35b18218c7c6ca61','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910456,''),
	('765f7be28fdb4de0aeb8d3d3db7bd93e','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910459,'a:2:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:6:{s:7:\"user_id\";s:1:\"1\";s:11:\"user_active\";s:1:\"1\";s:10:\"user_email\";s:5:\"admin\";s:8:\"user_acl\";s:1:\"2\";s:10:\"user_fname\";s:5:\"Admin\";s:10:\"user_lname\";s:0:\"\";}}'),
	('b888ec9d60ed6a48c4be3d7b85810564','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910459,''),
	('6513dd5fb7e4b63a5085404085e1aeba','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910459,''),
	('b8f5c2d1cc892b293aa76baf94584d6e','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910459,''),
	('a1ff91fa3225fef09aac81db498edb88','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910459,''),
	('0009a13f90931323b34ba64a40594f2e','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910459,''),
	('0ed31e9ef53bc44eec8a9bf35bf12beb','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910459,''),
	('b01d573dbab4308840bfdf9a8c13ada4','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',1409910459,''),
	('9cd5655cc49bbfccc10bb7dc5f07b87a','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.78.2 (KHTML, like Gecko) Version/7.0.6 Safari/537.78.2',1409910510,''),
	('a71d038505ee41f85467e1bd9e5c8bfe','::1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.78.2 (KHTML, like Gecko) Version/7.0.6 Safari/537.78.2',1409910510,'a:2:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";a:6:{s:7:\"user_id\";s:1:\"1\";s:11:\"user_active\";s:1:\"1\";s:10:\"user_email\";s:5:\"admin\";s:8:\"user_acl\";s:1:\"2\";s:10:\"user_fname\";s:5:\"Admin\";s:10:\"user_lname\";s:0:\"\";}}');

/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_answer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_answer`;

CREATE TABLE `mc_answer` (
  `answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL DEFAULT '',
  `correct_id` int(11) NOT NULL,
  PRIMARY KEY (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `mc_answer` WRITE;
/*!40000 ALTER TABLE `mc_answer` DISABLE KEYS */;

INSERT INTO `mc_answer` (`answer_id`, `question_id`, `answer`, `correct_id`)
VALUES
	(1,1,'Display hook',1),
	(2,1,'Module hook',0),
	(3,1,'Controller hook',0),
	(4,3,'ersdfsdfsdfdsffsf',1);

/*!40000 ALTER TABLE `mc_answer` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_correct
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_correct`;

CREATE TABLE `mc_correct` (
  `id` int(11) NOT NULL,
  `correct_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `mc_correct` WRITE;
/*!40000 ALTER TABLE `mc_correct` DISABLE KEYS */;

INSERT INTO `mc_correct` (`id`, `correct_value`)
VALUES
	(0,'No'),
	(1,'Yes Exclusive'),
	(2,'Yes With others');

/*!40000 ALTER TABLE `mc_correct` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_question`;

CREATE TABLE `mc_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL DEFAULT '',
  `score` double(11,2) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `mc_question` WRITE;
/*!40000 ALTER TABLE `mc_question` DISABLE KEYS */;

INSERT INTO `mc_question` (`id`, `test_id`, `question`, `score`)
VALUES
	(1,1,'What kind of hook is used by the adaptive framework?',1.00),
	(2,1,'Vraag 2',1.00),
	(3,2,'test 2 question',1.00);

/*!40000 ALTER TABLE `mc_question` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_test
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_test`;

CREATE TABLE `mc_test` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `test_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `mc_test` WRITE;
/*!40000 ALTER TABLE `mc_test` DISABLE KEYS */;

INSERT INTO `mc_test` (`id`, `test_name`)
VALUES
	(1,'Adaptative'),
	(2,'2');

/*!40000 ALTER TABLE `mc_test` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_test_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_test_question`;

CREATE TABLE `mc_test_question` (
  `test_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  UNIQUE KEY `test_id` (`test_id`,`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `mc_test_question` WRITE;
/*!40000 ALTER TABLE `mc_test_question` DISABLE KEYS */;

INSERT INTO `mc_test_question` (`test_id`, `question_id`)
VALUES
	(1,1),
	(2,1),
	(3,2);

/*!40000 ALTER TABLE `mc_test_question` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `menu_id` int(11) NOT NULL DEFAULT '1',
  `order` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL DEFAULT 'left',
  `acl_id` int(1) NOT NULL DEFAULT '0' COMMENT '0=public,1=user,2=manager,3=admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;

INSERT INTO `menu` (`id`, `parent_id`, `menu_id`, `order`, `name`, `link`, `position`, `acl_id`)
VALUES
	(2,0,1,1,'About','page/about','right',0),
	(3,0,1,1,'Logout','user/logout','right',1),
	(4,0,2,1,'Logout','user/logout','right',2),
	(6,0,1,2,'Admin','admin','left',2),
	(7,22,2,3,'Users','admin/users','left',2),
	(8,22,2,1,'Menus','admin/menus','left',2),
	(9,22,2,2,'Pages','admin/pages','left',2),
	(10,0,2,1,'Home','admin','left',1),
	(11,0,1,1,'Contact {$visited/}','page/contact','footer',0),
	(12,0,1,2,'Disclamer','page/disclamer','footer',0),
	(13,0,1,0,'Help','page/help','footer',0),
	(14,0,1,9,'Admin','admin','left',0),
	(15,0,1,1,'{adaptationlayer$order;top;min;conceptGlobalVar;0;next/}','{adaptationlayer$order;top;min;conceptGlobalVar;0;nextLink/}','left',0),
	(16,21,2,1,'Settings Adap.','admin/a_settings','left',2),
	(17,21,2,2,'Concepten','admin/a_concepts','left',2),
	(18,21,2,3,'Variables','admin/a_variables','left',2),
	(19,21,2,4,'Relationships','admin/a_relationships','left',2),
	(20,21,2,5,'Values','admin/a_variable_values','left',2),
	(21,0,2,3,'Adaptation','#','left',2),
	(22,22,2,2,'CMS','#','left',2),
	(23,0,2,4,'MC Plugin','#','left',2),
	(24,23,2,1,'Tests','admin/mc_test','left',2),
	(25,23,2,2,'Questions','admin/mc_question','left',0),
	(26,23,2,3,'Answers','admin/mc_answer','left',2);

/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;

INSERT INTO `menus` (`id`, `name`)
VALUES
	(1,'Mainmenu'),
	(2,'Adminmenu');

/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) NOT NULL DEFAULT '',
  `acl_id` int(11) NOT NULL DEFAULT '0',
  `page_title` varchar(255) NOT NULL DEFAULT '',
  `page_text` text,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`page_id`, `page_name`, `acl_id`, `page_title`, `page_text`)
VALUES
	(2,'contact',0,'Contact','<p>\n bestand aanwezig</p>\n'),
	(3,'disclamer',0,'Disclamer','<div class=\"row\">\n <div class=\"col-md-8\">\n  <h2>\n   Cookies</h2>\n  <p>\n   This website uses session-cookies to bind your session to our server. The session cookie contains a unique value, in your case: {#expression;$this-&gt;sessionId/}. {#fade;in;4000%0}External parties are not involved, information stays on our servers.{/#fade%0}</p>\n  <p>\n   {$visited;&gt;2}<i>You visited this page more than two times, maybe we can provide additional information. Please contact us at 0031 623192869</i>{/$visited}</p>\n </div>\n <div class=\"col-md-4\">\n  <div class=\"panel panel-primary\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     Visited variable</h3>\n   </div>\n   <div class=\"panel-body\">\n    <p>\n     U visited this page {$visited/} times.</p>\n   </div>\n  </div>\n </div>\n</div>\n<p>\n &nbsp;</p>\n'),
	(6,'Help',0,'Help','<div class=\"panel-body\">\n <strong>Menu sorted from max to min:</strong></div>\n<div class=\"panel-body\">\n Menu item 1: {site$visited;top;max;concept;0/}<br />\n Menu item 2: {site$visited;top;max;concept;1/}<br />\n Menu item 3:&nbsp;{site$visited;top;max;concept;2/}</div>\n<div class=\"panel-body\">\n Menu example based on all pages<br />\n {page$visited;top;max;menu;/}</div>\n'),
	(7,'Home',0,'Home','{hook$knowledge;init;0;/}{cms$knowledge;init;0;/}\n<div class=\"row\">\n <div class=\"col-md-8\">\n  <p>\n   {#processbar;1;0;1;adaptationlayer$knowledge/}</p>\n  <p>\n   {0;1;adaptationlayer$knowledge;&lt;1}</p>\n  <div class=\"jumbotron\">\n   <h1>\n    Welcome</h1>\n   <p>\n    Welcome on <u>adaptive</u> web-site used as a prove of concept for a master thesis. You are reading this text because your current knowlegde level is&nbsp;{0;5;adaptationlayer$knowledge/}.</p>\n  </div>\n  <div class=\"panel panel-success\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     Concept</h3>\n   </div>\n   <div class=\"panel-body\">\n    This website isused as a proof of concept. It uses a PHP framework, called CodeIngiter. Pages are defined in a MySQL database and loaded through different controllers, models and views (MVC architecture). The output of the framework (CI) is processed by an adaptation layer. This layer is hooked on the framework.\n    <p>\n     &nbsp;</p>\n   </div>\n  </div>\n  <ul class=\"pager\">\n   <li class=\"next\">\n    <a href=\"{adaptationlayer$order;top;min;conceptGlobalVar;0;nextLink/}\">Next topic: {adaptationlayer$order;top;min;conceptGlobalVar;0;next/}</li>\n  </ul>\n  <p>\n   &nbsp;</p>\n  <p>\n   {/$knowledge} {0;1;adaptationlayer$knowledge;==1}</p>\n  <div class=\"jumbotron\">\n   <h1>\n    Welcome back!</h1>\n   <p>\n    U visited about {page$visited/} pages in total, including duplicates, without duplicates: {page$visit/}.<br/>You completed the course!<br/><br/><span id=\'reload\'><a id=\'reset\'>Reset your knowledge</a>{#alink;areset;reload;reset;0/}</span></p>\n  </div>\n  <p>\n   {/$knowledge}</p>\n </div>\n <div class=\"col-md-4\">\n{0;1;adaptationlayer$knowledge;!=1}\n  <div class=\"panel panel-primary\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     Next page</h3>\n   </div>\n   <div class=\"panel-body\" id=\"next\">\n    <a href=\"{adaptationlayer$order;top;min;conceptGlobalVar;0;nextLink/}\">{adaptationlayer$order;top;min;conceptGlobalVar;0;next/}</a></div>\n  </div>\n{/$knowledge}\n  <div class=\"panel panel-default\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     Adaptation knowledge level</h3>\n   </div>\n   <div class=\"panel-body\">\n    Your current level: {0;5;adaptationlayer$knowledge/}</div>\n  </div>\n  <div class=\"panel panel-default\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     Adaptation Menu</h3>\n   </div>\n   <div class=\"panel-body\">\n    Menu example based on the <u>number</u> of visits to&nbsp;all pages:<br />\n    {page$visited;top;max;menu;start:0;start:0;linkVar:link;linkNameVar:linkName/}</div>\n  </div>\n  <div class=\"panel panel-default\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     Adaptation command examples</h3>\n   </div>\n   <div class=\"panel-body\">\n    <strong>If-command:</strong><br />\n    { $visit;==1}You visited the current page{ /$visit}</div>\n   <div class=\"panel-body\">\n    <strong>Echo-command:</strong><br />\n    { $visit/} Here you echo the value of the variable of the current concept</div>\n   <div class=\"panel-body\">\n    <strong>Hashtag-command:</strong><br />\n    The hashtag-command supports different methods, e.g.:<br />\n    <br />\n    1. Time based comparison<br />\n    2.&nbsp;Fade-In<br />\n    3.&nbsp;Fade-Out</div>\n   <div class=\"panel-body\">\n    <strong>Menu sorted from max to min:</strong></div>\n   <div class=\"panel-body\">\n    Menu item 1: { site$visited;top;max;concept;0/} <span class=\"badge\">{site$visited;top;max;conceptVar;0;visited/}</span><br />\n    Menu item 2: {site$visited;top;max;concept;1/} <span class=\"badge\">{site$visited;top;max;conceptVar;1;visited/}</span><br />\n    Menu item 3: {site$visited;top;max;concept;2/} <span class=\"badge\">{site$visited;top;max;conceptVar;2;visited/}</span></div>\n  </div>\n </div>\n</div>\n<p>\n &nbsp;</p>\n'),
	(10,'about',0,'About','<div class=\"panel panel-default\">\n  <div class=\"panel-body\">\n    <p>\n This project is built as a &#39;proof of concept&#39; for my master thesis. The master thesis is about defining an adaptive web-based framework. This framework should provide a basic concept for defining adaptation within current general web-techniques used, like PHP, MySQL. {$visited;==0}{#fadein;10000}Approximately 39% of the web-sites are using content management systems (CMS). The most commonly used CMS &nbsp;is wordpress followed by Joomla.{/#fadein}{/$visited}{$visited;&gt;0%1}Approximately 39% of the web-sites are using content management systems (CMS). The most commonly used CMS &nbsp;is wordpress followed by Joomla.{/$visited%1}</p>\n<h2>\n Concept Relation Model</h2>\n<p>\n The framework uses a concept, relation model for defining and setting variables.</p>\n\n  </div>\n</div>\n'),
	(11,'Benchmark',0,'Benchmark','<p>\n view exists</p>\n'),
	(12,'hook',0,'Hook','<div id=\"adaptationlayerknowledge\">\n Adaptation layer knowledge level: {#processbar;1;0;1;adaptationlayer$knowledge/}</div>\n<p>\n {#read;ahookread;knowledgediv;5000/}{#read;anext;menu_1_0;5500/}{#read;anext2;next;5500/}{#read;aknowledgebar;adaptationlayerknowledge;5500/}</p>\n<div class=\"row\">\n <div class=\"col-sm-5 col-md-6\">\n  <div class=\"jumbotron\">\n   <h1>\n    Hook</h1>\n   This web-application is built on the codeIgniter framework. Within the codeIngiter framework, different controllers, models and views are written for content management purposes. The framework is connected with a special adaptation layer. This conneciton is a so called `hook`. This hook processes all page content before being sent to the client (you).</div>\n  <ul class=\"pager\">\n   <li class=\"next\" id=\"next\">\n    &nbsp;</li>\n  </ul>\n </div>\n <div class=\"col-sm-5 col-sm-offset-2 col-md-6 col-md-offset-0\">\n  <div class=\"panel panel-default\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     Hook knowledge level</h3>\n   </div>\n   <div class=\"panel-body\" id=\"knowledgediv\">\n    Your current level: {#processbar;1;0;1;hook$knowledge/}</div>\n  </div>\n  <div class=\"panel panel-info\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     Code of the hook</h3>\n   </div>\n   <div class=\"panel-body\">\n    The code of the hook is very simple, it just contains a `require` to import the adaptation layer file. Then an object is created and within this object the function `run` is called.</div>\n  </div>\n </div>\n</div>\n<p>\n &nbsp;</p>\n'),
	(8,'Overview',0,'Overview','<div class=\"jumbotron\">\n <h1>\n  Congratulations!</h1>\n You completed the course.</div>\n<div class=\"panel panel-info\">\n <div class=\"panel-heading\">\n  <h3 class=\"panel-title\">\n   Variable overview</h3>\n </div>\n <div class=\"panel-body\">\n  <p>\n   {adaptationlayer$order;top;min;menu;linkVar:nextLink;linkNameVar:next/}<br />\n   Hook knowledge {hook$knowledge/}<br />\n   CMS knowledge {cms$knowledge/}<br />\n   Intro knowledge {intro$knowledge/}<br />\n   Hook order {hook$order/}<br />\n   CMS order {cms$order/}<br />\n   <br />\n   Adaptation Layer knowledge (normal calculation): {adaptationlayer$knowledge/}<br />\n   Adaptation Layer knowledge (wa calculation): {0;1;adaptationlayer$knowledge/}<br />\n   Adaptation Layer knowledge (global + normal calculation): {0;2;adaptationlayer$knowledge/}<br />\n   Adaptation Layer knowledge (global + wa calculation): {0;3;adaptationlayer$knowledge/}<br />\n   Adaptation Layer knowledge (global): {1;0;adaptationlayer$knowledge/}<br />\n   Adaptation Layer knowledge (wa global): {1;1;adaptationlayer$knowledge/}</p>\n </div>\n</div>\n<p>\n &nbsp;</p>\n'),
	(17,'acmsread',0,'Adaptation CMS Read','<div>\n {done$knowledge;set;1/}{cms$knowledge;set;1/}Your current level:&nbsp;{#processbar;1;0;1;cms$knowledge/}</div>\n'),
	(13,'ahookread',0,'Adaptation Hook Read','<div>\n {hook$knowledge;set;1/}Your current level:&nbsp;{#processbar;1;0;1;hook$knowledge/}</div>\n'),
	(20,'anext2',0,'anext2','<a href=\"{adaptationlayer$order;top;min;conceptGlobalVar;0;nextLink/}\">Next: {adaptationlayer$order;top;min;conceptGlobalVar;0;next/}</a>'),
	(15,'anext',0,'anext','<a href=\"{adaptationlayer$order;top;min;conceptGlobalVar;0;nextLink/}\">{adaptationlayer$order;top;min;conceptGlobalVar;0;next/}</a>'),
	(16,'cms',0,'CMS','<div id=\"adaptationlayerknowledge\">\n Adaptation layer knowledge level: {#processbar;1;0;1;adaptationlayer$knowledge/}</div>\n<p>\n {#read;acmsread;knowledgediv;5000/}{#read;anext;menu_1_0;5500/}{#read;anext2;next;5500/}{#read;aknowledgebar;adaptationlayerknowledge;5500/}</p>\n<div class=\"row\">\n <div class=\"col-sm-5 col-md-6\">\n  <div class=\"jumbotron\">\n   <h1>\n    Content Management Systems</h1>\n   This web-application is built on the codeIgniter framework. Within the codeIngiter framework, different controllers, models and views are written for content management purposes. The framework is connected with a special adaptation layer. This conneciton is a so called `hook`. This hook processes all page content before being sent to the client (you).</div>\n  <ul class=\"pager\">\n   <li class=\"next\" id=\"next\">\n    &nbsp;</li>\n  </ul>\n </div>\n <div class=\"col-sm-5 col-sm-offset-2 col-md-6 col-md-offset-0\">\n  <div class=\"panel panel-default\">\n   <div class=\"panel-heading\">\n    <h3 class=\"panel-title\">\n     CMS knowledge level</h3>\n   </div>\n   <div class=\"panel-body\" id=\"knowledgediv\">\n    Your current level: {#processbar;1;0;1;cms$knowledge/}</div>\n  </div>\n </div>\n</div>\n<p>\n &nbsp;</p>\n'),
	(18,'aknowledgebar',0,'aknowledgebar','<div id=\"adaptationlayerknowledge\">\n Adaptation layer knowledge level: {#processbar;1;0;1;adaptationlayer$knowledge/}</div>\n'),
	(21,'test',0,'test','<div>\n {1;0;page/home$linkName;init;0;+1/}</div>\n<div>\n global set test {1;0;page/home$linkName;set;&#39;Home Page Link&#39; / }</div>\n<div>\n {1;0;page/hook$linkName;set;&#39;Hook Page&#39;/}</div>\n<div>\n home link global: {1;0;page/home$link/} , linkName:&nbsp;{1;0;page/home$linkName/}</div>\n<div id=\"test\">\n test div</div>\n<p>\n <h> Test{#interval;atest;test;5000/}</h></p>\n<p>\n {$lya;init;0;+1/}</p>\n<p>\n {$lya;&gt;2}<strong>Blabla</strong> Polo blabla {/$lya}</p>\n<h>\n<p>\n sskdfjsdkfjslkafj, the home page is visited {page/home$visited/} times.</p>\n<p>\n {#fade;in;2000}<img alt=\"\" src=\"http://doowansnewsandevents.files.wordpress.com/2013/05/one-size-fits-all-1.jpg\" />{/#fade}</p>\n</h>'),
	(22,'atest',0,'atest','<p>\n {$visited;init;0;+1/}</p>\n<p>\n content after 5 seconds -&gt; dit is de {$visited/} keer</p>\n'),
	(23,'areset',0,'areset',' {hook$knowledge;set;0/}{cms$knowledge;set;0/}{intro$knowledge;set;0/}{done$knowledge;set;0/}<a href=\'page/home\'>Reset done! Reload page</a>\n');

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_active` int(1) NOT NULL DEFAULT '1',
  `user_email` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) NOT NULL DEFAULT '',
  `acl_id` int(1) NOT NULL DEFAULT '1',
  `user_fname` varchar(255) NOT NULL,
  `user_lname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`user_id`, `user_active`, `user_email`, `user_password`, `acl_id`, `user_fname`, `user_lname`)
VALUES
	(1,1,'admin','d033e22ae348aeb5660fc2140aec35850c4da997',2,'Admin','');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
