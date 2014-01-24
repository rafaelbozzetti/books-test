create database thallerbooks;
create database thallerbooks_test;

use thallerbooks;
 
CREATE  TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(200) NOT NULL ,
  `password` VARCHAR(250) NOT NULL ,
  `role` VARCHAR(20) NULL,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;
 
CREATE  TABLE IF NOT EXISTS `books` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(250) NOT NULL ,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;