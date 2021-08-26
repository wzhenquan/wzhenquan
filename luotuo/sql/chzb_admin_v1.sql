
-- -------------------------------------------------------

--
-- 表的结构chzb_admin
--

DROP TABLE IF EXISTS `chzb_admin`;
CREATE TABLE `chzb_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `psw` varchar(32) NOT NULL,
  `showcounts` tinyint(4) NOT NULL DEFAULT '20',
  `author` tinyint(4) NOT NULL DEFAULT '0',
  `useradmin` tinyint(4) NOT NULL DEFAULT '0',
  `ipcheck` tinyint(4) NOT NULL DEFAULT '0',
  `epgadmin` tinyint(4) NOT NULL DEFAULT '0',
  `channeladmin` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 chzb_admin
--

INSERT INTO `chzb_admin` VALUES('1','admin','8114c88b2062d554b895f92bd3d7b9b8','20','1','1','1','1','1');
