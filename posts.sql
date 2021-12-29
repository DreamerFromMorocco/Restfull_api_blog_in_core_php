CREATE TABLE `blog`.`posts` ( 
 `id` INT NOT NULL AUTO_INCREMENT ,
 `title` VARCHAR(100) NOT NULL , 
 `status` ENUM('draft', 'published') NOT NULL DEFAULT 'draft' ,
 `content` TEXT NOT NULL ,
 `user_id` INT NOT NULL ,
 PRIMARY KEY (`id`), INDEX('user_id')
) 
ENGINE = InnoDB;

ALTER TABLE `posts` 
ADD CONSTRAINT `user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;