-- MySQL dump 10.13  Distrib 5.5.19, for osx10.6 (i386)
--
-- Host: localhost    Database: frontendtest
-- ------------------------------------------------------
-- Server version	5.5.19

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
-- Table structure for table `CoreTest`
--

DROP TABLE IF EXISTS `CoreTest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CoreTest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) NOT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `heading` varchar(2000) NOT NULL,
  `description` longtext,
  `extended_description` longtext,
  `more_details` longtext,
  `resources` longtext,
  `weight` smallint(6) DEFAULT NULL,
  `notes` varchar(2000) DEFAULT NULL,
  `run_by_default` tinyint(1) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `print_line_numbers` tinyint(1) DEFAULT NULL,
  `print_details` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FtRequest`
--

DROP TABLE IF EXISTS `FtRequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FtRequest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `environment` varchar(200) DEFAULT NULL,
  `notes` varchar(1000) DEFAULT NULL,
  `updates_req` tinyint(1) DEFAULT NULL,
  `moretests_req` tinyint(1) DEFAULT NULL,
  `type` varchar(4) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `delivered` datetime DEFAULT NULL,
  `report_summary` varchar(1000) DEFAULT NULL,
  `ft_score_a` smallint(6) DEFAULT NULL,
  `ft_score_b` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TestResult`
--

DROP TABLE IF EXISTS `TestResult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TestResult` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ft_request_id` int(11) NOT NULL,
  `kind` varchar(255) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `version` varchar(4) DEFAULT NULL,
  `heading` varchar(3000) NOT NULL,
  `body` longtext,
  `weight` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DA7D8D62CC9C00BB` (`ft_request_id`),
  CONSTRAINT `FK_DA7D8D62CC9C00BB` FOREIGN KEY (`ft_request_id`) REFERENCES `FtRequest` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-04-24 11:11:25
