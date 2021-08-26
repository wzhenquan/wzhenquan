
-- -------------------------------------------------------

--
-- 表的结构chzb_loginrec
--

DROP TABLE IF EXISTS `chzb_loginrec`;
CREATE TABLE `chzb_loginrec` (
  `userid` bigint(15) NOT NULL,
  `deviceid` varchar(32) NOT NULL,
  `mac` varchar(32) NOT NULL,
  `model` varchar(32) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `region` varchar(32) NOT NULL,
  `logintime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
