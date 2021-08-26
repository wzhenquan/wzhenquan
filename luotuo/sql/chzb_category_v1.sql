
--
-- 表的结构chzb_category
--

DROP TABLE IF EXISTS `chzb_category`;
CREATE TABLE `chzb_category` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `enable` tinyint(4) NOT NULL DEFAULT '1',
  `psw` varchar(16) DEFAULT '',
  `type` varchar(16) NOT NULL DEFAULT 'default',
  `url` varchar(1024) DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 chzb_category
--

INSERT INTO `chzb_category` VALUES('1','默认频道','1','','default',NULL);
INSERT INTO `chzb_category` VALUES('2','HomeNET','1','','default','https://homenet6.github.io/nj.txt');
INSERT INTO `chzb_category` VALUES('3','Sason','1','','default','https://raw.githubusercontent.com/sasoncheung/iptv/master/all.txt');
INSERT INTO `chzb_category` VALUES('50','隐藏频道','1','','vip',NULL);
