/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.4.13-MariaDB : Database - plagiarism_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`plagiarism_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `plagiarism_db`;

/*Table structure for table `document_tbl` */

DROP TABLE IF EXISTS `document_tbl`;

CREATE TABLE `document_tbl` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `guid` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `sts` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `document_tbl` */

insert  into `document_tbl`(`id`,`guid`,`file_name`,`path`,`sts`) values (1,'C385F0B7-B5C3-4602-BA46-C8888FB077C5','file_C385F0B7-B5C3-4602-BA46-C8888FB077C5.docx','http://localhost/plagiarism/document_file/',1),(2,'D4032104-9068-479A-A84F-54A60874BF7C','file_D4032104-9068-479A-A84F-54A60874BF7C.docx','http://localhost/plagiarism/document_file/',1),(3,'8E26B46E-A726-4115-8CD7-AA81B7CCF9E9','file_8E26B46E-A726-4115-8CD7-AA81B7CCF9E9.docx','http://localhost/plagiarism/document_file/',1),(4,'038A99F5-925B-4E1D-BE6A-F8BD27972EA5','file_038A99F5-925B-4E1D-BE6A-F8BD27972EA5.docx','http://localhost/plagiarism/document_file/',1);

/*Table structure for table `user_tbl` */

DROP TABLE IF EXISTS `user_tbl`;

CREATE TABLE `user_tbl` (
  `id` bigint(250) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `user_type` varchar(150) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 1,
  `admin_verified` tinyint(1) DEFAULT 1,
  `sts` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_tbl` */

insert  into `user_tbl`(`id`,`username`,`email`,`password`,`user_type`,`email_verified`,`admin_verified`,`sts`) values (1,'admin','skuri.cse@gmail.com','1','admin',1,1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
