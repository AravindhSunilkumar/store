/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.1.73-community : Database - student
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`student` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `student`;

/*Table structure for table `class_details` */

DROP TABLE IF EXISTS `class_details`;

CREATE TABLE `class_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `class` varchar(50) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `class_details` */

insert  into `class_details`(`id`,`class`,`status`) values (1,'1st standard','active'),(2,'2th standard','inactive'),(4,'4th standard','active'),(5,'5th standard','active');

/*Table structure for table `student_details` */

DROP TABLE IF EXISTS `student_details`;

CREATE TABLE `student_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `register_id` varchar(10) NOT NULL,
  `class_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` smallint(6) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `photo` text NOT NULL,
  `priority` smallint(6) DEFAULT NULL,
  `status` enum('active','inactive','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `student_details_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class_details` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

/*Data for the table `student_details` */

insert  into `student_details`(`id`,`register_id`,`class_id`,`name`,`age`,`dob`,`gender`,`photo`,`priority`,`status`) values (2,'0001',4,'web2',23,'2007-06-05','male','students_images/8800_witness.png',1,'active'),(3,'0002',1,'Siva',24,'2003-05-09','female','students_images/7796_man.png',2,'active'),(5,'0003',5,'web3',23,'2007-06-05','male','students_images/8780_witness.png',3,'active'),(9,'0004',1,'aravindh',23,'2007-06-05','male','students_images/5078_man.png',4,'active'),(16,'0006',1,'vivek',12,'2007-06-05','male','students_images/8843_man.png',5,'active'),(31,'0010',1,'reenu',12,'2003-06-05','male','students_images/2531_witness.png',6,'active'),(35,'0012',1,'subash',12,'2003-04-02','male','students_images/4042_man.png',7,'active'),(40,'0013',4,'sooraj',12,'2004-02-28','male','students_images/6715_download.png',8,'active');

/*Table structure for table `student_gallery` */

DROP TABLE IF EXISTS `student_gallery`;

CREATE TABLE `student_gallery` (
  `gallery_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) NOT NULL,
  `gallery_photo` text,
  PRIMARY KEY (`gallery_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `student_id` FOREIGN KEY (`student_id`) REFERENCES `student_details` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Data for the table `student_gallery` */

insert  into `student_gallery`(`gallery_id`,`student_id`,`gallery_photo`) values (7,3,'students_images/8126_man.png'),(9,3,'students_images/7176_download.png'),(15,9,'students_images/6620_download.png'),(16,9,'students_images/9843_man.png'),(17,9,'students_images/7097_up-arrow.png'),(25,40,'students_images/1764_man.png'),(28,3,'man.png'),(29,40,'students_images/4796_man.png');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
