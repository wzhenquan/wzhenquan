
-- -------------------------------------------------------

--
-- 表的结构chzb_appdata
--

DROP TABLE IF EXISTS `chzb_appdata`;
CREATE TABLE `chzb_appdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataver` int(11) NOT NULL,
  `appver` varchar(16) NOT NULL,
  `setver` int(11) NOT NULL DEFAULT '0',
  `dataurl` varchar(255) NOT NULL,
  `appurl` varchar(255) NOT NULL,
  `adtext` varchar(1024) NOT NULL,
  `showtime` int(11) NOT NULL,
  `showinterval` int(11) NOT NULL,
  `needauthor` int(11) NOT NULL DEFAULT '1',
  `splash` varchar(255) NOT NULL,
  `decoder` int(11) NOT NULL DEFAULT '0',
  `buffTimeOut` int(11) NOT NULL DEFAULT '10',
  `tipusernoreg` varchar(100) NOT NULL,
  `tipuserexpired` varchar(100) NOT NULL,
  `tipuserforbidden` varchar(100) NOT NULL,
  `tiploading` varchar(100) NOT NULL,
  `ipcount` int(11) NOT NULL DEFAULT '5',
  `trialdays` int(11) DEFAULT NULL,
  `qqinfo` varchar(255) DEFAULT NULL,
  `autoupdate` int(11) DEFAULT '1',
  `randkey` varchar(100) DEFAULT '827ccb0eea8a706c4c34a16891f84e7b',
  `updateinterval` int(11) DEFAULT '15',
  `up_size` varchar(16) DEFAULT NULL,
  `up_sets` int(11) NOT NULL,
  `up_text` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 chzb_appdata
--

INSERT INTO `chzb_appdata` VALUES('1','2','1.0','1','http://127.0.0.1','http://127.0.0.1/1.apk','','120','5','1','http://127.0.0.1/bg.png','1','30','该设备无授权，请联系公众号客服@luo2888的工作室。','账号已到期，请联系公众号客服@luo2888的工作室续费。','账号已禁用，请联系公众号客服@luo2888的工作室。','正在连接，请稍后 ...','2','-999','欢迎关注微信公众号@luo2888的工作室','1','827ccb0eea8a706c4c34a16891f84e7b','10','','1','');
