
-- -------------------------------------------------------

--
-- 表的结构chzb_epg
--

DROP TABLE IF EXISTS `chzb_epg`;
CREATE TABLE `chzb_epg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remarks` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 chzb_epg
--

INSERT INTO `chzb_epg` VALUES('1','cntv-cctv1','CCTV-1','1','');
