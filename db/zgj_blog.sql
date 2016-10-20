-- MySQL dump 10.13  Distrib 5.5.52, for Win64 (x86)
--
-- Host: localhost    Database: zgj_blog
-- ------------------------------------------------------
-- Server version	5.5.52

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
-- Table structure for table `zb_admin`
--

DROP TABLE IF EXISTS `zb_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zb_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `login_at` int(11) DEFAULT NULL COMMENT '登录时间',
  `login_ip` char(15) DEFAULT NULL COMMENT '登录IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_admin`
--

LOCK TABLES `zb_admin` WRITE;
/*!40000 ALTER TABLE `zb_admin` DISABLE KEYS */;
INSERT INTO `zb_admin` VALUES (1,'zgj','$2y$13$4L25i1P40OdjpgQwdWudKu8ugfvafamH2iSq2zEkP3YJuYbnbKngK',NULL,NULL);
/*!40000 ALTER TABLE `zb_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zb_post`
--

DROP TABLE IF EXISTS `zb_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zb_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `content` text COMMENT '文章内容',
  `created_at` int(11) NOT NULL COMMENT '录入时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_post`
--

LOCK TABLES `zb_post` WRITE;
/*!40000 ALTER TABLE `zb_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `zb_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zb_post_tags`
--

DROP TABLE IF EXISTS `zb_post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zb_post_tags` (
  `post_id` int(11) unsigned NOT NULL COMMENT '关联文章ID',
  `tags_id` int(11) unsigned NOT NULL COMMENT '关联标签ID',
  KEY `idx_post` (`post_id`),
  KEY `idx_tags` (`tags_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章标签表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_post_tags`
--

LOCK TABLES `zb_post_tags` WRITE;
/*!40000 ALTER TABLE `zb_post_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `zb_post_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zb_search`
--

DROP TABLE IF EXISTS `zb_search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zb_search` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(255) NOT NULL COMMENT '搜索引擎标题',
  `pic` varchar(355) NOT NULL COMMENT '图标路径',
  `description` text COMMENT '描述',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='搜索引擎表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_search`
--

LOCK TABLES `zb_search` WRITE;
/*!40000 ALTER TABLE `zb_search` DISABLE KEYS */;
/*!40000 ALTER TABLE `zb_search` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zb_tags`
--

DROP TABLE IF EXISTS `zb_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zb_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `tag_name` varchar(50) NOT NULL COMMENT '标签名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_tags`
--

LOCK TABLES `zb_tags` WRITE;
/*!40000 ALTER TABLE `zb_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `zb_tags` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-10-20 17:22:06
