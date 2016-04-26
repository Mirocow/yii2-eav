/*
SQLyog Ultimate v11.42 (64 bit)
MySQL - 5.6.25-1~dotdeb+7.1 : Database - jiajiayoupin_loc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `eav_attribute` */

DROP TABLE IF EXISTS `eav_attribute`;

CREATE TABLE `eav_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entityId` int(11) DEFAULT NULL,
  `typeId` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT 'default',
  `name` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `defaultValue` varchar(255) DEFAULT NULL,
  `defaultOptionId` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `required` tinyint(1) DEFAULT '1',
  `order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_Attribute_typeId` (`typeId`),
  KEY `FK_EntityId` (`entityId`),
  KEY `FK_Attribute_defaultOptionId` (`defaultOptionId`),
  CONSTRAINT `FK_Attribute_defaultOptionId` FOREIGN KEY (`defaultOptionId`) REFERENCES `eav_attribute_option` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_Attribute_typeId` FOREIGN KEY (`typeId`) REFERENCES `eav_attribute_type` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_EntityId` FOREIGN KEY (`entityId`) REFERENCES `eav_entity` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

/*Data for the table `eav_attribute` */

insert  into `eav_attribute`(`id`,`entityId`,`typeId`,`type`,`name`,`label`,`defaultValue`,`defaultOptionId`,`description`,`required`,`order`) values (77,13,5,'default','c1','Тип генератора',NULL,NULL,'Для резервного электроснабжения подойдут генераторы от 2 до 15 кВт (от 19 100 до 260 000 рублей).\n\nДля ежедневного или постоянного использования от 10 до 500 кВт (от 230 000 рублей)',1,0),(78,13,5,'filtr','c78','Untitled',NULL,NULL,'',1,1);

/*Table structure for table `eav_attribute_option` */

DROP TABLE IF EXISTS `eav_attribute_option`;

CREATE TABLE `eav_attribute_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attributeId` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `defaultOptionId` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Option_attributeId` (`attributeId`),
  CONSTRAINT `FK_Option_attributeId` FOREIGN KEY (`attributeId`) REFERENCES `eav_attribute` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=358 DEFAULT CHARSET=utf8;

/*Data for the table `eav_attribute_option` */

insert  into `eav_attribute_option`(`id`,`attributeId`,`value`,`defaultOptionId`) values (352,77,'резервный',1),(353,77,'ежедневный',0),(354,77,'постоянный',0),(355,78,'A',1),(356,78,'B',0),(357,78,'C',0);

/*Table structure for table `eav_attribute_type` */

DROP TABLE IF EXISTS `eav_attribute_type`;

CREATE TABLE `eav_attribute_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `handlerClass` varchar(255) DEFAULT NULL,
  `storeType` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `eav_attribute_type` */

insert  into `eav_attribute_type`(`id`,`name`,`handlerClass`,`storeType`) values (1,'text','\\mirocow\\eav\\widgets\\TextInput',0),(2,'option','\\mirocow\\eav\\widgets\\DropDownList',1),(3,'checkbox','\\mirocow\\eav\\widgets\\CheckBoxList',2),(4,'array','\\mirocow\\eav\\widgets\\EncodedTextInput',3),(5,'radio','\\mirocow\\eav\\widgets\\RadioList',1),(6,'area','\\mirocow\\eav\\widgets\\Textarea',2);

/*Table structure for table `eav_attribute_value` */

DROP TABLE IF EXISTS `eav_attribute_value`;

CREATE TABLE `eav_attribute_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entityId` int(11) NOT NULL,
  `attributeId` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `optionId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Value_entityId` (`entityId`),
  KEY `FK_Value_attributeId` (`attributeId`),
  KEY `FK_Value_optionId` (`optionId`),
  CONSTRAINT `FK_Value_attributeId` FOREIGN KEY (`attributeId`) REFERENCES `eav_attribute` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_Value_optionId` FOREIGN KEY (`optionId`) REFERENCES `eav_attribute_option` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `eav_attribute_value` */

/*Table structure for table `eav_entity` */

DROP TABLE IF EXISTS `eav_entity`;

CREATE TABLE `eav_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entityName` varchar(50) DEFAULT NULL COMMENT 'Наименование',
  `entityModel` varchar(100) DEFAULT NULL COMMENT 'ID Модели',
  `categoryId` int(11) DEFAULT NULL COMMENT 'ID Category',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `eav_entity` */

insert  into `eav_entity`(`id`,`entityName`,`entityModel`,`categoryId`) values (13,'Продукт','common\\models\\Product',8);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
