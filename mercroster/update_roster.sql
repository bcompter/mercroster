ALTER TABLE `crew` ADD COLUMN `callsign` varchar(30);

ALTER TABLE `equipment` ADD COLUMN `troid` int(10);

--
-- Definition of table `abilities`
--

CREATE TABLE  `abilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `ability` int(11) NOT NULL,
  `notes` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=latin1;

--
-- create table `abilitytypes`
--
CREATE TABLE  `abilitytypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mercroster`.`abilitytypes`
--

/*!40000 ALTER TABLE `abilitytypes` DISABLE KEYS */;
LOCK TABLES `abilitytypes` WRITE;
INSERT INTO `abilitytypes` VALUES  (1,'Blood Stalker'),
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
-- Definition of table `options`
--
DROP TABLE IF EXISTS `options`;
CREATE TABLE  `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `value` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `options`
--
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
LOCK TABLES `options` WRITE;
INSERT INTO `options` VALUES  (1,'Subtype Last',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
