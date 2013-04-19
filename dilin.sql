-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-ipg21.eigbox.net
-- Generation Time: Apr 07, 2013 at 11:03 AM
-- Server version: 5.0.91
-- PHP Version: 4.4.9
-- 
-- Database: `dilindb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `daigous`
-- 

CREATE TABLE `daigous` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `url` varchar(200) NOT NULL,
  `brand` varchar(45) NOT NULL,
  `unit_price` varchar(45) NOT NULL,
  `quantity` int(4) NOT NULL default '1',
  `buyer` varchar(45) NOT NULL,
  `status` enum('pending','completed') NOT NULL default 'pending',
  `has_img` int(1) NOT NULL,
  `note` varchar(200) NOT NULL,
  `create_ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `daigous`
-- 

INSERT INTO `daigous` VALUES (1, 'kindle', '', 'amazon', '100', 1, '刘苗', 'pending', 0, '', '2013-03-25 04:46:43');
INSERT INTO `daigous` VALUES (2, '倩碧黄油', '', 'clinique', '25', 1, '陈生', 'pending', 0, '', '2013-03-25 05:13:52');
INSERT INTO `daigous` VALUES (3, '兰蔻红水200ml', '', 'lancome', '25', 3, '王妤', 'pending', 0, '', '2013-03-25 05:28:23');
INSERT INTO `daigous` VALUES (4, '鱼油', '', 'gnc', '10', 6, '张裕培', 'pending', 0, '', '2013-03-25 05:29:58');
INSERT INTO `daigous` VALUES (5, '小毛衣外套', '', '', '100', 0, '张云斐', 'pending', 0, 'prefer burburry', '2013-03-25 05:32:03');
INSERT INTO `daigous` VALUES (6, '小毛衣外套', '', '', '60', 1, '刘中璞', 'pending', 0, '', '2013-03-25 05:33:02');
INSERT INTO `daigous` VALUES (7, 'coach电脑包', '', '', '150', 1, '叶志峰', 'pending', 0, '', '2013-03-25 05:33:43');
INSERT INTO `daigous` VALUES (8, '男士领带', '', '', '150', 2, '张骏', 'pending', 0, '', '2013-03-25 05:34:29');
INSERT INTO `daigous` VALUES (9, '电动牙刷', '', '', '100', 1, '陈立怡', 'pending', 0, '', '2013-03-25 05:36:06');
INSERT INTO `daigous` VALUES (10, 'tiffany男士婚戒', 'http://www.tiffany.com/Shopping/Item.aspx?sku=GRP03838&mcat=148204&cid=288222&fromGrid=1&search_params=s+5-p+3-c+288222-r+101287466+0-x+-n+6-ri+-ni+0-t+', 'tiffany', '1680', 1, '李晓春', 'pending', 0, '大小: 8.5号', '2013-03-26 00:43:22');

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `status` enum('enabled','disabled') NOT NULL,
  `create_ts` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modify_ts` timestamp NOT NULL default '0000-00-00 00:00:00',
  `last_login_ts` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 'dilin110', '7c902d6f2cc037fd332aacf715691cee', '', '', '', 'enabled', '2013-03-27 03:45:59', '0000-00-00 00:00:00', '2013-03-27 03:45:59');



CREATE  TABLE `dilindb`.`albums` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `category_id` INT(10) UNSIGNED NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `seq` INT(4) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_albums1_idx` (`category_id` ASC) ,
  CONSTRAINT `fk_albums1`
    FOREIGN KEY (`category_id` )
    REFERENCES `dilindb`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE  TABLE `dilindb`.`contact` (
  `id` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `tel` VARCHAR(45) NOT NULL ,
  `content` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) );

ALTER TABLE `dilindb`.`contact` ADD COLUMN `create_ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `content` ;