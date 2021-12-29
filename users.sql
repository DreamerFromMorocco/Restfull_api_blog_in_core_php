CREATE TABLE `blog`.`users` (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `name` VARCHAR(100) NOT NULL ,
 `email` VARCHAR(50) NOT NULL ,
 `password` VARCHAR(50) NOT NULL ,
 PRIMARY KEY (`id`), 
 UNIQUE `email_unique` (`email`))
ENGINE = InnoDB;