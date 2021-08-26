
-- -------------------------------------------------------

--
-- 表的结构chzb_config
--

DROP TABLE IF EXISTS `chzb_config`;
CREATE TABLE `chzb_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 chzb_config
--

INSERT INTO `chzb_config` VALUES('1','secret_key','');
INSERT INTO `chzb_config` VALUES('2','epg_api_chk','0');
INSERT INTO `chzb_config` VALUES('3','ip_chk','1');
INSERT INTO `chzb_config` VALUES('4','showwea','0');
INSERT INTO `chzb_config` VALUES('5','weaapi_id','');
INSERT INTO `chzb_config` VALUES('6','weaapi_key','');
INSERT INTO `chzb_config` VALUES('7','app_sign','12315');
INSERT INTO `chzb_config` VALUES('8','app_appname','IPTV');
INSERT INTO `chzb_config` VALUES('9','app_packagename','cn.player.tv');
INSERT INTO `chzb_config` VALUES('10','jisuapi_key','');
INSERT INTO `chzb_config` VALUES('11','max_sameip_user','5');
