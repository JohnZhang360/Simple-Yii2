-- MySQL dump 10.13  Distrib 5.5.52, for Win64 (x86)
--
-- Host: localhost    Database: simple_yii2
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
INSERT INTO `zb_admin` VALUES (1,'zgj','$2y$13$4L25i1P40OdjpgQwdWudKu8ugfvafamH2iSq2zEkP3YJuYbnbKngK',1477476063,'127.0.0.1');
/*!40000 ALTER TABLE `zb_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zb_config`
--

DROP TABLE IF EXISTS `zb_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zb_config` (
  `variable` varchar(50) NOT NULL COMMENT '变量名',
  `value` text COMMENT '变量值',
  PRIMARY KEY (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_config`
--

LOCK TABLES `zb_config` WRITE;
/*!40000 ALTER TABLE `zb_config` DISABLE KEYS */;
INSERT INTO `zb_config` VALUES ('site_name','Zhang Guangjian'),('site_title','Zhang Guangjian'),('site_keywords','Blog - Zhang Guangjian'),('site_description','Blog - Zhang Guangjian'),('site_icp','粤ICP备16083517号-1'),('about','额... 没什么写的?好可怕! 一定要尽快不上'),('signature','Good Good Code, Day Day Up.');
/*!40000 ALTER TABLE `zb_config` ENABLE KEYS */;
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
  `views` int(11) NOT NULL DEFAULT '0' COMMENT '浏览数',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `summary` text COMMENT '摘要',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='文章表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_post`
--

LOCK TABLES `zb_post` WRITE;
/*!40000 ALTER TABLE `zb_post` DISABLE KEYS */;
INSERT INTO `zb_post` VALUES (1,'第一篇BLOG','###我的GIT地址：https://github.com/guang-zhang',1474732800,1477386882,255,1,0,0,'###我的GIT地址：https://github.com/guang-zhang'),(2,'八数码问题','该分支使用了DBFS算法很大幅度上提升了检索的效率\n\n* 正向初始状态到目标状态,逆向目标状态到初始状态两向扩展\n* 很好的解决了普通广度优先搜索2次幂的数据量递增情况',1477041673,1477385103,255,1,0,0,'该分支使用了DBFS算法很大幅度上提升了检索的效率\n\n* 正向初始状态到目标状态,逆向目标状态到初始状态两向扩展\n* 很好的解决了普通广度优先搜索2次幂的数据量递增情况'),(3,'jQuery Validate隐藏域验证','Validate插件默认是不验证隐藏的field的。\n\n那我要把隐藏的也要验证怎么办呢?\n\n------------\n\n\n网上有说直接改插件的源码，但是插件更新了又得改回来？？？\n\n既然说到源码，那就看看源码。\n![](http://static.zgjian.cc/post/20161021175633.png)\n\n这么说它是可配置的！所以呢。\n```\n$(\"#formPost\").validate({\n	ignore: \"\",\n	submitHandler: function (form) {\n	}\n});\n```\n搞定~',1477041772,1477385085,255,1,0,0,'Validate插件默认是不验证隐藏的field的。\n\n那我要把隐藏的也要验证怎么办呢?\n\n------------\n\n\n网上有说直接改插件的源码，但是插件更新了又得改回来？？？');
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
INSERT INTO `zb_post_tags` VALUES (2,1),(3,3),(2,4),(1,1);
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
  `title` varchar(50) NOT NULL COMMENT '搜索引擎标题',
  `pic` varchar(255) NOT NULL COMMENT '图标路径',
  `description` text COMMENT '描述',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL COMMENT '录入时间',
  `link` varchar(255) NOT NULL COMMENT '搜索链接',
  `target` tinyint(1) NOT NULL DEFAULT '0' COMMENT '窗口目标，0：默认IFRAME，1：新开窗口',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='搜索引擎表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_search`
--

LOCK TABLES `zb_search` WRITE;
/*!40000 ALTER TABLE `zb_search` DISABLE KEYS */;
INSERT INTO `zb_search` VALUES (1,'百度','search/580d7223a1768.ico','',1,1,1477271611,'http://www.baidu.com',0),(2,'Bing','search/580d738052671.ico','',1,30,1477276514,'http://global.bing.com/?FORM=HPCNEN&setmkt=en-us&setlang=en-us',0),(3,'必应','search/580d738052671.ico','',1,20,1477276544,'http://cn.bing.com/',0),(4,'微信搜狗','search/580d739a92aae.ico','',1,60,1477276570,'http://weixin.sogou.com/?p=73141200&kw=',0),(7,'Wikipedia','search/580db51412647.ico','',1,50,1477293565,'https://www.wikipedia.org/',0),(6,'维基百科','search/580db51412647.ico','',1,40,1477293428,'https://zh.wikipedia.org/zh-cn/Wikipedia:%E9%A6%96%E9%A1%B5',0),(8,'Google','search/580db64310a5a.ico','',1,10,1477293653,'https://www.google.com.hk/',1),(9,'搜狗知乎','search/580daa4b06746.ico	','',1,70,1477294006,'http://zhihu.sogou.com/',0),(10,'easyicon','search/580db803666fa.ico','',1,255,1477294169,'http://www.easyicon.net/',0);
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='标签表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zb_tags`
--

LOCK TABLES `zb_tags` WRITE;
/*!40000 ALTER TABLE `zb_tags` DISABLE KEYS */;
INSERT INTO `zb_tags` VALUES (1,'PHP'),(3,'JQUERY'),(4,'算法');
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

-- Dump completed on 2016-10-26 18:03:53
