-- MySQL dump 10.15  Distrib 10.0.14-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: dam
-- ------------------------------------------------------
-- Server version	10.0.14-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `access`
--

DROP TABLE IF EXISTS `access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fingerprint` varchar(255) DEFAULT NULL,
  `publickey` text,
  `privatekey` text,
  `app_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `host_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_access_app1_idx` (`app_id`),
  KEY `fk_access_host1_idx` (`host_id`),
  CONSTRAINT `fk_access_app1` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_access_host1` FOREIGN KEY (`host_id`) REFERENCES `host` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app`
--

DROP TABLE IF EXISTS `app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `host_id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_started` tinyint(1) DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `buildpack_url` varchar(255) DEFAULT NULL,
  `last_build` text,
  PRIMARY KEY (`id`),
  KEY `fk_app_host_idx` (`host_id`),
  KEY `fk_app_user1_idx` (`user_id`),
  CONSTRAINT `fk_app_host` FOREIGN KEY (`host_id`) REFERENCES `host` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_app_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` text,
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_config_app1_idx` (`app_id`),
  CONSTRAINT `fk_config_app1` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db`
--

DROP TABLE IF EXISTS `db`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `host_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_db_host1_idx` (`host_id`),
  CONSTRAINT `fk_db_host1` FOREIGN KEY (`host_id`) REFERENCES `host` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_link`
--

DROP TABLE IF EXISTS `db_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_db_link_db1_idx` (`db_id`),
  KEY `fk_db_link_app1_idx` (`app_id`),
  CONSTRAINT `fk_db_link_app1` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_db_link_db1` FOREIGN KEY (`db_id`) REFERENCES `db` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `domain`
--

DROP TABLE IF EXISTS `domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `app_id` int(11) NOT NULL,
  `is_redirect` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_domain_app1_idx` (`app_id`),
  CONSTRAINT `fk_domain_app1` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `host`
--

DROP TABLE IF EXISTS `host`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `host` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `addr` varchar(255) DEFAULT NULL,
  `notes` text,
  `private_key` text,
  `public_key` text,
  `is_debug` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `host_log`
--

DROP TABLE IF EXISTS `host_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `host_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `line` text,
  `ts` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_host_log_host1_idx` (`host_id`),
  CONSTRAINT `fk_host_log_host1` FOREIGN KEY (`host_id`) REFERENCES `host` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `priv_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-29 12:10:43
