# Host: 192.168.100.25  (Version 5.5.56-MariaDB)
# Date: 2019-05-25 21:45:52
# Generator: MySQL-Front 6.1  (Build 1.26)


#
# Structure for table "calloutcampana"
#

DROP TABLE IF EXISTS `calloutcampana`;
CREATE TABLE `calloutcampana` (
  `idcampana` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `context` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `fechacreacion` datetime DEFAULT NULL,
  `extension` int(11) DEFAULT NULL,
  `prefijo` varchar(100) DEFAULT NULL,
  `maxcall` int(11) DEFAULT NULL,
  `trunk` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `callid` varchar(11) DEFAULT NULL,
  `espera` int(11) DEFAULT NULL,
  `hinicio` varchar(10) DEFAULT NULL,
  `hfin` varchar(10) DEFAULT NULL,
  `estado` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idcampana`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

#
# Structure for table "calloutnumeros"
#

DROP TABLE IF EXISTS `calloutnumeros`;
CREATE TABLE `calloutnumeros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campana` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `cedula` int(10) DEFAULT NULL,
  `option1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `option2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `callid` varchar(12) NOT NULL,
  `respuesta` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_call` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `recordingfile` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `uniqueid` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

#
# Structure for table "settings"
#

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `MaxRetries` varchar(10) DEFAULT NULL,
  `RetryTime` varchar(10) DEFAULT NULL,
  `WaitTime` varchar(10) DEFAULT NULL,
  `MaxCall` varchar(10) DEFAULT NULL,
  `Recording` varchar(10) DEFAULT NULL,
  `Prefix` varchar(10) DEFAULT NULL,
  `Prefix_Callerid` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Data for table "settings"
#

INSERT INTO `settings` VALUES (1,'1','60','30','1','0','','1911');
