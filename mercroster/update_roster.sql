ALTER TABLE `command` ADD COLUMN `header` VARCHAR(100)  DEFAULT 'link' AFTER `image`;

DROP TABLE IF EXISTS `pages`;
CREATE TABLE  `pages` (
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

INSERT INTO `pages` (id, name, game, news, prefpos) VALUES(1, 'Main', 1, 1, 1);
INSERT INTO `pages` (id, name, game, news, notables, prefpos) VALUES(2, 'Description', 1, 1, 1, 2);
INSERT INTO `pages` (id, name, game, news, units, prefpos) VALUES(3, 'Services', 1, 1, 1, 3);
INSERT INTO `pages` (id, name, game, news, prefpos) VALUES(4, 'Contact', 1, 1, 4);

UPDATE pages, command SET pages.text = command.main WHERE pages.id=1 AND command.id=1;
UPDATE pages, command SET pages.text = command.description WHERE pages.id=2 AND command.id=1;
UPDATE pages, command SET pages.text = command.services WHERE pages.id=3 AND command.id=1;
UPDATE pages, command SET pages.text = command.contact WHERE pages.id=4 AND command.id=1;

ALTER TABLE `command` drop `description`;
ALTER TABLE `command` drop `services`;
ALTER TABLE `command` drop `main`;
ALTER TABLE `command` drop `contact`;
