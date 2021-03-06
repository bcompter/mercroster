-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.3


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema mercroster
--

CREATE DATABASE IF NOT EXISTS mercroster;
USE mercroster;

--
-- Definition of table `mercroster`.`command`
--

DROP TABLE IF EXISTS `mercroster`.`command`;
CREATE TABLE  `mercroster`.`command` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'Merc Unit',
  `abbreviation` varchar(10) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'MU',
  `motto` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'Motto',
  `image` varchar(100) COLLATE utf8_swedish_ci DEFAULT 'link',
  `header` varchar(100) COLLATE utf8_swedish_ci DEFAULT 'link',
  `icon` varchar(100) COLLATE utf8_swedish_ci DEFAULT 'link',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `mercroster`.`command`
--

/*!40000 ALTER TABLE `command` DISABLE KEYS */;
LOCK TABLES `command` WRITE;
INSERT INTO `mercroster`.`command` VALUES  (1,'Mercenary Force','Mercs','You Pay, We Kill','commandimage.png','commandheader.png','commandicon.png');
UNLOCK TABLES;
/*!40000 ALTER TABLE `command` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`comments`
--

DROP TABLE IF EXISTS `mercroster`.`comments`;
CREATE TABLE  `mercroster`.`comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(10) unsigned NOT NULL,
  `opid` int(11) NOT NULL DEFAULT '0',
  `text` text COLLATE utf8_swedish_ci NOT NULL,
  `op` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'default',
  `opdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `le` varchar(20) COLLATE utf8_swedish_ci DEFAULT NULL,
  `ledate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `mercroster`.`comments`
--

/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
LOCK TABLES `comments` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`contracts`
--

DROP TABLE IF EXISTS `mercroster`.`contracts`;
CREATE TABLE  `mercroster`.`contracts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `employer` varchar(100) NOT NULL DEFAULT 'Employer',
  `missiontype` varchar(100) NOT NULL DEFAULT 'Mission',
  `target` varchar(100) NOT NULL DEFAULT 'System',
  `result` varchar(100) NOT NULL DEFAULT 'Ongoing',
  `name` varchar(100) NOT NULL DEFAULT 'Contract',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`contracts`
--

/*!40000 ALTER TABLE `contracts` DISABLE KEYS */;
LOCK TABLES `contracts` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `contracts` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`crew`
--

DROP TABLE IF EXISTS `mercroster`.`crew`;
CREATE TABLE  `mercroster`.`crew` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL DEFAULT 'Active',
  `rank` int(10) unsigned NOT NULL DEFAULT '1',
  `lname` varchar(30) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `callsign` varchar(30) NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `crewnumber` int(10) unsigned NOT NULL DEFAULT '1',
  `joiningdate` date DEFAULT NULL,
  `notes` text,
  `bday` date NOT NULL,
  `notable` tinyint(1) DEFAULT '0',
  `image` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`crew`
--

/*!40000 ALTER TABLE `crew` DISABLE KEYS */;
LOCK TABLES `crew` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `crew` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`crewtypes`
--

DROP TABLE IF EXISTS `mercroster`.`crewtypes`;
CREATE TABLE  `mercroster`.`crewtypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  `squad` tinyint(1) unsigned NOT NULL,
  `vehicletype` int(10) unsigned NOT NULL,
  `prefpos` int(11) NOT NULL,
  `equipment` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`crewtypes`
--

/*!40000 ALTER TABLE `crewtypes` DISABLE KEYS */;
LOCK TABLES `crewtypes` WRITE;
INSERT INTO `mercroster`.`crewtypes` VALUES  (1,'Mechwarrior',0,1,2,1),
 (2,'Vehicle Crew',1,2,4,1),
 (3,'Infantry Squad',1,3,5,1),
 (4,'Special Force Squad',1,3,6,1),
 (5,'Support Vehicle Crew',1,4,7,1),
 (6,'AeroSpace Pilot',0,5,3,1),
 (7,'Conventional Pilot',0,6,9,1),
 (8,'VTOL Pilot',1,7,10,1),
 (9,'DropShip Crew',1,8,11,1),
 (10,'JumpShip Crew',1,9,12,1),
 (11,'Staff Officer',1,0,1,0),
 (12,'IndustrialMech Pilot',0,10,8,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `crewtypes` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`dates`
--

DROP TABLE IF EXISTS `mercroster`.`dates`;
CREATE TABLE  `mercroster`.`dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `startingdate` date NOT NULL DEFAULT '2975-01-01',
  `currentdate` date NOT NULL DEFAULT '3025-01-01',
  `endingdate` date NOT NULL DEFAULT '3080-01-01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`dates`
--

/*!40000 ALTER TABLE `dates` DISABLE KEYS */;
LOCK TABLES `dates` WRITE;
INSERT INTO `mercroster`.`dates` VALUES  (1,'3025-01-01','3025-01-01','3050-01-01');
UNLOCK TABLES;
/*!40000 ALTER TABLE `dates` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`equipment`
--

DROP TABLE IF EXISTS `mercroster`.`equipment`;
CREATE TABLE  `mercroster`.`equipment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `subtype` varchar(45) NOT NULL,
  `crew` int(10) unsigned DEFAULT '0',
  `weight` int(10) unsigned NOT NULL,
  `regnumber` int(10) unsigned NOT NULL,
  `notes` text NOT NULL,
  `troid` int(10) unsigned DEFAULT '0',
  `image` varchar(45) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`equipment`
--

/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
LOCK TABLES `equipment` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`equipmenttypes`
--

DROP TABLE IF EXISTS `mercroster`.`equipmenttypes`;
CREATE TABLE  `mercroster`.`equipmenttypes` (
  `id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(45) NOT NULL,
  `license` int(10) unsigned NOT NULL,
  `maxweight` int(10) unsigned NOT NULL,
  `minweight` int(10) unsigned NOT NULL,
  `weightstep` int(10) unsigned NOT NULL,
  `weightscale` varchar(4) NOT NULL,
  `prefpos` int(11) NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `requirement` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`equipmenttypes`
--

/*!40000 ALTER TABLE `equipmenttypes` DISABLE KEYS */;
LOCK TABLES `equipmenttypes` WRITE;
INSERT INTO `mercroster`.`equipmenttypes` VALUES  (1,'BattleMech',1,100,20,5,'ton',1,1,1),
 (2,'Combat Vehicle',3,100,5,1,'ton',3,1,2),
 (3,'Infantry Weapon',7,100,1,1,'kg',4,1,3),
 (4,'Support Vehicle',6,200,1,1,'ton',6,1,5),
 (5,'AeroSpace Fighter',2,100,20,5,'ton',2,1,6),
 (6,'Conventional Fighter',4,100,5,1,'ton',7,1,7),
 (7,'VTOL',5,25,5,1,'ton',8,1,8),
 (8,'DropShip',8,100000,100,50,'ton',9,1,9),
 (9,'JumpShip',9,1000,1,1,'Mton',10,1,10),
 (10,'Industrial Mech',10,100,10,5,'ton',5,1,12);
UNLOCK TABLES;
/*!40000 ALTER TABLE `equipmenttypes` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`gallery`
--

DROP TABLE IF EXISTS `mercroster`.`gallery`;
CREATE TABLE  `mercroster`.`gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` char(32) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`gallery`
--

/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
LOCK TABLES `gallery` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`guests`
--

DROP TABLE IF EXISTS `mercroster`.`guests`;
CREATE TABLE  `mercroster`.`guests` (
  `ipaddress` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lastlogin` datetime NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `logged` tinyint(1) NOT NULL DEFAULT '0',
  `lastlogvisited` int(11) unsigned NOT NULL DEFAULT '0',
  `lastlogvisitedtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `referer` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`ipaddress`)
) ENGINE=InnoDB AUTO_INCREMENT=3632363276 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`guests`
--

/*!40000 ALTER TABLE `guests` DISABLE KEYS */;
LOCK TABLES `guests` WRITE;
INSERT INTO `mercroster`.`guests` VALUES  (3232235922,'2010-07-22 15:27:28',1,0,0,'0000-00-00 00:00:00','http://192.168.1.114/mercroster/index.php?action=profile&user=administrator&page=1&c=1');
UNLOCK TABLES;
/*!40000 ALTER TABLE `guests` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`images`
--

DROP TABLE IF EXISTS `mercroster`.`images`;
CREATE TABLE  `mercroster`.`images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `filename` varchar(250) NOT NULL,
  `gallery` int(11) NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`images`
--

/*!40000 ALTER TABLE `images` DISABLE KEYS */;
LOCK TABLES `images` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`kills`
--

DROP TABLE IF EXISTS `mercroster`.`kills`;
CREATE TABLE  `mercroster`.`kills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(10) unsigned NOT NULL,
  `type` varchar(45) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `killdate` date NOT NULL,
  `equipment` varchar(45) NOT NULL,
  `eweight` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`kills`
--

/*!40000 ALTER TABLE `kills` DISABLE KEYS */;
LOCK TABLES `kills` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `kills` ENABLE KEYS */;

--
-- Definition of table `mercroster`.`options`
--
DROP TABLE IF EXISTS `mercroster`.`options`;
CREATE TABLE  `mercroster`.`options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `value` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`options`
--
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
LOCK TABLES `options` WRITE;
INSERT INTO `mercroster`.`options` VALUES  (1,'Subtype Last',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `options` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`lastlog`
--

DROP TABLE IF EXISTS `mercroster`.`lastlog`;
CREATE TABLE  `mercroster`.`lastlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member` int(11) NOT NULL,
  `logtype` int(11) NOT NULL,
  `lasttopic` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `mercroster`.`lastlog`
--

/*!40000 ALTER TABLE `lastlog` DISABLE KEYS */;
LOCK TABLES `lastlog` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `lastlog` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`logentry`
--

DROP TABLE IF EXISTS `mercroster`.`logentry`;
CREATE TABLE  `mercroster`.`logentry` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logtype` int(10) unsigned NOT NULL,
  `opid` int(11) NOT NULL DEFAULT '0',
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `place` varchar(60) DEFAULT NULL,
  `text` text,
  `op` varchar(20) NOT NULL DEFAULT 'default',
  `opdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `le` varchar(20) DEFAULT NULL,
  `ledate` datetime DEFAULT NULL,
  `contract` int(11) NOT NULL DEFAULT '0',
  `topic` varchar(60) NOT NULL DEFAULT 'topic',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`logentry`
--

/*!40000 ALTER TABLE `logentry` DISABLE KEYS */;
LOCK TABLES `logentry` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `logentry` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`logsvisited`
--

DROP TABLE IF EXISTS `mercroster`.`logsvisited`;
CREATE TABLE  `mercroster`.`logsvisited` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logtype` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  `logid` int(11) NOT NULL,
  `lastcomment` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `mercroster`.`logsvisited`
--

/*!40000 ALTER TABLE `logsvisited` DISABLE KEYS */;
LOCK TABLES `logsvisited` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `logsvisited` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`logtypes`
--

DROP TABLE IF EXISTS `mercroster`.`logtypes`;
CREATE TABLE  `mercroster`.`logtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(60) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL DEFAULT 'log',
  `start` tinyint(1) NOT NULL DEFAULT '0',
  `end` tinyint(1) NOT NULL DEFAULT '0',
  `location` tinyint(1) NOT NULL DEFAULT '0',
  `text` tinyint(1) NOT NULL,
  `contract` tinyint(1) NOT NULL,
  `writepermission` tinyint(1) unsigned NOT NULL,
  `readpermission` tinyint(1) unsigned NOT NULL,
  `prefpos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`logtypes`
--

/*!40000 ALTER TABLE `logtypes` DISABLE KEYS */;
LOCK TABLES `logtypes` WRITE;
INSERT INTO `mercroster`.`logtypes` VALUES  (1,'Battle Report',1,1,1,1,1,3,6,1),
 (2,'Situation Update',1,0,1,1,1,3,6,2),
 (3,'AAR',1,0,1,1,1,4,6,3),
 (4,'GM\'s Scribble',1,1,1,1,1,2,2,4),
 (5,'CO\'s Scribble',1,1,1,1,1,3,3,5);
UNLOCK TABLES;
/*!40000 ALTER TABLE `logtypes` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`members`
--

DROP TABLE IF EXISTS `mercroster`.`members`;
CREATE TABLE  `mercroster`.`members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(32) NOT NULL,
  `password` varchar(45) NOT NULL,
  `cookie` char(32) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `sitename` varchar(45) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `type` int(1) unsigned NOT NULL,
  `lastlogin` datetime NOT NULL,
  `postcount` int(10) unsigned NOT NULL,
  `timeoffset` tinyint(1) NOT NULL DEFAULT '0',
  `timeformat` varchar(20) NOT NULL DEFAULT 'Y-m-d H:i:s',
  `online` tinyint(1) NOT NULL DEFAULT '0',
  `favoredunit` int(11) NOT NULL DEFAULT '0',
  `lastlogvisited` int(11) unsigned NOT NULL DEFAULT '0',
  `lastlogvisitedtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `referer` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`members`
--

/*!40000 ALTER TABLE `members` DISABLE KEYS */;
LOCK TABLES `members` WRITE;
INSERT INTO `mercroster`.`members` VALUES  (1,'administrator','e99a18c428cb38d5f260853678922e03',0x3464356335613137636563303930623836333539323332373533656539613130,'Admin','admin','admin',1,'2010-07-22 15:27:31',27,0,'d.m.Y - H:i:s',1,0,75,'2010-06-17 07:30:11',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `members` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`personnelpositions`
--

DROP TABLE IF EXISTS `mercroster`.`personnelpositions`;
CREATE TABLE  `mercroster`.`personnelpositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personneltype` int(11) NOT NULL DEFAULT '0',
  `person` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`personnelpositions`
--

/*!40000 ALTER TABLE `personnelpositions` DISABLE KEYS */;
LOCK TABLES `personnelpositions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `personnelpositions` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`ranks`
--

DROP TABLE IF EXISTS `mercroster`.`ranks`;
CREATE TABLE  `mercroster`.`ranks` (
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `rankname` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`ranks`
--

/*!40000 ALTER TABLE `ranks` DISABLE KEYS */;
LOCK TABLES `ranks` WRITE;
INSERT INTO `mercroster`.`ranks` VALUES  (1,'Recruit'),
 (2,'Private'),
 (3,'Corporal'),
 (4,'Sergeant'),
 (5,'Master Sergeant'),
 (6,'Warrant Officer'),
 (7,'Lieutenant'),
 (8,'Captain'),
 (9,'Major'),
 (10,'Colonel'),
 (11,'Lt. General'),
 (12,'Major General'),
 (13,'General'),
 (14,''),
 (15,'Commanding General');
UNLOCK TABLES;
/*!40000 ALTER TABLE `ranks` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`skillrequirements`
--

DROP TABLE IF EXISTS `mercroster`.`skillrequirements`;
CREATE TABLE  `mercroster`.`skillrequirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skilltype` int(11) NOT NULL,
  `personneltype` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`skillrequirements`
--

/*!40000 ALTER TABLE `skillrequirements` DISABLE KEYS */;
LOCK TABLES `skillrequirements` WRITE;
INSERT INTO `mercroster`.`skillrequirements` VALUES  (1,1,1),
 (2,2,1),
 (3,5,2),
 (5,6,2),
 (6,3,6),
 (7,4,6),
 (8,5,5),
 (9,8,7),
 (10,7,7),
 (11,8,8),
 (12,7,8),
 (13,9,9),
 (14,10,9),
 (15,9,10),
 (16,10,10),
 (17,1,12),
 (18,11,11),
 (19,12,3),
 (20,12,4),
 (21,13,4);
UNLOCK TABLES;
/*!40000 ALTER TABLE `skillrequirements` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`skills`
--

DROP TABLE IF EXISTS `mercroster`.`skills`;
CREATE TABLE  `mercroster`.`skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `skill` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
LOCK TABLES `skills` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`abilities`
--

DROP TABLE IF EXISTS `mercroster`.`abilities`;
CREATE TABLE  `mercroster`.`abilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `ability` int(11) NOT NULL,
  `notes` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=latin1;


--
-- Definition of table `mercroster`.`skilltypes`
--

DROP TABLE IF EXISTS `mercroster`.`skilltypes`;
CREATE TABLE  `mercroster`.`skilltypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `shortname` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`skilltypes`
--

/*!40000 ALTER TABLE `skilltypes` DISABLE KEYS */;
LOCK TABLES `skilltypes` WRITE;
INSERT INTO `mercroster`.`skilltypes` VALUES  (1,'Piloting/Mech','Piloting'),
 (2,'Gunnery/Mech','Gunnery'),
 (3,'Gunnery/AeroSpace','Gunnery'),
 (4,'Piloting/AeroSpace','Piloting'),
 (5,'Piloting/Ground Vehicle','Piloting'),
 (6,'Gunnery/Ground Vehicle','Gunnery'),
 (7,'Gunnery/Air Vehicle','Gunnery'),
 (8,'Piloting/Air Vehicle','Piloting'),
 (9,'Piloting/Spacecraft','Piloting'),
 (10,'Gunnery/Spacecraft','Gunnery'),
 (11,'Adminstration','Adminstration'),
 (12,'Gunnery/Infantry','Gunnery'),
 (13,'Special Operations','Specops');
UNLOCK TABLES;
/*!40000 ALTER TABLE `skilltypes` ENABLE KEYS */;

--
-- Definition of table `mercroster`.`abilitytypes`
--

DROP TABLE IF EXISTS `mercroster`.`abilitytypes`;
CREATE TABLE  `mercroster`.`abilitytypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`abilitytypes`
--

/*!40000 ALTER TABLE `abilitytypes` DISABLE KEYS */;
LOCK TABLES `abilitytypes` WRITE;
INSERT INTO `mercroster`.`abilitytypes` VALUES  (1,'Blood Stalker'),
 (2,'Fist Fire'),
 (3,'Marksman'),
 (4,'Multi-Tasker'),
 (5,'Oblique Attacker'),
 (6,'Range Master'),
 (7,'Sharpshooter'),
 (8,'Sniper'),
 (9,'Weapon Specialist'),
 (10,'Dodge'),
 (11,'Hot Dog'),
 (12,'Heavy Lifter'),
 (13,'Jumping Jack'),
 (14,'Maneuvering Ace'),
 (15,'Melee Master'),
 (16,'Melee Specialist'),
 (17,'Natural Grace'),
 (18,'Speed Demon'),
 (19,'Combat Intuition'),
 (20,'Demoralizer'),
 (21,'Tactical Genius'),
 (22,'Edge');
UNLOCK TABLES;
/*!40000 ALTER TABLE `abilitytypes` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`technicalreadouts`
--

DROP TABLE IF EXISTS `mercroster`.`technicalreadouts`;
CREATE TABLE  `mercroster`.`technicalreadouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `weight` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`technicalreadouts`
--

/*!40000 ALTER TABLE `technicalreadouts` DISABLE KEYS */;
LOCK TABLES `technicalreadouts` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `technicalreadouts` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`unit`
--

DROP TABLE IF EXISTS `mercroster`.`unit`;
CREATE TABLE  `mercroster`.`unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `parent` int(10) unsigned DEFAULT NULL,
  `prefpos` tinyint(3) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `limage` varchar(45) DEFAULT NULL,
  `rimage` varchar(45) DEFAULT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_swedish_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`unit`
--

/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
LOCK TABLES `unit` WRITE;
INSERT INTO `mercroster`.`unit` VALUES  (1,'1','Command',4294967295,0,9,'empty.png','',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`unitlevel`
--

DROP TABLE IF EXISTS `mercroster`.`unitlevel`;
CREATE TABLE  `mercroster`.`unitlevel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `prefpos` int(11) NOT NULL,
  `picture` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`unitlevel`
--

/*!40000 ALTER TABLE `unitlevel` DISABLE KEYS */;
LOCK TABLES `unitlevel` WRITE;
INSERT INTO `mercroster`.`unitlevel` VALUES  (1,'Individual Unit/Squad',1,'ul1.png'),
 (2,'Team/Section (Element)',2,'ul2.png'),
 (3,'Lance/Platoon (Flight)',3,'ul3.png'),
 (4,'Augmented Lance',4,'ul4.png'),
 (5,'Company/BAttery (Squadron)',5,'ul5.png'),
 (6,'Company Task Force',6,'ul6.png'),
 (7,'Battalion (Wing)',7,'ul7.png'),
 (8,'Battle Group',8,'ul8.png'),
 (9,'Regiment',9,'ul9.png');
UNLOCK TABLES;
/*!40000 ALTER TABLE `unitlevel` ENABLE KEYS */;


--
-- Definition of table `mercroster`.`unittypes`
--

DROP TABLE IF EXISTS `mercroster`.`unittypes`;
CREATE TABLE  `mercroster`.`unittypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `color` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `mercroster`.`unittypes`
--

/*!40000 ALTER TABLE `unittypes` DISABLE KEYS */;
LOCK TABLES `unittypes` WRITE;
INSERT INTO `mercroster`.`unittypes` VALUES  (1,'BattleMechs','#ADD8E6'),
 (2,'Combat Vehicles','#6B8E23'),
 (3,'Infantry','#DEB887'),
 (4,'Special Forces','#DEB887'),
 (5,'Support','#A0522D'),
 (6,'AeroSpace Forces','#8A2BE2'),
 (7,'Convention Air Forces','#1E90FF'),
 (8,'VTOLs','#1E90FF'),
 (9,'Navy','#8A2BE2'),
 (10,'HQ','#8A2BE2');
UNLOCK TABLES;
/*!40000 ALTER TABLE `unittypes` ENABLE KEYS */;

--
-- Definition of table `mercroster`.`pages`
--
DROP TABLE IF EXISTS `mercroster`.`pages`;
CREATE TABLE  `mercroster`.`pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `text` text COLLATE utf8_swedish_ci,
  `game` tinyint(1) NOT NULL,
  `news` tinyint(1) NOT NULL,
  `units` tinyint(1) NOT NULL,
  `notables` tinyint(1) NOT NULL,
  `prefpos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`pages`
--
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
LOCK TABLES `pages` WRITE;
INSERT INTO `mercroster`.`pages` (id, name, game, news, units, notables, text, prefpos) VALUES(1, 'Example Page', 1, 1, 1, 1, 'This is an example page. To edit it, go to Command>Front Pages', 1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
