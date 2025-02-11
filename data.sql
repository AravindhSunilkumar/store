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
  `age_from` smallint(6) NOT NULL,
  `age_to` smallint(6) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `class_details` */

insert  into `class_details`(`id`,`class`,`age_from`,`age_to`,`status`) values (1,'1st standard',5,7,'active'),(2,'2th standard',7,9,'inactive'),(4,'4th standard',9,11,'active'),(5,'5th standard',11,13,'active'),(6,'6th standard',13,15,'active'),(11,'7th standard',15,17,'active');

/*Table structure for table `parent_details` */

DROP TABLE IF EXISTS `parent_details`;

CREATE TABLE `parent_details` (
  `parent_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(10) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `father_number` varchar(10) NOT NULL,
  `father_mail` varchar(50) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `mother_number` varchar(10) NOT NULL,
  `mother_mail` varchar(50) NOT NULL,
  `primary_contact` enum('father','mother') NOT NULL,
  PRIMARY KEY (`parent_id`),
  KEY `parent_details` (`student_id`),
  CONSTRAINT `parent_details` FOREIGN KEY (`student_id`) REFERENCES `student_details` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `parent_details` */

insert  into `parent_details`(`parent_id`,`student_id`,`father_name`,`father_number`,`father_mail`,`mother_name`,`mother_number`,`mother_mail`,`primary_contact`) values (9,62,'sunilkumar','1234567890','lavax68876@shouxs.com','sandth','1234567890','lavax68876@shouxs.com','father'),(10,63,'aravindh','1234567890','lavax68876@shouxs.com','ads','1234567890','lavax68876@shouxs.com','father'),(11,64,'aravindh','1234567890','lavax68876@shouxs.com','ads','1234567890','lavax68876@shouxs.com','father');

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
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;

/*Data for the table `student_details` */

insert  into `student_details`(`id`,`register_id`,`class_id`,`name`,`age`,`dob`,`gender`,`photo`,`priority`,`status`) values (62,'0001',1,'aravindh sunilkumar',5,'2020-01-11','male','students_images/_Screenshot 2025-02-05 124227.png',1,'active'),(63,'0002',1,'sonu soman',5,'2020-01-01','male','students_images/0002_Screenshot 2025-02-05 124227.png',2,'active'),(64,'0003',2,'hari k k',9,'2016-01-11','male','students_images/0003_Screenshot 2025-02-05 124227.png',3,'active');

/*Table structure for table `student_gallery` */

DROP TABLE IF EXISTS `student_gallery`;

CREATE TABLE `student_gallery` (
  `gallery_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) NOT NULL,
  `gallery_photo` text,
  PRIMARY KEY (`gallery_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `student_id` FOREIGN KEY (`student_id`) REFERENCES `student_details` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

/*Data for the table `student_gallery` */

insert  into `student_gallery`(`gallery_id`,`student_id`,`gallery_photo`) values (58,62,'students_images/_Screenshot 2025-02-07 110312.png');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
