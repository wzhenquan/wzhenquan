
-- -------------------------------------------------------

--
-- 表的结构chzb_users
--

DROP TABLE IF EXISTS `chzb_users`;
CREATE TABLE `chzb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` bigint(15) NOT NULL,
  `mac` varchar(32) NOT NULL,
  `deviceid` varchar(32) NOT NULL,
  `model` varchar(200) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `region` varchar(50) NOT NULL,
  `exp` int(11) NOT NULL,
  `vpn` int(11) NOT NULL DEFAULT '0',
  `author` varchar(16) NOT NULL,
  `authortime` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '-1',
  `lasttime` int(11) NOT NULL,
  `marks` varchar(100) NOT NULL,
  `isvip` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mac` (`mac`,`deviceid`,`model`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
