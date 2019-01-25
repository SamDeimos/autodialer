# Host: 192.168.0.194  (Version: 5.5.52-MariaDB)
# Date: 2017-11-14 17:38:49
# Generator: MySQL-Front 5.3  (Build 4.95)

/*!40101 SET NAMES utf8 */;

#
# Trigger "autodialer_reg"
#
/*Calificador de llamadas Autodialer*/
DROP TRIGGER IF EXISTS `autodialer`;
CREATE DEFINER='root'@'%' TRIGGER `asteriskcdrdb`.`autodialer` AFTER INSERT ON `asteriskcdrdb`.`cdr`
  FOR EACH ROW BEGIN
IF(NEW.clid = 'Autodialer' AND NEW.dst = '') THEN
    UPDATE autodialer.calloutnumeros
      SET duration = NEW.duration, respuesta = NEW.disposition
      WHERE fecha_call = NEW.calldate;
   END IF;
END;

