
-- -------------------------------------------------------

--
-- 表的结构chzb_adminrec
--

DROP TABLE IF EXISTS `chzb_adminrec`;
CREATE TABLE `chzb_adminrec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `loc` varchar(64) NOT NULL,
  `time` varchar(64) NOT NULL,
  `func` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

